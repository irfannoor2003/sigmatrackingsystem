<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Customer;
use App\Models\User;
use App\Models\City;
use App\Models\Industry;
use App\Models\Category;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class SalesHeadDashboardController extends Controller
{


    /**
     * Dashboard view
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalCustomers = Customer::count();
        $totalSalesmen = User::where('role', 'salesman')->count();

        $visitsThisMonth = Visit::with('salesman', 'customer')
            ->whereBetween('started_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $todayVisits = Visit::with('salesman', 'customer')
            ->whereDate('started_at', $today)
            ->latest()
            ->get();

        $visitActivities = Visit::with('salesman', 'customer')
            ->latest()
            ->limit(6)
            ->get();

        return view('saleshead.dashboard', compact(
            'totalCustomers',
            'totalSalesmen',
            'visitsThisMonth',
            'todayVisits',
            'visitActivities'
        ));
    }

    /**
     * All Visits Report
     */
    public function visitsReport(Request $request)
    {
        $query = Visit::with('salesman', 'customer', 'pitstops.customer')->latest();

        if ($request->salesman_id) {
            $query->where('salesman_id', $request->salesman_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date) {
            $query->whereDate('started_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('started_at', '<=', $request->to_date);
        }

        $visits = $query->paginate(18)->withQueryString();

        $salesmen = User::where('role', 'salesman')->orderBy('name')->get();

        return view('saleshead.visits.index', compact('visits', 'salesmen'));
    }

    /**
     * Customers List (with filters, like Admin)
     */
    public function customers(Request $request)
    {
        $query = Customer::with(['city', 'industry', 'category', 'salesman']);

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone1', 'like', "%{$search}%")
                  ->orWhere('phone2', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('id', 'desc')
                           ->paginate(10)
                           ->appends($request->query());

        $cities     = City::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $salesmen   = User::where('role', 'salesman')->orderBy('name')->get();

        return view('saleshead.customers.index', compact(
            'customers',
            'cities',
            'industries',
            'categories',
            'salesmen'
        ));
    }

    /**
     * Show single customer
     */
    public function showCustomer($id)
    {
        $customer = Customer::with(['city', 'industry', 'category', 'salesman'])
                            ->findOrFail($id);

        $cities     = City::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('saleshead.customers.show', compact(
            'customer',
            'cities',
            'industries',
            'categories'
        ));
    }

     /*
     * Show single visit
     */
    public function showVisit($id)
    {
        $visit = Visit::with('salesman', 'customer', 'pitstops.customer')->findOrFail($id);
        return view('saleshead.visits.show', compact('visit'));
    }

    /**
     * Salesmen List (read-only)
     */
    public function salesmen()
    {
        $salesmen = User::where('role', 'salesman')
            ->withCount(['customers', 'visits'])
            ->orderBy('name')
            ->paginate(10);

        return view('saleshead.salesmen.index', compact('salesmen'));
    }
}
