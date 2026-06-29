<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{

private function allowOnly(array $roles)
{
    if (!in_array(auth()->user()->role, $roles)) {
        abort(403);
    }
}

    // Dashboard charts
    public function index()
    {
         $this->allowOnly(['admin']);
        $year = Carbon::now()->year;

        $monthlyVisits = Visit::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $monthlyCustomers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $visits = array_fill(1, 12, 0);
        foreach ($monthlyVisits as $month => $count) {
            $visits[$month] = $count;
        }

        $customers = array_fill(1, 12, 0);
        foreach ($monthlyCustomers as $month => $count) {
            $customers[$month] = $count;
        }

        return view('reports.index', [
            'visits' => array_values($visits),
            'customers' => array_values($customers),
        ]);
    }

    // Salesman personal report
    public function salesmanReport()
    {
        $this->allowOnly(['salesman']);
        $salesmanId = auth()->id();

        $visits = Visit::with('customer')
            ->where('salesman_id', $salesmanId)
            ->orderBy('started_at', 'desc')
            ->paginate(10);   // PAGINATED

        // Add duration field
        $visits->each(function ($visit) {
            $visit->duration = $visit->completed_at
                ? $visit->completed_at->diffInMinutes($visit->started_at)
                : null;
        });

        return view('salesman.reports.index', compact('visits'));
    }

    // Admin report (for your view)
    public function adminReport(Request $request)
    {
        $this->allowOnly(['admin', 'saleshead']);

        // Today's stats
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $todayVisits = Visit::whereBetween('started_at', [$todayStart, $todayEnd])->count();
        $todayCompleted = Visit::whereBetween('started_at', [$todayStart, $todayEnd])->where('status', 'completed')->count();
        $todayPending = Visit::whereBetween('started_at', [$todayStart, $todayEnd])->where('status', 'started')->count();
        $todayBlocked = Visit::whereBetween('started_at', [$todayStart, $todayEnd])->where('status', 'blocked')->count();

        $todaySalesmen = Visit::whereBetween('started_at', [$todayStart, $todayEnd])
            ->with('salesman')
            ->get()
            ->pluck('salesman.name')
            ->unique()
            ->values();

        $todayCompanies = Visit::whereBetween('started_at', [$todayStart, $todayEnd])
            ->with('customer')
            ->get()
            ->pluck('customer.name')
            ->unique()
            ->values();

        $query = Visit::with(['customer', 'salesman', 'pitstops.customer']);

        if ($request->salesman_id) {
            $query->where('salesman_id', $request->salesman_id);
        }

        if ($request->from_date) {
            $query->whereDate('started_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('started_at', '<=', $request->to_date);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // PAGINATION FIXED HERE
        $visits = $query->orderBy('id', 'desc')
                        ->paginate(10)   // you can change the number
                        ->appends($request->query()); // keeps filters

        // Calculate duration
        $visits->each(function ($visit) {
            $visit->duration = $visit->completed_at
                ? $visit->completed_at->diffInMinutes($visit->started_at)
                : null;
        });

        $salesmen = User::where('role', 'salesman')->get();

        return view('admin.reports.index', compact(
            'visits',
            'salesmen',
            'todayVisits',
            'todayCompleted',
            'todayPending',
            'todayBlocked',
            'todaySalesmen',
            'todayCompanies'
        ));
    }

    // Admin single visit details
    public function show($id)
    {
         $this->allowOnly(['admin', 'saleshead']);
        $visit = Visit::with(['salesman', 'customer', 'pitstops.customer'])->findOrFail($id);

        return view('admin.reports.show', compact('visit'));
    }
    // Salesman Monthly Visit Report (Printable)
public function monthlyVisitReport(Request $request)
{
    $this->allowOnly(['salesman']);
    $salesmanId = auth()->id();

    $monthInput = $request->month ?? now()->format('Y-m');

    $start = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', $monthInput)->endOfMonth();

    $visits = Visit::with('customer')
        ->where('salesman_id', $salesmanId)
        ->whereBetween('started_at', [$start, $end])
        ->orderBy('started_at')
        ->get();

    $totalVisits = $visits->count();
    $completedVisits = $visits->where('status', 'completed')->count();
    $totalKm = $visits->sum('distance_km');

    return view('salesman.reports.monthly-visits', compact(
        'visits',
        'monthInput',
        'totalVisits',
        'completedVisits',
        'totalKm'
    ));
}

}
