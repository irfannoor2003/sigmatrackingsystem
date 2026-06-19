@extends('layouts.app')

@section('title', 'Today Attendance')

@section('content')
@php
    use Carbon\Carbon;
    $todayDate = $today ?? now();
    $total = $presentStaff->count() + $leaveStaff->count() + $absentStaff->count();
@endphp

<style>
    /* Glassmorphism Core */
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-pulse {
        width: 10px;
        height: 10px;
        background: #ff2ba6;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(255, 43, 166, 0.5);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 43, 166, 0.6); }
        70% { box-shadow: 0 0 0 10px rgba(255, 43, 166, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 43, 166, 0); }
    }

    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 10px;
    }
</style>

<div class="max-w-7xl mx-auto p-0">

    {{-- HEADER --}}
    <div class="relative glass p-8 rounded-2xl border border-white/20 shadow-2xl mb-8 overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-400/20 blur-3xl rounded-full hidden lg:block"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-white/10 p-3 rounded-2xl">
                    <i data-lucide="activity" class="w-8 h-8 text-[#ff2ba6]"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-white">
                        {{ $todayDate->format('D, M d') }}
                    </h1>
                    <p class="text-sm text-white/40 uppercase tracking-widest">Live Attendance Feed</p>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="w-full sm:w-auto sm:ml-auto
flex items-center justify-center gap-2
px-6 py-3 rounded-2xl
bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
hover:scale-105 transition
text-white font-bold shadow-xl">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Dashboard
            </a>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach([
            ['Total Staff', $total, 'users', 'border-white/20'],
            ['Present', $presentStaff->count(), 'check-circle', 'border-emerald-500/30'],
            ['On Leave', $leaveStaff->count(), 'coffee', 'border-sky-500/30'],
            ['Absent', $absentStaff->count(), 'user-x', 'border-rose-500/30']
        ] as [$label, $value, $icon, $borderColor])
        <div class="glass p-6 rounded-2xl border {{ $borderColor }} hover:scale-[1.02] transition">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1">{{ $label }}</p>
            <div class="flex items-center justify-between">
                <h3 class="text-3xl font-extrabold text-white">{{ $value }}</h3>
                <div class="p-2 rounded-lg bg-white/5">
                    <i data-lucide="{{ $icon }}" class="w-6 h-6 text-[#ff2ba6]"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-12 gap-8">

        {{-- PRESENT STAFF SECTION --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i data-lucide="users" class="text-[#ff2ba6]"></i>
                    Present Today
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                @forelse($presentStaff as $staff)
                <div class="glass p-5 rounded-3xl border border-white/10 hover:border-pink-500/30 transition">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500/20 to-fuchsia-500/20 flex items-center justify-center border border-white/10">
                                <span class="text-[#ff2ba6] font-extrabold text-lg">
                                    {{ strtoupper(substr($staff->name,0,1)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-base">{{ $staff->name }}</h4>
                                <p class="text-white/40 text-[11px] font-semibold uppercase tracking-wide">
                                    {{ $staff->role }}
                                </p>
                            </div>
                        </div>
                        @if(!$staff->today_record->clock_out)
                            <div class="status-pulse" title="Currently Active"></div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 bg-black/40 p-3 rounded-2xl border border-white/5">
                        <div>
                            <p class="text-[10px] text-white/30 font-semibold uppercase">Clock In</p>
                            <p class="text-[#ff2ba6] font-bold text-sm">
                                {{ $staff->today_record->clock_in ? \Carbon\Carbon::parse($staff->today_record->clock_in)->format('h:i A') : '--:--' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-white/30 font-semibold uppercase">Clock Out</p>
                            <p class="font-bold text-sm {{ $staff->today_record->clock_out ? 'text-white' : 'text-emerald-400' }}">
                                {{ $staff->today_record->clock_out ? \Carbon\Carbon::parse($staff->today_record->clock_out)->format('h:i A') : 'Active' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-[11px] text-white/40 font-semibold uppercase">
                        <i data-lucide="clock" class="w-3 h-3 text-[#ff2ba6]"></i>
                        @php
                            $clockIn = \Carbon\Carbon::parse($staff->today_record->clock_in);
                            $clockOut = $staff->today_record->clock_out ? \Carbon\Carbon::parse($staff->today_record->clock_out) : now();
                            $diff = $clockIn->diff($clockOut);
                        @endphp
                        Worked: <span class="text-white">{{ $diff->h }}h {{ $diff->i }}m</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full glass p-10 rounded-3xl text-center text-white/30">
                    No staff members present yet.
                </div>
                @endforelse
            </div>
        </div>

        {{-- SIDEBAR: LEAVE + ABSENT --}}
        <div class="lg:col-span-4 space-y-8">

            {{-- LEAVE --}}
            <div class="glass p-6 rounded-3xl border border-sky-400/20">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                    <i data-lucide="coffee" class="text-sky-400"></i> On Leave
                </h2>
                <div class="max-h-[300px] overflow-y-auto custom-scrollbar space-y-3">
                    @forelse($leaveStaff as $staff)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/5">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center font-bold text-sky-400">
                                {{ substr($staff->name,0,1) }}
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $staff->name }}</p>
                                <p class="text-sky-400/60 text-[10px] font-bold uppercase tracking-tighter">Approved Leave</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-white/20 text-sm text-center py-4">No leaves today</p>
                    @endforelse
                </div>
            </div>

            {{-- ABSENT --}}
            <div class="glass p-6 rounded-3xl border border-rose-500/20">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                    <i data-lucide="user-x" class="text-rose-500"></i> Absent
                </h2>
                <div class="max-h-[300px] overflow-y-auto custom-scrollbar space-y-3">
                    @forelse($absentStaff as $staff)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/5">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                                <i data-lucide="user-minus" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $staff->name }}</p>
                                <p class="text-white/30 text-[10px] font-semibold uppercase">{{ $staff->role }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="text-2xl mb-1">🎉</div>
                            <p class="text-white/20 text-sm font-medium">Perfect attendance!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();
</script>
@endsection
