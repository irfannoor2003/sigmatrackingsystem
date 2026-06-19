@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-[#ff2ba7]/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[#ff2ba7] mb-1">Workforce</p>
                    <h3 class="text-white/70 text-sm font-medium">Total Staff</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $totalStaff }}</p>
                </div>
                <div class="bg-[#ff2ba7]/10 p-4 rounded-2xl group-hover:rotate-12 transition-transform">
                    <i data-lucide="users" class="w-8 h-8 text-[#ff2ba7]"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-[#ff2ba7]/20 absolute bottom-0 left-0"></div>
        </div>

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-green-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-green-400 mb-1">Attendance</p>
                    <h3 class="text-white/70 text-sm font-medium">Present Today</h3>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p class="text-4xl font-black text-white tracking-tight">{{ $presentCount }}</p>
                        <a href="{{ route('hr.attendance.today') }}" class="text-[10px] font-bold text-white/40 underline hover:text-green-400 uppercase tracking-tighter transition-colors">
                            Details
                        </a>
                    </div>
                </div>
                <div class="bg-green-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-400"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-green-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-green-500" style="width: {{ ($totalStaff > 0) ? ($presentCount / $totalStaff) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-red-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-red-400 mb-1">Absent</p>
                    <h3 class="text-white/70 text-sm font-medium">Absent Today</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $absentCount }}</p>
                </div>
                <div class="bg-red-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="x-circle" class="w-8 h-8 text-red-400"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-red-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-red-500" style="width: {{ ($totalStaff > 0) ? ($absentCount / $totalStaff) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-yellow-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-yellow-400 mb-1">On Leave</p>
                    <h3 class="text-white/70 text-sm font-medium">Leave Today</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $leaveCount }}</p>
                </div>
                <div class="bg-yellow-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-off" class="w-8 h-8 text-yellow-400"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-yellow-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-yellow-500" style="width: {{ ($totalStaff > 0) ? ($leaveCount / $totalStaff) * 100 : 0 }}%"></div>
            </div>
        </div>

    </div>

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#ff2ba7]/20 rounded-lg">
                    <i data-lucide="activity" class="w-5 h-5 text-[#ff2ba7]"></i>
                </div>
                <h3 class="text-lg font-bold text-white tracking-wide">Live Attendance Feed</h3>
            </div>
            <span class="text-[10px] font-bold py-1 px-3 rounded-full bg-white/10 text-white/60 uppercase tracking-tighter">
                Live Updates
            </span>
        </div>

        <div class="p-6">
            <div class="relative space-y-6">

                @forelse($attendanceActivities as $a)
                    <div class="relative flex items-center gap-6 group">
                        <div class="z-10 w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-2xl bg-[#ff2ba7]/10 border border-[#ff2ba7] text-[#ff2ba7] shadow-xl transition-colors">
                            @if($a->status === 'leave')
                                <i data-lucide="calendar-off" class="w-5 h-5"></i>
                            @elseif(!$a->clock_out)
                                <i data-lucide="play-circle" class="w-5 h-5"></i>
                            @else
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-white/10 transition-all">
                            <div class="mb-2 md:mb-0">
                                <p class="text-sm text-white leading-relaxed">
                                    <span class="font-bold text-indigo-300">{{ $a->salesman->name }}</span>
                                    @if($a->status === 'leave')
                                        <span class="text-white/40 mx-1">— marked on leave</span>
                                    @elseif(!$a->clock_out)
                                        <span class="text-white/40 mx-1">— clocked in</span>
                                    @else
                                        <span class="text-white/40 mx-1">— clocked out</span>
                                    @endif
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-white/30"></i>
                                    <span class="text-[11px] text-white/50 font-medium tracking-wide">
                                        {{ $a->updated_at->format('h:i A') }} • {{ $a->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                @if($a->status === 'leave')
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-lg border border-red-500/30 bg-red-500/10 text-red-400 uppercase">
                                        LEAVE
                                    </span>
                                @elseif(!$a->clock_out)
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-lg border border-green-500/30 bg-green-500/10 text-green-400 uppercase">
                                        ACTIVE
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-lg border border-blue-500/30 bg-blue-500/10 text-blue-400 uppercase">
                                        FINISHED
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i data-lucide="inbox" class="w-12 h-12 text-white/10 mx-auto mb-4"></i>
                        <p class="text-white/40 text-sm">No attendance activity recorded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
