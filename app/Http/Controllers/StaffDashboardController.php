<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Attendance;
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

        // Today's pending late reason (today only)
        $todayAttendance = Attendance::where('salesman_id', auth()->id())
            ->whereDate('date', Carbon::today())
            ->first();

        $pendingLateRecord = ($todayAttendance
            && $todayAttendance->is_late
            && empty($todayAttendance->late_reason))
            ? $todayAttendance
            : null;

        return view('staff.dashboard', [
            'isNonWorkingDay' => $isNonWorkingDay,
            'nonWorkingReason' => $nonWorkingReason,
            'pendingLateRecord' => $pendingLateRecord,
        ]);
    }
}
