@extends('layouts.app')

@section('title', 'Staff Attendance')

@section('content')
@php
use Carbon\Carbon;
$displayMonth = Carbon::createFromFormat('Y-m', $monthInput ?? now()->format('Y-m'))->format('F Y');
$weeks = collect($calendar)->chunk(7);
@endphp

<div class="pb-12">

    {{-- HEADER --}}
    <div class="card !rounded-2xl p-6 mb-6">
        <span class="text-xs font-bold uppercase tracking-widest text-[var(--accent)]">Personnel Record</span>

        <h2 class="page-title mt-2 mb-1">
            <span class="page-ico"><i data-lucide="user" class="w-6 h-6"></i></span>
            {{ $user->name }}
        </h2>

        <p class="muted text-sm">{{ $user->email }}</p>
        <p class="mt-1 text-sm font-semibold text-[var(--accent)]">Role: {{ ucfirst($user->role) }} • {{ $displayMonth }}</p>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </a>

            <a href="{{ route('admin.attendance.export.single', [$user->id, 'month' => $monthInput]) }}"
               class="btn btn-success btn-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Export Excel
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
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="stat-card tint-emerald">
            <div>
                <p class="text-xs muted font-bold uppercase tracking-wider">Presents</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $totalPresents }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="check-circle" class="w-5 h-5"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($totalPresents / $totalDays) * 100) : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-red">
            <div>
                <p class="text-xs muted font-bold uppercase tracking-wider">Absents</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $totalAbsents }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="user-x" class="w-5 h-5"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($totalAbsents / $totalDays) * 100) : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-rose">
            <div>
                <p class="text-xs muted font-bold uppercase tracking-wider">Leaves</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $totalLeaves }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="ban" class="w-5 h-5"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($totalLeaves / $totalDays) * 100) : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-orange">
            <div>
                <p class="text-xs muted font-bold uppercase tracking-wider">Short Leaves</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $totalShortLeaves }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="clock" class="w-5 h-5"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($totalShortLeaves / $totalDays) * 100) : 0 }}%"></div></div>
        </div>

        <div class="stat-card tint-amber">
            <div>
                <p class="text-xs muted font-bold uppercase tracking-wider">Late</p>
                <p class="text-2xl font-display font-bold text-[var(--text)] mt-1">{{ $totalLates }}</p>
            </div>
            <span class="stat-ico"><i data-lucide="alert-triangle" class="w-5 h-5"></i></span>
            <div class="stat-bar"><div style="width: {{ $totalDays > 0 ? round(($totalLates / $totalDays) * 100) : 0 }}%"></div></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT PANEL --}}
        <div class="space-y-6">

            {{-- LEAVE --}}
            <div class="card !rounded-2xl p-6">
                <h3 class="font-display font-semibold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="ban" class="w-5 h-5 text-red-500"></i> Mark Leave
                </h3>
                <form method="POST" action="{{ route('admin.attendance.leave', $user->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label">Date</label>
                        <input type="date" name="date" required class="input">
                    </div>
                    <div>
                        <label class="label">Leave Reason</label>
                        <textarea name="note" rows="3" required class="textarea" placeholder="Leave reason"></textarea>
                    </div>
                    <button class="btn btn-danger w-full">Confirm Leave</button>
                </form>
            </div>

            {{-- MANUAL VISIT — salesmen only (only salesmen go on out-of-city visits) --}}
            @if ($user->role === 'salesman')
            <div class="card !rounded-2xl p-6">
                <h3 class="font-display font-semibold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-5 h-5 text-violet-500"></i> Manual Visit
                </h3>
                <p class="muted text-xs mb-3">Only salesmen can have manual / out-of-city visits marked.</p>
                <form method="POST" action="{{ route('admin.attendance.manual.visit.store', $user->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label">Start Date</label>
                        <input type="date" name="start_date" required class="input">
                    </div>
                    <div>
                        <label class="label">End Date <span class="muted font-normal">(optional)</span></label>
                        <input type="date" name="end_date" class="input">
                    </div>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <input type="time" name="clock_in" class="input" placeholder="Start Time">
                        <input type="time" name="clock_out" class="input" placeholder="End Time">
                    </div>
                    <textarea name="note" rows="2" maxlength="255" class="textarea resize-none" placeholder="Client visit / meeting"></textarea>
                    <button class="btn btn-outline w-full">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Manual Visit
                    </button>
                </form>
            </div>
            @endif

            {{-- MANUAL ATTENDANCE --}}
            <div class="card !rounded-2xl p-6">
                <h3 class="font-display font-semibold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-amber-500"></i> Manual Attendance
                </h3>

                <form method="POST" action="{{ route('attendance.manual.store') }}" class="space-y-3">
                    @csrf
                    {{-- Hidden User ID (optional if route already has it) --}}
                    <input type="hidden" name="salesman_id" value="{{ $user->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="label uppercase">Date</label>
                            <input type="date" name="date" required class="input">
                        </div>
                        <div>
                            <label class="label uppercase">Note / Reason</label>
                            <input type="text" name="note" placeholder="Reason for manual attendance" class="input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="label uppercase">Clock In</label>
                            <input type="time" name="clock_in" class="input">
                        </div>
                        <div>
                            <label class="label uppercase">Clock Out</label>
                            <input type="time" name="clock_out" class="input">
                        </div>
                    </div>

                    <button class="btn btn-primary w-full mt-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Manual Attendance
                    </button>
                </form>
            </div>
        </div>

        {{-- CALENDAR --}}
        <div class="lg:col-span-2 space-y-6">

            @foreach ($weeks as $week)
                <div class="card !rounded-2xl p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2">

                        @foreach ($week as $day)
                            @php $attendance = $day['attendance'] ?? null; @endphp

                            <div class="cal-day rounded-2xl p-4 text-center text-xs
                                @if ($day['status']==='holiday') bg-violet-500/15 text-violet-700 dark:text-violet-300
                                @elseif ($day['status']==='short_leave') bg-orange-500/15 text-orange-700 dark:text-orange-300
                                @elseif ($day['status']==='present')
                                    @if($attendance && $attendance->clock_in && $attendance->clock_in->format('H:i') >= '10:16') bg-amber-500/15 text-amber-700 dark:text-amber-300
                                    @else bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 @endif
                                @elseif ($day['status']==='leave') bg-red-500/15 text-red-700 dark:text-red-300
                                @elseif ($day['status']==='off') bg-[var(--surface-3)] text-[var(--text-muted)]
                                @elseif ($day['status']==='future') bg-[var(--accent-softer)] text-[var(--text-muted)]
                                @else bg-red-900/20 text-red-500 @endif">

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
                                            <span class="badge badge-red" title="{{ \Illuminate\Support\Str::limit($attendance->note, 120) }}">Leave</span>
                                        @endif
                                    </div>

                                    {{-- Note (clipped) --}}
                                    @if($attendance->note)
                                        <div class="cal-day__note mt-1 text-[9px] text-[var(--text-muted)]"
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