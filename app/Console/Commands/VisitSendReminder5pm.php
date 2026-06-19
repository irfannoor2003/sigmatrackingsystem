<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use App\Services\NotificationService;
use App\Services\VisitService;

class VisitSendReminder5pm extends Command
{
    protected $signature = 'visit:send-reminder-5pm';
    protected $description = 'Send 5 PM visit reminder (runs once at 17:00)';

    public function handle()
    {
        $today = now()->toDateString();
        $now = now();

        $visits = Visit::where('status', 'started')
            ->whereDate('started_at', $today)
            ->where('reminder_5pm_sent', false)
            ->with('salesman')
            ->get();

        $sent = 0;

        foreach ($visits as $visit) {
            if (!$visit->salesman) continue;

            if (VisitService::send5pmReminder($visit)) {
                NotificationService::sendEmailVisitReminder(
                    $visit->salesman->email,
                    $visit->salesman->name,
                    $visit
                );
                $sent++;
            }
        }

        $this->info("5 PM reminder: {$sent} reminders sent out of {$visits->count()} eligible visits.");
    }
}
