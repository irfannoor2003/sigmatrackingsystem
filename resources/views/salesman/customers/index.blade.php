@extends('layouts.app')
@section('title', 'My Customers')

@section('content')

    <div class="p-0">

        {{-- Header --}}
        <div class="page-head !mb-6">
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
                My Customers
            </h1>
        </div>

        {{-- Add Customer / Export Buttons --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between gap-3">
            <a href="{{ route('salesman.customers.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add Customer
            </a>

            <a href="{{ route('salesman.customers.export.all') }}" class="btn btn-success">
                <i data-lucide="download" class="w-5 h-5"></i>
                Export Excel
            </a>
        </div>


        <div class="card !rounded-2xl shadow-card-lg overflow-hidden hidden md:block">
            <div class="tbl-wrap !border-0 !shadow-none">
            <table class="tbl w-full table-auto">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $c)
                        <tr>
                            <td class="muted">{{ $c->id }}</td>
                            <td class="text-[var(--text)]">{{ $c->name }}</td>
                            <td class="text-[var(--text-soft)]">
                                {{ $c->contact_person ?? '-' }}
                            </td>
                            <td class="text-[var(--text-soft)]">
                                {{ $c->phone1 ?? '-' }}
                            </td>
                            <td class="text-[var(--text-muted)]">
                                {{ $c->address ?? '-' }}
                            </td>
                            <td>
                                <div class="flex gap-2">

                                    {{-- View --}}
                                    <a href="{{ route('salesman.customers.show', $c->id) }}" class="btn btn-soft btn-xs">
                                        View
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('salesman.customers.edit', $c->id) }}" class="btn btn-outline btn-xs">
                                        Edit
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center muted soft-panel">
                                No customers found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
            </div>
        </div>



        <div class="md:hidden p-1 space-y-4">

            @forelse ($customers as $customer)
                <div class="card !rounded-xl p-4 border border-[var(--border)] shadow-lg mb-3">

                    {{-- Customer Name --}}
                    <h2 class="text-lg font-semibold text-[var(--text)] mb-2 flex items-center">
                        <i data-lucide="building" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                        {{ $customer->name }}
                    </h2>

                    {{-- Contact Person --}}
                    <p class="text-[var(--text-soft)] text-sm mb-2 flex items-center">
                        <i data-lucide="user-round" class="w-4 h-4 mr-2 text-[var(--text-muted)]"></i>
                        <span class="font-medium mr-1">Contact Person:</span> {{ $customer->contact_person ?? '—' }}
                    </p>

                    {{-- Phone --}}
                    <p class="text-[var(--text-soft)] text-sm mb-2 flex items-center">
                        <i data-lucide="phone" class="w-4 h-4 mr-2 text-[var(--text-muted)]"></i>
                        <span class="font-medium mr-1">Mobile:</span> {{ $customer->phone1 ?? '—' }}
                    </p>

                    {{-- Address --}}
                    <p class="text-[var(--text-soft)] text-sm mb-3 flex items-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-[var(--text-muted)] flex-shrink-0 mt-0.5"></i>
                        <span class="font-medium mr-1">Address:</span> {{ $customer->address ?? '—' }}
                    </p>

                    <div class="flex gap-2">

                        {{-- View --}}
                        <a href="{{ route('salesman.customers.show', $customer->id) }}" class="btn btn-soft btn-sm flex-1">
                            View
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('salesman.customers.edit', $customer->id) }}" class="btn btn-outline btn-sm flex-1">
                            Edit
                        </a>

                    </div>

                </div>

            @empty
                <p class="text-center muted">No customers found</p>
            @endforelse

        </div>

    </div>
    <div class="mt-5">
        {{ $customers->links() }}
    </div>

@endsection