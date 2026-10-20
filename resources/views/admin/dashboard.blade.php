@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

@php
    use App\Models\User;
    use App\Models\Attendance;
    use App\Models\Visit;
    use App\Models\Customer;

    // TOTAL STAFF (exclude admin)
    $totalStaff = User::whereIn('role', [
        'salesman', 'it', 'account', 'store', 'office_boy',
    ])->whereNull('left_at')->count();

    // TODAY WORKING STAFF
    $todayWorkingStaff = Attendance::whereDate('date', now()->toDateString())
        ->where('status', 'present')
        ->distinct('salesman_id')
        ->count('salesman_id');

    $salesmenCount = User::where('role', 'salesman')->whereNull('left_at')->count();
    $customersCount = Customer::count();
    $monthVisits = Visit::whereMonth('created_at', now()->month)->count();
    $todayVisits = Visit::whereDate('created_at', now()->toDateString())->count();
    $lateCount = $lateStaff->count();
@endphp

<div class="page-head">
    <h1 class="page-title">
        <span class="page-ico"><i data-lucide="layout-dashboard" class="w-6 h-6"></i></span>
        Admin Dashboard
    </h1>
    <span class="badge badge-magenta !py-1.5 !px-4 !text-xs">
        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
        {{ now()->format('l, d M Y') }}
    </span>
</div>

{{-- Stat Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

    <div class="stat-card tint-magenta">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Total Staff</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $totalStaff }}</p>
                <span class="text-[.68rem] muted inline-block mt-1">Sales · IT · Accounts · Store · Office_boy</span>
            </div>
            <span class="stat-ico"><i data-lucide="briefcase" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-emerald">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Working Today</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-display font-bold text-[var(--text)] tracking-tight">{{ $todayWorkingStaff }}</p>
                    <a href="{{ route('admin.attendance.today') }}" class="text-xs font-bold text-[var(--accent)] hover:underline tracking-tight">Details</a>
                </div>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ ($totalStaff > 0) ? ($todayWorkingStaff / $totalStaff) * 100 : 0 }}%"></div></div>
    </div>

    <div class="stat-card tint-violet">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Salesmen</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $salesmenCount }}</p>
                <a href="{{ route('admin.salesmen.index') }}" class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">Open Section</a>
            </div>
            <span class="stat-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ ($totalStaff > 0) ? ($salesmenCount / $totalStaff) * 100 : 0 }}%"></div></div>
    </div>

    <div class="stat-card tint-cyan">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Customers</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $customersCount }}</p>
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Customers</a>
            </div>
            <span class="stat-ico"><i data-lucide="contact" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-amber">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Visits This Month</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $monthVisits }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="calendar" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-orange">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Today's Visits</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $todayVisits }}</p>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Reports</a>
            </div>
            <span class="stat-ico"><i data-lucide="sun" class="w-6 h-6 animate-pulse"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <a href="{{ route('admin.visits.blocked') }}" class="stat-card tint-red block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Blocked Visits</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $blockedVisits }}</p>
                <span class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Blocked Visits</span>
            </div>
            <span class="stat-ico"><i data-lucide="alert-circle" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </a>

    <a href="{{ route('admin.attendance.late-staff') }}" class="stat-card tint-amber block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Late Staff</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-display font-bold text-[var(--text)] tracking-tight">{{ $lateCount }}</p>
                    <span class="text-xs font-bold text-[var(--accent)] hover:underline tracking-tight">Details</span>
                </div>
                <span class="text-[.68rem] muted inline-block mt-1">Clocked in after 10:15 AM</span>
            </div>
            <span class="stat-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ ($todayWorkingStaff > 0) ? ($lateCount / $todayWorkingStaff) * 100 : 0 }}%"></div></div>
    </a>

    <a href="{{ route('admin.salesmen.index') }}" class="stat-card tint-rose block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Sales Perf.</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">
                    {{ $todayWorkingStaff > 0 ? round(($todayVisits / max($todayWorkingStaff, 1)) * 100) / 20 : 0 }}<span class="text-base muted font-medium">/5</span>
                </p>
                <span class="text-[.68rem] muted inline-block mt-1">Daily engagement index</span>
            </div>
            <span class="stat-ico"><i data-lucide="gauge" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ min(100, $todayWorkingStaff > 0 ? (($todayVisits / max($todayWorkingStaff, 1)) / 5) * 100 : 0) }}%"></div></div>
    </a>
