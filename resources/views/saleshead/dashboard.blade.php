@extends('layouts.app')

@section('title', 'Sales Head Dashboard')

@section('content')

<div class="page-head">
    <h1 class="page-title">
        <span class="page-ico"><i data-lucide="layout-dashboard" class="w-6 h-6"></i></span>
        Sales Head Dashboard
    </h1>
    <span class="badge badge-magenta !py-1.5 !px-4 !text-xs">
        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
        {{ now()->format('l, d M Y') }}
    </span>
</div>

@php
    $monthVisitCount = $visitsThisMonth->count();
    $todayVisitCount = $todayVisits->count();
    $completedToday = $todayVisits->where('status', 'completed')->count();
    $engagementIndex = $totalSalesmen > 0 ? round((min($todayVisitCount, $totalSalesmen * 5) / ($totalSalesmen * 5)) * 100) : 0;
@endphp

{{-- Stat Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

    <div class="stat-card tint-cyan">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Total Customers</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $totalCustomers }}</p>
                <a href="{{ route('salehead.customers.index') }}"
                    class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Customers</a>
            </div>
            <span class="stat-ico"><i data-lucide="contact" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-magenta">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Total Salesmen</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $totalSalesmen }}</p>
                <a href="{{ route('salehead.salesmen.index') }}"
                    class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">Open Section</a>
            </div>
            <span class="stat-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-amber">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Visits This Month</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $monthVisitCount }}</p>
                <a href="{{ route('salehead.reports.index') }}"
                    class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Reports</a>
            </div>
            <span class="stat-ico"><i data-lucide="calendar" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <a href="{{ route('salehead.reports.index') }}" class="stat-card tint-orange block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Today's Visits</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $todayVisitCount }}</p>
                <span class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Reports</span>
            </div>
            <span class="stat-ico"><i data-lucide="sun" class="w-6 h-6 animate-pulse"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </a>

    <a href="{{ route('salehead.reports.index', ['status' => 'blocked']) }}" class="stat-card tint-red block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Completed Today</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $completedToday }}</p>
                <span class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Completed</span>
            </div>
            <span class="stat-ico"><i data-lucide="alert-circle" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar">
            <div style="width: {{ $todayVisitCount > 0 ? ($completedToday / $todayVisitCount) * 100 : 0 }}%"></div>
        </div>
    </a>

    <a href="{{ route('salehead.out-of-city-visits.index') }}" class="stat-card tint-violet block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Out of City Visits</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">
                    {{ $engagementIndex }}<span class="text-base muted font-medium">%</span>
                </p>
                <span class="text-[.68rem] muted inline-block mt-1">Team field coverage index</span>
            </div>
            <span class="stat-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $engagementIndex }}%"></div></div>
    </a>
</div>

{{-- Recent Activities --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-7">

    <div class="card p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h3 class="font-display font-semibold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-[var(--accent)]"></i>
                Recent Visits Activities
            </h3>
            <span class="live-pill"><span></span> Live</span>
        </div>
        <ul class="space-y-4">
            @forelse($visitActivities as $v)
                <li class="flex items-start gap-3">
                    <div class="activity-live w-9 h-9 flex items-center justify-center rounded-xl shrink-0 bg-[var(--accent-soft)] text-[var(--accent)]">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-[var(--text)]">
                            <strong>{{ $v->salesman->name }}</strong> started visit at
                            <strong>{{ $v->customer->name }}</strong>
                        </p>
                        <span class="text-xs muted">{{ $v->created_at->diffForHumans() }}</span>
                    </div>
                </li>
            @empty
                <li class="text-sm muted">No visit activity found.</li>
            @endforelse
        </ul>
    </div>

    <div class="card p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h3 class="font-display font-semibold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-[var(--accent)]"></i>
                Today's Team Activity
            </h3>
            <span class="live-pill"><span></span> Live</span>
        </div>
        <ul class="space-y-4">
            @forelse($todayVisits as $v)
                <li class="flex items-start gap-3">
                    <div class="w-9 h-9 flex items-center justify-center rounded-xl shrink-0
                        @if($v->status === 'completed') bg-emerald-500/10 text-emerald-500
                        @elseif($v->status === 'blocked') bg-red-500/10 text-red-500
                        @else bg-sky-500/10 text-sky-500 @endif">
                        <i data-lucide="{{ $v->status === 'completed' ? 'check-circle' : ($v->status === 'blocked' ? 'ban' : 'play-circle') }}" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-[var(--text)]">
                            <strong>{{ $v->salesman->name }}</strong>
                            @if($v->status === 'completed')
                                <span class="text-emerald-500 font-semibold">completed</span>
                                at {{ \Carbon\Carbon::parse($v->started_at)->format('h:i A') }}
                            @elseif($v->status === 'blocked')
                                <span class="text-red-500 font-semibold">was blocked</span>
                                at {{ \Carbon\Carbon::parse($v->started_at)->format('h:i A') }}
                            @else
                                <span class="text-sky-500 font-semibold">is in progress</span>
                                at {{ \Carbon\Carbon::parse($v->started_at)->format('h:i A') }}
                            @endif
                        </p>
                        <span class="text-xs muted">{{ $v->updated_at->diffForHumans() }}</span>
                    </div>
                </li>
            @empty
                <li class="text-sm muted">No team activity found.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection
