<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\City;
use App\Models\Industry;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /* =========================================================
        List customers of logged-in salesman
    ========================================================== */
    public function index()
    {
        $customers = Customer::with(['city', 'industry', 'category'])
            ->where('salesman_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('salesman.customers.index', compact('customers'));
    }

    /* =========================================================
        Show create form
    ========================================================== */
    public function create()
    {
        $cities     = City::orderBy('name', 'asc')->get();
        $industries = Industry::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        return view(
            'salesman.customers.create',
            compact('cities', 'industries', 'categories')
        );
    }

    /* =========================================================
        Store customer
    ========================================================== */
   public function store(Request $request)
{
    // 1️⃣ Validate input
    $request->validate([
        'name'            => 'required|string|max:255',
        'contact_person'  => 'required|string|max:255',
        'phone1'          => 'nullable|string|max:20',
        'phone2'          => 'nullable|string|max:20',
        'email'           => 'nullable|email|max:255',
        'address'         => 'required|string|max:255',
        'city_id'         => 'required|exists:cities,id',
        'industry_id'     => 'required|exists:industries,id',
        'category_id'     => 'required|exists:categories,id',
        'image'           => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // 2️⃣ Normalize the name for duplicate checking
    $normalizedName = trim(strtolower($request->name));

    // 3️⃣ Check for existing customer by name
    $existingCustomer = Customer::whereRaw('LOWER(name) = ?', [$normalizedName])->first();

    if ($existingCustomer) {
        $similarCustomers = Customer::where('name', 'like', '%' . $request->name . '%')
            ->take(5)
            ->pluck('name');

        $suggestions = $similarCustomers->count()
            ? ' Existing Customer Name: ' . $similarCustomers->join(', ')
            : '';

        return back()
            ->withErrors(['duplicate' => 'This contact person already exists.' . $suggestions])
            ->withInput();
    }

    // 4️⃣ Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time().'_'.$image->getClientOriginalName();
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/customers';
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        $image->move($destination, $filename);
        $imagePath = 'storage/customers/' . $filename;
    }

    // 5️⃣ Insert inside try/catch to handle rare race conditions
    try {
        Customer::create([
            'salesman_id'     => Auth::id(),
            'name'            => $request->name,
            'contact_person'  => $request->contact_person,
            'phone1'          => $request->phone1,
            'phone2'          => $request->phone2,
            'email'           => $request->email,
            'address'         => $request->address,
            'city_id'         => $request->city_id,
            'industry_id'     => $request->industry_id,
            'category_id'     => $request->category_id,
            'image'           => $imagePath,
        ]);
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') { // duplicate entry
            $similarCustomers = Customer::where('name', 'like', '%' . $request->name . '%')
                ->take(5)
                ->pluck('name');

            $suggestions = $similarCustomers->count()
                ? ' Existing Customer Name: ' . $similarCustomers->join(', ')
                : '';

            return back()
                ->withErrors(['duplicate' => 'This contact person already exists' . $suggestions])
                ->withInput();
        }
        throw $e; // other database errors
    }

    return redirect()
        ->route('salesman.customers.index')
        ->with('success', 'Customer added successfully.');
}

    /* =========================================================
        Show single customer (only own)
    ========================================================== */
    public function show($id)
    {
        $customer = Customer::with(['city', 'industry', 'category'])
            ->where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        return view('salesman.customers.show', compact('customer'));
    }

    /* =========================================================
        Edit customer
    ========================================================== */
    public function edit($id)
    {
        $customer = Customer::where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        $cities     = City::orderBy('name', 'asc')->get();
        $industries = Industry::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        return view(
            'salesman.customers.edit',
            compact('customer', 'cities', 'industries', 'categories')
        );
    }

    /* =========================================================
        Update customer
    ========================================================== */
    public function update(Request $request, $id)
    {
        $customer = Customer::where('id', $id)
            ->where('salesman_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'phone1'          => 'nullable|string|max:20',
            'phone2'          => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'required|string|max:255',
            'city_id'         => 'required|exists:cities,id',
            'industry_id'     => 'required|exists:industries,id',
            'category_id'     => 'required|exists:categories,id',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'name',
            'contact_person',
            'phone1',
            'phone2',
            'email',
            'address',
            'city_id',
            'industry_id',
            'category_id',
        ]);

        if ($request->hasFile('image')) {

            // delete old image
            if ($customer->image && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$customer->image)) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$customer->image);
            }

            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName();
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/customers';

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $image->move($destination, $filename);

            $data['image'] = 'storage/customers/' . $filename;
        }

        $customer->update($data);

        return redirect()
            ->route('salesman.customers.index')
            ->with('success', 'Customer updated successfully.');
    }
}
