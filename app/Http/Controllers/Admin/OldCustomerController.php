<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OldCustomer;
use App\Models\User;
use App\Imports\OldCustomersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OldCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = OldCustomer::with('salesman');

        // Filter by salesman
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $salesmen = User::where('role', 'salesman')
            ->orderBy('name')
            ->get();

        return view('admin.old-customers.index', compact('customers', 'salesmen'));
    }

    public function importForm()
    {
        $salesmen = User::where('role', 'salesman')
            ->orderBy('name')
            ->get();

        return view('admin.old-customers.import', compact('salesmen'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'        => 'required|mimes:xlsx,xls,csv',
            'salesman_id' => 'required|exists:users,id',
        ]);

        $startTime = microtime(true);

        $import = new OldCustomersImport($request->salesman_id);
        Excel::import($import, $request->file('file'));

        $timeTaken = round(microtime(true) - $startTime, 2);

        return redirect()
            ->route('admin.old-customers.index')
            ->with(
                'success',
                "Import completed in {$timeTaken}s. "
                . "Inserted: {$import->inserted}, "
                . "Skipped: {$import->skipped}"
            );
    }
}
