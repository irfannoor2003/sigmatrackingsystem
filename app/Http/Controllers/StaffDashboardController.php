<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Helpers\AttendanceHelper;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        // ✅ Use AttendanceHelper to detect Sundays, DB holidays, and config holidays
        $isNonWorkingDay = AttendanceHelper::isNonWorkingDay($today);
        $nonWorkingReason = AttendanceHelper::nonWorkingReason($today);

        return view('staff.dashboard', [
            'isNonWorkingDay' => $isNonWorkingDay,
            'nonWorkingReason' => $nonWorkingReason,
        ]);
    }
}
