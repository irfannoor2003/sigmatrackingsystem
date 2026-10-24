@extends('layouts.app')

@section('title', 'Staff Attendance')

@section('content')
@php
use Carbon\Carbon;
$displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
$weeks = collect($calendar)->chunk(7);
@endphp

<div class="pb-24">

    {{-- HEADER --}}
    <div class="card !rounded-3xl p-6 mb-6">
        <div class="page-head !mb-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[var(--accent)]">Personnel Record</span>

            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="user" class="w-6 h-6"></i></span>
                {{ $user->name }}
            </h2>
        </div>

        <p class="text-[var(--text-soft)] text-sm">{{ $user->email }}</p>
        <p class="mt-1 text-sm text-[var(--accent)] font-semibold">Role: {{ ucfirst($user->role) }} • {{ $displayMonth }}</p>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <a href="{{ route('hr.attendance.index') }}" class="btn btn-ghost btn-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </a>

            <a href="{{ route('hr.attendance.export.single', [$user->id, 'month' => $monthInput]) }}" class="btn btn-success btn-sm">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Excel
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <x-filter-bar>
        <x-filter-field icon="calendar" label="Filter Month">
            <input type="month" name="month" value="{{ $monthInput }}" class="input">
        </x-filter-field>

        <x-filter-actions submit="Apply" />
    </x-filter-bar>

    {{-- STATS GRID --}}
    @php
        $totalDays = $totalPresents + $totalAbsents + $totalLeaves + $totalShortLeaves;
        $stats = [
            ['Presents', $totalPresents, 'check-circle', 'tint-emerald'],
            ['Absents', $totalAbsents, 'user-x', 'tint-red'],
            ['Leaves', $totalLeaves, 'ban', 'tint-rose'],
            ['Short Leaves', $totalShortLeaves, 'clock', 'tint-orange'],
            ['Late', $totalLates, 'alert-triangle', 'tint-amber'],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @foreach($stats as [$label, $value, $icon, $tint])
        <div class="stat-card {{ $tint }}">
            <div>
                <p class="text-[.68rem] font-bold uppercase tracking-widest muted mb-1">{{ $label }}</p>
                <p class="text-2xl font-display font-extrabold text-[var(--text)] mb-1">{{ $value }}</p>
                @if($totalDays > 0)
                    <div class="w-full h-1 bg-[var(--accent-softer)] rounded-full overflow-hidden">
                        <div class="h-full rounded-full gradient-surface" style="width: {{ round(($value / $totalDays) * 100) }}%"></div>
                    </div>
                    <p class="text-[10px] muted mt-1 font-medium">{{ round(($value / $totalDays) * 100) }}% of month</p>
                @endif
            </div>
            <span class="stat-ico"><i data-lucide="{{ $icon }}" class="w-6 h-6"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($value / $totalDays) * 100) : 0 }}%"></div></div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT PANEL --}}
        <div class="space-y-6">

            {{-- LEAVE --}}
            <div class="card !rounded-3xl p-6">
                <h3 class="font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="ban" class="w-5 h-5 text-red-500"></i> Mark Leave
                </h3>
                <form method="POST" action="{{ route('hr.attendance.leave', $user->id) }}" class="space-y-3">
                    @csrf
                    <div class="input-icon-wrap">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <input type="date" name="start_date" required class="input w-full" placeholder="Start date">
                    </div>
                    <div class="input-icon-wrap">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <input type="date" name="end_date" class="input w-full" placeholder="End date (optional)">
                    </div>
                    <textarea name="note" rows="3" required class="textarea w-full" placeholder="Leave reason"></textarea>
                    <button class="btn btn-danger w-full !py-3">
                        <i data-lucide="calendar-x" class="w-4 h-4"></i>
                        Confirm Leave
                    </button>
                </form>
            </div>

            {{-- MANUAL VISIT — salesmen only (only salesmen go on out-of-city visits) --}}
            @if ($user->role === 'salesman')
            <div class="card !rounded-3xl p-6">
                <h3 class="font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-5 h-5 text-indigo-500"></i> Manual Visit
                </h3>
                <p class="muted text-xs mb-3">Only salesmen can have manual / out-of-city visits marked.</p>
                <form method="POST" action="{{ route('hr.attendance.manual.visit.store', $user->id) }}" class="space-y-3">
                    @csrf
                    <div class="input-icon-wrap">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <input type="date" name="start_date" required class="input w-full">
                    </div>
                    <div class="input-icon-wrap">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <input type="date" name="end_date" class="input w-full">
                    </div>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <input type="time" name="clock_in" class="input w-full" placeholder="Start Time">
                        <input type="time" name="clock_out" class="input w-full" placeholder="End Time">
                    </div>
                    <textarea name="note" rows="2" maxlength="255" class="textarea w-full resize-none" placeholder="Client visit / meeting"></textarea>
                    <button class="btn btn-primary w-full">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Manual Visit
                    </button>
                </form>
            </div>
            @endif

            {{-- MANUAL ATTENDANCE --}}
            <div class="card !rounded-3xl p-6">
                <h3 class="font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-amber-500"></i> Manual Attendance
                </h3>
                <form method="POST" action="{{ route('attendance.manual.store') }}" class="space-y-3">
                    @csrf
                    {{-- Hidden User ID --}}
                    <input type="hidden" name="salesman_id" value="{{ $user->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="label uppercase mb-1">Date</label>
                            <input type="date" name="date" required class="input w-full">
                        </div>
                        <div>
                            <label class="label mb-1">Note / Reason</label>
                            <input type="text" name="note" placeholder="Reason for manual attendance" class="input w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="label mb-1">Clock In</label>
                            <input type="time" name="clock_in" class="input w-full">
                        </div>
                        <div>
                            <label class="label mb-1">Clock Out</label>
                            <input type="time" name="clock_out" class="input w-full">
                        </div>
                    </div>

                    <button class="btn w-full !py-3 bg-amber-500/15 text-amber-600 dark:text-amber-400 hover:bg-amber-500/30 border border-amber-500/25 rounded-xl font-bold mt-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Manual Attendance
                    </button>
                </form>
            </div>
        </div>

        {{-- CALENDAR --}}
        <div class="lg:col-span-2 space-y-6">

            @foreach ($weeks as $week)
                <div class="card !rounded-3xl p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2">

                        @foreach ($week as $day)
                            @php $attendance = $day['attendance'] ?? null; @endphp

                            <div class="cal-day rounded-2xl p-4 text-center text-xs border
                                @if ($day['status']==='holiday') bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/30
                                @elseif ($day['status']==='short_leave') bg-orange-500/10 text-orange-600 dark:text-orange-400 border-orange-500/30
                                @elseif ($day['status']==='present')
                                    @if($attendance && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16') bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/30
                                    @else bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/30 @endif
                                @elseif ($day['status']==='leave') bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/30
                                @elseif ($day['status']==='off') bg-gray-500/10 text-gray-500 dark:text-gray-400 border-gray-500/30
                                @elseif ($day['status']==='future') bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/30
                                @else bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/30 @endif">

                                {{-- Day --}}
                                <div class="opacity-70 text-[10px]">{{ Carbon::parse($day['date'])->format('D') }}</div>
                                <div class="text-lg font-bold">{{ Carbon::parse($day['date'])->format('d') }}</div>
                                <div class="font-semibold mb-1 truncate">{{ $day['label'] }}</div>

                                @if ($day['status']==='holiday')
                                    <div class="mb-1 flex justify-center">
                                        <span class="badge badge-violet">Holiday</span>
                                    </div>
                                    @if(($day['holiday'] ?? false) && $day['holiday'] !== $day['label'])
                                        <div class="cal-day__note mt-1 text-[9px] opacity-70" title="{{ $day['holiday'] }}">
                                            {{ \Illuminate\Support\Str::limit($day['holiday'], 30) }}
                                        </div>
                                    @endif
                                @endif

                                @if($attendance)
                                    {{-- Clock-in/out --}}
                                    <div class="text-[10px] font-mono opacity-80">
                                        {{ optional($attendance->clock_in)->format('h:i A') ?? '--' }} -
                                        {{ optional($attendance->clock_out)->format('h:i A') ?? '--' }}
                                    </div>

                                    {{-- Badges --}}
                                    <div class="flex flex-wrap justify-center gap-1 mt-1">
                                        @if($attendance->short_leave)
                                            <span class="badge badge-orange">Short</span>
                                        @endif
                                        @if(!$attendance->short_leave && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16')
                                            <span class="badge badge-amber"
                                                  title="{{ $attendance->late_reason ? 'Late reason: ' . $attendance->late_reason : 'Late - no reason submitted yet' }}">
                                                Late{{ empty($attendance->late_reason) ? ' ⚠' : '' }}
                                            </span>
                                        @endif
                                        @if($attendance->auto_clock_out)
                                            <span class="badge badge-sky">Auto</span>
                                        @endif
                                        @if($attendance->manual_visit)
                                            @php
                                                $markerRole = $attendance->markedBy?->role;
                                                $markerLabel = $markerRole === 'hr' ? 'By HR' : 'By Admin';
                                            @endphp
                                            <span class="badge badge-violet"
                                                  title="Marked {{ $markerLabel }}{{ $attendance->markedBy ? ' · ' . $attendance->markedBy->name : '' }}{{ $attendance->note ? ' · ' . $attendance->note : '' }}">
                                                {{ $markerLabel }}
                                            </span>
                                        @endif
                                        @if($attendance->status === 'leave')
                                            @php
                                                $markerRole = $attendance->markedBy?->role;
                                                $markerLabel = $markerRole === 'hr' ? 'By HR' : 'By Admin';
                                            @endphp
                                            @if($attendance->markedBy)
                                                <span class="badge badge-violet"
                                                      title="Marked {{ $markerLabel }}{{ $attendance->markedBy ? ' · ' . $attendance->markedBy->name : '' }}{{ $attendance->note ? ' · ' . $attendance->note : '' }}">
                                                    {{ $markerLabel }}
                                                </span>
                                            @endif
                                            <span class="badge badge-red" title="{{ \Illuminate\Support\Str::limit($attendance->note, 120) }}">Leave</span>
                                        @endif
                                    </div>

                                    {{-- Note (clipped) --}}
                                    @if($attendance->note)
                                        <div class="cal-day__note mt-1 text-[9px] text-[var(--text-soft)]"
                                             title="{{ $attendance->note }}">
                                            {{ \Illuminate\Support\Str::limit($attendance->note, 40) }}
                                        </div>
                                    @endif
                                @endif

                            </div>
                        @endforeach

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection