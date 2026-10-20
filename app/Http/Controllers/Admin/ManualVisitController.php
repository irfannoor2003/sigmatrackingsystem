<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OutOfCityVisit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManualVisitController extends Controller
{
    /**
     * Only salesmen can go on out-of-city / manual visits.
     */
    private function guardSalesman($userId): User
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'salesman') {
            abort(403, 'Manual visits are only available for salesmen.');
        }

        return $user;
    }

    public function store(Request $request, $userId)
    {
        $user = $this->guardSalesman($userId);

        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'clock_in'   => 'nullable|date_format:H:i',
            'clock_out'  => 'nullable|date_format:H:i',
            'note'       => 'required|string|max:255',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date ?? $request->start_date)->startOfDay();

        $clockInTime  = $request->clock_in ?: '10:00';
        $clockOutTime = $request->clock_out ?: '17:00';

        $marked = 0;
        $skippedSundays = [];

        while ($start->lte($end)) {

            // Sundays are weekly off — never mark a manual visit on one.
            if ($start->isSunday()) {
                $skippedSundays[] = $start->toDateString();
                $start->addDay();
                continue;
            }

            $clockIn  = $start->copy()->setTimeFromTimeString($clockInTime);
            $clockOut = $start->copy()->setTimeFromTimeString($clockOutTime);

            if ($clockOut->lessThan($clockIn)) {
                $clockOut = $clockIn->copy()->addDay();
            }

            $attendance = Attendance::updateOrCreate(
                [
                    'salesman_id' => $user->id,
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

                    'marked_by'       => auth()->id(),
                    'note'            => $request->note,
                ]
            );

            if (! OutOfCityVisit::where('attendance_id', $attendance->id)->exists()) {
                OutOfCityVisit::create([
                    'attendance_id' => $attendance->id,
                    'salesman_id'   => $user->id,
                    'status'        => 'pending',
                ]);
            }

            $marked++;
            $start->addDay();
        }

        if ($marked === 0) {
            return redirect()
                ->route('admin.attendance.staff', ['id' => $user->id, 'month' => now()->format('Y-m')])
                ->with('warning', 'No manual visit was marked — the selected range only contains Sundays.');
        }

        $message = 'Manual visit marked successfully for ' . $marked . ' day' . ($marked > 1 ? 's' : '') . '.';

        if ($skippedSundays) {
            $message .= ' Sundays were skipped (weekly off).';
        }

        return redirect()
            ->route('admin.attendance.staff', ['id' => $user->id, 'month' => now()->format('Y-m')])
            ->with('success', $message);
    }
}
