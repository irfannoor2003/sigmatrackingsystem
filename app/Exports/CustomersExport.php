<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;
    protected $salesmanId;

    public function __construct($ids = null, $salesmanId = null)
    {
        $this->ids = $ids;
        $this->salesmanId = $salesmanId;
    }

    public function collection()
    {
        $query = Customer::with(['city', 'industry', 'category', 'salesman']);

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

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
            'Mobile Number 1',
            'Mobile Number 2',
            'Email',
            'City',
            'Industry',
            'Category',
            'Salesman Name',
            'Image',
            'Address',       // ⭐ Clickable Image Link
            'Created At'
        ];
    }

    public function map($c): array
    {
        return [
            $c->id,
            $c->name,
            $c->contact_person,
            $c->phone1,
            $c->phone2,
            $c->email,
            $c->city->name ?? '-',
            $c->industry->name ?? '-',
            $c->category->name ?? '-',
            $c->salesman->name ?? '-',

            // ✅ Clickable image link (opens in Chrome/browser)

                $c->image
    ? '=HYPERLINK("' . url($c->image) . '", "View Image")'
    : '-',

             $c->address ?? '-',
            $c->created_at->format('Y-m-d'),
        ];
    }
}
