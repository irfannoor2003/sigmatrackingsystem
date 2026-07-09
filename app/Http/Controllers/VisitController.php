<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\VisitPitstop;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VisitController extends Controller
{
    /**
     * Show create visit form
     */
    public function create()
{
    // Fetch all customers
    $customers = Customer::orderBy('name')->get();

    return view('salesman.visits.create', compact('customers'));
}


    /**
     * List visits
     */


public function index(Request $request)
{
    $query = Visit::with(['customer', 'pitstops.customer'])
        ->where('salesman_id', Auth::id())
        ->orderBy('id', 'desc');

    // ================= MONTH FILTER =================
    if ($request->month === 'current') {
        $query->whereBetween('started_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ]);
    }

    if ($request->month === 'previous') {
        $query->whereBetween('started_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth(),
        ]);
    }

    $visits = $query->paginate(18)->withQueryString();

    $customers = Customer::orderBy('name')->get();

    return view('salesman.visits.index', compact('visits', 'customers'));
}


    /**
     * Distance calculator (meters)
     */
    private function distanceInMeters($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    /**
     * Start visit (office-only)
     */
    public function store(Request $request)
    {
        // ❌ Check if salesman has any blocked visits
        $blockedVisit = Visit::where('salesman_id', Auth::id())
            ->where('status', 'blocked')
            ->first();

        if ($blockedVisit) {
            return back()->with(
                'error',
                'Access Denied: You have a blocked visit that must be resolved before starting a new visit. Please contact your administrator.'
            );
        }

        // ❌ Block multiple active visits
        $activeVisit = Visit::where('salesman_id', Auth::id())
            ->where('status', 'started')
            ->first();

        if ($activeVisit) {
            return back()->with(
                'error',
                'You already have an active visit. Complete it first.'
            );
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'customer_id' => 'required|exists:customers,id',
            'purpose' => 'required|string|max:255',
        ]);

        $officeLat = config('office.lat');
        $officeLng = config('office.lng');
        $radius    = config('office.radius');

        if (!$officeLat || !$officeLng) {
            return back()->with('error', 'Office location not configured.');
        }

        $distance = $this->distanceInMeters(
            $officeLat,
            $officeLng,
            $request->lat,
            $request->lng
        );

        Log::info('Visit start attempt', [
            'lat' => $request->lat,
            'lng' => $request->lng,
            'distance' => round($distance),
        ]);

        if ($distance > $radius) {
            return back()->with(
                'error',
                'You are ' . round($distance) . ' meters away from the office.'
            );
        }

        Visit::create([
            'customer_id' => $request->customer_id,
            'salesman_id' => Auth::id(),
            'purpose' => $request->purpose,
            'status' => 'started',
            'started_at' => now(),
            'start_lat' => $request->lat,
            'start_lng' => $request->lng,
        ]);

        return redirect()
            ->route('salesman.visits.index')
            ->with('success', 'Visit started successfully!');
    }

    /**
     * Complete visit
     */
    public function complete(Request $request, $id)
{
    $visit = Visit::where('id', $id)
        ->where('salesman_id', Auth::id())
        ->whereIn('status', ['started', 'blocked'])
        ->firstOrFail();

    if ($visit->status === 'blocked') {
        return back()->with(
            'error',
            'This visit is blocked and cannot be completed by the salesman. Please contact your administrator.'
        );
    }

    $request->validate([
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'notes' => 'required|string|max:1000',
        'distance_km' => 'nullable|numeric|min:0',
        'pitstop_total_km' => 'nullable|numeric|min:0',
        'images' => 'required|array',
        'images.*' => 'required|image|max:5120',
    ]);

    $officeLat = config('office.lat');
    $officeLng = config('office.lng');
    $radius    = config('office.radius');

    if (!$officeLat || !$officeLng) {
        return back()->with('error', 'Office location not configured.');
    }

    $distance = $this->distanceInMeters(
        $officeLat,
        $officeLng,
        $request->lat,
        $request->lng
    );

    if ($distance > $radius) {
        return back()->with(
            'error',
            'You must be at the office to complete a visit. You are ' . round($distance) . ' meters away.'
        );
    }

   $images = [];

if ($request->hasFile('images')) {

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/visit_images';

    // Ensure folder exists
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    foreach ($request->file('images') as $image) {

        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

        // Move image to PUBLIC subdomain folder
        $image->move($destination, $filename);

        // Save PUBLIC relative path
        $images[] = 'storage/visit_images/' . $filename;
    }
}

    $visit->notes = $request->notes;
    if ($request->has('distance_km')) {
        $visit->distance_km = $request->distance_km;
    }
    if ($request->has('pitstop_total_km')) {
        $visit->pitstop_total_km = $request->pitstop_total_km;
    }
    $visit->images = array_merge($visit->images ?? [], $images);
    $visit->status = 'completed';
    $visit->completed_at = now();
    $visit->save();

    return redirect()
        ->route('salesman.visits.index')
        ->with('success', 'Visit completed successfully!');
}


    /**
     * Show single visit
     */
    public function show($id)
    {
        $visit = Visit::with(['pitstops.customer'])
            ->where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        return view('salesman.visits.show', compact('visit'));
    }
    public function edit(Visit $visit)
{
    // ownership check
    if ($visit->salesman_id !== Auth::id()) {
        abort(403);
    }

    // only completed visits editable
    if ($visit->status !== 'completed') {
        return back()->with('error', 'Only completed visits can be edited.');
    }

    // month lock (allow current + previous only)
    $allowedFrom = now()->subMonth()->startOfMonth();

    if ($visit->started_at->lt($allowedFrom)) {
        return back()->with('error', 'You cannot edit older visits.');
    }

    $customers = Customer::orderBy('name')->get();

    return view('salesman.visits.edit', compact('visit', 'customers'));
}
public function update(Request $request, Visit $visit)
{
    if ($visit->salesman_id !== Auth::id()) {
        abort(403);
    }

    if ($visit->status !== 'completed') {
        return back()->with('error', 'Invalid visit status.');
    }

    $allowedFrom = now()->subMonth()->startOfMonth();
    if ($visit->started_at->lt($allowedFrom)) {
        return back()->with('error', 'Visit editing period expired.');
    }

    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'purpose'     => 'required|string|max:255',
        'notes'       => 'nullable|string|max:1000',
        'distance_km' => 'nullable|numeric|min:0',
    ]);

    $visit->update([
        'customer_id' => $request->customer_id,
        'purpose'     => $request->purpose,
        'notes'       => $request->notes,
        'distance_km' => $request->distance_km,
    ]);

    return redirect()
        ->route('salesman.visits.index')
        ->with('success', 'Visit updated successfully.');
}

    /**
     * Add a pitstop (additional customer visit) to an active visit
     */
    public function addPitstop(Request $request, $id)
    {
        $visit = Visit::where('id', $id)
            ->where('salesman_id', Auth::id())
            ->where('status', 'started')
            ->firstOrFail();

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'purpose'     => 'nullable|string|max:255',
            'notes'       => 'nullable|string|max:1000',
            'distance_km' => 'nullable|numeric|min:0',
            'lat'         => 'nullable|numeric',
            'lng'         => 'nullable|numeric',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/visit_images';
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            foreach ($request->file('images') as $image) {
                $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move($destination, $filename);
                $images[] = 'storage/visit_images/' . $filename;
            }
        }

        VisitPitstop::create([
            'visit_id'    => $visit->id,
            'customer_id' => $request->customer_id,
            'purpose'     => $request->purpose,
            'notes'       => $request->notes,
            'distance_km' => $request->distance_km,
            'images'      => $images ?: null,
            'visited_at'  => now(),
            'lat'         => $request->lat,
            'lng'         => $request->lng,
        ]);

        return back()->with('success', 'Additional stop added successfully!');
    }

    /**
     * Delete a pitstop
     */
    public function deletePitstop($visitId, $pitstopId)
    {
        $visit = Visit::where('id', $visitId)
            ->where('salesman_id', Auth::id())
            ->where('status', 'started')
            ->firstOrFail();

        $pitstop = VisitPitstop::where('id', $pitstopId)
            ->where('visit_id', $visit->id)
            ->firstOrFail();

        // Delete images from storage
        if ($pitstop->images) {
            foreach ($pitstop->images as $imagePath) {
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        $pitstop->delete();

        return back()->with('success', 'Stop removed.');
    }
}
