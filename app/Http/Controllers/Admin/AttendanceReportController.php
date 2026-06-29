<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/* EXPORTS */
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceReportController extends Controller
{
    /**
     * ADMIN: Attendance Index (Month + Staff filter + Insights)
     */
    public function index(Request $request)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date  = Carbon::createFromFormat('Y-m', $monthInput);
        $month = $date->month;
        $year  = $date->year;

        $staffId = $request->staff;
        $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];

        /** Staff list */
        $allStaff = User::whereIn('role', $roles)
            ->orderBy('name')
            ->get();

        /** Attendance stats */
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

        /** Insights */
        $bestAttendance = $attendanceStats->sortByDesc('presents')->first();
        $mostLeaves     = $attendanceStats->sortByDesc('leaves')->first();
        $hardestWorker  = $attendanceStats->sortByDesc('minutes')->first();

        $totalStaff    = $attendanceStats->count();
        $totalPresents = $attendanceStats->sum('presents');
        $daysInMonth   = $date->daysInMonth;

        $attendanceRate = $totalStaff > 0
            ? round(($totalPresents / ($totalStaff * $daysInMonth)) * 100)
            : 0;

        if ($attendanceStats->isEmpty()) {
            return view('admin.attendance.index', compact(
                'allStaff',
                'monthInput',
                'staffId',
                'attendanceRate'
            ))->with([
                'staff' => collect(),
                'bestAttendance' => null,
                'mostLeaves' => null,
                'hardestWorker' => null,
            ]);
        }

        /** Staff table */
        $staffQuery = User::whereIn('role', $roles)
            ->whereIn('id', $attendanceStats->pluck('salesman_id'));

        if ($staffId) {
            $staffQuery->where('id', $staffId);
        }

        $staff = $staffQuery->get()->map(function ($user) use ($attendanceStats) {
            $stats = $attendanceStats->firstWhere('salesman_id', $user->id);

            $user->monthAttendance = $stats->presents ?? 0;
            $user->monthLeaves     = $stats->leaves ?? 0;
            $user->shortLeaves     = $stats->short_leaves ?? 0;

            return $user;
        });

        return view('admin.attendance.index', compact(
            'staff',
            'allStaff',
            'monthInput',
            'staffId',
            'bestAttendance',
            'mostLeaves',
            'hardestWorker',
            'attendanceRate'
        ));
    }

    /**
     * ADMIN: Single Staff Monthly Attendance (Calendar View)
     */
    public function staffReport($id, Request $request)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $monthInput);
        $today = now()->toDateString();

        $user = User::findOrFail($id);

        /** Attendance records */
        $attendanceRecords = Attendance::where('salesman_id', $id)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->get()
            ->keyBy(fn ($att) => $att->date->toDateString());

        /** Admin holidays */
        $dbHolidays = Holiday::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->pluck('title', 'date')
            ->toArray();

        $pakHolidays = config('pakistan_holidays', []);

        $calendar = collect();

        for ($day = 1; $day <= $date->daysInMonth; $day++) {
            $currentDate = Carbon::create($date->year, $date->month, $day);
            $dateString  = $currentDate->toDateString();
            $md          = $currentDate->format('m-d');

            $dayData = [
                'date' => $dateString,
                'day'  => $currentDate->format('l'),
                'status' => $dateString > $today ? 'future' : 'absent',
                'label'  => $dateString > $today ? 'Upcoming' : 'Absent',
                'attendance' => null,
            ];

            /** Admin holiday */
            if (isset($dbHolidays[$dateString])) {
                $dayData['status'] = 'off';
                $dayData['label']  = $dbHolidays[$dateString];
            }
            /** Pakistan holiday */
            elseif (isset($pakHolidays[$md])) {
                $dayData['status'] = 'off';
                $dayData['label']  = $pakHolidays[$md];
            }
            /** Sunday */
            elseif ($currentDate->isSunday()) {
                $dayData['status'] = 'off';
                $dayData['label']  = 'Sunday';
            }
            /** Attendance */
            elseif ($attendanceRecords->has($dateString)) {
                $attendance = $attendanceRecords[$dateString];
                $dayData['attendance'] = $attendance;

                if ($attendance->status === 'leave') {
                    $dayData['status'] = 'leave';
                    $dayData['label']  = 'Leave';
                } elseif ($attendance->short_leave) {
                    $dayData['status'] = 'short_leave';
                    $dayData['label']  = 'Short Leave';
                } else {
                    $dayData['status'] = 'present';
                    $dayData['label']  = 'Present';
                }
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

        return view('admin.attendance.staff', compact(
            'user',
            'calendar',
            'monthInput',
            'totalPresents',
            'totalAbsents',
            'totalLeaves',
            'totalShortLeaves',
            'totalLates'
        ));
    }

    /**
     * ADMIN: Mark Leave
     */
    public function markLeave(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        Attendance::updateOrCreate(
            [
                'salesman_id' => $id,
                'date' => $request->date,
            ],
            [
                'status' => 'leave',
                'note' => $request->note,
                'office_verified' => true,
                'short_leave' => false,
            ]
        );

        return back()->with('success', 'Leave marked successfully.');
    }

    /**
     * ADMIN: Update Attendance (Apply Short Leave Rules)
     */
    public function updateAttendance(Request $request, $attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);

        $clockOut = now();
        $shortLeave = false;

        if ($attendance->clock_in && $attendance->clock_in->format('H:i') >= '12:00') {
            $shortLeave = true;
        }

        if ($clockOut->format('H:i') < '17:00') {
            $shortLeave = true;
        }

        $attendance->update([
            'clock_out' => $clockOut,
            'total_minutes' => $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes($clockOut)
                : 0,
            'short_leave' => $shortLeave,
        ]);

        return back()->with('success', 'Attendance updated successfully.');
    }

/**
 * EXPORT: ALL Attendance (Excel)
 */
public function exportAllExcel(Request $request)
{
    $monthInput = $request->month ?? now()->format('Y-m');
    $date = Carbon::createFromFormat('Y-m', $monthInput);

    return Excel::download(
        new AttendanceExport(
            null,               // ❌ no staff filter
            $date->month,
            $date->year
        ),
        'attendance_all_' . $monthInput . '.xlsx'
    );
}

/**
 * EXPORT: SINGLE Staff Attendance (Excel)
 */
public function exportSingleExcel($id, Request $request)
{
    $monthInput = $request->month ?? now()->format('Y-m');
    $date = Carbon::createFromFormat('Y-m', $monthInput);

    $user = User::findOrFail($id);

    return Excel::download(
        new AttendanceExport(
            $user->id,
            $date->month,
            $date->year
        ),
        'attendance_' . $user->name . '_' . $monthInput . '.xlsx'
    );
}


    /**
     * ADMIN: Today Leave Requests
     */
    public function leaveRequests()
    {
        $today = Carbon::today()->toDateString();

        $leaves = Attendance::with('user')
            ->where('date', $today)
            ->where('status', 'leave')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.attendance.leave-requests', compact('leaves'));
    }


    public function exportRange(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    return Excel::download(
        new AttendanceExport(
            $request->staff,
            null,
            null,
            $request->start_date,
            $request->end_date
        ),
        'attendance_'.$request->start_date.'_to_'.$request->end_date.'.xlsx'
    );
}
}
