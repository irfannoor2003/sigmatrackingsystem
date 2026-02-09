@extends('layouts.app')

@section('title','HR Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

    <!-- Total Staff -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="users" class="w-8 h-8 text-cyan-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Total Staff</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ $totalStaff }}
        </p>
    </div>

    <!-- Working Today -->

    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
    <i data-lucide="check-circle" class="w-8 h-8 text-green-400 absolute top-4 right-4"></i>
    <h3 class="text-sm font-medium text-white/80">Working Today</h3>
    <p class="text-4xl font-extrabold text-white mt-2">{{ $workingToday }}</p>
    <span class="text-xs text-white/60 mt-2 inline-block">Marked present today</span>

    <!-- See Details Link -->
    <a href="{{ route('hr.attendance.today') }}"
       class="mt-4 inline-block text-xs text-white/80 underline hover:text-white">
        See Details
    </a>
</div>

</div>

<!-- Attendance Activities -->
<div class="mt-8 bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">

    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
        <i data-lucide="activity" class="w-6 h-6 text-pink-400"></i>
        Recent Attendance Activities
    </h3>

    <ul class="mt-5 space-y-4">

        @forelse($attendanceActivities as $a)
            <li class="flex items-start gap-3">

                <div class="w-9 h-9 flex items-center justify-center rounded-xl
                    @if($a->status === 'present' && !$a->clock_out)
                        bg-green-500/20 text-green-400
                    @elseif($a->status === 'present')
                        bg-blue-500/20 text-blue-400
                    @else
                        bg-red-500/20 text-red-400
                    @endif">

                    @if($a->status === 'leave')
                        <i data-lucide="ban" class="w-4 h-4"></i>
                    @elseif(!$a->clock_out)
                        <i data-lucide="play-circle" class="w-4 h-4"></i>
                    @else
                        <i data-lucide="stop-circle" class="w-4 h-4"></i>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-white">
                        <strong>{{ $a->salesman->name }}</strong>

                        @if($a->status === 'leave')
                            is on <span class="text-red-400 font-semibold">leave</span>
                        @elseif(!$a->clock_out)
                            <span class="text-green-400 font-semibold">clocked in</span>
                        @else
                            <span class="text-blue-400 font-semibold">clocked out</span>
                        @endif
                    </p>

                    <span class="text-xs text-white/50">
                        {{ $a->updated_at->diffForHumans() }}
                    </span>
                </div>
            </li>
        @empty
            <li class="text-white/60 text-sm">
                No attendance activity found.
            </li>
        @endforelse

    </ul>
</div>

@endsection
