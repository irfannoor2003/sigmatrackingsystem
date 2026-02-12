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

        return view('admin.dashboard', compact(
            'totalSalesmen',
            'workingToday',
            'attendanceActivities',
            'visitActivities'
        ));
         return view('dashboard', [
        'role' => auth()->user()->role, // pass current role
        'attendanceActivities' => $attendanceActivities,
    ]);
    }

   public function todayAttendance()
{
    $today = Carbon::today();
    $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];

    // Eager load today's attendance only
    $allStaff = User::whereIn('role', $roles)
        ->with(['attendances' => function($query) use ($today) {
            $query->whereDate('date', $today);
        }])
        ->orderBy('name')
        ->get();

    // Map through staff to attach the specific record for easier access in Blade
    $presentStaff = $allStaff->filter(function($user) {
        return $user->attendances->isNotEmpty();
    })->map(function($user) {
        // Create a temporary property for the blade to read easily
        $user->today_record = $user->attendances->first();
        return $user;
    });

    $absentStaff = $allStaff->filter(function($user) {
        return $user->attendances->isEmpty();
    });

    return view('admin.attendance.today', compact('presentStaff', 'absentStaff', 'today'));
}
}
