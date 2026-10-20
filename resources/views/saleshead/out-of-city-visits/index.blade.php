@extends('layouts.app')
@section('title', 'Out of City Visits')

@section('content')

<style>
    .report-main {
        max-width: 68rem;
        margin-left: auto;
        margin-right: auto;
    }

    .report-table th,
    .report-table td {
        white-space: nowrap;
    }
</style>

    <div class="report-main p-0 sm:p-0">

        <div class="page-head !mb-6">
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
                Out of City Visits
            </h1>
        </div>

        {{-- FILTER BAR --}}
        <x-filter-bar :action="route('salehead.out-of-city-visits.index')">
            <x-filter-field icon="filter">
                <select name="status" onchange="this.form.submit()" class="select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                </select>
            </x-filter-field>

            <x-filter-actions :reset="route('salehead.out-of-city-visits.index')" />
        </x-filter-bar>

        {{-- Desktop Table --}}
        <div class="card !rounded-2xl shadow-card-lg hidden md:block overflow-x-auto">
            <div class="tbl-wrap !border-0 !shadow-none">
            <table class="report-table tbl w-full">
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
                            <td class="text-[var(--text-soft)]">{{ $v->id }}</td>
                            <td class="text-[var(--text)]">{{ $v->salesman->name ?? '-' }}</td>
                            <td class="text-[var(--text-soft)]">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</td>
                            <td>
                                @if ($v->status === 'pending')
                                    <span class="badge badge-amber">
                                        <i data-lucide="loader-2" class="w-3 h-3"></i>
                                        {{ ucfirst($v->status) }}
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <i data-lucide="check-square" class="w-3 h-3"></i>
                                        {{ ucfirst($v->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-[var(--text-soft)]">{{ $v->items->count() }}</td>
                            <td class="text-[var(--text-soft)]">{{ optional($v->submitted_at)->format('d M Y, h:i A') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('salehead.out-of-city-visits.show', $v->id) }}" class="btn btn-primary btn-sm">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center muted">
                                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-[var(--text-soft)]"></i>
                                No out of city visits found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden space-y-4">
            @forelse ($visits as $v)
                <div class="card !rounded-xl p-4 border border-[var(--border)]">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="avatar avatar-md bg-[var(--accent-softer)] text-[var(--accent)]">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-[var(--text)] font-semibold text-sm">{{ $v->salesman->name ?? '-' }}</h3>
                                <p class="text-[var(--text-muted)] text-xs">{{ optional($v->attendance)->date ? $v->attendance->date->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        @if ($v->status === 'pending')
                            <span class="badge badge-amber">
                                <i data-lucide="loader-2" class="w-3 h-3"></i>
                                {{ ucfirst($v->status) }}
                            </span>
                        @else
                            <span class="badge badge-emerald">
                                <i data-lucide="check-square" class="w-3 h-3"></i>
                                {{ ucfirst($v->status) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-[var(--text-soft)] text-xs mb-3 flex items-center gap-1.5">
                        <i data-lucide="building-2" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                        {{ $v->items->count() }} customer(s) visited
                    </p>
                    <a href="{{ route('salehead.out-of-city-visits.show', $v->id) }}" class="btn btn-primary btn-sm block w-full">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Details
                    </a>
                </div>
            @empty
                <div class="p-6 text-center muted soft-panel rounded-xl">
                    <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-[var(--text-soft)]"></i>
                    No out of city visits found.
                </div>
            @endforelse
        </div>

    <div class="mt-6">
        {{ $visits->links() }}
    </div>

@endsection