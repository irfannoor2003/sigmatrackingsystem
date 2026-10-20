@extends('layouts.app')

@section('title', 'Salesman Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm muted">My Customers</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1">
                    {{ \App\Models\Customer::where('salesman_id', auth()->id())->count() }}</p>
                <a href="{{ route('salesman.customers.index') }}"
                    class="text-xs font-semibold text-[var(--accent)] hover:underline mt-2 inline-block">View</a>
            </div>
            <span class="stat-ico"><i data-lucide="building-2" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar mt-4"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm muted">My Visits (this month)</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1">
                    {{ \App\Models\Visit::where('salesman_id', auth()->id())->whereMonth('created_at', now()->month)->count() }}</p>
                <a href="{{ route('salesman.visits.index') }}"
                    class="text-xs font-semibold text-[var(--accent)] hover:underline mt-2 inline-block">View</a>
            </div>
            <span class="stat-ico"><i data-lucide="calendar-check" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar mt-4"><div style="width: 100%"></div></div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <a href="{{ route('salesman.customers.create') }}"
        class="card card-hover p-6 hover:-translate-y-0.5">
        <div class="flex items-center gap-3">
            <span class="stat-ico"><i data-lucide="user-plus" class="w-6 h-6"></i></span>
            <h4 class="font-display font-semibold text-[var(--text)]">Add Customer</h4>
        </div>
        <p class="text-sm muted mt-2.5">Quick add a customer to your list.</p>
    </a>

    <a href="{{ route('salesman.visits.create') }}"
        class="card card-hover p-6 hover:-translate-y-0.5">
        <div class="flex items-center gap-3">
            <span class="stat-ico"><i data-lucide="map-pin" class="w-6 h-6"></i></span>
            <h4 class="font-display font-semibold text-[var(--text)]">Start Visit</h4>
        </div>
        <p class="text-sm muted mt-2.5">Open a visit (office-only start).</p>
    </a>

    <a href="{{ route('salesman.attendance.index') }}"
        class="card card-hover p-6 hover:-translate-y-0.5">
        <div class="flex items-center gap-3">
            <span class="stat-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
            <h4 class="font-display font-semibold text-[var(--text)]">Attendance</h4>
        </div>
        <p class="text-sm muted mt-2.5">Clock in / Clock out.</p>
    </a>
</div>
@endsection