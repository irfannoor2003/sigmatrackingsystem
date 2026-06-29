<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use App\Models\OldCustomer;
use App\Imports\OldCustomersImport;
use App\Exports\OldCustomersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OldCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = OldCustomer::where('salesman_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query
            ->orderByDesc('id')
            ->paginate(10);

        return view('salesman.old-customers.index', compact('customers'));
    }

    public function importForm()
    {
        return view('salesman.old-customers.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $startTime = microtime(true);

        $import = new OldCustomersImport;
        Excel::import($import, $request->file('file'));

        $timeTaken = round(microtime(true) - $startTime, 2);

        return redirect()
            ->route('salesman.old-customers.index')
            ->with(
                'success',
                "Import completed in {$timeTaken}s. "
                . "Inserted: {$import->inserted}, "
                . "Skipped: {$import->skipped}"
            );
    }

    public function exportAll()
    {
        return Excel::download(
            new OldCustomersExport(Auth::id()),
            'my_old_customers.xlsx'
        );
    }
}
