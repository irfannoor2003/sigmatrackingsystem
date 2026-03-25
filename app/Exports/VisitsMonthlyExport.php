<?php

namespace App\Exports;

use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitsMonthlyExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function collection()
    {
        $query = Visit::with(['salesman', 'customer'])
            ->whereMonth('started_at', $this->month)
            ->whereYear('started_at', $this->year);

        $user = Auth::user();

        // Salesman → only own visits
        if ($user->role === 'salesman') {
            $query->where('salesman_id', $user->id);
        }

        // Admin & Sales Head → all visits
        return $query->orderBy('started_at')->get();
    }

    public function headings(): array
    {
        return [
            'Visit ID',
            'Salesman',
            'Customer',
            'Address',
            'Purpose',
            'Status',
            'Notes',
            'Distance (KM)',
            'Visit Date',
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->salesman->name ?? '-',
            $visit->customer->name ?? '-',
            $visit->customer->address ?? '-',
            $visit->purpose,
            ucfirst($visit->status),
            $visit->notes,
            $visit->distance_km,
            $visit->started_at->format('Y-m-d H:i'),
        ];
    }
}
