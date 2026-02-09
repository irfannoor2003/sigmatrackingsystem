<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $salesmanId;
    protected $month;
    protected $year;

    public function __construct($salesmanId = null, $month = null, $year = null)
    {
        $this->salesmanId = $salesmanId;
        $this->month     = $month;
        $this->year      = $year;
    }

    public function collection()
    {
        $month = $this->month ?? now()->month;
        $year  = $this->year ?? now()->year;

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        /* ================= ATTENDANCE ================= */
        $attQuery = Attendance::with('salesman')
            ->whereBetween('date', [$start, $end]);

        if ($this->salesmanId) {
            $attQuery->where('salesman_id', $this->salesmanId);
        }

        $attendances = $attQuery->get()
            ->keyBy(fn ($a) =>
                $a->salesman_id . '_' . Carbon::parse($a->date)->format('Y-m-d')
            );

        /* ================= HOLIDAYS ================= */

// DB holidays
$dbHolidays = Holiday::whereBetween('date', [$start, $end])
    ->get()
    ->keyBy(fn ($h) => Carbon::parse($h->date)->format('Y-m-d'));

// Config Pakistan holidays (MM-DD)
$configHolidays = collect(config('pakistan_holidays'))
    ->mapWithKeys(function ($title, $md) use ($year) {

        $date = Carbon::createFromFormat('Y-m-d', $year . '-' . $md);

        return [
            $date->format('Y-m-d') => (object)[
                'title' => $title
            ]
        ];
    });

// 🔥 IMPORTANT FIX HERE
$holidays = $dbHolidays->toBase()->merge($configHolidays);


        /* ================= SALESMEN ================= */
        $salesmen = $this->salesmanId
            ? User::where('id', $this->salesmanId)->get()
            : User::whereIn('role', [
                'salesman','it','account','store','office_boy'
            ])->get();

        $rows = collect();

        foreach ($salesmen as $salesman) {

            $date = $start->copy();

            while ($date <= $end) {

                $dateKey = $date->format('Y-m-d');
                $attKey  = $salesman->id . '_' . $dateKey;

                $attendance = $attendances->get($attKey);
                $holiday    = $holidays->get($dateKey);

                /* ================= STATUS PRIORITY ================= */
              if ($holiday) {
    $status  = 'Holiday';
    $remarks = $holiday->title;

} elseif ($date->isSunday()) {
    $status  = 'Sunday';
    $remarks = 'Weekly Off';

} elseif ($attendance && $attendance->status === 'leave') {
    $status  = 'Leave';
    $remarks = $attendance->note ?: '--';

} elseif ($attendance && $attendance->short_leave) {
    $status  = 'Short Leave';
    $remarks = $attendance->note ?: 'Late arrival / Early leave';

} elseif ($attendance) {
    $status  = 'Present';
    $remarks = $attendance->note ?: '--';

} else {
    $status  = 'Absent';
    $remarks = '--';
}

                /* ================= WORK HOURS ================= */
                $workHours = ($attendance && $attendance->total_minutes)
                    ? floor($attendance->total_minutes / 60) . ':' .
                      str_pad($attendance->total_minutes % 60, 2, '0', STR_PAD_LEFT)
                    : '0:00';

                $rows->push([
                    'Date'           => $date->format('d M Y'),
                    'Day'            => $date->format('l'),
                    'Name'           => $salesman->name,
                    'Role'           => ucfirst($salesman->role),
                    'Status'         => $status,
                    'Clock In'       => $attendance?->clock_in?->format('h:i A') ?? '-',
                    'Clock Out'      => $attendance?->clock_out?->format('h:i A') ?? '-',
                    'Work Hours'     => $workHours,
                    'Reason / Note'  => $remarks,
                ]);

                $date->addDay();
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Day',
            'Name',
            'Role',
            'Status',
            'Clock In',
            'Clock Out',
            'Work Hours',
            'Reason / Note',
        ];
    }
}
