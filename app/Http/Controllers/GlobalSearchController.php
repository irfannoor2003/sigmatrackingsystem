<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OldCustomer;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $scope = trim($request->query('scope', 'all'));
        $items = [];

        if (mb_strlen($q) < 2) {
            return response()->json(['items' => $items]);
        }

        $user = $request->user();
        $role = $user->role;
        $search = "%{$q}%";

        if (in_array($role, ['admin', 'hr'], true)) {
            if (in_array($scope, ['all', 'staff'], true)) {
                $people = User::query()
                    ->where(fn ($query) => $query->where('name', 'like', $search)->orWhere('email', 'like', $search))
                    ->when($role !== 'admin', fn ($query) => $query->where('role', '!=', 'admin'))
                    ->limit(8)
                    ->get();

                foreach ($people as $person) {
                    $items[] = [
                        'type' => 'Staff',
                        'title' => $person->name,
                        'sub' => ucfirst(str_replace('_', ' ', $person->role)).($person->is_blocked ? ' • Blocked' : ''),
                        'url' => $role === 'admin'
                            ? route('admin.attendance.staff', $person->id)
                            : route('hr.attendance.staff', $person->id),
                        'icon' => 'user',
                    ];
                }
            }

            if ($role === 'admin' && in_array($scope, ['all', 'customers'], true)) {
                $customers = Customer::query()
                    ->where(fn ($query) => $query->where('name', 'like', $search)->orWhere('phone1', 'like', $search)->orWhere('email', 'like', $search))
                    ->with('city')
                    ->limit(8)
                    ->get();

                foreach ($customers as $customer) {
                    $items[] = [
                        'type' => 'Customer',
                        'title' => $customer->name,
                        'sub' => ($customer->city->name ?? '').' • '.($customer->phone1 ?? '—'),
                        'url' => route('admin.customers.show', $customer->id),
                        'icon' => 'building-2',
                    ];
                }

                $old = OldCustomer::query()
                    ->where(fn ($query) => $query->where('company_name', 'like', $search)->orWhere('contact', 'like', $search))
                    ->limit(5)
                    ->get();

                foreach ($old as $oldCustomer) {
                    $items[] = [
                        'type' => 'Old Customer',
                        'title' => $oldCustomer->company_name,
                        'sub' => $oldCustomer->contact ?? '—',
                        'url' => route('admin.old-customers.index', ['q' => $q]),
                        'icon' => 'database',
                    ];
                }
            }
        }

        if ($role === 'saleshead' && $scope === 'customers') {
            $customers = Customer::query()
                ->where(fn ($query) => $query->where('name', 'like', $search)->orWhere('phone1', 'like', $search)->orWhere('email', 'like', $search))
                ->with('city')
                ->limit(8)
                ->get();

            foreach ($customers as $customer) {
                $items[] = [
                    'type' => 'Customer',
                    'title' => $customer->name,
                    'sub' => ($customer->city->name ?? '').' • '.($customer->phone1 ?? '—'),
                    'url' => route('salehead.customers.show', $customer->id),
                    'icon' => 'building-2',
                ];
            }
        }

        if ($role === 'salesman') {
            if (in_array($scope, ['all', 'customers'], true)) {
                $customers = Customer::query()
                    ->where('salesman_id', $user->id)
                    ->where(fn ($query) => $query->where('name', 'like', $search)->orWhere('phone1', 'like', $search))
                    ->limit(6)
                    ->get();

                foreach ($customers as $customer) {
                    $items[] = [
                        'type' => 'My Customer',
                        'title' => $customer->name,
                        'sub' => $customer->phone1 ?? '—',
                        'url' => route('salesman.customers.show', $customer->id),
                        'icon' => 'building-2',
                    ];
                }

                $old = OldCustomer::query()
                    ->where('salesman_id', $user->id)
                    ->where(fn ($query) => $query->where('company_name', 'like', $search)->orWhere('contact', 'like', $search))
                    ->limit(5)
                    ->get();

                foreach ($old as $oldCustomer) {
                    $items[] = [
                        'type' => 'Old Customer',
                        'title' => $oldCustomer->company_name,
                        'sub' => $oldCustomer->contact ?? '—',
                        'url' => route('salesman.old-customers.index', ['q' => $q]),
                        'icon' => 'database',
                    ];
                }
            }

            if (in_array($scope, ['all', 'visits'], true)) {
                $visits = Visit::query()
                    ->where('salesman_id', $user->id)
                    ->whereHas('customer', fn ($query) => $query->where('name', 'like', $search))
                    ->with('customer')
                    ->limit(6)
                    ->get();

                foreach ($visits as $visit) {
                    $items[] = [
                        'type' => 'Visit',
                        'title' => $visit->customer->name ?? 'Visit',
                        'sub' => $visit->display_status.' • '.optional($visit->started_at)->format('d M Y'),
                        'url' => route('salesman.visits.show', $visit->id),
                        'icon' => 'map-pin',
                    ];
                }
            }
        }

        return response()->json(['items' => array_slice($items, 0, 14)]);
    }
}
