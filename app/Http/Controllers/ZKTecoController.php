<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Helpers\AttendanceHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserBlocked as UserBlockedMail;
use App\Services\NotificationService;
use Carbon\Carbon;

class ZKTecoController extends Controller
{
    // =========================================================
    // HANDSHAKE — Device connects and gets its config
    // =========================================================
    public function handshake(Request $request)
    {
        Log::info('ZKTeco Handshake', $request->all());

        $sn = $request->query('SN', 'UNKNOWN');

        $response  = "GET OPTION FROM: {$sn}\n";
        $response .= "ATTLOGStamp=None\n";
        $response .= "OPERLOGStamp=9999\n";
        $response .= "ATTPHOTOStamp=None\n";
        $response .= "ErrorDelay=30\n";
        $response .= "Delay=10\n";
        $response .= "TransTimes=00:00;14:05\n";
        $response .= "TransInterval=1\n";
        $response .= "TransFlag=TransData AttLog\n";
        $response .= "Realtime=1\n";
        $response .= "Encrypt=None\n";

        return response($response, 200)
            ->header('Content-Type', 'text/plain');
    }

    // =========================================================
    // HELPER — Map ZKTeco verify code → checkin_method string
    //   1, 2  = Fingerprint
    //   4     = Card / RFID
    //   15    = Face
    //   20    = Face + Fingerprint
    // =========================================================
    private function resolveMethod(int $verify): string
    {
        return match ($verify) {
            1, 2   => 'finger',
            4      => 'card',
            15, 20 => 'device',
            default => 'device',
        };
    }

    // =========================================================
    // RECEIVE — Main punch data handler
    // =========================================================
    public function receive(Request $request)
    {
        $rawData = $request->getContent();
        $sn      = $request->query('SN', 'UNKNOWN');
        $table   = $request->query('table', '');

        Log::info('ZKTeco Raw Data', [
            'query' => $request->all(),
            'body'  => $rawData,
        ]);

        // Acknowledge options table immediately
        if ($table === 'options') {
            return response("OK: {$sn}\n", 200)
                ->header('Content-Type', 'text/plain');
        }

        // Only process attendance log entries
        if ($table === 'ATTLOG' && !empty(trim($rawData))) {
            $lines = explode("\n", $rawData);

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $parts = explode("\t", $line);

                // Need at least PIN + timestamp
                if (count($parts) < 2 || empty($parts[0])) continue;

                $pin       = $parts[0];
                $punchTime = Carbon::parse($parts[1]);
                $status    = (int) ($parts[2] ?? 0);
                $verify    = (int) ($parts[3] ?? 15);
                $date      = $punchTime->toDateString();
                $method    = $this->resolveMethod($verify);

                Log::info("ZKTeco Punch received", [
                    'pin'    => $pin,
                    'status' => $status,
                    'verify' => $verify,
                    'method' => $method,
                    'time'   => $punchTime,
                ]);

                // -------------------------------------------------
                // FIND USER by PIN (PIN = user primary key on device)
                // -------------------------------------------------
                $user = User::find($pin);
                if (!$user) {
                    Log::warning("ZKTeco: No user found for PIN", ['pin' => $pin]);
                    continue;
                }

                // -------------------------------------------------
                // BLOCK: User account blocked
                // -------------------------------------------------
                if (method_exists($user, 'isBlocked') && $user->isBlocked()) {
                    Log::info("ZKTeco blocked — user account is blocked", [
                        'date' => $date,
                        'user' => $user->name,
                    ]);

                    // Notify user (best-effort)
                    try {
                        Mail::to($user->email)->send(new UserBlockedMail($user));
                    } catch (\Exception $e) {
                        Log::error('ZKTeco: failed to send block email: '.$e->getMessage());
                    }

                    try {
                        NotificationService::sendWhatsApp($user->phone, 'Unable to mark attendance: your account is blocked. Contact admin.');
                    } catch (\Exception $e) {
                        Log::error('ZKTeco: failed to send block WhatsApp: '.$e->getMessage());
                    }

                    continue;
                }

                // -------------------------------------------------
                // BLOCK: Public holiday
                // -------------------------------------------------
                if (Holiday::isHoliday($date)) {
                    Log::info("ZKTeco blocked — holiday", [
                        'date' => $date,
                        'user' => $user->name,
                    ]);
                    continue;
                }

                // -------------------------------------------------
                // BLOCK: Non-working day (weekend / off day)
                // -------------------------------------------------
                if (AttendanceHelper::isNonWorkingDay($date)) {
                    Log::info("ZKTeco blocked — non-working day", [
                        'date' => $date,
                        'user' => $user->name,
                    ]);
                    continue;
                }

                // -------------------------------------------------
                // BLOCK: Employee already on approved leave today
                // -------------------------------------------------
                $onLeave = Attendance::where('salesman_id', $user->id)
                    ->where('date', $date)
                    ->where('status', 'leave')
                    ->exists();

                if ($onLeave) {
                    Log::info("ZKTeco blocked — employee on approved leave", [
                        'date' => $date,
                        'user' => $user->name,
                    ]);
                    continue;
                }

                // -------------------------------------------------
                // SMART STATUS DETECTION
                // Status 0 / 4  → explicit clock-in
                // Status 1 / 5  → explicit clock-out
                // Status 255    → auto-detect from existing record
                // -------------------------------------------------
                $isClockIn  = false;
                $isClockOut = false;

                if (in_array($status, [0, 4])) {
                    $isClockIn = true;

                } elseif (in_array($status, [1, 5])) {
                    $isClockOut = true;

                } elseif ($status === 255) {
                    $existing = Attendance::where('salesman_id', $user->id)
                        ->where('date', $date)
                        ->first();

                    if (!$existing) {
                        // No record at all → first punch = clock-in
                        $isClockIn = true;
                        Log::info("ZKTeco auto-detect → CLOCK IN (no record exists)", [
                            'pin'  => $pin,
                            'user' => $user->name,
                        ]);

                    } elseif (!$existing->clock_out) {
                        // Has clock-in but no clock-out yet
                        $minutesSinceClockIn = Carbon::parse($existing->clock_in)
                            ->diffInMinutes($punchTime);

                        if ($minutesSinceClockIn < 5) {
                            // Too soon after clock-in — ignore accidental double-scan
                            Log::info("ZKTeco punch ignored — too soon after clock-in", [
                                'user'    => $user->name,
                                'minutes' => $minutesSinceClockIn,
                            ]);
                            continue;
                        }

                        $isClockOut = true;
                        Log::info("ZKTeco auto-detect → CLOCK OUT", [
                            'pin'     => $pin,
                            'user'    => $user->name,
                            'minutes' => $minutesSinceClockIn,
                        ]);

                    } else {
                        // Already fully clocked out — ignore extra punches
                        Log::info("ZKTeco extra punch ignored — already clocked out", [
                            'user' => $user->name,
                            'date' => $date,
                        ]);
                        continue;
                    }
                }

                // -------------------------------------------------
                // CLOCK IN
                // -------------------------------------------------
                if ($isClockIn) {
                    $existing = Attendance::where('salesman_id', $user->id)
                        ->where('date', $date)
                        ->first();

                    if (!$existing) {
                        Attendance::create([
                            'salesman_id'     => $user->id,
                            'date'            => $date,
                            'status'          => 'present',
                            'clock_in'        => $punchTime,
                            'short_leave'     => $punchTime->format('H:i') >= '12:00',
                            'office_verified' => true,
                            'qr_verified'     => false,
                            'checkin_method'  => $method,
                            'checkin_ip'      => $request->ip(),
                            'lat'             => null,
                            'lng'             => null,
                            'distance_meters' => 0,
                        ]);

                        Log::info("ZKTeco Clock-IN saved", [
                            'user'   => $user->name,
                            'pin'    => $pin,
                            'method' => $method,
                            'time'   => $punchTime,
                        ]);

                    } else {
                        Log::info("ZKTeco Clock-IN skipped — record already exists", [
                            'user' => $user->name,
                            'date' => $date,
                        ]);
                    }

                // -------------------------------------------------
                // CLOCK OUT
                // -------------------------------------------------
                } elseif ($isClockOut) {
                    $attendance = Attendance::where('salesman_id', $user->id)
                        ->where('date', $date)
                        ->first();

                    if ($attendance && !$attendance->clock_out) {
                        $attendance->update([
                            'clock_out'     => $punchTime,
                            'total_minutes' => $attendance->clock_in
                                ? Carbon::parse($attendance->clock_in)->diffInMinutes($punchTime)
                                : 0,
                            // Mark short leave if they left before 17:00
                            'short_leave'   => $attendance->short_leave
                                || $punchTime->format('H:i') < '17:00',
                        ]);

                        Log::info("ZKTeco Clock-OUT saved", [
                            'user' => $user->name,
                            'pin'  => $pin,
                            'time' => $punchTime,
                        ]);

                    } else {
                        Log::info("ZKTeco Clock-OUT skipped", [
                            'user'             => $user->name,
                            'date'             => $date,
                            'already_clocked'  => (bool) ($attendance->clock_out ?? false),
                            'record_exists'    => (bool) $attendance,
                        ]);
                    }
                }
            }
        }

        return response("OK: {$sn}\n", 200)
            ->header('Content-Type', 'text/plain');
    }

    // =========================================================
    // GET REQUEST — Polled by device for pending commands
    // =========================================================
    public function getRequest(Request $request)
    {
        return response("OK\n", 200)
            ->header('Content-Type', 'text/plain');
    }

    // =========================================================
    // DEVICE CMD — Device acknowledges a command result
    // =========================================================
    public function deviceCmd(Request $request)
    {
        return response("OK\n", 200)
            ->header('Content-Type', 'text/plain');
    }

    // =========================================================
    // CDATA — Legacy push endpoint (some firmware variants)
    // Delegates to receive() so both routes work identically
    // =========================================================
    public function cdata(Request $request)
    {
        return $this->receive($request);
    }
}
