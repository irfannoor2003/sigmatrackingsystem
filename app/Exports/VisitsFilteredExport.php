<?php

namespace App\Exports;

use App\Models\Visit;
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

        $query = Visit::with(['salesman', 'customer']);

        /** 🔐 ROLE RULES */
        if ($user->role === 'salesman') {
            // Salesman → ONLY own visits
            $query->where('salesman_id', $user->id);
        } else {
            // Admin / Sales Head → optional salesman filter
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

    public function map($v): array
    {
        return [
            $v->id,
            $v->salesman->name ?? '-',
            $v->customer->name ?? '-',
            $v->customer->address ?? '-',
            $v->purpose,
            ucfirst($v->status),
            $v->notes,
            $v->distance_km,
            $v->started_at->format('Y-m-d H:i'),
        ];
    }
}
