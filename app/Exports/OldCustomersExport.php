<?php

namespace App\Exports;

use App\Models\OldCustomer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OldCustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $salesmanId;

    public function __construct($salesmanId = null)
    {
        $this->salesmanId = $salesmanId;
    }

    public function collection()
    {
        $query = OldCustomer::with('salesman');

        if ($this->salesmanId) {
            $query->where('salesman_id', $this->salesmanId);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Company Name',
            'Contact Person',
            'Phone',
            'Email',
            'Address',
            'Salesman Name',
            'Created At',
        ];
    }

    public function map($c): array
    {
        return [
            $c->id,
            $c->company_name,
            $c->contact_person ?? '-',
            $c->contact ?? '-',
            $c->email ?? '-',
            $c->address ?? '-',
            $c->salesman->name ?? '-',
            $c->created_at->format('Y-m-d'),
        ];
    }
}
