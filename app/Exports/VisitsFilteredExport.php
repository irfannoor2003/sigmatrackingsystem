<?php

namespace App\Exports;

use App\Models\Visit;
use App\Models\VisitPitstop;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitsFilteredExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $user = Auth::user();

        $query = Visit::with(['salesman', 'customer', 'pitstops.customer', 'pitstops.visit.salesman']);

        /** 🔐 ROLE RULES */
        if ($user->role === 'salesman') {
            $query->where('salesman_id', $user->id);
        } else {
            if (!empty($this->filters['salesman_id'])) {
                $query->where('salesman_id', $this->filters['salesman_id']);
            }
        }

        /** 📅 DATE FILTERS */
        if (!empty($this->filters['from_date'])) {
            $query->whereDate('started_at', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('started_at', '<=', $this->filters['to_date']);
        }

        /** 📌 STATUS FILTER */
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $visits = $query->orderBy('started_at')->get();

        $rows = collect();
        foreach ($visits as $visit) {
            $rows->push($visit);
            foreach ($visit->pitstops as $pitstop) {
                $rows->push($pitstop);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Type',
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

    public function map($row): array
    {
        if ($row instanceof VisitPitstop) {
            $parentSalesman = $row->visit->salesman->name ?? '-';
            return [
                "Pitstop of Visit #{$row->visit_id}",
                $row->visit_id,
                $parentSalesman,
                $row->customer->name ?? '-',
                $row->customer->address ?? '-',
                $row->purpose,
                '-',
                $row->notes,
                $row->distance_km,
                $row->visited_at ? $row->visited_at->format('Y-m-d H:i') : '',
            ];
        }

        return [
            'Visit',
            $row->id,
            $row->salesman->name ?? '-',
            $row->customer->name ?? '-',
            $row->customer->address ?? '-',
            $row->purpose,
            ucfirst($row->status),
            $row->notes,
            $row->distance_km,
            $row->started_at->format('Y-m-d H:i'),
        ];
    }
}
