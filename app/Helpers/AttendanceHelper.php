<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Holiday;

class AttendanceHelper
{
    public static function isNonWorkingDay($date = null): bool
    {
        $date = $date
            ? Carbon::parse($date)
            : Carbon::today();

        // 1️⃣ Sunday
        if ($date->isSunday()) {
            return true;
        }

        // 2️⃣ Admin-marked holidays (DB)
        if (Holiday::isHoliday($date)) {
            return true;
        }

        // 3️⃣ Pakistan fixed holidays (config)
        $monthDay = $date->format('m-d');

        if (array_key_exists($monthDay, config('pakistan_holidays'))) {
            return true;
        }

        return false;
    }

    public static function nonWorkingReason($date = null): ?string
    {
        $date = $date
            ? Carbon::parse($date)
            : Carbon::today();

        if ($date->isSunday()) {
            return 'Sunday (Weekly Off)';
        }

        if (Holiday::isHoliday($date)) {
            return Holiday::title($date);
        }

        $monthDay = $date->format('m-d');

        return config('pakistan_holidays')[$monthDay] ?? null;
    }
}
