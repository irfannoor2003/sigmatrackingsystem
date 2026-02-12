<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Helpers\AttendanceHelper;
use Carbon\Carbon;

class AttendanceService
{
    public function processScan($userId, $ip)
    {
        $today = today()->toDateString();
        $now = now();

        // 1. Check Global Conditions (Holidays / Non-Working Days)
        if (AttendanceHelper::isNonWorkingDay($today) || Holiday::isHoliday($today)) {
            return ['status' => 'error', 'message' => 'Attendance disabled today.'];
        }

        // 2. Find or Init Attendance
        $attendance = Attendance::firstOrNew([
            'salesman_id' => $userId,
            'date'        => $today
        ]);

        if (!$attendance->exists) {
            // Check cut-off time (3 PM)
            if ($now->hour >= 15) {
                return ['status' => 'error', 'message' => 'Clock-in blocked after 3 PM.'];
            }

            // --- CLOCK IN ---
            $attendance->status = 'present';
            $attendance->clock_in = $now;
            $attendance->checkin_method = 'biometric';
            $attendance->checkin_ip = $ip;
            $attendance->office_verified = true;
            $attendance->qr_verified = true;

            // Arriving after 12 PM is a short leave
            $attendance->short_leave = $now->format('H:i') >= '12:00';

            $attendance->save();
            return ['status' => 'success', 'type' => 'in'];
        } else {
            // --- CLOCK OUT ---
            if ($attendance->status === 'leave') {
                return ['status' => 'error', 'message' => 'User is on leave.'];
            }

            $attendance->clock_out = $now;
            $attendance->total_minutes = $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes($now)
                : 0;

            // If they arrive late OR leave before 5 PM, it's a short leave
            $attendance->short_leave = $attendance->short_leave || ($now->format('H:i') < '17:00');

            $attendance->save();
            return ['status' => 'success', 'type' => 'out'];
        }
    }
}
