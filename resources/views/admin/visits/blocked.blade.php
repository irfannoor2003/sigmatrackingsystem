@extends('layouts.app')

@section('title','Blocked Visits Management')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="shield-alert" class="w-6 h-6"></i></span>
            Blocked Visits
        </h1>
        <span class="badge badge-red !py-1.5 !px-4 !text-sm">
            {{ $blockedVisits->total() }} Total
        </span>
    </div>

    @if($blockedVisits->isEmpty())
        <div class="card !rounded-2xl p-12 text-center">
            <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-5">
                <i data-lucide="check-circle" class="w-10 h-10 text-emerald-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-[var(--text)] mb-2">No Blocked Visits</h3>
            <p class="muted">All visits are up to date. No visits are currently blocked.</p>
        </div>
    @else

        {{-- Desktop Table --}}
        <div class="hidden md:block tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Salesman</th>
                        <th>Customer</th>
                        <th>Visit Start</th>
                        <th>Days Overdue</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blockedVisits as $visit)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm bg-blue-500/15 !text-blue-500">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <span class="font-medium text-[var(--text)]">{{ $visit->salesman->name }}</span>
                                </div>
                            </td>
                            <td><span class="text-[var(--text-soft)]">{{ $visit->customer->name }}</span></td>
                            <td>
                                <div class="text-[var(--text)] text-sm">{{ $visit->started_at->format('M d, Y') }}</div>
                                <div class="text-xs muted">{{ $visit->started_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                @if($visit->days_overdue)
                                    <span class="badge badge-red">{{ $visit->days_overdue }} days</span>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                            <td><span class="text-sm muted">Auto Blocked at 8 PM</span></td>
                            <td>
                                <button onclick="openUnblockModal({{ $visit->id }}, '{{ $visit->salesman->name }}', '{{ $visit->customer->name }}')"
                                    class="btn btn-xs btn-success">
                                    <i data-lucide="unlock" class="w-3.5 h-3.5"></i>
                                    Unblock
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden space-y-4">
            @foreach($blockedVisits as $visit)
                <div class="card !rounded-2xl p-4">

                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="avatar avatar-sm bg-blue-500/15 !text-blue-500">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h3 class="text-[var(--text)] font-semibold text-sm">{{ $visit->salesman->name }}</h3>
                                <p class="muted text-xs">{{ $visit->customer->name }}</p>
                            </div>
                        </div>
                        @if($visit->days_overdue)
                            <span class="badge badge-red">{{ $visit->days_overdue }}d overdue</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                        <div>
                            <span class="muted text-xs block mb-0.5">Visit Start</span>
                            <span class="text-[var(--text)]">{{ $visit->started_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="muted text-xs block mb-0.5">Time</span>
                            <span class="text-[var(--text)]">{{ $visit->started_at->format('h:i A') }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="muted text-xs block mb-0.5">Reason</span>
                            <span class="text-[var(--text-soft)] text-sm">Auto Blocked at 8 PM</span>
                        </div>
                    </div>

                    <button onclick="openUnblockModal({{ $visit->id }}, '{{ $visit->salesman->name }}', '{{ $visit->customer->name }}')"
                        class="btn btn-success btn-sm w-full">
                        <i data-lucide="unlock" class="w-4 h-4"></i>
                        Unblock Visit
                    </button>

                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $blockedVisits->links() }}
        </div>

    @endif

</div>

{{-- Unblock Confirmation Modal --}}
<div id="unblockModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 px-4">
    <div class="card w-full max-w-md !rounded-2xl p-6 shadow-card-lg">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-12 h-12 bg-emerald-500/15 rounded-full flex items-center justify-center">
                <i data-lucide="shield-check" class="w-6 h-6 text-emerald-500"></i>
            </div>
            <div>
                <h3 class="text-lg font-display font-bold text-[var(--text)]">Confirm Unblock</h3>
                <p class="muted text-sm">This action will resume the visit</p>
            </div>
        </div>

        <div class="soft-panel rounded-xl p-4 mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="muted text-xs block mb-1">Salesman</span>
                    <p class="text-[var(--text)] font-semibold" id="modalSalesman">—</p>
                </div>
                <div>
                    <span class="muted text-xs block mb-1">Customer</span>
                    <p class="text-[var(--text)] font-semibold" id="modalCustomer">—</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="closeUnblockModal()" class="btn btn-outline flex-1">Cancel</button>
            <form method="POST" action="" id="unblockForm" class="flex-1">
                @csrf
                <button type="submit" class="btn btn-success w-full">
                    <i data-lucide="unlock" class="w-4 h-4"></i> Unblock Visit
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function openUnblockModal(visitId, salesmanName, customerName) {
    const modal = document.getElementById('unblockModal');
    const form = document.getElementById('unblockForm');
    const salesmanEl = document.getElementById('modalSalesman');
    const customerEl = document.getElementById('modalCustomer');

    salesmanEl.textContent = salesmanName;
    customerEl.textContent = customerName;
    form.action = `/admin/visits/${visitId}/unblock`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUnblockModal() {
    const modal = document.getElementById('unblockModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection