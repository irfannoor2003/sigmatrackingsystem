@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')

<div class="page-head">
    <h1 class="page-title">
        <span class="page-ico"><i data-lucide="layout-dashboard" class="w-6 h-6"></i></span>
        HR Dashboard
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
                <a href="{{ route('hr.staff.index') }}"
                    class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">Open Section</a>
            </div>
            <span class="stat-ico"><i data-lucide="users-round" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: 100%"></div></div>
    </div>

    <div class="stat-card tint-emerald">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Present Today</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-display font-bold text-[var(--text)] tracking-tight">{{ $presentCount }}</p>
                    <a href="{{ route('hr.attendance.today') }}"
                        class="text-xs font-bold text-[var(--accent)] hover:underline tracking-tight">Details</a>
                </div>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $totalStaff > 0 ? ($presentCount / $totalStaff) * 100 : 0 }}%"></div></div>
    </div>

    <div class="stat-card tint-red">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Absent Today</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $absentCount }}</p>
                <a href="{{ route('hr.attendance.index') }}"
                    class="text-xs font-bold text-[var(--accent)] hover:underline inline-block mt-1">View Report</a>
            </div>
            <span class="stat-ico"><i data-lucide="x-circle" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $totalStaff > 0 ? ($absentCount / $totalStaff) * 100 : 0 }}%"></div></div>
    </div>

    <div class="stat-card tint-amber">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">On Leave</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-display font-bold text-[var(--text)] tracking-tight">{{ $leaveCount }}</p>
                    <a href="{{ route('hr.attendance.leave-requests') }}"
                        class="text-xs font-bold text-[var(--accent)] hover:underline tracking-tight">Details</a>
                </div>
            </div>
            <span class="stat-ico"><i data-lucide="calendar-off" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $totalStaff > 0 ? ($leaveCount / $totalStaff) * 100 : 0 }}%"></div></div>
    </div>

    <a href="{{ route('hr.attendance.late-staff') }}" class="stat-card tint-orange block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Late Today</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-display font-bold text-[var(--text)] tracking-tight">{{ $lateStaff->count() }}</p>
                    <span class="text-xs font-bold text-[var(--accent)] tracking-tight">Details</span>
                </div>
                <span class="text-[.68rem] muted inline-block mt-1">Clocked in after 10:15 AM</span>
            </div>
            <span class="stat-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $presentCount > 0 ? ($lateStaff->count() / $presentCount) * 100 : 0 }}%"></div></div>
    </a>

    <a href="{{ route('hr.attendance.leave-requests') }}" class="stat-card tint-violet block">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm muted font-medium">Attendance Rate</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">
                    {{ $totalStaff > 0 ? round(($presentCount / $totalStaff) * 100) : 0 }}<span class="text-base muted font-medium">%</span>
                </p>
                <span class="text-[.68rem] muted inline-block mt-1">Present vs total workforce</span>
            </div>
            <span class="stat-ico"><i data-lucide="gauge" class="w-6 h-6"></i></span>
        </div>
        <div class="stat-bar"><div style="width: {{ $totalStaff > 0 ? ($presentCount / $totalStaff) * 100 : 0 }}%"></div></div>
    </a>
</div>

{{-- Late Staff Today --}}
@if($lateStaff->count() > 0)
<div class="card mt-7 p-6 !rounded-2xl overflow-hidden">
    <div class="flex items-center gap-2 mb-4">
        <i data-lucide="clock" class="w-5 h-5 text-amber-500"></i>
        <h3 class="font-display font-semibold text-[var(--text)]">Late Staff Today</h3>
        <a href="{{ route('hr.attendance.late-staff') }}" class="ml-auto badge badge-amber hover:opacity-80 transition">
            {{ $lateStaff->count() }} staff — View All
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
                        @if($a->status === 'leave')
                        bg-amber-500/10 text-amber-500
                        @elseif($a->status === 'present' && !$a->clock_out) bg-emerald-500/10 text-emerald-500
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
                                is on <span class="text-amber-500 font-semibold">leave</span>
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
                <i data-lucide="calendar-check" class="w-5 h-5 text-[var(--accent)]"></i>
                Leave Status Today
            </h3>
            <span class="live-pill"><span></span> Live</span>
        </div>
        <ul class="space-y-4">
            @forelse($todayLeaves as $l)
                <li class="flex items-start gap-3">
                    <div class="w-9 h-9 flex items-center justify-center rounded-xl shrink-0 bg-amber-500/10 text-amber-500">
                        <i data-lucide="calendar-off" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-[var(--text)]">
                            <strong>{{ $l->salesman->name ?? 'N/A' }}</strong>
                            is on <span class="text-amber-500 font-semibold">leave</span>
                        </p>
                        <span class="text-xs muted">
                            {{ \Illuminate\Support\Str::limit($l->note ?: 'No note submitted.', 80) }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="flex items-center gap-3 text-sm muted">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl shrink-0 bg-emerald-500/10 text-emerald-500">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </span>
                    Nobody is on leave today.
                </li>
            @endforelse
        </ul>
    </div>
</div>

@endsection
