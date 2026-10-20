@extends('layouts.app')

@section('title','Staff Dashboard')

@section('content')

@include('partials.late-reason-modal', ['lateRecord' => $pendingLateRecord ?? null])

<div class="space-y-6" style="--brand: #eb268f">
    <div class="page-head">
        <div>
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="layout-dashboard" class="w-6 h-6"></i></span>
                Staff Dashboard
            </h1>
            <p class="mt-2 text-sm muted">Mark your attendance and review your work history.</p>
        </div>
        <span class="badge badge-gray !px-3 !py-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            {{ now()->format('d M Y') }}
        </span>
    </div>

    @if($isNonWorkingDay)
        <div class="alert alert-info">
            <i data-lucide="calendar-off" class="w-5 h-5 shrink-0 mt-px"></i>
            <span>Today is a non-working day: <strong>{{ $nonWorkingReason }}</strong></span>
        </div>
    @endif

    <div class="card p-6 sm:p-7 flex items-center gap-4">
        <div class="w-11 h-11 rounded-2xl bg-[var(--accent-soft)] text-[var(--brand)] flex items-center justify-center shrink-0">
            <i data-lucide="briefcase" class="w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-display font-semibold text-[var(--text)]">Welcome, {{ auth()->user()->name }}</h2>
            <p class="mt-1 text-sm muted">Your attendance and daily work record are available here.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <a href="{{ $isNonWorkingDay ? '#' : route('staff.attendance.index') }}"
            @if($isNonWorkingDay) onclick="alert('Attendance actions are disabled today: {{ $nonWorkingReason }}')" @endif
            class="card card-hover p-5 sm:p-6 block {{ $isNonWorkingDay ? 'opacity-50' : '' }}">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">My Attendance</h3>
            </div>
            <p class="mt-3 text-sm muted">Clock in or out for today.</p>
        </a>

        <a href="{{ $isNonWorkingDay ? '#' : route('staff.attendance.index') }}"
            @if($isNonWorkingDay) onclick="alert('Leave requests are disabled today: {{ $nonWorkingReason }}')" @endif
            class="card card-hover p-5 sm:p-6 block {{ $isNonWorkingDay ? 'opacity-50' : '' }}">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">Request Leave</h3>
            </div>
            <p class="mt-3 text-sm muted">Submit a leave request.</p>
        </a>

        <a href="{{ route('staff.attendance.history') }}" class="card card-hover p-5 sm:p-6 block">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </span>
                <h3 class="font-display font-semibold text-[var(--text)]">Attendance History</h3>
            </div>
            <p class="mt-3 text-sm muted">View your previous records.</p>
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (window.SigmaIcons) window.SigmaIcons.refresh();
    });
</script>

@endsection
