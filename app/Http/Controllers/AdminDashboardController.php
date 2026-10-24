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
        $totalSalesmen = User::active()->where('role', 'salesman')->count();

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

        // Visit Activities (include starts & completions; order by latest change)
        $visitActivities = Visit::with('salesman', 'customer')
            ->latest('updated_at')
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

    public function sidebarActivity()
    {
        $attendances = Attendance::with('salesman')
            ->whereDate('date', Carbon::today())
            ->whereNotNull('clock_in')
            ->orderByDesc('clock_in')
            ->limit(3)
            ->get()
            ->map(function ($a) {
                return [
                    'name' => $a->salesman->name ?? '—',
                    'status' => $a->status,
                    'clock_in' => $a->clock_in?->format('H:i'),
                    'clock_out' => $a->clock_out?->format('H:i'),
                    'clock_in_iso' => $a->clock_in?->toIso8601String(),
                    'clock_out_iso' => $a->clock_out?->toIso8601String(),
                    'total_minutes' => $a->total_minutes,
                    'open' => is_null($a->clock_out),
                ];
            });

        $visits = Visit::with('salesman', 'customer')
            ->orderByDesc('started_at')
            ->limit(3)
            ->get()
            ->map(function ($v) {
                return [
                    'salesman' => $v->salesman->name ?? '—',
                    'customer' => $v->customer->name ?? '—',
                    'status' => $v->status,
                    'started_at' => $v->started_at?->format('H:i'),
                    'completed_at' => $v->completed_at?->format('H:i'),
                    'cancelled_at' => $v->cancelled_at?->format('H:i'),
                    'started_iso' => $v->started_at?->toIso8601String(),
                    'completed_iso' => $v->completed_at?->toIso8601String(),
                    'cancelled_iso' => $v->cancelled_at?->toIso8601String(),
                    'open' => $v->status === 'started',
                ];
            });

        return response()->json([
            'attendances' => $attendances,
            'visits' => $visits,
            'cancelled_visits' => Visit::where('status', 'cancelled')->count(),
        ]);
    }

public function todayAttendance()
{
    $today = Carbon::today();
    $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];

    $allStaff = User::active()->whereIn('role', $roles)
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
