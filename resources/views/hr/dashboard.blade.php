@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

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
                    <h3 class="text-white/70 text-sm font-medium">Working Today</h3>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p class="text-4xl font-black text-white tracking-tight">{{ $workingToday }}</p>
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
                <div class="h-full bg-green-500" style="width: {{ ($totalStaff > 0) ? ($workingToday / $totalStaff) * 100 : 0 }}%"></div>
            </div>
        </div>

    </div>

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-pink-500/20 rounded-lg">
                    <i data-lucide="activity" class="w-5 h-5 text-pink-400"></i>
                </div>
                <h3 class="text-lg font-bold text-white tracking-wide">Attendance Stream</h3>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-ping"></span>
                <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Live Updates</span>
            </div>
        </div>

        <div class="p-6">
            <div class="relative space-y-6">


                @forelse($attendanceActivities as $a)
                    <div class="relative flex items-center gap-6 group">

                        <div class="z-10 w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-2xl bg-[#ff2ba7]/10 border border-[#ff2ba7] shadow-xl transition-all group-hover:scale-105
                            @if($a->status === 'leave') text-red-400 border-red-500/30
                            @elseif(!$a->clock_out) text-green-400 border-green-500/30
                            @else text-blue-400 border-blue-500/30 @endif">

                            @if($a->status === 'leave')
                                <i data-lucide="ban" class="w-5 h-5"></i>
                            @elseif(!$a->clock_out)
                                <i data-lucide="play-circle" class="w-5 h-5"></i>
                            @else
                                <i data-lucide="stop-circle" class="w-5 h-5"></i>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-white/10 transition-all">
                            <div>
                                <p class="text-sm text-white">
                                    <span class="font-bold text-white/90">{{ $a->salesman->name }}</span>

                                    @if($a->status === 'leave')
                                        <span class="text-red-400/80 mx-1">— placed on leave</span>
                                    @elseif(!$a->clock_out)
                                        <span class="text-green-400/80 mx-1">— clocked in</span>
                                    @else
                                        <span class="text-blue-400/80 mx-1">— clocked out</span>
                                    @endif
                                </p>
                                <div class="flex items-center gap-2 mt-1 text-white/40">
                                    <i data-lucide="history" class="w-3 h-3"></i>
                                    <span class="text-[11px] font-medium uppercase tracking-tight">{{ $a->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="mt-3 md:mt-0">
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full border
                                    @if($a->status === 'leave') border-red-500/20 bg-red-500/10 text-red-400
                                    @elseif(!$a->clock_out) border-green-500/20 bg-green-500/10 text-green-400
                                    @else border-blue-500/20 bg-blue-500/10 text-blue-400 @endif">
                                    @if($a->status === 'leave') LEAVE @elseif(!$a->clock_out) ACTIVE @else FINISHED @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="inline-flex p-4 rounded-full bg-white/5 mb-4 text-white/20">
                            <i data-lucide="ghost" class="w-8 h-8"></i>
                        </div>
                        <p class="text-white/40 text-sm font-medium tracking-wide">No attendance movement detected today.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
