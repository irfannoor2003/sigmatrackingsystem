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
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="briefcase" class="w-8 h-8 text-cyan-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Total Staff</h3>
        <p class="text-4xl font-extrabold text-white mt-2">{{ $totalStaff }}</p>
        <span class="text-xs text-white/60 mt-2 inline-block">Sales · IT · Accounts · Store · Office_boy </span>
    </div>

   <!-- Staff Working Today -->
<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
    <i data-lucide="check-circle" class="w-8 h-8 text-green-400 absolute top-4 right-4"></i>
    <h3 class="text-sm font-medium text-white/80">Working Today</h3>
    <p class="text-4xl font-extrabold text-white mt-2">{{ $todayWorkingStaff }}</p>
    <span class="text-xs text-white/60 mt-2 inline-block">Marked present today</span>

    <!-- See Details Link -->
    <a href="{{ route('admin.attendance.today') }}"
       class="mt-4 inline-block text-xs text-white/80 underline hover:text-white">
        See Details
    </a>
</div>


    <!-- Salesmen -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="users" class="w-8 h-8 text-blue-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Salesmen</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ User::where('role','salesman')->count() }}
        </p>
        <a href="{{ route('admin.salesmen.index') }}"
           class="text-xs text-white/80 underline mt-3 inline-block hover:text-white">
            Open Section
        </a>
    </div>

    <!-- Customers -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="contact" class="w-8 h-8 text-green-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Customers</h3>
        <p class="text-4xl font-extrabold text-white mt-2">{{ Customer::count() }}</p>
        <a href="{{ route('admin.customers.index') }}"
           class="text-xs text-white/80 underline mt-3 inline-block hover:text-white">
            View Customers
        </a>
    </div>

    <!-- Visits This Month -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="calendar" class="w-8 h-8 text-yellow-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Visits This Month</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ Visit::whereMonth('created_at', now()->month)->count() }}
        </p>
    </div>

    <!-- Today Visits -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="sun" class="w-8 h-8 text-orange-400 absolute top-4 right-4"></i>
        <h3 class="text-sm font-medium text-white/80">Today's Visits</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ Visit::whereDate('created_at', now()->toDateString())->count() }}
        </p>
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
