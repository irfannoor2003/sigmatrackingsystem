@extends('layouts.app')

@section('title','All Salesmen Visits Report')

@section('content')

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }
    .report-main {
        max-width: 68rem;
        margin-left: auto;
        margin-right: auto;
    }

    /* Hide horizontal scrollbar */
    body {
        overflow-x: hidden;
    }

    .overflow-x-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }

    .report-table th,
    .report-table td {
        white-space: nowrap;
    }
</style>

<div class="report-main p-0 sm:p-0">

    <div class="page-head !mb-6">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="bar-chart-3" class="w-6 h-6"></i></span>
            Salesmen Visits Report
        </h1>
    </div>

    {{-- Filter Form --}}
    <x-filter-bar>
        <x-filter-field icon="users">
            <select name="salesman_id" class="select">
                <option value="">All Salesmen</option>
                @foreach($salesmen as $s)
                    <option value="{{ $s->id }}" {{ request('salesman_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </x-filter-field>

        <x-filter-field icon="calendar">
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="input">
        </x-filter-field>

        <x-filter-field icon="calendar">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="input">
        </x-filter-field>

        <x-filter-field icon="check-circle">
            <select name="status" class="select">
                <option value="">All Status</option>
                <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Started</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </x-filter-field>

        <x-filter-actions :reset="route('salehead.reports.index')">
            <button type="button" onclick="window.print()" class="btn btn-outline w-full sm:w-auto">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Print
            </button>

            <a href="{{ route('visits.export.monthly', request()->all()) }}" class="btn btn-success w-full sm:w-auto">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export
            </a>
        </x-filter-actions>
    </x-filter-bar>

    {{-- Desktop Table --}}
    <div class="card !rounded-2xl shadow-card-lg hidden md:block overflow-x-auto">
        <div class="tbl-wrap !border-0 !shadow-none">
        <table class="report-table tbl w-full">
            <thead>
                <tr>
                    <th class="print:hidden">Id</th>
                    <th>Salesman</th>
                    <th>Customer</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Km</th>
                    <th>Total Km</th>
                    <th>Stops</th>
                    <th>Address</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $v)
                    <tr>
                        <td class="muted print:hidden">{{ $v->id }}</td>
                        <td class="text-[var(--text)]">{{ $v->salesman->name }}</td>
                        <td class="text-[var(--text)]">{{ $v->customer->name }}</td>
                        <td>
                            <a href="{{ route('salehead.visits.show', $v->id) }}"
                               class="text-[var(--accent)] hover:underline font-medium flex items-center gap-1">
                                <i data-lucide="link" class="w-3 h-3"></i>
                                {{ $v->purpose }}
                            </a>
                        </td>
                        <td>
                            @if($v->status == 'started')
                                <span class="badge badge-amber"><i data-lucide="loader-2" class="w-3 h-3"></i>{{ ucfirst($v->status) }}</span>
                            @elseif($v->status == 'completed')
                                <span class="badge badge-emerald"><i data-lucide="check-square" class="w-3 h-3"></i>{{ ucfirst($v->status) }}</span>
                            @elseif($v->status == 'blocked')
                                <span class="badge badge-red"><i data-lucide="shield-off" class="w-3 h-3"></i>{{ ucfirst($v->status) }}</span>
                            @else
                                <span class="badge badge-red">{{ ucfirst($v->status) }}</span>
                            @endif
                        </td>
                        <td class="text-[var(--text-soft)] text-sm max-w-[120px] truncate" title="{{ $v->notes ?? '' }}">{{ $v->notes ?? '-' }}
                            @if($v->status == 'cancelled' && $v->cancelled_reason)
                                <div class="mt-1 text-xs text-red-400/90 break-words max-w-[120px]" title="{{ $v->cancelled_reason }}">
                                    <i data-lucide="x-circle" class="w-3 h-3 inline mr-0.5"></i>
                                    {{ \Illuminate\Support\Str::limit($v->cancelled_reason, 30) }}
                                </div>
                            @endif
                        </td>
                        <td class="text-[var(--text-soft)]">{{ $v->distance_km ?? '-' }}</td>
                        <td class="text-[var(--text-soft)]">{{ $v->pitstop_total_km ?? '-' }}</td>
                        <td>
                            @if($v->pitstops && count($v->pitstops) > 0)
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--accent)] text-white text-[10px] font-bold">{{ count($v->pitstops) }}</span>
                                    <span class="text-[var(--text-soft)] text-xs">stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                                </div>
                            @else
                                <span class="text-[var(--text-soft)] text-xs">-</span>
                            @endif
                        </td>
                        <td class="text-[var(--text-soft)] text-sm max-w-[120px] truncate" title="{{ $v->customer->address ?? '' }}">{{ $v->customer->address ?? '-' }}</td>
                        <td class="text-[var(--text-soft)] text-sm">{{ $v->started_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="p-8 text-center muted">
                            <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-[var(--text-soft)]"></i>
                            No report found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse($visits as $v)
            <div class="card !rounded-xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="avatar avatar-md bg-[var(--accent-softer)] text-[var(--accent)]">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[var(--text)] font-semibold text-sm">{{ $v->salesman->name }}</h3>
                            <p class="text-[var(--text-soft)] text-xs">{{ $v->customer->name }}</p>
                        </div>
                    </div>
                    @if($v->status == 'started')
                        <span class="badge badge-amber">Started</span>
                    @elseif($v->status == 'completed')
                        <span class="badge badge-emerald">Completed</span>
                    @elseif($v->status == 'blocked')
                        <span class="badge badge-red">Blocked</span>
                    @else
                        <span class="badge badge-red">{{ ucfirst($v->status) }}</span>
                    @endif
                </div>

                @if($v->customer->address)
                    <p class="text-[var(--text-soft)] text-xs mb-3 flex items-center">
                        <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                        {{ $v->customer->address }}
                    </p>
                @endif

                <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                    <div>
                        <span class="text-[var(--text-soft)] text-xs block mb-0.5">Purpose</span>
                        <a href="{{ route('salehead.visits.show', $v->id) }}"
                           class="text-[var(--accent)] text-sm flex items-center gap-1">
                            <i data-lucide="link" class="w-3 h-3"></i>
                            {{ $v->purpose }}
                        </a>
                    </div>
                    <div>
                        <span class="text-[var(--text-soft)] text-xs block mb-0.5">Distance</span>
                        <span class="text-[var(--text)]">{{ $v->distance_km ?? '-' }} km</span>
                    </div>
                    <div>
                        <span class="text-[var(--text-soft)] text-xs block mb-0.5">Total Km</span>
                        <span class="text-[var(--text)]">{{ $v->pitstop_total_km ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[var(--text-soft)] text-xs block mb-0.5">Stops</span>
                        @if($v->pitstops && count($v->pitstops) > 0)
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--accent)] text-white text-[10px] font-bold">{{ count($v->pitstops) }}</span>
                                <span class="text-[var(--text-soft)] text-xs">stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                            </div>
                        @else
                            <span class="text-[var(--text-soft)] text-xs">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[var(--text-soft)] text-xs block mb-0.5">Date</span>
                        <span class="text-[var(--text)]">{{ $v->started_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>

                @if($v->notes)
                    <div class="soft-panel rounded-lg p-3 text-sm">
                        <span class="text-[var(--text-soft)] text-xs block mb-1">Notes</span>
                        <span class="text-[var(--text-soft)] text-sm">{{ $v->notes }}</span>
                    </div>
                @endif

                @if($v->status == 'cancelled' && $v->cancelled_reason)
                    <div class="alert alert-error rounded-lg p-3 text-sm">
                        <span class="text-red-400 text-xs block mb-1 flex items-center">
                            <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Cancellation Reason
                        </span>
                        <span class="text-red-200 text-sm break-words">{{ $v->cancelled_reason }}</span>
                    </div>
                @endif

            </div>
        @empty
            <div class="p-6 text-center muted bg-[var(--surface)] border border-[var(--border)] rounded-xl">
                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 text-[var(--text-soft)]"></i>
                No report found
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

        .card, .tbl-wrap, .table, table {
            background: transparent !important;
            box-shadow: none !important;
        }

        .hidden.md\:block {
            display: block !important;
        }

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

        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }

        h1 {
            color: black !important;
            margin-bottom: 20px;
        }

        .md\:hidden {
            display: none !important;
        }

        [data-lucide] {
            display: none !important;
        }
    }
</style>

@endsection