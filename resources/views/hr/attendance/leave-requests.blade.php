@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="max-w-7xl mx-auto mt-10">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <i data-lucide="calendar-x" class="w-6 h-6"></i>
            Leave Messages
        </h1>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('hr.attendance.leave-requests') }}" class="glass rounded-2xl border border-white/10 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label class="text-white/70 text-sm mb-1 block">Period</label>
                <select name="period" id="leavePeriod"
                    class="w-full bg-white/10 text-white border border-white/20 p-2.5 rounded-xl outline-none focus:bg-white/20 text-sm">
                    <option value="" class="text-black">All Time</option>
                    <option value="current_month" class="text-black" @selected($period === 'current_month')>Current Month</option>
                    <option value="previous_month" class="text-black" @selected($period === 'previous_month')>Previous Month</option>
                    <option value="custom" class="text-black" @selected($period === 'custom')>Custom Range</option>
                </select>
            </div>

            <div id="customFromWrap" class="hidden">
                <label class="text-white/70 text-sm mb-1 block">From</label>
                <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" id="customFrom"
                    class="w-full bg-white/10 text-white border border-white/20 p-2.5 rounded-xl outline-none focus:bg-white/20 text-sm [color-scheme:dark]">
            </div>

            <div id="customToWrap" class="hidden">
                <label class="text-white/70 text-sm mb-1 block">To</label>
                <input type="date" name="to_date" value="{{ $toDate ?? '' }}" id="customTo"
                    class="w-full bg-white/10 text-white border border-white/20 p-2.5 rounded-xl outline-none focus:bg-white/20 text-sm [color-scheme:dark]">
            </div>

            <div id="customStaffWrap">
                <label class="text-white/70 text-sm mb-1 block">Staff Name</label>
                <select name="staff"
                    class="w-full bg-white/10 text-white border border-white/20 p-2.5 rounded-xl outline-none focus:bg-white/20 text-sm">
                    <option value="" class="text-black">All Staff</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->id }}" class="text-black" @selected($staffId == $s->id)>{{ $s->name }} ({{ ucfirst($s->role) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow hover:opacity-90 transition text-sm">
                    <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i> Apply
                </button>
                <a href="{{ route('hr.attendance.leave-requests') }}"
                    class="py-2.5 px-4 rounded-xl text-white font-semibold bg-white/10 hover:bg-white/20 transition text-sm">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </form>

    <script>
        const leavePeriod = document.getElementById('leavePeriod');
        const customFromWrap = document.getElementById('customFromWrap');
        const customToWrap = document.getElementById('customToWrap');

        function toggleCustomDates() {
            const show = leavePeriod.value === 'custom';
            customFromWrap.classList.toggle('hidden', !show);
            customToWrap.classList.toggle('hidden', !show);
        }

        leavePeriod.addEventListener('change', toggleCustomDates);
        toggleCustomDates();
    </script>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-500/20 text-green-300 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-500/20 text-red-300 flex items-center gap-2">
            <i data-lucide="x-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block glass rounded-2xl border border-white/10 overflow-x-auto">
        <table class="w-full text-sm text-left text-white">
            <thead class="bg-white/10 text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i> Staff
                        </div>
                    </th>
                    <th class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield" class="w-4 h-4"></i> Role
                        </div>
                    </th>
                    <th class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i> Date
                        </div>
                    </th>
                    <th class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-4 h-4"></i> Reason
                        </div>
                    </th>
                    <th class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4"></i> Status
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">
                @forelse($leaves as $leave)
                    <tr class="hover:bg-white/5">
                        <td class="px-6 py-4 font-semibold">
                            {{ $leave->user->name ?? 'N/A' }}
                        </td>

                        <td class="px-6 py-4 capitalize text-gray-300">
                            {{ $leave->user->role ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 max-w-md text-gray-300">
                            {{ $leave->note ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                @if($leave->status === 'leave') bg-yellow-500/20 text-yellow-300
                                @elseif($leave->status === 'approved') bg-green-500/20 text-green-300
                                @else bg-gray-500/20 text-gray-300 @endif">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                            No leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">
        @forelse($leaves as $leave)
            <div class="glass rounded-xl border border-white/10 p-4 space-y-2">
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-white flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        {{ $leave->user->name ?? 'N/A' }}
                    </div>

                    <span class="px-2 py-1 rounded-full text-xs font-bold
                        @if($leave->status === 'leave') bg-yellow-500/20 text-yellow-300
                        @elseif($leave->status === 'approved') bg-green-500/20 text-green-300
                        @else bg-gray-500/20 text-gray-300 @endif">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>

                <div class="text-sm text-gray-300 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    {{ ucfirst($leave->user->role ?? '-') }}
                </div>

                <div class="text-sm text-gray-300 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                </div>

                <div class="text-sm text-gray-300 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    {{ $leave->note ?? '-' }}
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-10">
                No leave requests found.
            </div>
        @endforelse
    </div>
    <div class="mt-6">
    {{ $leaves->links() }}
</div>

</div>
@endsection
