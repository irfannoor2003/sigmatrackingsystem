@extends('layouts.app')

@section('title', 'Staff Attendance')

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
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1); /* Makes the calendar icon white */
    cursor: pointer;
}
input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}
.glass {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.calendar-grid { gap: .6rem; }

.badge { font-size: 9px; padding: 2px 4px; border-radius: 4px; }
</style>

<div class="max-w-6xl mx-auto px-3 sm:px-0 pb-24">

    {{-- HEADER --}}
    <div class="glass p-6 rounded-3xl border border-white/20 shadow-xl mb-6">
        <span class="text-xs font-bold uppercase tracking-widest text-pink-400">Personnel Record</span>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-2 flex items-center gap-2">
            <i data-lucide="user" class="w-6 h-6 text-pink-400"></i>
            {{ $user->name }}
        </h2>

        <p class="text-white/50 text-sm">{{ $user->email }}</p>
        <p class="mt-1 text-sm text-pink-300 font-semibold">Role: {{ ucfirst($user->role) }} • {{ $displayMonth }}</p>

      <div class="flex flex-col sm:flex-row gap-3 mt-4">
    <a href="{{ route('admin.attendance.index') }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl
              bg-white/5 hover:bg-white/10 text-white border border-white/10">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
    </a>

    <a href="{{ route('admin.attendance.export.single', [$user->id, 'month' => $monthInput]) }}"
       class="inline-flex items-center justify-center px-4 py-2 rounded-xl
              bg-blue-600 hover:bg-blue-700 text-white font-semibold">
        Export Excel
    </a>
</div>

    </div>

    {{-- FILTER --}}
    <form method="GET" class="glass p-5 rounded-3xl border border-white/20 shadow mb-6 flex flex-col sm:flex-row gap-4 items-end">
        <div class="w-full">
            <label class="text-xs text-white/50 uppercase block mb-2">Filter Month</label>
            <input type="month" name="month" value="{{ $monthInput }}" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
        </div>
        <button class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-600 text-white font-bold">Apply</button>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT PANEL --}}
        <div class="space-y-6">

            {{-- LEAVE --}}
            <div class="glass p-6 rounded-3xl border border-white/20 shadow">
                <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="ban" class="w-5 h-5 text-red-400"></i> Mark Leave
                </h3>
                <form method="POST" action="{{ route('admin.attendance.leave', $user->id) }}" class="space-y-3">
                    @csrf
                    <input type="date" name="date" required class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
                    <textarea name="note" rows="3" required class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white" placeholder="Leave reason"></textarea>
                    <button class="w-full py-3 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 text-white font-bold">Confirm Leave</button>
                </form>
            </div>

            {{-- MANUAL VISIT --}}
            <div class="glass p-6 rounded-3xl border border-white/20 shadow">
                <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-5 h-5 text-indigo-400"></i> Manual Visit
                </h3>
                <form method="POST" action="{{ route('admin.attendance.manual.visit.store', $user->id) }}" class="space-y-3">
                    @csrf
                    <input type="date" name="start_date" required class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
                    <input type="date" name="end_date" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <input type="time" name="clock_in" class="px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white w-full" placeholder="Start Time">
                        <input type="time" name="clock_out" class="px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white w-full" placeholder="End Time">
                    </div>
                    <textarea name="note" rows="2" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white" placeholder="Client visit / meeting"></textarea>
                    <button class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 text-white font-bold">Save Manual Visit</button>
                </form>
            </div>
           {{-- MANUAL ATTENDANCE --}}
<div class="glass p-6 rounded-3xl border border-white/20 shadow">
    <h3 class="font-bold text-white mb-4 flex items-center gap-2">
        <i data-lucide="edit" class="w-5 h-5 text-yellow-400"></i> Manual Attendance
    </h3>

        <form method="POST" action="{{ route('attendance.manual.store') }}" class="space-y-3">
        @csrf
        {{-- Hidden User ID (optional if route already has it) --}}
        <input type="hidden" name="salesman_id" value="{{ $user->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div>
                <label class="text-xs text-white/50 uppercase block mb-1">Date</label>
                <input type="date" name="date" required class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
            </div>
            <div>
                <label class="text-xs text-white/50 uppercase block mb-1">Note / Reason</label>
                <input type="text" name="note" placeholder="Reason for manual attendance" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div>
                <label class="text-xs text-white/50 uppercase block mb-1">Clock In</label>
                <input type="time" name="clock_in" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
            </div>
            <div>
                <label class="text-xs text-white/50 uppercase block mb-1">Clock Out</label>
                <input type="time" name="clock_out" class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
            </div>
        </div>

        <button class="w-full py-3 rounded-xl bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-bold mt-2">
            Save Manual Attendance
        </button>
    </form>
</div>
        </div>

        {{-- CALENDAR --}}
        <div class="lg:col-span-2 space-y-6">

            @foreach ($weeks as $week)
                <div class="glass p-5 rounded-3xl border border-white/20 shadow">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2">


                        @foreach ($week as $day)
                            @php $attendance = $day['attendance'] ?? null; @endphp

                            <div class="rounded-2xl p-4 text-center text-xs
                                @if ($day['status']==='present') bg-green-500/20 text-green-300
                                @elseif ($day['status']==='leave') bg-red-500/20 text-red-300
                                @elseif ($day['status']==='off') bg-gray-500/20 text-gray-300
                                @elseif ($day['status']==='future') bg-blue-500/10 text-blue-300
                                @else bg-yellow-500/20 text-yellow-300 @endif">

                                {{-- Day --}}
                                <div class="opacity-70 text-[10px]">{{ Carbon::parse($day['date'])->format('D') }}</div>
                                <div class="text-lg font-bold">{{ Carbon::parse($day['date'])->format('d') }}</div>
                                <div class="font-semibold mb-1">{{ $day['label'] }}</div>

                                @if($attendance)
                                    {{-- Clock-in/out --}}
                                    <div class="text-[10px] font-mono opacity-80">
                                        {{ optional($attendance->clock_in)->format('h:i A') ?? '--' }} -
                                        {{ optional($attendance->clock_out)->format('h:i A') ?? '--' }}
                                    </div>

                                    {{-- Badges --}}
                                    <div class="flex flex-wrap justify-center gap-1 mt-1">
                                        @if($attendance->short_leave)
                                            <span class="badge bg-yellow-500/30 text-yellow-300">Short</span>
                                        @endif
                                        @if($attendance->auto_clock_out)
                                            <span class="badge bg-blue-500/30 text-blue-300">Auto</span>
                                        @endif
                                    @if($attendance->manual_visit)
    <span class="badge bg-purple-500/30 text-purple-300"
          title="{{ $attendance->note ?? '' }}">
        Manual
        @if($attendance->marked_by)
            by {{ $attendance->markedBy->name ?? 'N/A' }}
        @endif
    </span>
@endif
                                        @if($attendance->status === 'leave')
                                            <span class="badge bg-red-500/30 text-red-300" title="{{ $attendance->note }}">Leave</span>
                                        @endif
                                    </div>

                                    {{-- Note --}}
                                    @if($attendance->note)
                                        <div class="mt-1 text-[9px] text-white/60 break-words">
                                            {{ $attendance->note }}
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

<script>
lucide.createIcons();
</script>
@endsection
