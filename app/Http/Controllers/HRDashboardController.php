<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class HrDashboardController extends Controller
{
    /**
     * 1. HR DASHBOARD OVERVIEW
     */
    public function index()
    {
        $today = Carbon::today();
        $totalStaff = User::whereNotIn('role', ['admin', 'hr', 'saleshead'])->count();

        $presentCount = Attendance::whereDate('date', $today)
            ->where(function ($q) {
                $q->where('status', 'present')
                  ->orWhere('manual_visit', true);
            })
            ->distinct('salesman_id')
            ->count('salesman_id');

        $leaveCount = Attendance::whereDate('date', $today)
            ->where('status', 'leave')
            ->distinct('salesman_id')
            ->count('salesman_id');

        $absentCount = $totalStaff - $presentCount - $leaveCount;
        if ($absentCount < 0) $absentCount = 0;

        $attendanceActivities = Attendance::with('salesman')
            ->latest()
            ->limit(6)
            ->get();

        // Late Staff (clocked in after 10:15 AM)
        $lateStaff = Attendance::with('salesman')
            ->whereDate('date', $today)
            ->where('status', 'present')
            ->where('clock_in', '>', Carbon::today()->setTime(10, 16))
            ->get();

        return view('hr.dashboard', compact(
            'totalStaff',
            'presentCount',
            'leaveCount',
            'absentCount',
            'attendanceActivities',
            'lateStaff'
        ));
    }

    /**
     * LATE STAFF PAGE
     */
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
            ->with('backRoute', route('hr.dashboard'));
    }

    /**
     * 2. ATTENDANCE REPORTING (MONTHLY & FILTERS)
     */
    public function attendanceIndex(Request $request)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date  = Carbon::createFromFormat('Y-m', $monthInput);
        $month = $date->month;
        $year  = $date->year;

        $staffId = $request->staff;
        $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];

        $allStaff = User::whereIn('role', $roles)->orderBy('name')->get();

        $attendanceStats = Attendance::select(
                'salesman_id',
                DB::raw("SUM(status = 'present') as presents"),
                DB::raw("SUM(status = 'leave') as leaves"),
                DB::raw("SUM(short_leave = 1) as short_leaves"),
                DB::raw("SUM(total_minutes) as minutes")
            )
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('salesman_id')
            ->get();

        $bestAttendance = $attendanceStats->sortByDesc('presents')->first();
        $mostLeaves     = $attendanceStats->sortByDesc('leaves')->first();
        $hardestWorker  = $attendanceStats->sortByDesc('minutes')->first();

        $totalStaffCount = $attendanceStats->count();
        $totalPresents   = $attendanceStats->sum('presents');
        $attendanceRate  = $totalStaffCount > 0
            ? round(($totalPresents / ($totalStaffCount * $date->daysInMonth)) * 100)
            : 0;

        $staffQuery = User::whereIn('role', $roles)
            ->whereIn('id', $attendanceStats->pluck('salesman_id'));
        if ($staffId) { $staffQuery->where('id', $staffId); }

        $staff = $staffQuery->get()->map(function ($user) use ($attendanceStats) {
            $stats = $attendanceStats->firstWhere('salesman_id', $user->id);
            $user->monthAttendance = $stats->presents ?? 0;
            $user->monthLeaves     = $stats->leaves ?? 0;
            $user->shortLeaves     = $stats->short_leaves ?? 0;
            return $user;
        });

        return view('hr.attendance.index', compact(
            'staff', 'allStaff', 'monthInput', 'staffId',
            'bestAttendance', 'mostLeaves', 'hardestWorker', 'attendanceRate'
        ));
    }

    /**
     * 3. SINGLE STAFF CALENDAR REPORT
     */
    public function staffReport($id, Request $request)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $monthInput);
        $today = now()->toDateString();
        $user = User::findOrFail($id);

        $attendanceRecords = Attendance::where('salesman_id', $id)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->get()
            ->keyBy(fn ($att) => $att->date->toDateString());

        $dbHolidays = Holiday::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->pluck('title', 'date')->toArray();

        $pakHolidays = config('pakistan_holidays', []);
        $calendar = collect();

        for ($day = 1; $day <= $date->daysInMonth; $day++) {
            $currentDate = Carbon::create($date->year, $date->month, $day);
            $dateString  = $currentDate->toDateString();
            $md = $currentDate->format('m-d');

            $dayData = [
                'date' => $dateString,
                'day'  => $currentDate->format('l'),
                'status' => $dateString > $today ? 'future' : 'absent',
                'label'  => $dateString > $today ? 'Upcoming' : 'Absent',
                'attendance' => null,
            ];

            if (isset($dbHolidays[$dateString])) {
                $dayData['status'] = 'off'; $dayData['label'] = $dbHolidays[$dateString];
            } elseif (isset($pakHolidays[$md])) {
                $dayData['status'] = 'off'; $dayData['label'] = $pakHolidays[$md];
            } elseif ($currentDate->isSunday()) {
                $dayData['status'] = 'off'; $dayData['label'] = 'Sunday';
            } elseif ($attendanceRecords->has($dateString)) {
                $attendance = $attendanceRecords[$dateString];
                $dayData['attendance'] = $attendance;
                $dayData['status'] = $attendance->status === 'leave' ? 'leave' : ($attendance->short_leave ? 'short_leave' : 'present');
                $dayData['label'] = ucfirst(str_replace('_', ' ', $dayData['status']));
            }
            $calendar->push($dayData);
        }

        $totalPresents  = $calendar->where('status', 'present')->count();
        $totalAbsents   = $calendar->where('status', 'absent')->count();
        $totalLeaves    = $calendar->where('status', 'leave')->count();
        $totalShortLeaves = $calendar->where('status', 'short_leave')->count();

        $totalLates = $calendar->where('status', 'present')->filter(function ($day) {
            if (!$day['attendance'] || !$day['attendance']->clock_in) {
                return false;
            }
            $lateThreshold = $day['attendance']->date->copy()->setTime(10, 16);
            return Carbon::parse($day['attendance']->clock_in)->gt($lateThreshold);
        })->count();

        return view('hr.attendance.staff', compact(
            'user', 'calendar', 'monthInput',
            'totalPresents', 'totalAbsents', 'totalLeaves', 'totalShortLeaves', 'totalLates'
        ));
    }

    /**
     * 4. LEAVE & MANUAL ACTIONS
     */
    public function leaveRequests()
    {
        $leaves = Attendance::with('salesman')
            ->where('status', 'leave')
            ->latest()
            ->paginate(10);

        return view('hr.attendance.leave-requests', compact('leaves'));
    }

    public function markLeave(Request $request, $id)
    {
        $request->validate(['date' => 'required|date', 'note' => 'nullable|string']);
        Attendance::updateOrCreate(
            ['salesman_id' => $id, 'date' => $request->date],
            ['status' => 'leave', 'note' => $request->note, 'office_verified' => true, 'short_leave' => false]
        );
        return back()->with('success', 'Leave marked successfully.');
    }

    public function storeManualVisit(Request $request, $userId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'note'       => 'required|string',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date ?? $request->start_date);

        while ($start->lte($end)) {
            $clockIn  = $start->copy()->setTime(10, 0);
            $clockOut = $start->copy()->setTime(17, 0);

            Attendance::updateOrCreate(
                ['salesman_id' => $userId, 'date' => $start->toDateString()],
                [
                    'status' => 'present', 'clock_in' => $clockIn, 'clock_out' => $clockOut,
                    'total_minutes' => $clockIn->diffInMinutes($clockOut),
                    'office_verified' => false, 'manual_visit' => true, 'note' => $request->note
                ]
            );
            $start->addDay();
        }
        return back()->with('success', 'Manual visit marked.');
    }

    /**
     * 5. HOLIDAY MANAGEMENT
     */
    public function storeHoliday(Request $request)
    {
        $request->validate(['title' => 'required|string', 'start_date' => 'required|date']);
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date ?? $request->start_date);

        while ($start->lte($end)) {
            Holiday::updateOrCreate(['date' => $start->toDateString()], ['title' => $request->title]);
            $start->addDay();
        }
        return back()->with('success', 'Holiday saved.');
    }

    /**
     * 6. EXPORTS
     */
    public function exportExcel(Request $request, $id = null)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $monthInput);
        $fileName = $id ? "attendance_staff_{$id}_{$monthInput}.xlsx" : "attendance_all_{$monthInput}.xlsx";

        return Excel::download(new AttendanceExport($id, $date->month, $date->year), $fileName);
    }

    public function todayAttendance()
{
    $todayDate = now(); // Carbon instance

    // All staff except admin
    $allStaff = User::whereNotIn('role', ['admin', 'hr', 'saleshead'])->orderBy('name')->get();

    // Staff present today (including manual visits)
    $presentStaff = Attendance::with('salesman')
        ->whereDate('date', $todayDate)
        ->where(function($q){
            $q->where('status', 'present')
              ->orWhere('manual_visit', true);
        })
        ->get()
        ->map(fn($a) => $a->salesman)
        ->filter(); // remove nulls

    // Staff on leave today
    $leaveStaff = Attendance::with('salesman')
        ->whereDate('date', $todayDate)
        ->where('status', 'leave')
        ->get()
        ->map(fn($a) => $a->salesman)
        ->filter();

    // Absent staff (exclude present and leave)
    $absentStaff = $allStaff->diff($presentStaff)->diff($leaveStaff);

    return view('hr.attendance.today', compact('todayDate', 'presentStaff', 'leaveStaff', 'absentStaff'));
}

/**
 * Export Attendance By Date Range (HR)
 */
public function exportRange(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'staff'      => 'nullable|exists:users,id',
    ]);

    $startDate = $request->start_date;
    $endDate   = $request->end_date;
    $staffId   = $request->staff;

    $fileName = $staffId
        ? "attendance_staff_{$staffId}_{$startDate}_to_{$endDate}.xlsx"
        : "attendance_all_{$startDate}_to_{$endDate}.xlsx";

    return Excel::download(
        new AttendanceExport(
            $staffId,
            null,
            null,
            $startDate,
            $endDate
        ),
        $fileName
    );
}

}
