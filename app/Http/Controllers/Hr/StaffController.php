<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // ✅ Central role list
    private array $roles = [
        'admin',
        'salesman',
        'it',
        'account',
        'store',
        'office_boy',
        'hr',
        'saleshead'
    ];



    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $staff = $query->orderBy('name')->paginate(10);

        return view('hr.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('hr.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:' . implode(',', $this->roles),
            'phone'    => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
        ]);

        return redirect()->route('hr.staff.index')->with('success', 'Staff created successfully.');
    }

    public function edit(User $staff)
    {
        return view('hr.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $staff->id,
            'role'     => 'required|in:' . implode(',', $this->roles),
            'password' => 'nullable|min:6',
            'phone'    => 'nullable|string|max:255',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('hr.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('hr.staff.index')->with('success', 'Staff deleted successfully.');
    }
}
