@extends('layouts.app')
@section('title', 'My Visits')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="route" class="w-6 h-6"></i></span>
            My Visits
        </h1>
        <div class="flex flex-wrap items-center gap-2">
            <span class="badge badge-magenta !py-1.5 !px-4 !text-xs">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> {{ now()->format('l, d M Y') }}
            </span>
            <a href="{{ route('salesman.visits.create') }}" class="btn btn-primary btn-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Start Visit
            </a>
        </div>
    </div>

    <x-filter-bar class="print:hidden">
        <x-filter-field icon="calendar">
            <select name="month" onchange="this.form.submit()" class="select">
                <option value="" @selected(!request('month'))>All Visits</option>
                <option value="current" @selected(request('month') == 'current')>Current Month</option>
                <option value="previous" @selected(request('month') == 'previous')>Previous Month</option>
            </select>
        </x-filter-field>

        <x-filter-actions :reset="route('salesman.visits.index')">
            <a href="{{ route('visits.export.monthly', request()->all()) }}" class="btn btn-success w-full sm:w-auto">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Excel
            </a>
        </x-filter-actions>
    </x-filter-bar>


    <div id="print-header" class="hidden print:block mb-4">
        <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">
            Name: {{ auth()->user()->name }}
        </p>
        <p style="font-size: 12px; font-weight: 400; color: #555;">
            Date: {{ now()->format('d M Y') }}
        </p>
    </div>

    <div class="tbl-wrap">
        <table class="tbl hidden md:table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Customer</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>KM</th>
                    <th>Duration</th>
                    <th>Started</th>
                    <th>Stops</th>
                    <th class="print:hidden">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($visits as $v)
                    <tr>
                        <td class="font-semibold text-[var(--text)]">{{ $v->id }}</td>
                        <td class="font-semibold text-[var(--text)]">{{ $v->customer->name }}</td>

                        <td>{{ $v->purpose }}</td>

                        <td>
                            @if ($v->status == 'started')
                                <span class="badge badge-amber">Started</span>
                            @elseif ($v->status == 'blocked')
                                <span class="badge badge-red">Blocked</span>
                            @elseif ($v->status == 'cancelled')
                                <span class="badge badge-gray">Cancelled</span>
                            @else
                                <span class="badge badge-emerald">{{ ucfirst($v->status) }}</span>
                            @endif
                        </td>

                        <td class="max-w-xs">
                            <div class="break-words whitespace-pre-line truncate max-w-[120px] text-[var(--text-soft)]"
                                title="{{ $v->notes ?? '' }}">
                                {{ \Illuminate\Support\Str::limit($v->notes ?? '-', 10) }}
                            </div>
                            @if($v->status == 'cancelled' && $v->cancelled_reason)
                                <div class="mt-1 text-xs text-red-500 break-words max-w-[120px]"
                                    title="{{ $v->cancelled_reason }}">
                                    <i data-lucide="x-circle" class="w-3 h-3 inline"></i>
                                    {{ \Illuminate\Support\Str::limit($v->cancelled_reason, 30) }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if ($v->distance_km)
                                {{ $v->distance_km }} km
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if ($v->status == 'completed' && $v->completed_at)
                                {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ optional($v->started_at)->format('d M Y, h:i A') ?? '-' }}
                        </td>

                        <td>
                            @if($v->pitstops && count($v->pitstops) > 0)
                                <span class="badge badge-magenta">{{ count($v->pitstops) }} stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                            @else
                                <span class="muted text-xs">-</span>
                            @endif
                        </td>

                        <td class="print:hidden align-top">
                            @if ($v->status == 'started')
                                <div class="action-stack">
                                    @if($v->pitstops && count($v->pitstops) > 0)
                                        <div class="space-y-1.5">
                                            @foreach($v->pitstops as $idx => $ps)
                                                <div class="soft-panel px-2 py-1.5 flex items-center gap-2 !rounded-xl">
                                                    <span
                                                        class="shrink-0 w-5 h-5 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] text-[10px] font-bold flex items-center justify-center border border-[var(--border)]">{{ $idx + 1 }}</span>
                                                    <span
                                                        class="truncate text-[var(--text-soft)] text-xs flex-1">{{ $ps->customer->name ?? 'N/A' }}</span>
                                                    <form
                                                        action="{{ route('salesman.visits.pitstop.delete', [$v->id, $ps->id]) }}"
                                                        method="POST" onsubmit="return confirm('Remove this stop?')">
                                                        @csrf @method('DELETE')
                                                        <button
                                                            class="shrink-0 w-5 h-5 rounded-full bg-red-500/10 text-red-500 text-[10px] flex items-center justify-center hover:opacity-80 border border-[var(--border)] transition">
                                                            <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button type="button" onclick="openPitstopModal({{ $v->id }})"
                                        class="btn btn-soft btn-sm">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Add Stop
                                    </button>

                                    @if($v->pitstops && count($v->pitstops) > 0)
                                        <button type="button" onclick="openPitstopCompleteModal({{ $v->id }})"
                                            class="btn btn-success btn-sm">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            Complete Visit
                                        </button>
                                    @else
                                        <form action="{{ route('salesman.visits.complete', $v->id) }}" method="POST"
                                            enctype="multipart/form-data" id="completeForm_{{ $v->id }}">
                                            @csrf
                                            <textarea name="notes" class="textarea w-full resize-none" rows="2"
                                                placeholder="Add notes" required></textarea>
                                            <input type="number" step="0.1" min="0" name="distance_km"
                                                placeholder="Distance in KM" class="input w-full" required>
                                            <input type="file" name="images[]" multiple accept="image/*" class="input w-full"
                                                required>
                                            <input type="hidden" name="lat" id="complete_lat_{{ $v->id }}">
                                            <input type="hidden" name="lng" id="complete_lng_{{ $v->id }}">
                                            <button type="button"
                                                onclick="completeVisit('completeForm_{{ $v->id }}', 'complete_lat_{{ $v->id }}', 'complete_lng_{{ $v->id }}')"
                                                class="btn btn-success btn-sm">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Complete
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" onclick="openCancelModal({{ $v->id }})"
                                        class="btn btn-dark btn-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                        Cancel Visit
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('salesman.visits.show', $v->id) }}" title="View Visit"
                                        class="icon-btn !text-sky-500">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>

                                    @if(
                                        $v->status === 'completed' &&
                                        $v->started_at->gte(now()->subMonth()->startOfMonth()))
                                        <a href="{{ route('salesman.visits.edit', $v->id) }}" title="Edit Visit"
                                            class="icon-btn !text-amber-500">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center muted">
                            <i data-lucide="inbox" class="w-8 h-8 inline-block mb-2"></i>
                            No record found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="md:hidden p-4 space-y-4">
            @forelse ($visits as $v)
                <div class="card !rounded-2xl p-4">

                    <h2 class="text-base font-bold text-[var(--text)] flex items-center gap-2">
                        <i data-lucide="building-2" class="w-4 h-4 text-[var(--accent)]"></i>
                        {{ $v->customer->name }}
                    </h2>

                    <p class="text-sm text-[var(--text-soft)] mb-2 flex items-center gap-2">
                        <i data-lucide="list-checks" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                        Purpose: {{ $v->purpose }}
                    </p>

                    <p class="text-sm text-[var(--text-soft)] mb-2 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                        Started:
                        <span class="ml-1">{{ optional($v->started_at)->format('d M Y, h:i A') ?? '-' }}</span>
                    </p>

                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        @if ($v->status == 'started')
                            <span class="badge badge-amber">Started</span>
                        @elseif ($v->status == 'blocked')
                            <span class="badge badge-red">Blocked</span>
                        @elseif ($v->status == 'cancelled')
                            <span class="badge badge-gray">Cancelled</span>
                        @else
                            <span class="badge badge-emerald">{{ ucfirst($v->status) }}</span>
                        @endif

                        @if($v->pitstops && count($v->pitstops) > 0)
                            <span class="badge badge-magenta">{{ count($v->pitstops) }} stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                        @endif
                    </div>

                    @if($v->status == 'cancelled' && $v->cancelled_reason)
                        <div class="alert alert-error mb-3 !py-2 text-sm">
                            <i data-lucide="x-circle" class="w-4 h-4 shrink-0"></i>
                            <span class="break-words"><strong>Cancelled:</strong> {{ $v->cancelled_reason }}</span>
                        </div>
                    @endif

                    <div class="text-sm mb-3">
                        <div class="flex items-center gap-2 mb-1 text-[var(--text)] font-semibold">
                            <i data-lucide="sticky-note" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                            Notes
                        </div>

                        <div id="notes-{{ $v->id }}"
                            class="text-[var(--text-soft)] break-words whitespace-pre-line line-clamp-3 transition-all duration-300">
                            {{ $v->notes ?? '-' }}
                        </div>

                        @if ($v->notes && strlen($v->notes) > 120)
                            <button type="button" onclick="toggleNotes({{ $v->id }}, this)"
                                class="mt-1 text-xs font-semibold text-[var(--accent)]">
                                Read more
                            </button>
                        @endif
                    </div>

                    <p class="text-sm text-[var(--text-soft)] mb-2 flex items-center gap-2">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                        Duration:
                        @if ($v->status == 'completed' && $v->completed_at)
                            {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                        @else
                            -
                        @endif
                    </p>

                    @if ($v->distance_km)
                        <p class="text-sm text-[var(--text-soft)] mb-3 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[var(--text-muted)]"></i>
                            Distance: <span class="ml-1 font-semibold text-[var(--text)]">{{ $v->distance_km }} km</span>
                        </p>
                    @endif

                    <div class="mt-4 action-stack">
                        @if ($v->status == 'started')
                            @if($v->pitstops && count($v->pitstops) > 0)
                                <div class="space-y-1.5">
                                    @foreach($v->pitstops as $idx => $ps)
                                        <div class="soft-panel px-3 py-2 flex items-center gap-2 !rounded-xl">
                                            <span
                                                class="shrink-0 w-6 h-6 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] text-[11px] font-bold flex items-center justify-center border border-[var(--border)]">{{ $idx + 1 }}</span>
                                            <span
                                                class="truncate text-[var(--text-soft)] text-sm flex-1">{{ $ps->customer->name ?? 'N/A' }}</span>
                                            <form
                                                action="{{ route('salesman.visits.pitstop.delete', [$v->id, $ps->id]) }}"
                                                method="POST" onsubmit="return confirm('Remove this stop?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="shrink-0 w-6 h-6 rounded-full bg-red-500/10 text-red-500 text-[11px] flex items-center justify-center hover:opacity-80 border border-[var(--border)] transition">
                                                    <i data-lucide="x" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button type="button" onclick="openPitstopModal({{ $v->id }})" class="btn btn-soft">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Stop
                            </button>

                            @if($v->pitstops && count($v->pitstops) > 0)
                                <button type="button" onclick="openPitstopCompleteModal({{ $v->id }})"
                                    class="btn btn-success">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Complete Visit
                                </button>
                            @else
                                <form action="{{ route('salesman.visits.complete', $v->id) }}" method="POST"
                                    enctype="multipart/form-data" id="completeFormMobile_{{ $v->id }}">
                                    @csrf
                                    <textarea name="notes" class="textarea w-full resize-none" rows="2" placeholder="Add notes" required></textarea>
                                    <input type="number" step="0.1" min="0" name="distance_km" placeholder="Distance in KM"
                                        class="input w-full" required>
                                    <input type="file" name="images[]" multiple accept="image/*" class="input w-full"
                                        required>
                                    <input type="hidden" name="lat" id="complete_lat_m_{{ $v->id }}">
                                    <input type="hidden" name="lng" id="complete_lng_m_{{ $v->id }}">
                                    <button type="button"
                                        onclick="completeVisit('completeFormMobile_{{ $v->id }}', 'complete_lat_m_{{ $v->id }}', 'complete_lng_m_{{ $v->id }}')"
                                        class="btn btn-success">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        Complete Visit
                                    </button>
                                </form>
                            @endif

                            <button type="button" onclick="openCancelModal({{ $v->id }})" class="btn btn-dark">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Cancel Visit
                            </button>
                        @else
                            <a href="{{ route('salesman.visits.show', $v->id) }}" class="btn btn-outline w-full">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                View Visit Details
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center muted p-6">No records found</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $visits->links() }}
    </div>
</div>

@endsection

<div id="pitstopCompleteModal"
    class="hidden fixed inset-0 z-[9998] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
    <div class="card !rounded-3xl shadow-[var(--shadow-lg)] w-full max-w-lg p-6 relative">

        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                Complete Pit Stop Visit
            </h3>
            <button onclick="closePitstopCompleteModal()" class="icon-btn">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div id="pcm-gps-banner"
            class="mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-yellow-500/10 border border-yellow-400/30 text-yellow-300 text-xs">
            <i data-lucide="loader" class="w-3.5 h-3.5 shrink-0 animate-pulse"></i>
            <span id="pcm-gps-text">Acquiring GPS location…</span>
        </div>

        <form id="pitstopCompleteForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="label">Notes <span class="text-red-500">*</span></label>
                <textarea name="notes" rows="3" required placeholder="Add your visit notes…"
                    class="textarea w-full resize-none"></textarea>
            </div>

            <div>
                <label class="label">Main Visit KM <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" name="distance_km" step="0.1" min="0" required
                        placeholder="Distance to main customer" class="input w-full pr-14">
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] text-xs font-bold">KM</span>
                </div>
            </div>

            <div class="soft-panel !rounded-2xl p-4 border-l-4 !border-l-[var(--accent)]">
                <label class="label !mb-2 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-[var(--accent)]"></i>
                    Total KM Traveled (All Stops) <span class="text-red-500">*</span>
                </label>
                <p class="text-xs muted mb-2">Enter the total distance you covered during this entire pit stop visit
                    including all stops.</p>
                <div class="relative">
                    <input type="number" name="pitstop_total_km" step="0.1" min="0" required placeholder="e.g. 24.5"
                        class="input w-full pr-14 font-semibold">
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--accent)] text-xs font-bold">KM</span>
                </div>
            </div>

            <div>
                <label class="label">Documents / Images <span class="text-red-500">*</span></label>
                <input type="file" name="images[]" multiple accept="image/*" required class="input w-full">
            </div>

            <input type="hidden" name="lat" id="pcm_lat">
            <input type="hidden" name="lng" id="pcm_lng">

            <button type="button" id="pcm-submit-btn" onclick="submitPitstopComplete()" disabled class="btn btn-success w-full"
                disabled:opacity-40 disabled:cursor-not-allowed">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Complete Visit
            </button>
        </form>
    </div>
