<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ClockOutReminderMail;
use Carbon\Carbon;
use App\Helpers\AttendanceHelper;
use Exception;

class ClockOutReminderCommand extends Command
{
    protected $signature = 'attendance:clock-out-reminder';
    protected $description = 'Send clock-out reminder emails';

    public function handle()
    {
        $today = now()->toDateString();
        $now   = now();

        // ✅ Skip Sundays, company holidays, and Pakistan holidays
        if (AttendanceHelper::isNonWorkingDay($today)) {
            $this->info('Non-working day. Clock-out emails skipped.');
            Log::info('Clock-out reminder skipped: non-working day.');
            return;
        }

        // Clock-out reminder after 6:00 PM
        if ($now->hour < 18) {
            $this->info('Before 6 PM. Clock-out reminders not sent yet.');
            return;
        }

        $attendances = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')            // Must have clocked in
            ->whereNull('clock_out')              // Not clocked out yet
            ->where('auto_clock_out', 0)          // Not auto clocked out
            ->where('clock_out_reminder_sent', 0) // Reminder not already sent
            ->with('salesman')
            ->get();

        $processed = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($attendances as $attendance) {
            try {
                if (!$attendance->salesman) {
                    Log::warning("Clock-out reminder skipped: no salesman for attendance ID {$attendance->id}");
                    $skipped++;
                    continue;
                }

                if (!$attendance->salesman->email) {
                    Log::warning("Clock-out reminder skipped: no email for salesman ID {$attendance->salesman_id} (attendance ID {$attendance->id})");
                    $skipped++;
                    continue;
                }

                // Send email FIRST
                Mail::to($attendance->salesman->email)
                    ->send(new ClockOutReminderMail($attendance->salesman));

                // Only set flag AFTER email is sent
                $attendance->clock_out_reminder_sent = true;
                $attendance->save();

                $processed++;

            } catch (Exception $e) {
                Log::error("Failed clock-out reminder for attendance ID {$attendance->id}: " . $e->getMessage());
                $this->error("Error processing attendance ID {$attendance->id}");
                $failed++;
            }
        }

        $this->info("Clock-out reminder completed. Processed: {$processed}, Failed: {$failed}, Skipped: {$skipped}");
        Log::info("Clock-out reminder completed. Processed: {$processed}, Failed: {$failed}, Skipped: {$skipped}");
    }
}
