@extends('layouts.app')

@section('title', 'Today Attendance')

@section('content')
@php
    use Carbon\Carbon;
    $todayDate = $today ?? now();
    $total = $presentStaff->count() + $leaveStaff->count() + $absentStaff->count();
@endphp

<div>

    {{-- HEADER --}}
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="activity" class="w-6 h-6"></i></span>
            Live Attendance Feed
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>
    </div>

    <p class="text-sm muted -mt-3 mb-6 uppercase tracking-widest text-[.7rem]">{{ $todayDate->format('D, M d') }}</p>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card tint-magenta">
            <div>
                <p class="text-sm muted font-medium">Total Staff</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $total }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="users" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-emerald">
            <div>
                <p class="text-sm muted font-medium">Present</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $presentStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $total > 0 ? ($presentStaff->count() / $total) * 100 : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-cyan">
            <div>
                <p class="text-sm muted font-medium">On Leave</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $leaveStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="coffee" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $total > 0 ? ($leaveStaff->count() / $total) * 100 : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-red">
            <div>
                <p class="text-sm muted font-medium">Absent</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $absentStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="user-x" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $total > 0 ? ($absentStaff->count() / $total) * 100 : 0 }}%"></div></div>
        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">

        {{-- PRESENT STAFF SECTION --}}
        <div class="lg:col-span-8 space-y-5">
            <h2 class="font-display font-semibold text-lg text-[var(--text)] flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-[var(--accent)]"></i>
                Present Today
            </h2>

            <div class="grid sm:grid-cols-2 gap-4">
                @forelse($presentStaff as $staff)
                <div class="card card-hover !rounded-2xl p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="avatar avatar-md bg-[var(--accent-soft)] !text-[var(--accent)]">
                                {{ strtoupper(substr($staff->name,0,1)) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-[var(--text)] text-base">{{ $staff->name }}</h4>
                                <p class="muted text-[11px] font-semibold uppercase tracking-wide">
                                    {{ str_replace('_', ' ', $staff->role) }}
                                </p>
                            </div>
                        </div>
                        @if(!$staff->today_record->clock_out)
                            <span class="relative flex h-2.5 w-2.5 mt-2">
                                <span class="absolute inline-flex h-full w-full rounded-full bg-[var(--accent)] opacity-75 animate-ping"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[var(--accent)]"></span>
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 soft-panel p-3 rounded-2xl">
                        <div>
                            <p class="text-[10px] muted font-semibold uppercase">Clock In</p>
                            <p class="text-[var(--accent)] font-bold text-sm">
                                {{ $staff->today_record->clock_in ? \Carbon\Carbon::parse($staff->today_record->clock_in)->format('h:i A') : '--:--' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] muted font-semibold uppercase">Clock Out</p>
                            <p class="font-bold text-sm {{ $staff->today_record->clock_out ? 'text-[var(--text)]' : 'text-emerald-500' }}">
                                {{ $staff->today_record->clock_out ? \Carbon\Carbon::parse($staff->today_record->clock_out)->format('h:i A') : 'Active' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-[11px] muted font-semibold uppercase">
                        <i data-lucide="clock" class="w-3 h-3 text-[var(--accent)]"></i>
                        Worked:
                        @php
                            $clockIn = \Carbon\Carbon::parse($staff->today_record->clock_in);
                            $clockOut = $staff->today_record->clock_out ? \Carbon\Carbon::parse($staff->today_record->clock_out) : now();
                            $diff = $clockIn->diff($clockOut);
                        @endphp
                        <span class="text-[var(--text)]">{{ $diff->h }}h {{ $diff->i }}m</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full card !rounded-2xl p-10 text-center muted">
                    No staff members present yet.
                </div>
                @endforelse
            </div>
        </div>

        {{-- SIDEBAR: LEAVE + ABSENT --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- LEAVE --}}
            <div class="card !rounded-2xl p-6 !border-sky-500/25">
                <h2 class="font-display font-semibold text-lg text-[var(--text)] flex items-center gap-2 mb-4">
                    <i data-lucide="coffee" class="w-5 h-5 text-sky-500"></i> On Leave
                </h2>
                <div class="max-h-[300px] overflow-y-auto space-y-3">
                    @forelse($leaveStaff as $staff)
                        <div class="flex items-center gap-3 p-3 rounded-2xl soft-panel">
                            <div class="avatar avatar-sm !rounded-xl bg-sky-500/15 !text-sky-500">
                                {{ substr($staff->name,0,1) }}
                            </div>
                            <div>
                                <p class="text-[var(--text)] font-semibold text-sm">{{ $staff->name }}</p>
                                <p class="text-sky-500/70 text-[10px] font-bold uppercase tracking-tighter">Approved Leave</p>
                            </div>
                        </div>
                    @empty
                        <p class="muted text-sm text-center py-4">No leaves today</p>
                    @endforelse
                </div>
            </div>

            {{-- ABSENT --}}
            <div class="card !rounded-2xl p-6 !border-red-500/25">
                <h2 class="font-display font-semibold text-lg text-[var(--text)] flex items-center gap-2 mb-4">
                    <i data-lucide="user-x" class="w-5 h-5 text-red-500"></i> Absent
                </h2>
                <div class="max-h-[300px] overflow-y-auto space-y-3">
                    @forelse($absentStaff as $staff)
                        <div class="flex items-center gap-3 p-3 rounded-2xl soft-panel">
                            <div class="avatar avatar-sm !rounded-xl bg-red-500/10 !text-red-500">
                                <i data-lucide="user-minus" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[var(--text)] font-semibold text-sm">{{ $staff->name }}</p>
                                <p class="muted text-[10px] font-semibold uppercase">{{ $staff->role }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="text-2xl mb-1">🎉</div>
                            <p class="muted text-sm font-medium">Perfect attendance!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection