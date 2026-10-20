@extends('layouts.app')

@section('title', 'Today Attendance')

@section('content')
@php
use Carbon\Carbon;
$todayDate = $today ?? now();
@endphp

<div class="pb-24">

    {{-- HEADER --}}
    <div class="page-head !mb-6">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="calendar" class="w-6 h-6"></i></span>
            Attendance for {{ $todayDate->format('F d, Y') }}
        </h1>

        <a href="{{ route('hr.dashboard') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- PRESENT STAFF --}}
        <div class="card !rounded-3xl p-6 border-emerald-500/30">
            <h3 class="text-lg font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-2 mb-4">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Present ({{ $presentStaff->count() }})
            </h3>
            <div class="max-h-[400px] overflow-y-auto space-y-2">
                @foreach($presentStaff as $staff)
                    <div class="flex items-center justify-between soft-panel px-3 py-2 rounded-xl hover:bg-emerald-500/10 transition">
                        <span class="flex items-center gap-2 text-[var(--text)]">
                            <i data-lucide="user-check" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                            {{ $staff->name }}
                        </span>
                        <span class="badge badge-gray capitalize">{{ ucfirst($staff->role) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ON LEAVE STAFF --}}
        <div class="card !rounded-3xl p-6 border-amber-500/30">
            <h3 class="text-lg font-bold text-amber-600 dark:text-amber-400 flex items-center gap-2 mb-4">
                <i data-lucide="calendar-off" class="w-5 h-5"></i>
                On Leave ({{ $leaveStaff->count() }})
            </h3>
            <div class="max-h-[400px] overflow-y-auto space-y-2">
                @forelse($leaveStaff as $staff)
                    <div class="flex items-center justify-between soft-panel px-3 py-2 rounded-xl hover:bg-amber-500/10 transition">
                        <span class="flex items-center gap-2 text-[var(--text)]">
                            <i data-lucide="calendar-off" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                            {{ $staff->name }}
                        </span>
                        <span class="badge badge-gray capitalize">{{ ucfirst($staff->role) }}</span>
                    </div>
                @empty
                    <p class="muted text-sm text-center py-4">No one on leave today.</p>
                @endforelse
            </div>
        </div>

        {{-- ABSENT STAFF --}}
        <div class="card !rounded-3xl p-6 border-red-500/30">
            <h3 class="text-lg font-bold text-red-600 dark:text-red-400 flex items-center gap-2 mb-4">
                <i data-lucide="user-x" class="w-5 h-5"></i>
                Absent ({{ $absentStaff->count() }})
            </h3>
            <div class="max-h-[400px] overflow-y-auto space-y-2">
                @forelse($absentStaff as $staff)
                    <div class="flex items-center justify-between soft-panel px-3 py-2 rounded-xl hover:bg-red-500/10 transition">
                        <span class="flex items-center gap-2 text-[var(--text)]">
                            <i data-lucide="user-minus" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                            {{ $staff->name }}
                        </span>
                        <span class="badge badge-gray capitalize">{{ ucfirst($staff->role) }}</span>
                    </div>
                @empty
                    <p class="muted text-sm text-center py-4">No absent staff today.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- STATS FOOTER --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        @php
            $totalToday = $presentStaff->count() + $leaveStaff->count() + $absentStaff->count();
        @endphp
        <div class="stat-card tint-violet text-center">
            <div class="text-center w-full">
                <p class="text-sm muted">Total Staff</p>
                <p class="text-3xl font-display font-extrabold text-[var(--text)] mt-2">{{ $totalToday }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-emerald text-center">
            <div class="text-center w-full">
                <p class="text-sm muted">Present Today</p>
                <p class="text-3xl font-display font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">{{ $presentStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalToday > 0 ? ($presentStaff->count() / $totalToday) * 100 : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-amber text-center">
            <div class="text-center w-full">
                <p class="text-sm muted">On Leave</p>
                <p class="text-3xl font-display font-extrabold text-amber-600 dark:text-amber-400 mt-2">{{ $leaveStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="calendar-off" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalToday > 0 ? ($leaveStaff->count() / $totalToday) * 100 : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-red text-center">
            <div class="text-center w-full">
                <p class="text-sm muted">Absent Today</p>
                <p class="text-3xl font-display font-extrabold text-red-600 dark:text-red-400 mt-2">{{ $absentStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="user-x" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalToday > 0 ? ($absentStaff->count() / $totalToday) * 100 : 0 }}%"></div></div>
        </div>
    </div>

</div>

@endsection