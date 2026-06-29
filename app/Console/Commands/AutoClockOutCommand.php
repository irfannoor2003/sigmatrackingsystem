<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Mail\AutoClockOutMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class AutoClockOutCommand extends Command
{
    protected $signature = 'attendance:auto-clockout';
    protected $description = 'Automatically clock out users who forgot to clock out at 8:00 PM';

    public function handle()
    {
        $today = Carbon::today();
        $clockOutThreshold = $today->copy()->setTime(20, 0, 0); // 8:00 PM

        // Skip non-working days
        if (\App\Helpers\AttendanceHelper::isNonWorkingDay($today->toDateString())) {
            $this->info('Non-working day. Auto clock-out skipped.');
            return;
        }

        $attendances = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('clock_in')      // Must have clocked in
            ->whereNull('clock_out')        // Not clocked out yet
            ->where('status', 'present')    // Only present users
            ->where('auto_clock_out', 0)    // Not already auto clocked out
            ->get();

        $processed = 0;
        $failed = 0;

        foreach ($attendances as $attendance) {
            try {
                $clockInTime = Carbon::parse($attendance->clock_in);

                // Prevent clock_out earlier than clock_in
                $finalClockOut = $clockInTime->gt($clockOutThreshold)
                    ? $clockInTime
                    : $clockOutThreshold;

                $totalMinutes = $clockInTime->diffInMinutes($finalClockOut);

                // Update and force save
                $attendance->clock_out = $finalClockOut;
                $attendance->total_minutes = $totalMinutes;
                $attendance->auto_clock_out = 1;
                $saved = $attendance->save();

                if (!$saved) {
                    Log::error("Auto clock-out save returned false for attendance ID {$attendance->id}");
                    $failed++;
                    continue;
                }

                // Verify the save actually persisted
                $attendance->refresh();
                if (!$attendance->clock_out) {
                    Log::error("Auto clock-out verification failed for attendance ID {$attendance->id} - clock_out is still null after save");
                    $failed++;
                    continue;
                }

                // Send email
                if ($attendance->user?->email) {
                    Mail::to($attendance->user->email)
                        ->send(new AutoClockOutMail($attendance));
                }

                $processed++;

            } catch (Exception $e) {
                Log::error("Failed auto clock-out for attendance ID {$attendance->id}: " . $e->getMessage());
                $this->error("Error processing attendance ID {$attendance->id}");
                $failed++;
            }
        }

        $this->info("Auto clock-out completed. Processed: {$processed}, Failed: {$failed}");
        Log::info("Auto clock-out completed. Processed: {$processed}, Failed: {$failed}");
    }
}
