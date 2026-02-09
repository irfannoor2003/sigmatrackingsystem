<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClockInReminderMail;
use App\Mail\ClockOutReminderMail;
use Carbon\Carbon;
use App\Helpers\AttendanceHelper;

class AttendanceReminderCommand extends Command
{
    protected $signature = 'attendance:reminders';
    protected $description = 'Send clock-in and clock-out reminder emails safely';

    public function handle()
    {
        $today = now()->toDateString();

        // ✅ Skip reminders on non-working days (Sundays + holidays)
        if (AttendanceHelper::isNonWorkingDay($today)) {
            $this->info('Non-working day. Emails skipped.');
            return;
        }

        $now = now();

        /* =========================================================
         | CLOCK-IN REMINDER (11:00 AM – 3:00 PM)
         ========================================================= */
        if ($now->between(
            Carbon::today()->setTime(11, 0),
            Carbon::today()->setTime(15, 0)
        )) {

            User::whereIn('role', ['salesman','it','account','store','office_boy'])
                ->each(function ($user) use ($today) {

                    $attendance = Attendance::where('salesman_id', $user->id)
                        ->where('date', $today)
                        ->first();

                    // ❌ Skip if already clocked in, on leave, or reminder sent
                    if ($attendance && ($attendance->clock_in || $attendance->status === 'leave' || $attendance->clock_in_reminder_sent)) {
                        return;
                    }

                    Mail::to($user->email)->send(new ClockInReminderMail($user));

                    // ✅ Mark reminder sent if attendance exists
                    if ($attendance) {
                        $attendance->update(['clock_in_reminder_sent' => 1]);
                    }
                });
        }

        /* =========================================================
         | CLOCK-OUT REMINDER (AFTER 6:00 PM)
         ========================================================= */
        if ($now->hour >= 18) {

            Attendance::whereDate('date', $today)
                ->whereNotNull('clock_in')          // must have clocked in
                ->whereNull('clock_out')
                ->where('auto_clock_out', 0)
                ->where('clock_out_reminder_sent', 0)
                ->with('salesman')
                ->each(function ($attendance) {

                    if (!$attendance->salesman) return;

                    Mail::to($attendance->salesman->email)
                        ->send(new ClockOutReminderMail($attendance->salesman));

                    $attendance->update([
                        'clock_out_reminder_sent' => 1,
                    ]);
                });
        }

        $this->info('Attendance reminders processed safely.');
    }
}
