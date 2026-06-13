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

// Schedule::command('cron:test')
            // ->everyMinute()
            // ->timezone('Asia/Karachi');

// 🔥 ZKTeco Device Sync (MOST IMPORTANT)
Schedule::command('attendance:sync-zkteco')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('newsletter:send')->monthly();
