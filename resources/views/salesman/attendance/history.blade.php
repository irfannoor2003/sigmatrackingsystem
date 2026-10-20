@extends('layouts.app')

@section('title','My Attendance')

@section('content')
@php
use Carbon\Carbon;

$displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
$weeks = collect($calendar)->chunk(7);
@endphp

<style>
input[type="month"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: .9;
}
</style>

<div class="p-0 pb-24">

    {{-- HEADER --}}
    <div class="card !rounded-3xl border border-[var(--border)] p-5 sm:p-6 shadow-card-lg">
        <div class="page-head !mb-2">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="calendar-days" class="w-6 h-6"></i></span>
                My Attendance
            </h2>
        </div>

        <p class="text-sm muted mt-1">{{ $displayMonth }}</p>
    </div>

    <x-filter-bar>
        <x-filter-field icon="calendar">
            <input type="month" name="month" value="{{ $monthInput }}" class="input">
        </x-filter-field>

        <x-filter-actions submit="Apply" />
    </x-filter-bar>

    {{-- STATS GRID --}}
    @php
        $totalDays = $totalPresents + $totalAbsents + $totalLeaves + $totalShortLeaves;
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @foreach([
            ['Presents', $totalPresents, 'check-circle', 'tint-emerald'],
            ['Absents', $totalAbsents, 'user-x', 'tint-red'],
            ['Leaves', $totalLeaves, 'ban', 'tint-rose'],
            ['Short Leaves', $totalShortLeaves, 'clock', 'tint-orange'],
            ['Late', $totalLates, 'alert-triangle', 'tint-amber']
        ] as [$label, $value, $icon, $tint])
        <div class="stat-card tinted {{ $tint }}">
            <div class="stat-ico"><i data-lucide="{{ $icon }}" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider muted">{{ $label }}</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $value }}</p>
                @if($totalDays > 0)
                    <div class="stat-bar"><div style="width: {{ round(($value / $totalDays) * 100) }}%"></div></div>
                    <p class="text-[10px] muted mt-1 font-medium">{{ round(($value / $totalDays) * 100) }}% of month</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CALENDAR --}}
    <div class="space-y-6">

        @foreach ($weeks as $week)
            <div class="card !rounded-3xl border border-[var(--border)] p-4 sm:p-5 shadow">
                <div class="grid
            grid-cols-2
            sm:grid-cols-3
            md:grid-cols-4
            lg:grid-cols-7
            gap-2
            text-center text-xs">


                    @foreach ($week as $day)
                        @php
                            $date = Carbon::parse($day['date']);
                            $attendance = $day['attendance'] ?? null;
                        @endphp

                        <div class="cal-day rounded-2xl p-3 border
                            @if ($day['status']==='holiday') bg-violet-500/15 border-violet-500/30 text-violet-600 dark:text-violet-300
                            @elseif ($attendance && $attendance->short_leave) bg-orange-500/15 border-orange-500/30 text-orange-600 dark:text-orange-300
                            @elseif ($attendance && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16') bg-amber-500/15 border-amber-500/30 text-amber-600 dark:text-amber-300
                            @elseif ($day['status']==='present') bg-emerald-500/15 border-emerald-500/30 text-emerald-700 dark:text-emerald-300
                            @elseif ($day['status']==='leave') bg-red-500/15 border-red-500/30 text-red-600 dark:text-red-300
                            @elseif ($day['status']==='holiday') bg-violet-500/15 border-violet-500/30 text-violet-600 dark:text-violet-300
                            @elseif ($day['status']==='future') bg-blue-500/10 border-blue-500/20 text-blue-600 dark:text-blue-300 opacity-50
                            @else bg-red-800/15 border-red-800/30 text-red-600 dark:text-red-400 @endif
                            @if($date->isToday()) ring-2 ring-[var(--accent)] @endif">

                            {{-- DAY --}}
                            <div class="opacity-70 text-[10px]">{{ $date->format('D') }}</div>
                            <div class="text-lg font-bold">{{ $date->format('d') }}</div>

                            {{-- STATUS --}}
                            <div class="uppercase text-[10px] tracking-wide">
                                {{ $day['status']==='future' ? '--' : substr($day['status'],0,3) }}
                            </div>

                            @if($day['status']==='holiday' && ($day['holiday'] ?? null) && $day['holiday'] !== 'Sunday')
                                <div class="mt-1 flex justify-center">
                                    <span class="badge badge-violet">Holiday</span>
                                </div>
                            @endif

                            {{-- ATTENDANCE --}}
                            @if($attendance)
                                <div class="mt-1 text-[10px] font-mono opacity-80">
                                    {{ optional($attendance->clock_in)->format('h:i') ?? '--' }}
                                    -
                                    {{ optional($attendance->clock_out)->format('h:i') ?? '--' }}
                                </div>

                                <div class="flex justify-center gap-1 mt-1 flex-wrap">
                                    @if($attendance->short_leave)
                                        <span class="badge badge-orange">Short</span>
                                    @endif
                                    @if(!$attendance->short_leave && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16')
                                        <span class="badge badge-amber"
                                              title="{{ $attendance->late_reason ? 'Late reason: ' . $attendance->late_reason : 'Late - no reason submitted yet' }}">
                                            Late @if(empty($attendance->late_reason))<i data-lucide="alert-triangle" class="w-2.5 h-2.5 shrink-0"></i>@endif
                                        </span>
                                    @endif
                                    @if($attendance->auto_clock_out)
                                        <span class="badge badge-sky">Auto</span>
                                    @endif
                                </div>

                                @if($attendance->note)
                                    <div class="cal-day__note mt-1 text-[9px] text-[var(--text-muted)]"
                                         title="{{ $attendance->note }}">
                                        {{ \Illuminate\Support\Str::limit($attendance->note, 40) }}
                                    </div>
                                @endif
                            @endif

                            {{-- HOLIDAY / LEAVE REASON --}}
                            @if(in_array($day['status'], ['holiday','leave']) && $day['holiday'] ?? false)
                                <div class="mt-1 text-[9px] text-[var(--text-soft)] truncate"
                                     title="{{ $day['holiday'] }}">
                                    {{ $day['holiday'] }}
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>

    {{-- LEGEND --}}
    <div class="flex flex-wrap gap-4 text-xs mt-6 text-[var(--text-soft)]">
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500/40 border border-emerald-500/50"></span> Present</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-orange-500/40 border border-orange-500/50"></span> Short Leave</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500/40 border border-amber-500/50"></span> Late</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-red-800 border border-red-800/50"></span> Absent</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500/40 border border-red-500/50"></span> Leave</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-violet-500/40 border border-violet-500/50"></span> Holiday</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500/40 border border-blue-500/50"></span> Future</span>
    </div>

</div>

@endsection