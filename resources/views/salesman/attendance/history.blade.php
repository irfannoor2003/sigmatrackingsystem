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
    filter: invert(1);
    opacity: .9;
}
.glass {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(18px);
}

.badge {
    font-size: 9px;
    padding: 2px 5px;
    border-radius: 4px;
}
</style>

<div class="max-w-6xl mx-auto p-0 pb-24 text-white">

    {{-- HEADER --}}
    <div class="glass p-5 sm:p-6 rounded-3xl border border-white/20 shadow-xl mb-5">
        <h2 class="text-xl sm:text-2xl font-extrabold flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-6 h-6 text-pink-400"></i>
            My Attendance
        </h2>

        <p class="text-sm text-white/50 mt-1">{{ $displayMonth }}</p>

        <form method="GET" class="mt-4 flex flex-col sm:flex-row gap-3">
            <input type="month" name="month" value="{{ $monthInput }}"
                   class="px-4 py-2 rounded-xl bg-black/40 border border-white/10 text-white w-full sm:w-auto">
            <button
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-600 font-bold">
                Apply
            </button>
        </form>
    </div>

    {{-- STATS GRID --}}
    @php
        $totalDays = $totalPresents + $totalAbsents + $totalLeaves + $totalShortLeaves;
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @foreach([
            ['Presents', $totalPresents, 'check-circle', 'border-emerald-500/30', 'from-emerald-500/20 to-emerald-600/5', 'text-emerald-400', 'bg-emerald-500/10'],
            ['Absents', $totalAbsents, 'user-x', 'border-yellow-500/30', 'from-yellow-500/20 to-yellow-600/5', 'text-yellow-400', 'bg-yellow-500/10'],
            ['Leaves', $totalLeaves, 'ban', 'border-rose-500/30', 'from-rose-500/20 to-rose-600/5', 'text-rose-400', 'bg-rose-500/10'],
            ['Short Leaves', $totalShortLeaves, 'clock', 'border-orange-500/30', 'from-orange-500/20 to-orange-600/5', 'text-orange-400', 'bg-orange-500/10'],
            ['Late', $totalLates, 'alert-triangle', 'border-amber-500/30', 'from-amber-500/20 to-amber-600/5', 'text-amber-400', 'bg-amber-500/10']
        ] as [$label, $value, $icon, $borderColor, $gradient, $textColor, $iconBg])
        <div class="relative glass p-4 rounded-2xl border {{ $borderColor }} hover:scale-[1.03] transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="{{ $textColor }} text-xs font-bold uppercase tracking-wider">{{ $label }}</p>
                    <div class="{{ $iconBg }} p-1.5 rounded-xl">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $textColor }}"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-white mb-1">{{ $value }}</h3>
                @if($totalDays > 0)
                    <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $gradient }} {{ $textColor }}" style="width: {{ round(($value / $totalDays) * 100) }}%"></div>
                    </div>
                    <p class="text-[10px] text-white/30 mt-1 font-medium">{{ round(($value / $totalDays) * 100) }}% of month</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CALENDAR --}}
    <div class="space-y-6">

        @foreach ($weeks as $week)
            <div class="glass p-4 sm:p-5 rounded-3xl border border-white/20 shadow">
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

                        <div class="rounded-2xl p-3
                            @if ($day['status']==='present') bg-green-500/20 text-green-300
                            @elseif ($day['status']==='leave') bg-red-500/20 text-red-300
                            @elseif ($day['status']==='holiday') bg-purple-500/20 text-purple-300
                            @elseif ($day['status']==='future') bg-blue-500/10 text-blue-300 opacity-50
                            @else bg-yellow-500/20 text-yellow-300 @endif
                            @if($date->isToday()) ring-2 ring-pink-400 @endif">

                            {{-- DAY --}}
                            <div class="opacity-70 text-[10px]">{{ $date->format('D') }}</div>
                            <div class="text-lg font-bold">{{ $date->format('d') }}</div>

                            {{-- STATUS --}}
                            <div class="uppercase text-[10px] tracking-wide">
                                {{ $day['status']==='future' ? '--' : substr($day['status'],0,3) }}
                            </div>

                            {{-- ATTENDANCE --}}
                            @if($attendance)
                                <div class="mt-1 text-[10px] font-mono opacity-80">
                                    {{ optional($attendance->clock_in)->format('h:i') ?? '--' }}
                                    -
                                    {{ optional($attendance->clock_out)->format('h:i') ?? '--' }}
                                </div>

                                <div class="flex justify-center gap-1 mt-1 flex-wrap">
                                    @if($attendance->short_leave)
                                        <span class="badge bg-yellow-500/30 text-yellow-300">Short</span>
                                    @endif
                                    @if($attendance->auto_clock_out)
                                        <span class="badge bg-blue-500/30 text-blue-300">Auto</span>
                                    @endif
                                </div>

                                @if($attendance->note)
                                    <div class="mt-1 text-[9px] text-white/60 truncate"
                                         title="{{ $attendance->note }}">
                                        {{ $attendance->note }}
                                    </div>
                                @endif
                            @endif

                            {{-- HOLIDAY / LEAVE REASON --}}
                            @if(in_array($day['status'], ['holiday','leave']) && $day['holiday'] ?? false)
                                <div class="mt-1 text-[9px] text-white/70 truncate"
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
    <div class="flex flex-wrap gap-4 text-xs mt-6 text-white/70">
        <span>🟢 Present</span>
        <span>🟡 Absent</span>
        <span>🔴 Leave</span>
        <span>🟣 Holiday</span>
        <span>🔵 Future</span>
    </div>

</div>

<script>lucide.createIcons();</script>
@endsection
