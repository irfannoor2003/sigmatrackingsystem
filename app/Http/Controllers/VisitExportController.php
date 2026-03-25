<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\VisitsMonthlyExport;
use App\Exports\VisitsFilteredExport;
use Maatwebsite\Excel\Facades\Excel;

class VisitExportController extends Controller
{
  public function monthly(Request $request)
{
    $filters = $request->all();

    if (!empty($filters['month'])) {
        if ($filters['month'] === 'current') {
            $filters['from_date'] = now()->startOfMonth()->toDateString();
            $filters['to_date']   = now()->endOfMonth()->toDateString();
        } elseif ($filters['month'] === 'previous') {
            $filters['from_date'] = now()->subMonth()->startOfMonth()->toDateString();
            $filters['to_date']   = now()->subMonth()->endOfMonth()->toDateString();
        }
        unset($filters['month']);
    }

    return Excel::download(
        new VisitsFilteredExport($filters),
        'visits_export_' . now()->format('Y_m_d_His') . '.xlsx'
    );
}
}
