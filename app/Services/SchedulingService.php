<?php

namespace App\Services;

use App\Models\Visit;
use App\Services\VisitService;
use Illuminate\Support\Facades\Log;

class SchedulingService
{
    /**
     * Process daily visit reminders and blocking
     */
    public static function processDailyVisits(): array
    {
        $results = [
            'reminders_sent' => 0,
            'visits_blocked' => 0,
            'errors' => [],
        ];

        $now = now();
        $today = $now->format('Y-m-d');

        // Get all visits started today
        $todayVisits = Visit::where('status', 'started')
            ->whereDate('started_at', $today)
            ->with(['salesman', 'customer'])
            ->get();

        foreach ($todayVisits as $visit) {
            try {
                // Skip reminders if it's already 8 PM+ (visit will be blocked)
                if ($now->format('H:i') < '20:00') {
                    // Send 5 PM reminder (trigger from 17:00 onwards if not sent)
                    if ($now->format('H:i') >= '17:00' && !$visit->reminder_5pm_sent) {
                        if (VisitService::send5pmReminder($visit) && $visit->salesman) {
                            NotificationService::sendEmailVisitReminder(
                                $visit->salesman->email,
                                $visit->salesman->name,
                                $visit
                            );
                            $results['reminders_sent']++;
                        }
                    }

                    // Send 5:30 PM reminder (trigger from 17:30 onwards if not sent)
                    if ($now->format('H:i') >= '17:30' && !$visit->reminder_530pm_sent) {
                        if (VisitService::send530pmReminder($visit) && $visit->salesman) {
                            NotificationService::sendEmailVisitReminder(
                                $visit->salesman->email,
                                $visit->salesman->name,
                                $visit
                            );
                            $results['reminders_sent']++;
                        }
                    }
                }

                // Block at 8 PM (trigger from 20:00 onwards)
                // Skip if already blocked today (prevents re-blocking after admin unblock)
                $alreadyBlockedToday = $visit->blocked_at && $visit->blocked_at->isToday();
                if ($now->format('H:i') >= '20:00' && !$visit->isBlocked() && !$alreadyBlockedToday) {
                    if (VisitService::blockVisit($visit) && $visit->salesman) {
                        NotificationService::sendEmailVisitBlocked(
                            $visit->salesman->email,
                            $visit->salesman->name,
                            $visit
                        );
                        $results['visits_blocked']++;
                    }
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'visit_id' => $visit->id,
                    'error' => $e->getMessage(),
                ];
                Log::error('Error processing visit', [
                    'visit_id' => $visit->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }
}