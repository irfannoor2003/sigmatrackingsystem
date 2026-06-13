<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SalesmanController extends Controller
{
    public function index()
    {
        // Only salesmen
        $salesmen = User::where('role', 'salesman')
            ->latest()
            ->paginate(10);

        return view('admin.salesmen.index', compact('salesmen'));
    }

    public function create()
    {
        return view('admin.salesmen.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'salesman';

        User::create($data);

        return redirect()
            ->route('admin.salesmen.index')
            ->with('success', 'Salesman created successfully.');
    }

    public function edit($id)
    {
        $salesman = User::where('role', 'salesman')->findOrFail($id);
        return view('admin.salesmen.edit', compact('salesman'));
    }

    public function update(Request $request, $id)
    {
        $salesman = User::where('role', 'salesman')->findOrFail($id);

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $salesman->id,
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $salesman->update($data);

        return redirect()
            ->route('admin.salesmen.index')
            ->with('success', 'Salesman updated successfully.');
    }

    public function destroy($id)
    {
        $salesman = User::where('role', 'salesman')->findOrFail($id);
        $salesman->delete();

        return back()->with('success', 'Salesman deleted successfully.');
    }

    public function block($id)
    {
        $salesman = User::where('role', 'salesman')->findOrFail($id);
        $salesman->block();

        return back()->with('success', 'Salesman blocked and notified.');
    }

    public function unblock($id)
    {
        $salesman = User::where('role', 'salesman')->findOrFail($id);
        $salesman->unblock();

        return back()->with('success', 'Salesman unblocked and notified.');
    }
}