</div>

{{-- Late Staff Today --}}
@if($lateCount > 0)
<div class="card mt-7 p-6 !rounded-2xl overflow-hidden">
    <div class="flex items-center gap-2 mb-4">
        <i data-lucide="clock" class="w-5 h-5 text-amber-500"></i>
        <h3 class="font-display font-semibold text-[var(--text)]">Late Staff Today</h3>
        <a href="{{ route('admin.attendance.late-staff') }}"
            class="ml-auto badge badge-amber hover:opacity-80 transition">
            {{ $lateCount }} staff — View All
        </a>
    </div>
    <div class="max-h-[300px] overflow-y-auto space-y-2">
        @foreach($lateStaff as $late)
            @php
                $clockInTime = \Carbon\Carbon::parse($late->clock_in);
                $lateThreshold = \Carbon\Carbon::today()->setTime(10, 16);
                $minutesLate = (int) $clockInTime->diffInMinutes($lateThreshold);
            @endphp
            <div class="flex flex-col gap-3 soft-panel px-4 py-3 rounded-xl sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 text-sm font-semibold text-[var(--text)]">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </span>
                        {{ $late->salesman->name ?? 'N/A' }}
                        <span class="badge badge-gray capitalize">{{ ucfirst($late->salesman->role ?? '-') }}</span>
                    </div>
                    <p class="mt-2 flex items-start gap-1.5 pl-9 text-xs {{ $late->late_reason ? 'text-[var(--text-soft)]' : 'text-amber-600 dark:text-amber-400' }}">
                        <i data-lucide="message-square-text" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>
                        <span>{{ $late->late_reason ?: 'Late reason not submitted yet.' }}</span>
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-3 pl-9 sm:pl-0">
                    <span class="text-xs muted">{{ $clockInTime->format('h:i A') }}</span>
                    <span class="badge badge-amber">{{ $minutesLate }} min late</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Recent Activities --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-7">

    <div class="card p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h3 class="font-display font-semibold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="activity" class="w-5 h-5 text-[var(--accent)]"></i>
                Recent Attendance Activities
            </h3>
            <span class="live-pill"><span></span> Live</span>
        </div>
        <ul class="space-y-4">
            @forelse($attendanceActivities as $a)
                <li class="flex items-start gap-3">
                    <div class="w-9 h-9 flex items-center justify-center rounded-xl shrink-0 {{ $a->status === 'present' && !$a->clock_out ? 'activity-live' : '' }}
                        @if($a->status === 'present' && !$a->clock_out) bg-emerald-500/10 text-emerald-500
                        @elseif($a->status === 'present') bg-sky-500/10 text-sky-500
                        @else bg-red-500/10 text-red-500 @endif">
                        @if($a->status === 'leave')
                            <i data-lucide="ban" class="w-4 h-4"></i>
                        @elseif(!$a->clock_out)
                            <i data-lucide="play-circle" class="w-4 h-4"></i>
                        @else
                            <i data-lucide="stop-circle" class="w-4 h-4"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-[var(--text)]">
                            <strong>{{ $a->salesman->name }}</strong>
                            @if($a->status === 'leave')
                                is on <span class="text-red-500 font-semibold">leave</span>
                            @elseif(!$a->clock_out)
                                <span class="text-emerald-500 font-semibold">clocked in</span>
                                at {{ \Carbon\Carbon::parse($a->clock_in)->format('h:i A') }}
                            @else
                                <span class="text-sky-500 font-semibold">clocked out</span>
                                at {{ \Carbon\Carbon::parse($a->clock_out)->format('h:i A') }}
                            @endif
                        </p>
                        <span class="text-xs muted">{{ $a->updated_at->diffForHumans() }}</span>
                    </div>
                </li>
            @empty
                <li class="text-sm muted">No attendance activity found.</li>
            @endforelse
        </ul>
    </div>

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
</div>

@endsection