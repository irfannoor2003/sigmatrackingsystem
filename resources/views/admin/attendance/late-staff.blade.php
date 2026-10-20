@extends('layouts.app')

@section('title', 'Late Staff Today')

@section('content')
@php
    use Carbon\Carbon;
    $lateThreshold = Carbon::today()->setTime(10, 16);
@endphp

<div class="pb-12">

    {{-- HEADER --}}
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="clock" class="w-6 h-6"></i></span>
            Late Staff — {{ $today->format('F d, Y') }}
        </h1>
        <a href="{{ $backRoute }}" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    <p class="text-sm muted -mt-3 mb-6">Staff who clocked in after 10:15 AM.</p>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="stat-card tint-amber">
            <div>
                <p class="text-sm muted font-medium">Late Staff</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">{{ $lateStaff->count() }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="user-check" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        @php
            $firstStaff = $allTodayAttendance->count() > 0 ? $allTodayAttendance->sortBy('clock_in')->first() : null;
            $lastStaff = $allTodayAttendance->count() > 0 ? $allTodayAttendance->sortByDesc('clock_in')->first() : null;
        @endphp

        <div class="stat-card tint-blue">
            <div>
                <p class="text-sm muted font-medium">Latest Clock In</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">
                    {{ $lastStaff ? $lastStaff->clock_in->format('h:i A') : '--' }}
                </p>
                @if($lastStaff)
                    <p class="text-xs muted mt-1">{{ $lastStaff->salesman->name }}</p>
                @endif
            </div>
            <span class="stat-ico"><i data-lucide="sunset" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>

        <div class="stat-card tint-emerald">
            <div>
                <p class="text-sm muted font-medium">First Clock In</p>
                <p class="text-3xl font-display font-bold text-[var(--text)] mt-1 tracking-tight">
                    {{ $firstStaff ? $firstStaff->clock_in->format('h:i A') : '--' }}
                </p>
                @if($firstStaff)
                    <p class="text-xs muted mt-1">{{ $firstStaff->salesman->name }}</p>
                @endif
            </div>
            <span class="stat-ico"><i data-lucide="sunrise" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: 100%"></div></div>
        </div>
    </div>

    {{-- LATE STAFF LIST --}}
    <div class="card !rounded-2xl p-6 !border-amber-500/25">
        <h3 class="font-display font-semibold text-lg text-[var(--text)] flex items-center gap-2 mb-4">
            <i data-lucide="users" class="w-5 h-5 text-amber-500"></i>
            Late Staff Details ({{ $lateStaff->count() }})
        </h3>

        <div class="max-h-[500px] overflow-y-auto space-y-2">
            @forelse($lateStaff as $late)
                @php
                    $clockInTime = \Carbon\Carbon::parse($late->clock_in);
                    $minutesLate = (int) $clockInTime->diffInMinutes($lateThreshold);
                @endphp
                <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-amber-500/10 transition">
                    <span class="flex items-center gap-2.5 text-sm text-[var(--text)]">
                        <i data-lucide="user-check" class="w-5 h-5 text-amber-500"></i>
                        {{ $late->salesman->name ?? 'N/A' }}
                        <span class="badge badge-gray capitalize">{{ ucfirst($late->salesman->role ?? '-') }}</span>
                    </span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs muted">{{ $clockInTime->format('h:i A') }}</span>
                        <span class="badge badge-amber">{{ $minutesLate }} min late</span>
                    </div>
                </div>

                {{-- Late reason --}}
                <div class="px-3 pb-2.5 flex items-start gap-2">
                    @if(!empty($late->late_reason))
                        <span class="badge badge-emerald shrink-0">Reason</span>
                        <span class="text-xs text-[var(--text-soft)] break-words" title="{{ $late->late_reason }}">
                            {{ $late->late_reason }}
                        </span>
                    @else
                        <span class="badge badge-red">⚠ No reason submitted</span>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <i data-lucide="check-circle" class="w-12 h-12 text-emerald-500/40 mx-auto mb-4"></i>
                    <p class="text-[var(--text)] text-lg font-medium">No late staff today!</p>
                    <p class="muted text-sm mt-1">Everyone clocked in on time.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection