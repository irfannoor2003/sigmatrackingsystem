<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\AttendanceVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Mail\AttendanceClockInVerificationMail;
use App\Helpers\AttendanceHelper;


class AttendanceController extends Controller
{
    /* ================= AUTH ================= */
    private function staffUser()
    {
        $user = auth()->user();
        if (! $user) abort(403);

        if (! in_array($user->role, [
            'salesman','it','account','store','office_boy'
        ])) abort(403);

        if (method_exists($user, 'isBlocked') && $user->isBlocked()) {
            abort(403, 'Your account is blocked. Contact admin.');
        }

        return $user;
    }

    private function viewPath(string $view)
    {
        return auth()->user()->role === 'salesman'
            ? "salesman.attendance.$view"
            : "staff.attendance.$view";
    }

    /* ================= HELPERS ================= */
    private function distanceInMeters($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;

        return round($earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a))));
    }

    private function validateOfficeQr(?string $qrToken): bool
    {
        if (! $qrToken) return false;
        return hash_equals(config('office.qr_token'), $qrToken);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        $isNonWorkingDay = AttendanceHelper::isNonWorkingDay($today);
$nonWorkingReason = AttendanceHelper::nonWorkingReason($today);

        // ✅ ALWAYS DEFINED
        $hideLeaveButton   = now()->hour >= 12;
        $hideClockInButton = now()->hour >= 15;

       return view(
    $this->viewPath('index'),
    compact(
        'attendance',
        'hideLeaveButton',
        'hideClockInButton',
        'isNonWorkingDay',
        'nonWorkingReason'
    )
);
    }

    /* ================= CHECK-IN VIEW ================= */
    public function checkInView()
    {
        return view($this->viewPath('checkin'));
    }

    /* ================= CLOCK IN ================= */
    public function clockIn(Request $request)
    {

        $user = $this->staffUser();
    $today = today()->toDateString();

   if (AttendanceHelper::isNonWorkingDay($today)) {
    return back()->with('error', 'Attendance is disabled today.');
}

    // ⛔ BLOCK HOLIDAYS
    if (Holiday::isHoliday($today)) {
        return back()->with('error', 'Attendance disabled due to holiday.');
    }



        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'qr_token' => 'nullable|string',
        ]);

        if (! $this->validateOfficeQr($request->qr_token)) {
            return back()->with('error', 'Invalid QR code.');
        }

        if (Attendance::where('salesman_id', $user->id)->where('date', $today)->exists()) {
            return back()->with('error', 'Already checked in today.');
        }

        $distance = $this->distanceInMeters(
            $request->lat,
            $request->lng,
            config('office.lat'),
            config('office.lng')
        );

        if ($distance > config('office.radius')) {
            return back()->with('error', 'Outside office radius.');
        }

        // 🔥 Clear old unused verifications
        AttendanceVerification::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();



        $token = Str::uuid()->toString();

AttendanceVerification::create([
    'user_id'    => $user->id,
    'token'      => $token,
    'expires_at' => now()->addMinute(),
    'payload'    => json_encode([
        'lat'        => $request->lat,
        'lng'        => $request->lng,
        'distance'   => $distance,
        'qr'         => (bool) $request->qr_token,
        'ip'         => $request->ip(),

        // 🔐 DEVICE FINGERPRINT (IMPORTANT)
        'fingerprint' => sha1(
            $request->userAgent() . '|' . $request->ip()
        ),
    ])
]);


        $link = URL::temporarySignedRoute(
            'attendance.verify',
            now()->addMinute(),
            ['token' => $token]
        );

        Mail::to($user->email)
            ->send(new AttendanceClockInVerificationMail($link));

        return back()->with(
            'success',
            '📧 Verification email sent. Confirm within 1 minute.'
        );
    }

    /* ================= VERIFY CLOCK IN ================= */
    public function verifyClockIn(Request $request, $token)
{
     $today = today()->toDateString();
   if (AttendanceHelper::isNonWorkingDay($today)) {
    return back()->with('error', 'Attendance is disabled today.');
}
    // 1️⃣ Signed URL check
    if (! $request->hasValidSignature()) {
        abort(403, 'Invalid or expired verification link.');
    }

    // 2️⃣ Load verification record
    $verification = AttendanceVerification::where('token', $token)
        ->whereNull('verified_at')
        ->firstOrFail();

    if (now()->greaterThan($verification->expires_at)) {
        abort(403, 'Verification link expired.');
    }

    // 3️⃣ Decode payload
    $data = json_decode($verification->payload, true);
    if (! is_array($data)) {
        abort(403, 'Invalid verification payload.');
    }

    // 4️⃣ DEVICE MATCH CHECK 🔐 (CORE SECURITY)
    $currentFingerprint = sha1(
        $request->userAgent() . '|' . $request->ip()
    );

    if ($currentFingerprint !== ($data['fingerprint'] ?? null)) {
        abort(403, 'This link must be opened from the same device used to scan the QR.');
    }

    // 5️⃣ Login user safely
    $user = User::findOrFail($verification->user_id);
    Auth::login($user);



    // 6️⃣ Prevent duplicate attendance
    if (Attendance::where('salesman_id', $user->id)
        ->where('date', $today)
        ->exists()) {
        abort(403, 'Attendance already marked.');
    }

    $clockIn = now();

    Attendance::create([
        'salesman_id'     => $user->id,
        'date'            => $today,
        'status'          => 'present',
        'clock_in'        => $clockIn,
        'short_leave'     => $clockIn->format('H:i') >= '12:00',

        'lat'             => $data['lat'],
        'lng'             => $data['lng'],
        'distance_meters' => $data['distance'],

        'office_verified' => true,
        'qr_verified'     => $data['qr'],
        'checkin_method'  => $data['qr'] ? 'qr' : 'gps',
        'checkin_ip'      => $data['ip'],
    ]);

    // 7️⃣ Mark verification used
    $verification->update([
        'verified_at' => now()
    ]);

    // 8️⃣ Redirect correctly
    $redirectRoute = match ($user->role) {
        'salesman' => 'salesman.attendance.index',
        default => 'staff.attendance.index',
    };

    return redirect()
        ->route($redirectRoute)
        ->with('success', '✅ Clock-in verified successfully.');
}


    /* ================= CLOCK OUT ================= */
    public function clockOut(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (AttendanceHelper::isNonWorkingDay($today)) {
   abort(403, 'Attendance is disabled today.');

}

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->firstOrFail();

        if ($attendance->clock_out) {
            return back()->with('error', 'Already clocked out.');
        }

        $clockOut = now();

        $attendance->update([
            'clock_out' => $clockOut,
            'total_minutes' => $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes($clockOut)
                : 0,
            'short_leave' => $attendance->short_leave || $clockOut->format('H:i') < '17:00',
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return back()->with('success', 'Clock-out successful.');
    }

    /* ================= HISTORY ================= */
    public function history(Request $request)
{
    $user = $this->staffUser();
    $monthInput = $request->month ?? now()->format('Y-m');

    $start = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', $monthInput)->endOfMonth();

    $attendances = Attendance::where('salesman_id', $user->id)
        ->whereBetween('date', [$start, $end])
        ->get()
        ->keyBy(fn ($a) => Carbon::parse($a->date)->format('Y-m-d'));

    $calendar = [];
    $date = $start->copy();

    while ($date <= $end) {
        $key = $date->format('Y-m-d');
        $attendance = $attendances->get($key);

        // Determine holiday/non-working day
        $isHoliday = Holiday::isHoliday($key);
        $holidayTitle = Holiday::title($key);
        $isSunday = $date->isSunday();
        $isNonWorking = AttendanceHelper::isNonWorkingDay($key);
        $nonWorkingReason = AttendanceHelper::nonWorkingReason($key);

        // Determine status
        // Holidays and non-working days take precedence over attendance records
        if ($isHoliday || $isSunday || $isNonWorking) {
            $status = 'holiday';
        } elseif ($attendance) {
            if ($attendance->status === 'leave') {
                $status = 'leave';
            } elseif ($attendance->short_leave) {
                $status = 'short_leave';
            } else {
                $status = $attendance->status; // present
            }
        } elseif ($date->isFuture()) {
            $status = 'future';
        } else {
            $status = 'absent';
        }

        // Holiday / reason title
        $holidayText = $holidayTitle ?? ($isSunday ? 'Sunday' : ($isNonWorking ? $nonWorkingReason : null));

        $calendar[] = [
            'date' => $key,
            'status' => $status,
            'attendance' => $attendance,
            'holiday' => $holidayText,
        ];

        $date->addDay();
    }

    $calendarCollect = collect($calendar);
    $totalPresents    = $calendarCollect->where('status', 'present')->count();
    $totalAbsents     = $calendarCollect->where('status', 'absent')->count();
    $totalLeaves      = $calendarCollect->where('status', 'leave')->count();
    $totalShortLeaves = $calendarCollect->where('status', 'short_leave')->count();

    $totalLates = $calendarCollect->where('status', 'present')->filter(function ($day) {
        if (!$day['attendance'] || !$day['attendance']->clock_in) {
            return false;
        }
        $lateThreshold = $day['attendance']->date->copy()->setTime(10, 16);
        return Carbon::parse($day['attendance']->clock_in)->gt($lateThreshold);
    })->count();

    return view($this->viewPath('history'), compact(
        'calendar', 'monthInput',
        'totalPresents', 'totalAbsents', 'totalLeaves', 'totalShortLeaves', 'totalLates'
    ));
}


    /* ================= REQUEST LEAVE ================= */
    public function requestLeave(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();
 if (AttendanceHelper::isNonWorkingDay($today)) {
    return back()->with('error', 'Attendance is disabled today.');
}
        if (now()->hour >= 12) {
            return back()->with('error', 'Leave allowed before 12 PM only.');
        }

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Holiday today.');
        }

        if (Attendance::where('salesman_id', $user->id)->where('date', $today)->exists()) {
            return back()->with('error', 'Attendance already exists.');
        }

        Attendance::create([
            'salesman_id' => $user->id,
            'date' => $today,
            'status' => 'leave',
            'leave_type' => $request->leave_type ?? 'casual',
            'note' => $request->note,
        ]);

        return back()->with('success', 'Leave requested successfully.');
    }
    public function manualForm()
{
    $staff = User::where('role', 'salesman') // only staff
                 ->orderBy('name')
                 ->get();

    return view('attendance.manual', compact('staff'));
}


public function manualStore(Request $request)
{
    $request->validate([
        'salesman_id'   => 'required|exists:users,id',
        'date'      => 'required|date',
        'clock_in'  => 'required',
        'clock_out' => 'nullable',
        'note'    => 'nullable|string|max:255',
    ]);

    $attendance = \App\Models\Attendance::updateOrCreate(
    ['salesman_id' => $request->salesman_id, 'date' => $request->date],
    [
        'clock_in'      => $request->clock_in,
        'clock_out'     => $request->clock_out,
        'manual_visit'  => true,
        'note'          => $request->note,
        'status'        => 'present',
'marked_by'     => auth()->id(),
    ]
);

    return back()->with('success', 'Manual attendance saved successfully.');
}
}
