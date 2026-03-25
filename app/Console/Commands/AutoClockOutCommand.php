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

        Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('clock_in')      // Must have clocked in
            ->whereNull('clock_out')        // Not clocked out yet
            ->where('status', 'present')    // Only present users
            ->where('auto_clock_out', 0)    // Not already auto clocked out
            ->chunk(100, function ($attendances) use ($clockOutThreshold) {
                foreach ($attendances as $attendance) {
                    try {
                        $clockInTime = Carbon::parse($attendance->clock_in);

                        // Prevent clock_out earlier than clock_in
                        $finalClockOut = $clockInTime->gt($clockOutThreshold)
                            ? $clockInTime
                            : $clockOutThreshold;

                        $totalMinutes = $clockInTime->diffInMinutes($finalClockOut);

                        // Update attendance safely
                        $attendance->clock_out = $finalClockOut;
                        $attendance->total_minutes = $totalMinutes;
                        $attendance->auto_clock_out = 1;
                        $attendance->save();

                        // Send email
                        if ($attendance->user?->email) {
                            Mail::to($attendance->user->email)
                                ->send(new AutoClockOutMail($attendance));
                        }

                    } catch (Exception $e) {
                        Log::error("Failed auto clock-out for attendance ID {$attendance->id}: " . $e->getMessage());
                        $this->error("Error processing attendance ID {$attendance->id}");
                    }
                }
            });

        $this->info('Auto clock-out process completed.');
    }
}
