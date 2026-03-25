<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClockInReminderMail;
use Carbon\Carbon;
use App\Helpers\AttendanceHelper;

class ClockInReminderCommand extends Command
{
    protected $signature = 'attendance:clock-in-reminder';
    protected $description = 'Send clock-in reminder emails';

    public function handle()
    {
        $today = now()->toDateString();
        $now   = now();

        // Skip Sundays, company holidays, and Pakistan holidays
        if (AttendanceHelper::isNonWorkingDay($today)) {
            $this->info('Non-working day. Clock-in emails skipped.');
            return;
        }

        // Clock-in reminder window: 11:00 AM – 3:00 PM
        if (!$now->between(Carbon::today()->setTime(11, 0), Carbon::today()->setTime(15, 0))) {
            $this->info('Outside clock-in reminder window.');
            return;
        }

        User::whereIn('role', ['salesman','it','account','store','office_boy'])
            ->each(function ($user) use ($today) {

                // Get attendance if exists, do NOT create a new one
                $attendance = Attendance::where('salesman_id', $user->id)
                    ->where('date', $today)
                    ->first();

                // Skip if already clocked in, on leave, or reminder already sent
                if ($attendance && ($attendance->clock_in || $attendance->status === 'leave' || $attendance->clock_in_reminder_sent)) {
                    return;
                }

                // Send clock-in reminder
                Mail::to($user->email)->send(new ClockInReminderMail($user));

                // If attendance exists, update the flag
                if ($attendance) {
                    $attendance->clock_in_reminder_sent = true;
                    $attendance->save();
                }
            });

        $this->info('Clock-in reminders processed.');
    }
}
