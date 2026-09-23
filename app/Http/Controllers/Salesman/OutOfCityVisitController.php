<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use App\Models\OutOfCityVisit;
use App\Models\OutOfCityVisitItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutOfCityVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = OutOfCityVisit::with(['attendance', 'items.customer'])
            ->where('salesman_id', Auth::id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $visits = $query->latest()->paginate(15)->withQueryString();

        return view('salesman.out-of-city-visits.index', compact('visits'));
    }

    public function create($id)
    {
        $visit = OutOfCityVisit::with(['attendance', 'items.customer'])
            ->where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        if ($visit->isSubmitted()) {
            return redirect()->route('salesman.out-of-city-visits.show', $id)
                ->with('info', 'You have already submitted details for this visit.');
        }

        $customers = Customer::orderBy('name')->get();

        return view('salesman.out-of-city-visits.create', compact('visit', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'out_of_city_visit_id' => 'required|exists:out_of_city_visits,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.customer_id' => 'required|exists:customers,id',
            'items.*.note' => 'nullable|string|max:500',
            'items.*.images' => 'required|array|min:1',
            'items.*.images.*' => 'required|image|max:5120',
        ]);

        $visit = OutOfCityVisit::where('id', $request->out_of_city_visit_id)
            ->where('salesman_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/out_of_city_images';
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        foreach ($request->items as $index => $item) {
            $images = [];
            if ($request->hasFile("items.{$index}.images")) {
                $files = $request->file("items.{$index}.images");
                foreach ($files as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($destination, $filename);
                    $images[] = 'storage/out_of_city_images/' . $filename;
                }
            }

            OutOfCityVisitItem::create([
                'out_of_city_visit_id' => $visit->id,
                'customer_id' => $item['customer_id'],
                'note' => $item['note'] ?? null,
                'images' => $images,
            ]);
        }

        $visit->update([
            'notes' => $request->notes,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('salesman.out-of-city-visits.show', $visit->id)
            ->with('success', 'Out of city visit details submitted successfully!');
    }

    public function show($id)
    {
        $visit = OutOfCityVisit::with(['attendance', 'items.customer', 'salesman'])
            ->where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        return view('salesman.out-of-city-visits.show', compact('visit'));
    }
}
