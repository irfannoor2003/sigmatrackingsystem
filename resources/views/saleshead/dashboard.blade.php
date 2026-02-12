@extends('layouts.app')

@section('title', 'Sales Head Dashboard')

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-green-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-green-400 mb-1">Portfolio</p>
                    <h3 class="text-white/70 text-sm font-medium">Total Customers</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $totalCustomers }}</p>
                </div>
                <div class="bg-green-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-8 h-8 text-green-400"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-green-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-green-500 w-1/3"></div> </div>
        </div>

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-yellow-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-yellow-400 mb-1">Monthly</p>
                    <h3 class="text-white/70 text-sm font-medium">Visits Recorded</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $visitsThisMonth->count() }}</p>
                </div>
                <div class="bg-yellow-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-check" class="w-8 h-8 text-yellow-400"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-yellow-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-yellow-500 w-2/3"></div>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-orange-500/50">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-orange-400 mb-1">Real-Time</p>
                    <h3 class="text-white/70 text-sm font-medium">Today's Visits</h3>
                    <p class="text-4xl font-black text-white mt-2 tracking-tight">{{ $todayVisits->count() }}</p>
                </div>
                <div class="bg-orange-500/10 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="radar" class="w-8 h-8 text-orange-400 animate-pulse"></i>
                </div>
            </div>
            <div class="h-1 w-full bg-orange-500/20 absolute bottom-0 left-0">
                <div class="h-full bg-orange-500 w-full"></div>
            </div>
        </div>

    </div>

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-500/20 rounded-lg">
                    <i data-lucide="history" class="w-5 h-5 text-purple-400"></i>
                </div>
                <h3 class="text-lg font-bold text-white tracking-wide">Live Visit Activity Feed</h3>
            </div>
            <span class="text-[10px] font-bold py-1 px-3 rounded-full bg-white/10 text-white/60 uppercase tracking-tighter">
                Live Updates
            </span>
        </div>

        <div class="p-6">
            <div class="relative space-y-6">
                <div class="absolute left-6 top-0 bottom-0 w-px bg-gradient-to-b from-purple-500/50 via-white/10 to-transparent"></div>

                @forelse($visitActivities as $v)
                    <div class="relative flex items-center gap-6 group">
                        <div class="z-10 w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-2xl bg-slate-900 border border-white/20 text-purple-400 shadow-xl group-hover:border-purple-500 transition-colors">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>

                        <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-white/10 transition-all">
                            <div class="mb-2 md:mb-0">
                                <p class="text-sm text-white leading-relaxed">
                                    <span class="font-bold text-indigo-300">{{ $v->salesman->name }}</span>
                                    <span class="text-white/40 mx-1">reached</span>
                                    <span class="font-bold text-white">{{ $v->customer->name }}</span>
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-white/30"></i>
                                    <span class="text-[11px] text-white/50 font-medium tracking-wide">
                                        {{ $v->started_at->format('h:i A') }} • {{ $v->started_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <span class="text-[10px] font-bold px-3 py-1 rounded-lg border border-green-500/30 bg-green-500/10 text-green-400 uppercase">
                                    {{ $v->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i data-lucide="inbox" class="w-12 h-12 text-white/10 mx-auto mb-4"></i>
                        <p class="text-white/40 text-sm">No activity logs recorded for this period.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
