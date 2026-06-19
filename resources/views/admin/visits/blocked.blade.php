@extends('layouts.app')

@section('title','Blocked Visits Management')

@section('content')

<div class="p-0">

    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide mb-6">
        <i data-lucide="shield-alert" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
        Blocked Visits
        <span class="ml-3 px-3 py-1 bg-red-500/20 text-red-300 rounded-full text-sm font-medium align-middle">
            {{ $blockedVisits->total() }} Total
        </span>
    </h1>

    @if($blockedVisits->isEmpty())
        <div class="glass rounded-2xl border border-white/20 p-12 text-center">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-5">
                <i data-lucide="check-circle" class="w-10 h-10 text-green-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No Blocked Visits</h3>
            <p class="text-white/60">All visits are up to date. No visits are currently blocked.</p>
        </div>
    @else

        {{-- Desktop Table --}}
        <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl hidden md:block">
            <table class="w-full min-w-[700px]">
                <thead class="bg-white/10 backdrop-blur-xl">
                    <tr class="text-left text-white/70 text-xs uppercase tracking-wider">
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-white/50"></i>
                                Salesman
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="building-2" class="w-4 h-4 text-white/50"></i>
                                Customer
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-white/50"></i>
                                Visit Start
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-white/50"></i>
                                Days Overdue
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="message-circle" class="w-4 h-4 text-white/50"></i>
                                Reason
                            </div>
                        </th>
                        <th class="p-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="settings" class="w-4 h-4 text-white/50"></i>
                                Action
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($blockedVisits as $visit)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-blue-500/20 rounded-full flex items-center justify-center shrink-0">
                                        <i data-lucide="user" class="w-4 h-4 text-blue-400"></i>
                                    </div>
                                    <span class="text-white font-medium">{{ $visit->salesman->name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-white/80">{{ $visit->customer->name }}</span>
                            </td>
                            <td class="p-4">
                                <div class="text-white text-sm">{{ $visit->started_at->format('M d, Y') }}</div>
                                <div class="text-xs text-white/50">{{ $visit->started_at->format('h:i A') }}</div>
                            </td>
                            <td class="p-4">
                                @if($visit->days_overdue)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                        {{ $visit->days_overdue }} days
                                    </span>
                                @else
                                    <span class="text-white/50">—</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="text-white/70 text-sm">Auto Blocked at 8 PM</span>
                            </td>
                            <td class="p-4">
                                <button onclick="openUnblockModal({{ $visit->id }}, '{{ $visit->salesman->name }}', '{{ $visit->customer->name }}')"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-500/20 border border-green-400/30 text-green-300 text-sm font-medium hover:bg-green-500/30 transition-colors">
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
                <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center shrink-0">
                                <i data-lucide="user" class="w-5 h-5 text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-sm">{{ $visit->salesman->name }}</h3>
                                <p class="text-white/60 text-xs">{{ $visit->customer->name }}</p>
                            </div>
                        </div>
                        @if($visit->days_overdue)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-300">
                                {{ $visit->days_overdue }}d overdue
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                        <div>
                            <span class="text-white/50 text-xs block mb-0.5">Visit Start</span>
                            <span class="text-white/90">{{ $visit->started_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-white/50 text-xs block mb-0.5">Time</span>
                            <span class="text-white/90">{{ $visit->started_at->format('h:i A') }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-white/50 text-xs block mb-0.5">Reason</span>
                            <span class="text-white/70 text-sm">Auto Blocked at 8 PM</span>
                        </div>
                    </div>

                    <button onclick="openUnblockModal({{ $visit->id }}, '{{ $visit->salesman->name }}', '{{ $visit->customer->name }}')"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-green-500/20 border border-green-400/30 text-green-300 text-sm font-medium hover:bg-green-500/30 transition-colors">
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
<div id="unblockModal" class="fixed inset-0 bg-black/70 backdrop-blur-md hidden items-center justify-center z-50 px-4">
    <div class="w-full max-w-md rounded-2xl bg-gradient-to-br from-[#0f172a]/90 to-[#020617]/90 border border-white/10 shadow-2xl p-6">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                <i data-lucide="shield-check" class="w-6 h-6 text-green-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Confirm Unblock</h3>
                <p class="text-white/60 text-sm">This action will resume the visit</p>
            </div>
        </div>

        <div class="bg-white/5 rounded-xl p-4 mb-6 border border-white/10">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-white/50 text-xs block mb-1">Salesman</span>
                    <p class="text-white font-semibold" id="modalSalesman">—</p>
                </div>
                <div>
                    <span class="text-white/50 text-xs block mb-1">Customer</span>
                    <p class="text-white font-semibold" id="modalCustomer">—</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="closeUnblockModal()"
                class="flex-1 px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white font-medium hover:bg-white/20 transition-colors">
                Cancel
            </button>
            <form method="POST" action="" id="unblockForm" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold shadow hover:opacity-90 transition-colors">
                    Unblock Visit
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
