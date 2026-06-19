<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use App\Services\NotificationService;
use App\Services\VisitService;
use Illuminate\Support\Facades\Log;

class VisitBlockAfter8pm extends Command
{
    protected $signature = 'visit:block-after-8pm';
    protected $description = 'Block all still-open visits after 8 PM (runs once at 20:00)';

    public function handle()
    {
        $today = now()->toDateString();

        $visits = Visit::where('status', 'started')
            ->whereDate('started_at', $today)
            ->with('salesman')
            ->get();

        $blocked = 0;

        foreach ($visits as $visit) {
            $alreadyBlockedToday = $visit->blocked_at && $visit->blocked_at->isToday();
            if ($alreadyBlockedToday) continue;

            try {
                if (VisitService::blockVisit($visit) && $visit->salesman) {
                    NotificationService::sendEmailVisitBlocked(
                        $visit->salesman->email,
                        $visit->salesman->name,
                        $visit
                    );
                    $blocked++;
                }
            } catch (\Exception $e) {
                Log::error('Error blocking visit at 8pm', [
                    'visit_id' => $visit->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("8 PM block: {$blocked} visits blocked out of {$visits->count()} open visits.");
    }
}
