<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutOfCityVisit;
use Illuminate\Http\Request;

class OutOfCityVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = OutOfCityVisit::with(['attendance', 'items.customer', 'salesman']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->salesman_id) {
            $query->where('salesman_id', $request->salesman_id);
        }

        $visits = $query->latest()->paginate(20)->withQueryString();

        $salesmen = \App\Models\User::where('role', 'salesman')->orderBy('name')->get();

        return view('admin.out-of-city-visits.index', compact('visits', 'salesmen'));
    }

    public function show($id)
    {
        $visit = OutOfCityVisit::with(['attendance', 'items.customer', 'salesman'])
            ->findOrFail($id);

        return view('admin.out-of-city-visits.show', compact('visit'));
    }
}
