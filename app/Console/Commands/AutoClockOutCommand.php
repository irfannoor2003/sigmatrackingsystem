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
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'attendance:auto-clockout';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Automatically clock out users who forgot to clock out at 8:00 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $clockOutThreshold = $today->copy()->setTime(20, 0, 0); // 8:00 PM

        // Use chunking to handle large datasets efficiently
        Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->where('status', 'present')
            ->where('auto_clock_out', 0)
            ->chunk(100, function ($attendances) use ($clockOutThreshold) {
                foreach ($attendances as $attendance) {
                    try {
                        $clockInTime = Carbon::parse($attendance->clock_in);

                        // If they clocked in AFTER 8PM, we shouldn't set a clock_out that is earlier than clock_in
                        // This uses the later of the two times as a safety measure
                        $finalClockOut = $clockInTime->gt($clockOutThreshold) ? $clockInTime : $clockOutThreshold;

                        $totalMinutes = $clockInTime->diffInMinutes($finalClockOut);

                        $attendance->update([
                            'clock_out' => $finalClockOut->toTimeString(),
                            'total_minutes' => $totalMinutes,
                            'auto_clock_out' => 1,
                        ]);

                        // Send Email if user exists
                        if ($attendance->user?->email) {
                            Mail::to($attendance->user->email)->send(new AutoClockOutMail($attendance));
                        }

                    } catch (Exception $e) {
                        Log::error("Failed to auto clock-out attendance ID {$attendance->id}: " . $e->getMessage());
                        $this->error("Error processing ID {$attendance->id}");
                    }
                }
            });

        $this->info('Auto clock-out process completed.');
    }
}
