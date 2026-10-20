@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div>

    {{-- Header --}}
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="calendar-x" class="w-6 h-6"></i></span>
            Leave Messages
        </h1>
    </div>



    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Role</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td class="font-semibold text-[var(--text)]">{{ $leave->user->name ?? 'N/A' }}</td>

                        <td><span class="badge badge-gray capitalize">{{ $leave->user->role ?? '-' }}</span></td>

                        <td>{{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}</td>

                        <td class="max-w-md">{{ $leave->note ?? '-' }}</td>

                        <td>
                            @if($leave->status === 'approved')
                                <span class="badge badge-emerald">Approved</span>
                            @elseif($leave->status === 'leave')
                                <span class="badge badge-amber">Leave</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($leave->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center muted">No leave requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">
        @forelse($leaves as $leave)
            <div class="card !rounded-2xl p-4 space-y-2.5">
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-[var(--text)] flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-[var(--accent)]"></i>
                        {{ $leave->user->name ?? 'N/A' }}
                    </div>

                    @if($leave->status === 'approved')
                        <span class="badge badge-emerald">{{ ucfirst($leave->status) }}</span>
                    @elseif($leave->status === 'leave')
                        <span class="badge badge-amber">{{ ucfirst($leave->status) }}</span>
                    @else
                        <span class="badge badge-gray">{{ ucfirst($leave->status) }}</span>
                    @endif
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 muted"></i>
                    <span class="capitalize">{{ $leave->user->role ?? '-' }}</span>
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 muted"></i>
                    {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                </div>

                <div class="text-sm text-[var(--text-soft)] flex items-start gap-2">
                    <i data-lucide="message-square" class="w-4 h-4 muted mt-0.5"></i>
                    {{ $leave->note ?? '-' }}
                </div>
            </div>
        @empty
            <div class="card !rounded-2xl text-center muted py-10">No leave requests found.</div>
        @endforelse
    </div>

</div>
@endsection