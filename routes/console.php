<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clock-in Reminder — 11:00 am
Schedule::command('attendance:clock-in-reminder')
    ->dailyAt('11:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Clock-out Reminder — 6:00 PM
Schedule::command('attendance:clock-out-reminder')
    ->dailyAt('18:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// 3. Auto-Clockout at 8:00 PM (20:00)
Schedule::command('attendance:auto-clockout')
    ->dailyAt('20:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();


// Retry at 8:30 PM
Schedule::command('attendance:auto-clockout')
    ->dailyAt('20:30')          // ← Just add this block
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Schedule::command('cron:test')
            // ->everyMinute()
            // ->timezone('Asia/Karachi');

// 🔥 ZKTeco Device Sync (MOST IMPORTANT) - Command not implemented yet
// Schedule::command('attendance:sync-zkteco')
//     ->everyMinute()
//     ->withoutOverlapping()
//     ->runInBackground();

Schedule::command('newsletter:send')->monthly();

// Visit 5 PM Reminder — runs once at 17:00
Schedule::command('visit:send-reminder-5pm')
    ->dailyAt('17:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Visit 5:30 PM Reminder — runs once at 17:30
Schedule::command('visit:send-reminder-530pm')
    ->dailyAt('17:30')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Visit 8 PM Block — runs once at 20:00
Schedule::command('visit:block-after-8pm')
    ->dailyAt('20:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Visit Management - Cleanup old reminder flags
Schedule::command('visit:cleanup-reminders')
    ->weekly()
    ->sundays()
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

// Visit Management - Cleanup old audit logs
Schedule::command('visit:cleanup-audit-logs')
    ->monthlyOn(1)
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();
