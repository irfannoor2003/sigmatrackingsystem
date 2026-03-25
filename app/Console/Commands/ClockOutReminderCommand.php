<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClockOutReminderMail;
use Carbon\Carbon;
use App\Helpers\AttendanceHelper;

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
            return;
        }

        // Clock-out reminder after 6:00 PM
        if ($now->hour < 18) {
            $this->info('Before 6 PM. Clock-out reminders not sent yet.');
            return;
        }

        Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')            // Must have clocked in
            ->whereNull('clock_out')              // Not clocked out yet
            ->where('auto_clock_out', 0)          // Not auto clocked out
            ->where('clock_out_reminder_sent', 0) // Reminder not already sent
            ->with('salesman')
            ->each(function ($attendance) {

                if (!$attendance->salesman) return;

                // ✅ Set flag safely to prevent duplicate emails
                $attendance->clock_out_reminder_sent = true;
                $attendance->save();

                // Send clock-out reminder
                Mail::to($attendance->salesman->email)
                    ->send(new ClockOutReminderMail($attendance->salesman));
            });

        $this->info('Clock-out reminders processed.');
    }
}
