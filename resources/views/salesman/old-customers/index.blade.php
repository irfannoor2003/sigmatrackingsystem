@extends('layouts.app')

@section('title', 'My Imported Customers')

@section('content')

<div class="p-0">

<div class="page-head !mb-6">
    <h1 class="page-title">
        <span class="page-ico"><i data-lucide="database" class="w-6 h-6"></i></span>
        Imported Customers (Old)
    </h1>
    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <a href="{{ route('salesman.old-customers.export.all') }}" class="btn btn-success w-full md:w-auto">
            <i data-lucide="download" class="w-5 h-5"></i>
            Export Excel
        </a>

        <a href="{{ route('salesman.old-customers.import.form') }}" class="btn btn-primary w-full md:w-auto">
            <i data-lucide="upload" class="w-5 h-5"></i>
            Import Excel
        </a>
    </div>
</div>

<x-filter-bar>
    <x-filter-field icon="search" class="lg:col-span-2">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search company / contact / phone / email"
            class="input">
    </x-filter-field>

    <x-filter-actions :reset="route('salesman.old-customers.index')" />
</x-filter-bar>

<div class="card !rounded-2xl shadow-card-lg overflow-x-auto hidden md:block">
    <div class="tbl-wrap !border-0 !shadow-none">
    <table class="tbl min-w-full text-sm">
     <thead>
    <tr>
        <th class="w-20">Id</th>
        <th>Company</th>
        <th>Person</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Email</th>
    </tr>
</thead>

        <tbody>
            @forelse($customers as $c)
                <tr>
                    <td class="text-[var(--text-muted)] font-mono">{{ $c->id }}</td>
                    <td class="font-semibold text-[var(--text)]">{{ $c->company_name }}</td>
                    <td class="text-[var(--text-soft)]">{{ $c->contact_person ?? '-' }}</td>
                    <td class="text-[var(--text-soft)]">{{ $c->contact ?? '-' }}</td>
                    <td class="text-[var(--text-muted)]">{{ $c->address ?? '-' }}</td>
                    <td class="text-[var(--text-soft)]">{{ $c->email ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center muted">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="md:hidden p-1 space-y-4">
    @forelse($customers as $customer)
        <div class="card !rounded-xl p-4 border border-[var(--border)] shadow-lg">

            <h2 class="text-lg font-semibold text-[var(--text)] mb-2 flex items-center">
                <i data-lucide="building-2" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                {{ $customer->company_name }}
            </h2>

            <p class="text-[var(--text-muted)] text-sm mb-2 flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-[var(--text-muted)] shrink-0"></i>
                <strong>ID:</strong> {{ $customer->id }}
            </p>

            <p class="text-[var(--text-soft)] text-sm mb-1 flex items-start gap-2">
                <i data-lucide="user-check" class="w-4 h-4 mt-0.5 text-[var(--text-muted)] shrink-0"></i>
                <strong>Contact Person:</strong>
                <span class="break-words">
                    {{ $customer->contact_person ?? '—' }}
                </span>
            </p>

            <p class="text-[var(--text-soft)] text-sm mb-1 flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-[var(--text-muted)] shrink-0"></i>
                <strong>Mobile:</strong> {{ $customer->contact ?? '—' }}
            </p>

            <p class="text-[var(--text-soft)] text-sm mb-1 flex items-start gap-2">
                <i data-lucide="mail" class="w-4 h-4 mt-0.5 text-[var(--text-muted)] shrink-0"></i>
                <strong>Email:</strong>
                <span class="break-words">
                    {{ $customer->email ?? '—' }}
                </span>
            </p>

            <p class="text-[var(--text-soft)] text-sm flex items-start gap-2 mt-3">
                <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 text-[var(--text-muted)] shrink-0"></i>
                <strong>Address:</strong>
                <span class="break-words">
                    {{ $customer->address ?? '—' }}
                </span>
            </p>

        </div>
    @empty
        <p class="text-center muted">No records found</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>

</div>
@endsection