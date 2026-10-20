@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="mt-10">

    {{-- Header --}}
    <div class="page-head !mb-6">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="calendar-x" class="w-6 h-6"></i></span>
            Leave Messages
        </h1>
    </div>

    {{-- Filters --}}
    <x-filter-bar :action="route('hr.attendance.leave-requests')">
        <x-filter-field icon="calendar-range" label="Period">
            <select name="period" id="leavePeriod" class="select">
                <option value="">All Time</option>
                <option value="current_month" @selected($period === 'current_month')>Current Month</option>
                <option value="previous_month" @selected($period === 'previous_month')>Previous Month</option>
                <option value="custom" @selected($period === 'custom')>Custom Range</option>
            </select>
        </x-filter-field>

        <x-filter-field icon="calendar" label="From" class="hidden" id="customFromWrap">
            <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" id="customFrom" class="input">
        </x-filter-field>

        <x-filter-field icon="calendar" label="To" class="hidden" id="customToWrap">
            <input type="date" name="to_date" value="{{ $toDate ?? '' }}" id="customTo" class="input">
        </x-filter-field>

        <x-filter-field icon="users" label="Staff Name" id="customStaffWrap">
            <select name="staff" class="select">
                <option value="">All Staff</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" @selected($staffId == $s->id)>{{ $s->name }} ({{ ucfirst($s->role) }})</option>
                @endforeach
            </select>
        </x-filter-field>

        <x-filter-actions submit="Apply" :reset="route('hr.attendance.leave-requests')" />
    </x-filter-bar>

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

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block card !rounded-2xl overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i> Staff
                        </div>
                    </th>
                    <th>
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield" class="w-4 h-4"></i> Role
                        </div>
                    </th>
                    <th>
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i> Date
                        </div>
                    </th>
                    <th>
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-4 h-4"></i> Reason
                        </div>
                    </th>
                    <th>
                        <div class="flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4"></i> Status
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td class="font-semibold text-[var(--text)]">
                            {{ $leave->user->name ?? 'N/A' }}
                        </td>

                        <td class="capitalize text-[var(--text-soft)]">
                            {{ $leave->user->role ?? '-' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                        </td>

                        <td class="max-w-md text-[var(--text-soft)]">
                            {{ $leave->note ?? '-' }}
                        </td>

                        <td>
                            @if($leave->status === 'leave')
                                <span class="badge badge-amber">{{ ucfirst($leave->status) }}</span>
                            @elseif($leave->status === 'approved')
                                <span class="badge badge-emerald">{{ ucfirst($leave->status) }}</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($leave->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center muted">
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
            <div class="card !rounded-2xl p-4 space-y-2">
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-[var(--text)] flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-[var(--accent)]"></i>
                        {{ $leave->user->name ?? 'N/A' }}
                    </div>

                    @if($leave->status === 'leave')
                        <span class="badge badge-amber">{{ ucfirst($leave->status) }}</span>
                    @elseif($leave->status === 'approved')
                        <span class="badge badge-emerald">{{ ucfirst($leave->status) }}</span>
                    @else
                        <span class="badge badge-gray">{{ ucfirst($leave->status) }}</span>
                    @endif
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    {{ ucfirst($leave->user->role ?? '-') }}
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    {{ $leave->note ?? '-' }}
                </div>
            </div>
        @empty
            <div class="text-center muted py-10">
                No leave requests found.
            </div>
        @endforelse
    </div>
    <div class="mt-6">
    {{ $leaves->links() }}
</div>

</div>
@endsection