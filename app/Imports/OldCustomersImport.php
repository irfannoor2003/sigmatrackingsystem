<?php

namespace App\Imports;

use App\Models\OldCustomer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithColumnFormatting
};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OldCustomersImport implements
    ToModel,
    WithHeadingRow,
    WithColumnFormatting
{
    public int $inserted = 0;
    public int $skipped  = 0;
    private ?int $salesmanId;

    public function __construct(?int $salesmanId = null)
    {
        $this->salesmanId = $salesmanId;
    }

    public function model(array $row)
    {
        $companyName   = trim((string) ($row['company_name'] ?? ''));
        $contactPerson = trim((string) ($row['contact_person'] ?? ''));

        // ❌ Skip invalid rows
        if ($companyName === '' || $contactPerson === '') {
            $this->skipped++;
            return null;
        }

        // 🚫 Skip duplicate (company_name + contact_person)
        $exists = OldCustomer::where('company_name', $companyName)
            ->where('contact_person', $contactPerson)
            ->exists();

        if ($exists) {
            $this->skipped++;
            return null;
        }

        $this->inserted++;

        return new OldCustomer([
            'company_name'   => $companyName,
            'contact_person' => $contactPerson,
            'address'        => trim((string) ($row['address'] ?? '')),
            'email'          => trim((string) ($row['email'] ?? null)),
            'contact'        => isset($row['contact'])
                                    ? trim((string) $row['contact'])
                                    : null,
            'salesman_id'    => $this->salesmanId ?? Auth::id(),
        ]);
    }

    // 📞 Force phone column as text (prevents Excel auto-format)
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // adjust if contact column changes
        ];
    }
}
