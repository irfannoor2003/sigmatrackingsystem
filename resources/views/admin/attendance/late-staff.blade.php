@extends('layouts.app')

@section('title', 'Late Staff Today')

@section('content')
@php
    use Carbon\Carbon;
    $lateThreshold = Carbon::today()->setTime(10, 16);
@endphp

<style>
    .glass {
        background: rgba(255,255,255,.06);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }
    .badge { font-size: 9px; padding: 2px 4px; border-radius: 4px; }
</style>

<div class="max-w-6xl mx-auto pb-24">

    {{-- HEADER --}}
    <div class="glass p-6 rounded-3xl border border-white/20 shadow-xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white flex items-center gap-2">
                <i data-lucide="clock" class="w-6 h-6 text-amber-400"></i>
                Late Staff — {{ $today->format('F d, Y') }}
            </h2>
            <p class="text-white/50 mt-1 text-sm">Staff who clocked in after 10:15 AM.</p>
        </div>

        <a href="{{ $backRoute }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white border border-white/10">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    {{-- STATS FOOTER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">Late Staff</h4>
            <p class="text-3xl font-extrabold text-amber-400 mt-2">{{ $lateStaff->count() }}</p>
        </div>

        @php
            $firstStaff = $allTodayAttendance->count() > 0 ? $allTodayAttendance->sortBy('clock_in')->first() : null;
            $lastStaff = $allTodayAttendance->count() > 0 ? $allTodayAttendance->sortByDesc('clock_in')->first() : null;
        @endphp

        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">Latest Clock In</h4>
            <p class="text-3xl font-extrabold text-white mt-2">
                {{ $lastStaff ? $lastStaff->clock_in->format('h:i A') : '--' }}
            </p>
            @if($lastStaff)
                <p class="text-xs text-white/40 mt-1">{{ $lastStaff->salesman->name }}</p>
            @endif
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">First Clock In</h4>
            <p class="text-3xl font-extrabold text-white mt-2">
                {{ $firstStaff ? $firstStaff->clock_in->format('h:i A') : '--' }}
            </p>
            @if($firstStaff)
                <p class="text-xs text-white/40 mt-1">{{ $firstStaff->salesman->name }}</p>
            @endif
        </div>
    </div>

    {{-- LATE STAFF LIST --}}
    <div class="glass p-6 rounded-3xl border border-amber-400/20 shadow-xl">
        <h3 class="text-2xl font-bold text-amber-400 flex items-center gap-2 mb-4">
            <i data-lucide="users" class="w-6 h-6"></i>
            Late Staff Details ({{ $lateStaff->count() }})
        </h3>

        <div class="max-h-[500px] overflow-y-auto space-y-2">
            @forelse($lateStaff as $late)
                @php
                    $clockInTime = \Carbon\Carbon::parse($late->clock_in);
                    $minutesLate = (int) $clockInTime->diffInMinutes($lateThreshold);
                @endphp
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-amber-500/10 transition">
                    <span class="flex items-center gap-2">
                        <i data-lucide="user-check" class="w-5 h-5 text-amber-300"></i>
                        {{ $late->salesman->name ?? 'N/A' }}
                        <span class="badge bg-white/10 text-white px-2 py-0.5 rounded-full">{{ ucfirst($late->salesman->role ?? '-') }}</span>
                    </span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-white/50">{{ $clockInTime->format('h:i A') }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400">
                            {{ $minutesLate }} min late
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i data-lucide="check-circle" class="w-12 h-12 text-emerald-400/30 mx-auto mb-4"></i>
                    <p class="text-white/40 text-lg font-medium">No late staff today!</p>
                    <p class="text-white/20 text-sm mt-1">Everyone clocked in on time.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<script>
lucide.createIcons();
</script>
@endsection
