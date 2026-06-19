<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use App\Services\NotificationService;
use App\Services\VisitService;

class VisitSendReminder530pm extends Command
{
    protected $signature = 'visit:send-reminder-530pm';
    protected $description = 'Send 5:30 PM visit reminder (runs once at 17:30)';

    public function handle()
    {
        $today = now()->toDateString();

        $visits = Visit::where('status', 'started')
            ->whereDate('started_at', $today)
            ->where('reminder_5pm_sent', true)
            ->where('reminder_530pm_sent', false)
            ->with('salesman')
            ->get();

        $sent = 0;

        foreach ($visits as $visit) {
            if (!$visit->salesman) continue;

            if (VisitService::send530pmReminder($visit)) {
                NotificationService::sendEmailVisitReminder(
                    $visit->salesman->email,
                    $visit->salesman->name,
                    $visit
                );
                $sent++;
            }
        }

        $this->info("5:30 PM reminder: {$sent} reminders sent out of {$visits->count()} eligible visits.");
    }
}
