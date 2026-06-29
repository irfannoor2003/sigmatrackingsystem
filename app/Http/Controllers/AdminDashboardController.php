<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total Salesmen
        $totalSalesmen = User::where('role', 'salesman')->count();

        // Staff Working Today (clocked in but not clocked out)
        $workingToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->whereNull('clock_out')
            ->count();

        // Attendance Activities
        $attendanceActivities = Attendance::with('salesman')
            ->latest()
            ->limit(6)
            ->get();

        // Visit Activities
        $visitActivities = Visit::with('salesman', 'customer')
            ->latest()
            ->limit(6)
            ->get();

        $blockedVisits = Visit::where('status', 'blocked')->count();

        // Late Staff (clocked in after 10:15 AM)
        $lateStaff = Attendance::with('salesman')
            ->whereDate('date', $today)
            ->where('status', 'present')
            ->where('clock_in', '>', Carbon::today()->setTime(10, 16))
            ->get();

        return view('admin.dashboard', compact(
            'totalSalesmen',
            'workingToday',
            'attendanceActivities',
            'visitActivities',
            'blockedVisits',
            'lateStaff'
        ));
    }

public function lateStaff()
{
    $today = Carbon::today();

    $lateStaff = Attendance::with('salesman')
        ->whereDate('date', $today)
        ->where('status', 'present')
        ->where('clock_in', '>', Carbon::today()->setTime(10, 16))
        ->get();

    $allTodayAttendance = Attendance::with('salesman')
        ->whereDate('date', $today)
        ->whereNotNull('clock_in')
        ->get();

    return view('admin.attendance.late-staff', compact('lateStaff', 'today', 'allTodayAttendance'))
        ->with('backRoute', route('admin.dashboard'));
}

public function todayAttendance()
{
    $today = Carbon::today();
    $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];

    $allStaff = User::whereIn('role', $roles)
        ->with(['attendances' => function ($query) use ($today) {
            $query->whereDate('date', $today);
        }])
        ->orderBy('name')
        ->get();

    // ✅ PRESENT
    $presentStaff = $allStaff->filter(function ($user) {
        return $user->attendances->first()?->status === 'present';
    })->map(function ($user) {
        $user->today_record = $user->attendances->first();
        return $user;
    });

    // 🟡 ON LEAVE
    $leaveStaff = $allStaff->filter(function ($user) {
        return $user->attendances->first()?->status === 'leave';
    })->map(function ($user) {
        $user->today_record = $user->attendances->first();
        return $user;
    });

    // 🔴 ABSENT (no record at all)
    $absentStaff = $allStaff->filter(function ($user) {
        return $user->attendances->isEmpty();
    });

    return view('admin.attendance.today', compact(
        'presentStaff',
        'leaveStaff',
        'absentStaff',
        'today'
    ));
}

}
