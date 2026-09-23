<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OutOfCityVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManualVisitController extends Controller
{
    public function store(Request $request, $userId)
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

            $attendance = Attendance::updateOrCreate(
                [
                    'salesman_id' => $userId,
                    'date'        => $start->toDateString(),
                ],
                [
                    'status'          => 'present',

                    'clock_in'        => $clockIn,
                    'clock_out'       => $clockOut,
                    'total_minutes'   => $clockIn->diffInMinutes($clockOut),

                    'office_verified' => false,     // manual entry
                    'auto_clock_out'  => false,
                    'short_leave'     => false,
                    'manual_visit'    => true,

                    'note'            => $request->note,
                ]
            );

            OutOfCityVisit::create([
                'attendance_id' => $attendance->id,
                'salesman_id'   => $userId,
                'status'        => 'pending',
            ]);

            $start->addDay();
        }

        return back()->with('success', 'Manual visit marked successfully.');
    }
}
