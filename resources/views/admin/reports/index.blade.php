@extends('layouts.app')

@section('title','All Salesmen Visits Report')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="bar-chart-3" class="w-6 h-6"></i></span>
            Salesmen Visits Report
        </h1>
    </div>

    {{-- Today's Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="stat-card tint-blue">
            <div>
                <p class="text-sm muted mb-1">Today's Visits</p>
                <p class="text-3xl font-display font-bold text-[var(--text)]">{{ $todayVisits }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="calendar-check" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-emerald">
            <div>
                <p class="text-sm muted mb-1">Completed</p>
                <p class="text-3xl font-display font-bold text-emerald-600 dark:text-emerald-400">{{ $todayCompleted }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-amber">
            <div>
                <p class="text-sm muted mb-1">Pending</p>
                <p class="text-3xl font-display font-bold text-amber-600 dark:text-amber-400">{{ $todayPending }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-red">
            <div>
                <p class="text-sm muted mb-1">Blocked</p>
                <p class="text-3xl font-display font-bold text-red-600 dark:text-red-400">{{ $todayBlocked }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="shield-alert" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

    </div>

    {{-- Today's Salesmen & Companies --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

        <div class="card !rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="avatar avatar-md bg-blue-500/15 !text-blue-500">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <h3 class="font-semibold text-[var(--text)]">Today's Salesmen</h3>
            </div>
            @if($todaySalesmen->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($todaySalesmen as $name)
                        <span class="badge badge-sky">{{ $name }}</span>
                    @endforeach
                </div>
            @else
                <p class="muted text-sm">No visits today</p>
            @endif
        </div>

        <div class="card !rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="avatar avatar-md bg-violet-500/15 !text-violet-500">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
                <h3 class="font-semibold text-[var(--text)]">Today's Companies</h3>
            </div>
            @if($todayCompanies->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($todayCompanies as $name)
                        <span class="badge badge-violet">{{ $name }}</span>
                    @endforeach
                </div>
            @else
                <p class="muted text-sm">No visits today</p>
            @endif
        </div>

    </div>

    {{-- Filter Form --}}
    <x-filter-bar>
        <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
        <input type="hidden" name="year" value="{{ request('year', now()->year) }}">

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

        <x-filter-actions :reset="route('admin.reports.index')">
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
    <div class="hidden md:block tbl-wrap overflow-x-auto">
        <table class="tbl">
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
                        <td class="font-medium text-[var(--text)]">{{ $v->salesman->name }}</td>
                        <td class="text-[var(--text-soft)]">{{ $v->customer->name }}</td>
                        <td>
                            <a href="{{ route('admin.reports.show', $v->id) }}"
                               class="text-[var(--accent)] hover:underline font-medium flex items-center gap-1">
                                <i data-lucide="link" class="w-3 h-3"></i>
                                {{ $v->purpose }}
                            </a>
                        </td>
                        <td>
                            @if($v->status == 'started')
                                <span class="badge badge-amber"><i data-lucide="loader-2" class="w-3 h-3"></i> {{ ucfirst($v->status) }}</span>
                            @elseif($v->status == 'completed')
                                <span class="badge badge-emerald"><i data-lucide="check-square" class="w-3 h-3"></i> {{ ucfirst($v->status) }}</span>
                            @elseif($v->status == 'blocked')
                                <span class="badge badge-red"><i data-lucide="shield-off" class="w-3 h-3"></i> {{ ucfirst($v->status) }}</span>
                            @else
                                <span class="badge badge-red">{{ ucfirst($v->status) }}</span>
                            @endif
                        </td>
                        <td class="text-sm text-[var(--text-soft)] max-w-[120px] truncate">{{ $v->notes ?? '-' }}
                            @if($v->status == 'cancelled' && $v->cancelled_reason)
                                <div class="mt-1 text-xs text-red-500 break-words max-w-[120px]" title="{{ $v->cancelled_reason }}">
                                    <i data-lucide="x-circle" class="w-3 h-3 inline mr-0.5"></i>
                                    {{ \Illuminate\Support\Str::limit($v->cancelled_reason, 30) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $v->distance_km ?? '-' }}</td>
                        <td>{{ $v->pitstop_total_km ?? '-' }}</td>
                        <td>
                            @if($v->pitstops && count($v->pitstops) > 0)
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full gradient-surface text-white text-[10px] font-bold">{{ count($v->pitstops) }}</span>
                                    <span class="text-xs muted">stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                                </div>
                            @else
                                <span class="text-xs muted">-</span>
                            @endif
                        </td>
                        <td class="text-sm text-[var(--text-soft)] max-w-[120px] truncate">{{ $v->customer->address ?? '-' }}</td>
                        <td class="text-sm text-[var(--text-soft)] whitespace-nowrap">{{ $v->started_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="py-10 text-center muted">
                            <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 opacity-50"></i>
                            No report found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4">
        @forelse($visits as $v)
            <div class="card !rounded-2xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="avatar avatar-sm bg-[var(--accent-soft)] !text-[var(--accent)]">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-[var(--text)] font-semibold text-sm">{{ $v->salesman->name }}</h3>
                            <p class="muted text-xs">{{ $v->customer->name }}</p>
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
                    <p class="muted text-xs mb-3 flex items-center">
                        <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                        {{ $v->customer->address }}
                    </p>
                @endif

                <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                    <div>
                        <span class="muted text-xs block mb-0.5">Purpose</span>
                        <a href="{{ route('admin.reports.show', $v->id) }}" class="text-[var(--accent)] text-sm flex items-center gap-1">
                            <i data-lucide="link" class="w-3 h-3"></i>
                            {{ $v->purpose }}
                        </a>
                    </div>
                    <div>
                        <span class="muted text-xs block mb-0.5">Distance</span>
                        <span class="text-[var(--text)]">{{ $v->distance_km ?? '-' }} km</span>
                    </div>
                    <div>
                        <span class="muted text-xs block mb-0.5">Total Km</span>
                        <span class="text-[var(--text)]">{{ $v->pitstop_total_km ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="muted text-xs block mb-0.5">Stops</span>
                        @if($v->pitstops && count($v->pitstops) > 0)
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full gradient-surface text-white text-[10px] font-bold">{{ count($v->pitstops) }}</span>
                                <span class="text-xs muted">stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                            </div>
                        @else
                            <span class="text-xs muted">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="muted text-xs block mb-0.5">Date</span>
                        <span class="text-[var(--text)]">{{ $v->started_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>

                @if($v->notes)
                    <div class="soft-panel rounded-lg p-3 text-sm">
                        <span class="muted text-xs block mb-1">Notes</span>
                        <span class="text-[var(--text-soft)] text-sm">{{ $v->notes }}</span>
                    </div>
                @endif

                @if($v->status == 'cancelled' && $v->cancelled_reason)
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 text-sm">
                        <span class="text-red-500 text-xs block mb-1 flex items-center">
                            <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Cancellation Reason
                        </span>
                        <span class="text-red-500 dark:text-red-300 text-sm break-words">{{ $v->cancelled_reason }}</span>
                    </div>
                @endif

            </div>
        @empty
            <div class="card !rounded-2xl p-6 text-center muted">
                <i data-lucide="search-x" class="w-6 h-6 inline-block mr-2 opacity-50"></i>
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

        .tbl-wrap {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
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