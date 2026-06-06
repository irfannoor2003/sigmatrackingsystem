<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $salesmanId;
    protected $month;
    protected $year;
    protected $startDate;
    protected $endDate;

    public function __construct(
        $salesmanId = null,
        $month = null,
        $year = null,
        $startDate = null,
        $endDate = null
    ) {
        $this->salesmanId = $salesmanId;
        $this->month      = $month;
        $this->year       = $year;
        $this->startDate  = $startDate;
        $this->endDate    = $endDate;
    }

    public function collection()
    {
        /*
        |--------------------------------------------------------------------------
        | DATE RANGE OR MONTH EXPORT
        |--------------------------------------------------------------------------
        */
        if ($this->startDate && $this->endDate) {

            $start = Carbon::parse($this->startDate)->startOfDay();
            $end   = Carbon::parse($this->endDate)->endOfDay();

        } else {

            $month = $this->month ?? now()->month;
            $year  = $this->year ?? now()->year;

            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end   = Carbon::create($year, $month, 1)->endOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
        |--------------------------------------------------------------------------
        */
        $attQuery = Attendance::with(['salesman', 'markedBy'])
            ->whereBetween('date', [$start, $end]);

        if ($this->salesmanId) {
            $attQuery->where('salesman_id', $this->salesmanId);
        }

        $attendances = $attQuery->get()
            ->keyBy(function ($attendance) {
                return $attendance->salesman_id . '_' .
                    Carbon::parse($attendance->date)->format('Y-m-d');
            });

        /*
        |--------------------------------------------------------------------------
        | HOLIDAYS FROM DATABASE
        |--------------------------------------------------------------------------
        */
        $dbHolidays = Holiday::whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(function ($holiday) {
                return Carbon::parse($holiday->date)->format('Y-m-d');
            });

        /*
        |--------------------------------------------------------------------------
        | PAKISTAN CONFIG HOLIDAYS
        |--------------------------------------------------------------------------
        */
        $configHolidays = collect();

        $currentYear = $start->year;

        while ($currentYear <= $end->year) {

            $yearHolidays = collect(config('pakistan_holidays', []))
                ->mapWithKeys(function ($title, $md) use ($currentYear) {

                    $date = Carbon::createFromFormat(
                        'Y-m-d',
                        $currentYear . '-' . $md
                    );

                    return [
                        $date->format('Y-m-d') => (object) [
                            'title' => $title
                        ]
                    ];
                });

            $configHolidays = $configHolidays->merge($yearHolidays);

            $currentYear++;
        }

        $holidays = $dbHolidays->toBase()->merge($configHolidays);

        /*
        |--------------------------------------------------------------------------
        | STAFF LIST
        |--------------------------------------------------------------------------
        */
        $salesmen = $this->salesmanId
            ? User::where('id', $this->salesmanId)->get()
            : User::whereIn('role', [
                'salesman',
                'it',
                'account',
                'store',
                'office_boy'
            ])->orderBy('name')->get();

        $rows = collect();

        foreach ($salesmen as $salesman) {

            $date = $start->copy();

            while ($date <= $end) {

                $dateKey = $date->format('Y-m-d');
                $attKey  = $salesman->id . '_' . $dateKey;

                $attendance = $attendances->get($attKey);
                $holiday    = $holidays->get($dateKey);

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */
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

                /*
                |--------------------------------------------------------------------------
                | WORK HOURS
                |--------------------------------------------------------------------------
                */
                $workHours = '0:00';

                if ($attendance && $attendance->total_minutes) {
                    $workHours =
                        floor($attendance->total_minutes / 60) . ':' .
                        str_pad(
                            $attendance->total_minutes % 60,
                            2,
                            '0',
                            STR_PAD_LEFT
                        );
                }

                $rows->push([
                    'Date'          => $date->format('d M Y'),
                    'Day'           => $date->format('l'),
                    'Name'          => $salesman->name,
                    'Role'          => ucfirst($salesman->role),
                    'Status'        => $status,
                    'Clock In'      => $attendance?->clock_in?->format('h:i A') ?? '-',
                    'Clock Out'     => $attendance?->clock_out?->format('h:i A') ?? '-',
                    'Work Hours'    => $workHours,
                    'Reason / Note' => $remarks,
                    'Marked By'     => $attendance?->markedBy?->name ?? '-',
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
            'Marked By',
        ];
    }
}
