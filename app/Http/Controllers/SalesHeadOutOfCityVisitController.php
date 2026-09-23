<?php

namespace App\Http\Controllers;

use App\Models\OutOfCityVisit;
use Illuminate\Http\Request;

class SalesHeadOutOfCityVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = OutOfCityVisit::with(['attendance', 'items.customer', 'salesman']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $visits = $query->latest()->paginate(20)->withQueryString();

        return view('saleshead.out-of-city-visits.index', compact('visits'));
    }

    public function show($id)
    {
        $visit = OutOfCityVisit::with(['attendance', 'items.customer', 'salesman'])
            ->findOrFail($id);

        return view('saleshead.out-of-city-visits.show', compact('visit'));
    }
}
