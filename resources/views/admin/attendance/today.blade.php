@extends('layouts.app')

@section('title', 'Today Attendance')

@section('content')
@php
use Carbon\Carbon;
$todayDate = $today ?? now();
@endphp

<style>
.glass {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.badge { font-size: 9px; padding: 2px 4px; border-radius: 4px; }
</style>

<div class="max-w-6xl mx-auto px-3 sm:px-0 pb-24">

    {{-- HEADER --}}
    <div class="glass p-6 rounded-3xl border border-white/20 shadow-xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white flex items-center gap-2">
                <i data-lucide="calendar" class="w-6 h-6 text-pink-400"></i>
                Attendance for {{ $todayDate->format('F d, Y') }}
            </h2>
            <p class="text-white/50 mt-1 text-sm">Check who marked attendance today and who didn't.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white border border-white/10">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        {{-- PRESENT STAFF --}}
        <div class="glass p-6 rounded-3xl border border-emerald-400/20 shadow-xl">
            <h3 class="text-2xl font-bold text-emerald-400 flex items-center gap-2 mb-4">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                Present ({{ $presentStaff->count() }})
            </h3>
            <div class="max-h-[400px] overflow-y-auto space-y-2">
                @foreach($presentStaff as $staff)
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-emerald-500/10 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="user-check" class="w-5 h-5 text-emerald-300"></i>
                            {{ $staff->name }}
                        </span>
                        <span class="badge bg-white/10 text-white text-[10px] px-2 py-0.5 rounded-full">{{ ucfirst($staff->role) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ABSENT STAFF --}}
        <div class="glass p-6 rounded-3xl border border-rose-400/20 shadow-xl">
            <h3 class="text-2xl font-bold text-rose-400 flex items-center gap-2 mb-4">
                <i data-lucide="user-x" class="w-6 h-6"></i>
                Absent ({{ $absentStaff->count() }})
            </h3>
            <div class="max-h-[400px] overflow-y-auto space-y-2">
                @foreach($absentStaff as $staff)
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-rose-500/10 transition">
                        <span class="flex items-center gap-2">
                            <i data-lucide="user-minus" class="w-5 h-5 text-rose-300"></i>
                            {{ $staff->name }}
                        </span>
                        <span class="badge bg-white/10 text-white text-[10px] px-2 py-0.5 rounded-full">{{ ucfirst($staff->role) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- STATS FOOTER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">Total Staff</h4>
            <p class="text-3xl font-extrabold text-white mt-2">{{ $presentStaff->count() + $absentStaff->count() }}</p>
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">Present Today</h4>
            <p class="text-3xl font-extrabold text-emerald-400 mt-2">{{ $presentStaff->count() }}</p>
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 shadow-lg text-center">
            <h4 class="text-sm text-white/50">Absent Today</h4>
            <p class="text-3xl font-extrabold text-rose-400 mt-2">{{ $absentStaff->count() }}</p>
        </div>
    </div>

</div>

<script>
lucide.createIcons();
</script>
@endsection