</div>

<div id="pitstopModal"
    class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="card !rounded-3xl shadow-[var(--shadow-lg)] w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-[var(--accent)]"></i>
                Add Stop
            </h3>
            <button onclick="closePitstopModal()" class="icon-btn">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form id="pitstopForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div>
                <label class="label">Customer <span class="text-red-500">*</span></label>
                <select name="customer_id" required class="select w-full">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Purpose</label>
                <select name="purpose" class="select w-full">
                    <option value="">-- Select Purpose --</option>
                    <option value="Complaint Visit">Complaint Visit</option>
                    <option value="Delivery">Delivery</option>
                    <option value="Follow-up">Follow-up</option>
                    <option value="New Lead Visit">New Lead Visit</option>
                    <option value="Order Taking">Order Taking</option>
                    <option value="Office Work">Office Work</option>
                    <option value="Product Details">Product Details</option>
                    <option value="Payment Collection">Payment Collection</option>
                    <option value="Recovery">Recovery</option>
                </select>
            </div>
            <div>
                <label class="label">Notes <span class="text-red-500">*</span></label>
                <textarea name="notes" rows="2" placeholder="Optional notes" class="textarea w-full" required></textarea>
            </div>
            <div>
                <label class="label">Distance (KM) <span class="text-red-500">*</span></label>
                <input type="number" step="0.1" min="0" name="distance_km" placeholder="Distance in KM" class="input w-full"
                    required>
            </div>
            <div>
                <label class="label">Images <span class="text-red-500">*</span></label>
                <input type="file" name="images[]" multiple accept="image/*" required class="input w-full">
            </div>
            <input type="hidden" name="lat" id="pitstopLat">
            <input type="hidden" name="lng" id="pitstopLng">
            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Save Stop
            </button>
        </form>
    </div>
