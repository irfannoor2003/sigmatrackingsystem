@extends('layouts.app')

@section('title','Sales Head Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

    <!-- Customers -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative hover:scale-105 transition-transform duration-200">
        <i data-lucide="users" class="w-8 h-8 text-green-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Customers</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ $totalCustomers }}
        </p>
        <span class="text-xs text-white/50 mt-1 block">Total registered customers</span>
    </div>

    <!-- Visits This Month -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative hover:scale-105 transition-transform duration-200">
        <i data-lucide="calendar" class="w-8 h-8 text-yellow-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Visits This Month</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ $visitsThisMonth->count() }}
        </p>
        <span class="text-xs text-white/50 mt-1 block">
            Total visits recorded this month
        </span>
    </div>

    <!-- Today's Visits -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative hover:scale-105 transition-transform duration-200">
        <i data-lucide="sun" class="w-8 h-8 text-orange-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Today's Visits</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ $todayVisits->count() }}
        </p>
        <span class="text-xs text-white/50 mt-1 block">
            Visits recorded today
        </span>
    </div>

</div>

<!-- Visit Activities -->
<div class="mt-8 bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">

    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
        <i data-lucide="map-pin" class="w-6 h-6 text-purple-400"></i>
        Recent Visit Activities
    </h3>

    <ul class="mt-5 space-y-4">
        @forelse($visitActivities as $v)
            <li class="flex items-start gap-3 hover:bg-white/5 p-3 rounded-xl transition-colors">
                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-purple-500/30 to-purple-400/20 text-white">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>

                <div class="flex-1">
                    <p class="text-sm text-white">
                        <strong>{{ $v->salesman->name }}</strong> visited
                        <strong>{{ $v->customer->name }}</strong>
                    </p>
                    <span class="text-xs text-white/50">
                        {{ $v->started_at->format('d M Y, h:i A') }}
                        ({{ $v->started_at->diffForHumans() }})
                    </span>
                </div>

                <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-500/30 text-green-200 self-start">
                    {{ ucfirst($v->status) }}
                </span>
            </li>
        @empty
            <li class="text-white/60 text-sm text-center">
                No visit activity found.
            </li>
        @endforelse
    </ul>
</div>

@endsection
