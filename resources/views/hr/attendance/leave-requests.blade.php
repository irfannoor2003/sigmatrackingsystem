@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="max-w-7xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <i data-lucide="calendar-x" class="w-6 h-6"></i>
            Leave Messages
        </h1>
    </div>

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

</div>
@endsection
