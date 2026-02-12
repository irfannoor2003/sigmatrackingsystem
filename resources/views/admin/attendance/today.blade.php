@extends('layouts.app')

@section('title', 'Today Attendance')

@section('content')
@php
use Carbon\Carbon;
$todayDate = $today ?? now();
@endphp

<style>
    .bg-main {
        background: radial-gradient(circle at top left, #1e293b, #0f172a);
        min-height: 100vh;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.06);
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .status-pulse {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- TOP NAVIGATION & TITLE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 rounded-full bg-pink-500/10 text-pink-400 text-xs font-bold uppercase tracking-widest border border-pink-500/20">
                    Live Report
                </span>
            </div>
            <h1 class="text-4xl font-black text-white tracking-tight">
                {{ $todayDate->format('D, M d') }} <span class="text-white/30 font-light">|</span> {{ $todayDate->format('Y') }}
            </h1>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="group flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/5 hover:bg-white/10 text-white font-semibold border border-white/10 transition-all">
            <i data-lucide="layout-dashboard" class="w-5 h-5 text-blue-400 group-hover:rotate-12 transition-transform"></i>
            Dashboard
        </a>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="glass-card p-6 rounded-3xl border-l-4 border-l-blue-500">
            <p class="text-white/40 text-xs font-bold uppercase tracking-wider mb-1">Total Strength</p>
            <h3 class="text-3xl font-black text-white">{{ $presentStaff->count() + $absentStaff->count() }}</h3>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-l-emerald-500">
            <p class="text-white/40 text-xs font-bold uppercase tracking-wider mb-1">Checked In</p>
            <h3 class="text-3xl font-black text-emerald-400">{{ $presentStaff->count() }}</h3>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-l-rose-500">
            <p class="text-white/40 text-xs font-bold uppercase tracking-wider mb-1">Absent</p>
            <h3 class="text-3xl font-black text-rose-400">{{ $absentStaff->count() }}</h3>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-l-amber-500">
            <p class="text-white/40 text-xs font-bold uppercase tracking-wider mb-1">Attendance %</p>
            @php
                $total = $presentStaff->count() + $absentStaff->count();
                $percent = $total > 0 ? round(($presentStaff->count() / $total) * 100) : 0;
            @endphp
            <h3 class="text-3xl font-black text-amber-400">{{ $percent }}%</h3>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: PRESENT STAFF (2/3 width on desktop) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i data-lucide="users" class="text-emerald-400"></i>
                    Staff On-Duty
                </h2>
                <span class="text-white/40 text-sm font-medium">{{ $presentStaff->count() }} active today</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                @foreach($presentStaff as $staff)
    <div class="glass-card p-5 rounded-3xl relative overflow-hidden group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center border border-emerald-500/20">
                    <span class="text-emerald-400 font-bold text-lg">{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                </div>
                <div>
    <h4 class="text-white font-bold group-hover:text-emerald-400 transition-colors">{{ $staff->name }}</h4>
    <p class="text-white/40 text-xs uppercase">{{ $staff->role }}</p>

    {{-- Worked Time --}}
    @if($staff->today_record && $staff->today_record->clock_in)
        @php
            $clockIn = \Carbon\Carbon::parse($staff->today_record->clock_in);
            $clockOut = $staff->today_record->clock_out
                        ? \Carbon\Carbon::parse($staff->today_record->clock_out)
                        : now();
            $workedHours = $clockIn->diff($clockOut)->format('%h hr %i min');
        @endphp
        <p class="text-white/30 text-[10px] font-semibold mt-1">Worked: {{ $workedHours }}</p>
    @else
        <p class="text-white/30 text-[10px] font-semibold mt-1">Worked: --:--</p>
    @endif
</div>

            </div>

            {{-- Pulse dot if they haven't clocked out yet --}}
            @if($staff->today_record && !$staff->today_record->clock_out)
                <span class="status-pulse"></span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-2 bg-black/20 rounded-2xl p-3 border border-white/5">
            <div class="space-y-1">
                <p class="text-[10px] text-white/30 uppercase font-bold">Clock In</p>
                <div class="flex items-center gap-1.5 text-emerald-400">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                    <span class="text-sm font-black">
                        {{-- ACCESS THROUGH today_record --}}
                        {{ $staff->today_record && $staff->today_record->clock_in
                            ? \Carbon\Carbon::parse($staff->today_record->clock_in)->format('h:i A')
                            : '--:--' }}
                    </span>
                </div>
            </div>
            <div class="space-y-1 border-l border-white/10 pl-3">
                <p class="text-[10px] text-white/30 uppercase font-bold">Clock Out</p>
                <div class="flex items-center gap-1.5 {{ ($staff->today_record && $staff->today_record->clock_out) ? 'text-rose-400' : 'text-emerald-500' }}">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span class="text-sm font-black">
                        {{-- ACCESS THROUGH today_record --}}
                        @if($staff->today_record && $staff->today_record->clock_out)
                            {{ \Carbon\Carbon::parse($staff->today_record->clock_out)->format('h:i A') }}
                        @else
                            Active
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach
            </div>
        </div>

        {{-- RIGHT COLUMN: ABSENT STAFF --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i data-lucide="ghost" class="text-rose-400"></i>
                    Not Present Today
                </h2>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="max-h-[600px] overflow-y-auto p-4 custom-scrollbar space-y-3">
                    @forelse($absentStaff as $staff)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center border border-rose-500/10">
                                <i data-lucide="user-x" class="w-5 h-5 text-rose-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $staff->name }}</p>
                                <p class="text-white/30 text-[10px] uppercase font-bold">{{ $staff->role }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-md bg-white/5 text-white/40 text-[9px] font-bold uppercase">Absent</span>
                    </div>
                    @empty
                    <div class="py-10 text-center">
                        <p class="text-white/20 text-sm italic">Everyone is present today!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Refresh icons
    lucide.createIcons();
</script>
@endsection