</div>

<div id="cancelVisitModal"
    class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-xl p-4">
    <div class="card !rounded-3xl shadow-[var(--shadow-lg)] w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-[var(--text)] flex items-center gap-2">
                <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                Cancel Visit
            </h3>
            <button onclick="closeCancelModal()" class="icon-btn">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <p class="text-sm text-[var(--text-soft)] mb-4">Please provide a reason for cancelling this visit. This action
            cannot be undone.</p>

        <form id="cancelVisitForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="label">Reason <span class="text-red-500">*</span></label>
                <textarea id="cancelReason" name="cancelled_reason" rows="4" required
                    placeholder="Explain why you are cancelling this visit…" class="textarea w-full resize-none"></textarea>
                <p id="cancelMissingReason" class="mt-1 text-xs text-red-500 hidden">
                    Please enter a reason to enable cancellation.
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCancelModal()" class="btn btn-ghost">
                    Keep Visit
                </button>
                <button type="submit" id="cancelSubmitBtn" disabled class="btn btn-danger"
                    disabled:opacity-40 disabled:cursor-not-allowed">
                    Cancel Visit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPitstopModal(visitId) {
        const modal = document.getElementById('pitstopModal');
        const form = document.getElementById('pitstopForm');
        form.action = '/salesman/visits/' + visitId + '/pitstop';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('pitstopLat').value = pos.coords.latitude;
                document.getElementById('pitstopLng').value = pos.coords.longitude;
            });
        }
    }

    function closePitstopModal() {
        const modal = document.getElementById('pitstopModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('pitstopModal').addEventListener('click', function(e) {
        if (e.target === this) closePitstopModal();
    });
    function toggleNotes(id, btn) {
        const el = document.getElementById(`notes-${id}`);
        el.classList.toggle('line-clamp-3');

        btn.innerText = el.classList.contains('line-clamp-3') ?
            'Read more' :
            'Read less';
    }

    function completeVisit(formId, latId, lngId) {
        const form = document.getElementById(formId);

        // Enforce HTML5 required fields before checking GPS
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById(latId).value = pos.coords.latitude;
                document.getElementById(lngId).value = pos.coords.longitude;
                form.submit();
            },
            err => alert('Unable to get location: ' + err.message),
            { enableHighAccuracy: true }
        );
    }

    // ── Pitstop Complete Modal ─────────────────────────────────
    function openPitstopCompleteModal(visitId) {
        const modal  = document.getElementById('pitstopCompleteModal');
        const form   = document.getElementById('pitstopCompleteForm');
        const btn    = document.getElementById('pcm-submit-btn');
        const banner = document.getElementById('pcm-gps-banner');
        const gpsText = document.getElementById('pcm-gps-text');

        // Reset state
        form.action = '/salesman/visits/' + visitId + '/complete';
        document.getElementById('pcm_lat').value = '';
        document.getElementById('pcm_lng').value = '';
        btn.disabled = true;
        banner.className = 'mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-yellow-500/10 border border-yellow-400/30 text-yellow-300 text-xs';
        gpsText.textContent = 'Acquiring GPS location…';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Acquire GPS immediately on open
        if (!navigator.geolocation) {
            banner.className = 'mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-red-500/10 border border-red-400/30 text-red-300 text-xs';
            gpsText.textContent = 'GPS not supported by your browser.';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById('pcm_lat').value = pos.coords.latitude;
                document.getElementById('pcm_lng').value = pos.coords.longitude;
                btn.disabled = false;
                banner.className = 'mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-green-500/10 border border-green-400/30 text-green-300 text-xs';
                gpsText.textContent = '✓ GPS location confirmed — ready to submit.';
            },
            err => {
                banner.className = 'mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-red-500/10 border border-red-400/30 text-red-300 text-xs';
                gpsText.textContent = '✗ Could not get GPS: ' + err.message + '. Please enable location and try again.';
            },
            { enableHighAccuracy: true, timeout: 12000 }
        );
    }

    function closePitstopCompleteModal() {
        const modal = document.getElementById('pitstopCompleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function submitPitstopComplete() {
        const form = document.getElementById('pitstopCompleteForm');

        // Enforce HTML5 required fields (Notes and Total KM)
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const lat = document.getElementById('pcm_lat').value;
        const lng = document.getElementById('pcm_lng').value;
        if (!lat || !lng) {
            alert('GPS location is required. Please wait for GPS confirmation before submitting.');
            return;
        }
        form.submit();
    }

    document.getElementById('pitstopCompleteModal').addEventListener('click', function(e) {
        if (e.target === this) closePitstopCompleteModal();
    });

    // ── Cancel Visit Modal ───────────────────────────────────
    function openCancelModal(visitId) {
        document.getElementById('cancelVisitForm').action =
            '/salesman/visits/' + visitId + '/cancel';
        document.getElementById('cancelReason').value = '';
        document.getElementById('cancelSubmitBtn').disabled = true;
        document.getElementById('cancelMissingReason').classList.add('hidden');
        document.getElementById('cancelVisitModal').classList.remove('hidden');
        document.getElementById('cancelVisitModal').classList.add('flex');
    }

    function closeCancelModal() {
        document.getElementById('cancelVisitModal').classList.add('hidden');
        document.getElementById('cancelVisitModal').classList.remove('flex');
    }

    document.getElementById('cancelVisitModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });

    document.getElementById('cancelReason').addEventListener('input', function() {
        const val = this.value.trim();
        const btn = document.getElementById('cancelSubmitBtn');
        const warn = document.getElementById('cancelMissingReason');
        btn.disabled = val.length === 0;
        warn.classList.toggle('hidden', val.length > 0);
    });
</script>

<style>
    @media print {

        /* Page setup */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            background: #fff !important;
            color: #000 !important;
            font-family: Arial, sans-serif;

        }

        /* Show print header */
       #print-header {
        display: block !important;
        margin-bottom: 10px;
    }

        /* Hide unwanted UI */
        button,
        form,
        input,
        textarea,
        select,
        .print\:hidden,
        .md\:hidden {
            display: none !important;
        }

        /* Table styling */
        table {
            display: table !important;
            width: 100%;
            border-collapse: collapse;
            color: #000 !important;
        }

        thead {
            background: #f2f2f2 !important;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px;
            border: 1px solid #ccc;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Remove themed surfaces */
        .card,
        .soft-panel,
        .tbl-wrap {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Status badges simplified */
        span {
            background: none !important;
            border: none !important;
            color: #000 !important;
            padding: 0 !important;
        }
        table td, table th {
        font-size: 12px;
        color: #000;
    }
    }
</style>
