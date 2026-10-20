@extends('layouts.app')

@section('title','My Attendance')

@section('content')
@php
use Carbon\Carbon;

$displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
$weeks = collect($calendar)->chunk(7);
@endphp

<div class="space-y-6" style="--brand: #eb268f">
    <div class="page-head">
        <div>
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="calendar-days" class="w-6 h-6"></i></span>
                My Attendance
            </h1>
            <p class="mt-2 text-sm muted">Your monthly attendance record and working status.</p>
        </div>
        <span class="badge badge-gray !px-3 !py-1.5">{{ $displayMonth }}</span>
    </div>

    <x-filter-bar>
        <x-filter-field icon="calendar" label="Select month">
            <input type="month" id="month" name="month" value="{{ $monthInput }}" class="input">
        </x-filter-field>

        <x-filter-actions submit="Apply" />
    </x-filter-bar>

    @php
        $totalDays = $totalPresents + $totalAbsents + $totalLeaves + $totalShortLeaves;
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            ['Presents', $totalPresents, 'check-circle', 'text-emerald-600 dark:text-emerald-400', 'bg-emerald-500/10'],
            ['Absents', $totalAbsents, 'user-x', 'text-red-600 dark:text-red-400', 'bg-red-500/10'],
            ['Leaves', $totalLeaves, 'ban', 'text-rose-600 dark:text-rose-400', 'bg-rose-500/10'],
            ['Short Leaves', $totalShortLeaves, 'clock', 'text-orange-600 dark:text-orange-400', 'bg-orange-500/10'],
            ['Late', $totalLates, 'alert-triangle', 'text-amber-600 dark:text-amber-400', 'bg-amber-500/10']
        ] as [$label, $value, $icon, $textColor, $iconBg])
            <div class="stat-card">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[.68rem] font-bold uppercase tracking-widest muted">{{ $label }}</p>
                        <p class="text-3xl font-display font-bold text-[var(--text)] mt-1">{{ $value }}</p>
                    </div>
                    <span class="w-9 h-9 rounded-xl {{ $iconBg }} {{ $textColor }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </span>
                </div>
                @if($totalDays > 0)
                    <div class="mt-4 h-1.5 bg-[var(--surface-3)] rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $textColor }} bg-current" style="width: {{ round(($value / $totalDays) * 100) }}%"></div>
                    </div>
                    <p class="text-[10px] muted mt-1">{{ round(($value / $totalDays) * 100) }}% of month</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="space-y-5">
        @foreach ($weeks as $week)
            <div class="card p-4 sm:p-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2 text-center text-xs">
                    @foreach ($week as $day)
                        @php
                            $date = Carbon::parse($day['date']);
                            $attendance = $day['attendance'] ?? null;
                        @endphp

                        <div class="cal-day rounded-2xl p-3 border border-transparent
                            @if ($day['status']==='holiday') bg-violet-500/10 text-violet-700 dark:text-violet-300
                            @elseif ($attendance && $attendance->short_leave) bg-orange-500/10 text-orange-700 dark:text-orange-300
                            @elseif ($attendance && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16') bg-amber-500/10 text-amber-700 dark:text-amber-300
                            @elseif ($day['status']==='present') bg-emerald-500/10 text-emerald-700 dark:text-emerald-300
                            @elseif ($day['status']==='leave') bg-red-500/10 text-red-700 dark:text-red-300
                            @elseif ($day['status']==='holiday') bg-violet-500/10 text-violet-700 dark:text-violet-300
                            @elseif ($day['status']==='future') bg-sky-500/5 text-sky-700 dark:text-sky-300 opacity-50
                            @else bg-red-500/10 text-red-700 dark:text-red-300 @endif
                            @if($date->isToday()) ring-2 ring-[var(--brand)] @endif">

                            <div class="opacity-70 text-[10px]">{{ $date->format('D') }}</div>
                            <div class="text-lg font-bold">{{ $date->format('d') }}</div>

                            <div class="uppercase text-[10px] tracking-wide">
                                {{ $day['status']==='future' ? '--' : substr($day['status'],0,3) }}
                            </div>

                            @if($day['status']==='holiday' && ($day['holiday'] ?? null) && $day['holiday'] !== 'Sunday')
                                <div class="mt-1 flex justify-center">
                                    <span class="badge badge-violet">Holiday</span>
                                </div>
                            @endif

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
                                            Late
                                        </span>
                                    @endif
                                    @if($attendance->auto_clock_out)
                                        <span class="badge badge-sky">Auto</span>
                                    @endif
                                </div>

                                @if($attendance->note)
                                    <div class="cal-day__note mt-1 text-[9px] opacity-70" title="{{ $attendance->note }}">
                                        {{ \Illuminate\Support\Str::limit($attendance->note, 40) }}
                                    </div>
                                @endif
                            @endif

                            @if(in_array($day['status'], ['holiday','leave']) && $day['holiday'] ?? false)
                                <div class="mt-1 text-[9px] opacity-70 truncate" title="{{ $day['holiday'] }}">
                                    {{ $day['holiday'] }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="card p-4 sm:p-5">
        <div class="flex flex-wrap gap-x-5 gap-y-3 text-xs text-[var(--text-soft)]">
            <span class="inline-flex items-center gap-2"><span class="dot bg-emerald-500"></span>Present</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-orange-500"></span>Short Leave</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-amber-500"></span>Late</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-red-500"></span>Absent</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-rose-500"></span>Leave</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-violet-500"></span>Holiday</span>
            <span class="inline-flex items-center gap-2"><span class="dot bg-sky-500"></span>Future</span>
        </div>
    </div>
</div>

<script>if (window.SigmaIcons) window.SigmaIcons.refresh();</script>
@endsection
