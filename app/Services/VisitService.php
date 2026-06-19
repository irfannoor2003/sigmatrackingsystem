<?php

namespace App\Services;

use App\Models\Visit;
use Illuminate\Support\Facades\Log;

class VisitService
{
    /**
     * Start a new visit
     */
    public static function startVisit(array $data): Visit
    {
        $visit = Visit::create($data);
        Log::info('Visit started', ['visit_id' => $visit->id, 'salesman_id' => $visit->salesman_id]);
        return $visit;
    }

    /**
     * Complete a visit
     */
    public static function completeVisit(Visit $visit, array $data = []): bool
    {
        if (!$visit->canBeCompleted()) {
            Log::warning('Attempt to complete invalid visit', ['visit_id' => $visit->id, 'status' => $visit->status]);
            return false;
        }

        $visit->update(array_merge($data, ['status' => 'completed', 'completed_at' => now()]));
        Log::info('Visit completed', ['visit_id' => $visit->id]);
        return true;
    }

    /**
     * Block a visit
     */
    public static function blockVisit(Visit $visit): bool
    {
        if (!$visit->canBeBlocked()) {
            Log::warning('Attempt to block invalid visit', ['visit_id' => $visit->id, 'status' => $visit->status]);
            return false;
        }

        $visit->update([
            'status' => 'blocked',
            'blocked_at' => now(),
        ]);
        Log::info('Visit blocked', ['visit_id' => $visit->id]);
        return true;
    }

    /**
     * Unblock a visit
     */
    public static function unblockVisit(Visit $visit, int $adminId): bool
    {
        if (!$visit->canBeUnblocked()) {
            Log::warning('Attempt to unblock invalid visit', ['visit_id' => $visit->id, 'status' => $visit->status]);
            return false;
        }

        $visit->update([
            'status' => 'started',
            'unblocked_at' => now(),
            'unblocked_by' => $adminId,
        ]);
        Log::info('Visit unblocked', ['visit_id' => $visit->id, 'admin_id' => $adminId]);
        return true;
    }

    /**
     * Send 5 PM reminder
     */
    public static function send5pmReminder(Visit $visit): bool
    {
        if ($visit->isCompleted() || $visit->isBlocked() || $visit->reminder_5pm_sent) {
            return false;
        }

        $visit->update(['reminder_5pm_sent' => true]);
        // TODO: Send email notification
        Log::info('5 PM reminder sent', ['visit_id' => $visit->id]);
        return true;
    }

    /**
     * Send 5:30 PM reminder
     */
    public static function send530pmReminder(Visit $visit): bool
    {
        if ($visit->isCompleted() || $visit->isBlocked() || $visit->reminder_530pm_sent) {
            return false;
        }

        $visit->update(['reminder_530pm_sent' => true]);
        Log::info('5:30 PM reminder sent', ['visit_id' => $visit->id]);
        return true;
    }

    /**
     * Check if visit needs blocking
     */
    public static function checkAndBlockVisits(): array
    {
        $blockedCount = 0;
        $now = now();

        // Get all started visits from today
        $visitsToCheck = Visit::where('status', 'started')
            ->whereDate('started_at', $now->format('Y-m-d'))
            ->get();

        foreach ($visitsToCheck as $visit) {
            // Check if it's 8 PM
            // Skip if already blocked today (prevents re-blocking after admin unblock)
            $alreadyBlockedToday = $visit->blocked_at && $visit->blocked_at->isToday();
            if ($now->format('H:i') === '20:00' && !$alreadyBlockedToday) {
                if (self::blockVisit($visit)) {
                    $blockedCount++;
                }
            }
        }

        return [
            'blocked_count' => $blockedCount,
            'total_checked' => $visitsToCheck->count(),
        ];
    }
}