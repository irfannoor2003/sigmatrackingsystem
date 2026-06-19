<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use Carbon\Carbon;

class VisitCleanupReminders extends Command
{
    protected $signature = 'visit:cleanup-reminders';
    protected $description = 'Clean up old reminder flags';

    public function handle()
    {
        $this->info('Cleaning up old reminder flags...');

        $cutoffDate = Carbon::now()->subWeek();

        $updated = Visit::where('reminder_5pm_sent', true)
            ->orWhere('reminder_530pm_sent', true)
            ->where('updated_at', '<', $cutoffDate)
            ->update([
                'reminder_5pm_sent' => false,
                'reminder_530pm_sent' => false,
            ]);

        $this->info("Reset {$updated} old reminder flags.");
    }
}