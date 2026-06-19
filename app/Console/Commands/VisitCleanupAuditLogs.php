<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use Carbon\Carbon;

class VisitCleanupAuditLogs extends Command
{
    protected $signature = 'visit:cleanup-audit-logs';
    protected $description = 'Clean up old audit logs';

    public function handle()
    {
        $this->info('Cleaning up old audit logs...');

        $cutoffDate = Carbon::now()->subMonths(6);

        $deleted = Visit::whereNotNull('unblocked_at')
            ->where('unblocked_at', '<', $cutoffDate)
            ->delete();

        $this->info("Deleted {$deleted} old audit log entries.");
    }
}