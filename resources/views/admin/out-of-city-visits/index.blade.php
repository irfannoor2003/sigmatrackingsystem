@extends('layouts.app')

@section('title','Out of City Visits')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
            Out of City Visits
        </h1>
    </div>

    {{-- Filter Form --}}
    <x-filter-bar :action="route('admin.out-of-city-visits.index')">
        <x-filter-field icon="filter">
            <select name="status" onchange="this.form.submit()" class="select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
            </select>
        </x-filter-field>

        <x-filter-field icon="users">
            <select name="salesman_id" onchange="this.form.submit()" class="select">
                <option value="">All Salesmen</option>
                @foreach ($salesmen as $s)
                    <option value="{{ $s->id }}" {{ request('salesman_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </x-filter-field>

        <x-filter-actions :reset="route('admin.out-of-city-visits.index')" />
    </x-filter-bar>

    {{-- Desktop Table --}}
    <div class="hidden md:block tbl-wrap overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Salesman</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Visits</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $v)
                    <tr>
                        <td class="muted">{{ $v->id }}</td>
                        <td class="font-medium text-[var(--text)]">{{ $v->salesman->name ?? '-' }}</td>
                        <td>{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</td>
                        <td>
                            @if ($v->status === 'pending')
                                <span class="badge badge-amber">
                                    <i data-lucide="loader-2" class="w-3 h-3"></i> {{ ucfirst($v->status) }}
                                </span>
                            @else
                                <span class="badge badge-emerald">
                                    <i data-lucide="check-square" class="w-3 h-3"></i> {{ ucfirst($v->status) }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $v->items->count() }}</td>
                        <td>{{ optional($v->submitted_at)->format('d M Y, h:i A') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.out-of-city-visits.show', $v->id) }}" class="btn btn-xs btn-soft">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center muted">
                            <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 opacity-50"></i>
                            No out of city visits found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse ($visits as $v)
            <div class="card !rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="avatar avatar-sm bg-[var(--accent-soft)] !text-[var(--accent)]">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-[var(--text)] font-semibold text-sm">{{ $v->salesman->name ?? '-' }}</h3>
                            <p class="muted text-xs">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                    @if ($v->status === 'pending')
                        <span class="badge badge-amber">Pending</span>
                    @else
                        <span class="badge badge-emerald">Submitted</span>
                    @endif
                </div>

                <p class="muted text-xs mb-3 flex items-center">
                    <i data-lucide="building-2" class="w-3 h-3 mr-1"></i>
                    {{ $v->items->count() }} customer(s) visited
                </p>

                <a href="{{ route('admin.out-of-city-visits.show', $v->id) }}"
                    class="btn btn-sm btn-soft w-full">
                    <i data-lucide="eye" class="w-4 h-4"></i> View Details
                </a>
            </div>
        @empty
            <div class="card !rounded-2xl p-6 text-center muted">
                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 opacity-50"></i>
                No out of city visits found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $visits->links() }}
    </div>

</div>

<style>
    @media print {
        nav, .sidebar, form, button, .pagination, footer {
            display: none !important;
        }
        body {
            background: white !important;
            color: black !important;
            margin: 0;
            padding: 0;
        }
        .tbl-wrap {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }
        .tbl, .tbl th, .tbl td {
            color: black !important;
            border-color: #ddd !important;
        }
        .hidden.md\:block { display: block !important; }
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            color: black !important;
        }
        th, td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            color: black !important;
            text-align: left !important;
        }
        th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
        h1 { color: black !important; margin-bottom: 20px; }
        .md\:hidden { display: none !important; }
        [data-lucide] { display: none !important; }
    }
</style>

@endsection