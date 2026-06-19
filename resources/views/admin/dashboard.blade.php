@extends('layouts.app')

@section('title','Admin Dashboard')

@section('content')

@php
    use App\Models\User;
    use App\Models\Attendance;
    use App\Models\Visit;
    use App\Models\Customer;

    // TOTAL STAFF (exclude admin)
    $totalStaff = User::whereIn('role', [
    'salesman',
    'it',
    'account',
    'store',
    'office_boy',

])->count();


    // TODAY WORKING STAFF
    $todayWorkingStaff = Attendance::whereDate('date', now()->toDateString())
        ->where('status', 'present')
        ->distinct('salesman_id')
        ->count('salesman_id');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Total Staff -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-cyan-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Total Staff</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">{{ $totalStaff }}</p>
                <span class="text-[10px] text-white/40 inline-block">Sales · IT · Accounts · Store · Office_boy</span>
            </div>
            <div class="bg-cyan-500/10 p-3 rounded-2xl group-hover:rotate-12 transition-transform">
                <i data-lucide="briefcase" class="w-7 h-7 text-cyan-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-cyan-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-cyan-500" style="width: 100%"></div>
        </div>
    </div>

    <!-- Staff Working Today -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-green-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Working Today</h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-black text-white tracking-tight">{{ $todayWorkingStaff }}</p>
                    <a href="{{ route('admin.attendance.today') }}" class="text-[10px] font-bold text-white/40 underline hover:text-green-400 uppercase tracking-tighter transition-colors">
                        Details
                    </a>
                </div>
            </div>
            <div class="bg-green-500/10 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                <i data-lucide="check-circle" class="w-7 h-7 text-green-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-green-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-green-500" style="width: {{ ($totalStaff > 0) ? ($todayWorkingStaff / $totalStaff) * 100 : 0 }}%"></div>
        </div>
    </div>

    <!-- Salesmen -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-blue-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Salesmen</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">
                    {{ User::where('role','salesman')->count() }}
                </p>
                <a href="{{ route('admin.salesmen.index') }}" class="text-[10px] font-bold text-white/40 underline hover:text-blue-400 uppercase tracking-tighter transition-colors inline-block">
                    Open Section
                </a>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-2xl group-hover:rotate-12 transition-transform">
                <i data-lucide="users" class="w-7 h-7 text-blue-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-blue-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-blue-500" style="width: {{ ($totalStaff > 0) ? (User::where('role','salesman')->count() / $totalStaff) * 100 : 0 }}%"></div>
        </div>
    </div>

    <!-- Customers -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-emerald-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Customers</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">{{ Customer::count() }}</p>
                <a href="{{ route('admin.customers.index') }}" class="text-[10px] font-bold text-white/40 underline hover:text-emerald-400 uppercase tracking-tighter transition-colors inline-block">
                    View Customers
                </a>
            </div>
            <div class="bg-emerald-500/10 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                <i data-lucide="contact" class="w-7 h-7 text-emerald-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-emerald-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-emerald-500" style="width: 100%"></div>
        </div>
    </div>

    <!-- Visits This Month -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-yellow-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Visits This Month</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">
                    {{ Visit::whereMonth('created_at', now()->month)->count() }}
                </p>
            </div>
            <div class="bg-yellow-500/10 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                <i data-lucide="calendar" class="w-7 h-7 text-yellow-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-yellow-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-yellow-500" style="width: 100%"></div>
        </div>
    </div>

    <!-- Today Visits -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-orange-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Today's Visits</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">
                    {{ Visit::whereDate('created_at', now()->toDateString())->count() }}
                </p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                <i data-lucide="sun" class="w-7 h-7 text-orange-400 animate-pulse"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-orange-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-orange-500" style="width: 100%"></div>
        </div>
    </div>

    <!-- Blocked Visits -->
    <div class="group relative overflow-hidden bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-1 transition-all hover:border-red-500/50">
        <div class="p-5 flex items-center justify-between">
            <div>
                <h3 class="text-white/70 text-sm font-medium">Blocked Visits</h3>
                <p class="text-3xl font-black text-white mt-1 tracking-tight">{{ $blockedVisits }}</p>
                <a href="{{ route('admin.visits.blocked') }}" class="text-[10px] font-bold text-white/40 underline hover:text-red-400 uppercase tracking-tighter transition-colors inline-block">
                    View Blocked Visits
                </a>
            </div>
            <div class="bg-red-500/10 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                <i data-lucide="alert-circle" class="w-7 h-7 text-red-400"></i>
            </div>
        </div>
        <div class="h-1 w-full bg-red-500/20 absolute bottom-0 left-0">
            <div class="h-full bg-red-500" style="width: 100%"></div>
        </div>
    </div>

</div>

<!-- Recent Activities -->
<div class="mt-8 bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg border-l-8"
     style="border-left-color:#ff2ba6">

    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
        <i data-lucide="activity" class="w-6 h-6 text-pink-400"></i>
        Recent Staff Activities
    </h3>

    <ul class="mt-5 space-y-4">

        {{-- Attendance Activities --}}
        @forelse($attendanceActivities as $a)
            <li class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="w-9 h-9 flex items-center justify-center rounded-xl
                    @if($a->status === 'present' && !$a->clock_out) bg-green-500/20 text-green-400
                    @elseif($a->status === 'present') bg-blue-500/20 text-blue-400
                    @else bg-red-500/20 text-red-400 @endif">

                    @if($a->status === 'leave')
                        <i data-lucide="ban" class="w-4 h-4"></i>
                    @elseif(!$a->clock_out)
                        <i data-lucide="play-circle" class="w-4 h-4"></i>
                    @else
                        <i data-lucide="stop-circle" class="w-4 h-4"></i>
                    @endif
                </div>

                {{-- Text --}}
                <div>

                    <p class="text-sm text-white">
                        <strong>{{ $a->salesman->name }}</strong>

                        @if($a->status === 'leave')
                            is on <span class="text-red-400 font-semibold">leave</span>
                        @elseif(!$a->clock_out)
                            <span class="text-green-400 font-semibold">clocked in</span>
                            at {{ \Carbon\Carbon::parse($a->clock_in)->format('h:i A') }}
                        @else
                            <span class="text-blue-400 font-semibold">clocked out</span>
                            at {{ \Carbon\Carbon::parse($a->clock_out)->format('h:i A') }}
                        @endif
                    </p>

                    <span class="text-xs text-white/50">
                        {{ $a->updated_at->diffForHumans() }}
                    </span>
                </div>
            </li>
        @empty
            <li class="text-white/60 text-sm">No attendance activity found.</li>
        @endforelse

        {{-- Divider --}}
        <div class="border-t border-white/10 my-4"></div>


           <h3 class="text-lg font-semibold text-white flex items-center gap-2">
        <i data-lucide="activity" class="w-6 h-6 text-pink-400"></i>
        Recent Visits Activities
    </h3>

        {{-- Visit Activities --}}
        @forelse($visitActivities as $v)
            <li class="flex items-start gap-3">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-purple-500/20 text-purple-400">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>

                <div>
                    <p class="text-sm text-white">
                        <strong>{{ $v->salesman->name }}</strong> started visit at
                        <strong>{{ $v->customer->name }}</strong>
                    </p>
                    <span class="text-xs text-white/50">
                        {{ $v->created_at->diffForHumans() }}
                    </span>
                </div>
            </li>
        @empty
            <li class="text-white/60 text-sm">No visit activity found.</li>
        @endforelse

    </ul>
</div>


@endsection
