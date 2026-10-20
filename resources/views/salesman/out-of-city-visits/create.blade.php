@extends('layouts.app')
@section('title', 'Add Out of City Visit Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="plus" class="w-6 h-6"></i></span>
            Add Visit Details
        </h1>
        <a href="{{ route('salesman.out-of-city-visits.index') }}" class="icon-btn" title="Back">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
    </div>


    <div class="card !rounded-2xl p-6 mb-6">
        <h2 class="text-lg font-bold text-[var(--text)] mb-4 flex items-center gap-2">
            <i data-lucide="clock" class="w-5 h-5 text-[var(--accent)]"></i>
            Attendance Information
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="muted">Date:</span>
                <span
                    class="text-[var(--text)] font-semibold">{{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}</span>
            </div>
            <div>
                <span class="muted">Clock In:</span>
                <span
                    class="text-[var(--text)] font-semibold">{{ optional($visit->attendance)->clock_in ? $visit->attendance->clock_in->format('h:i A') : '-' }}</span>
            </div>
            <div>
                <span class="muted">Clock Out:</span>
                <span
                    class="text-[var(--text)] font-semibold">{{ optional($visit->attendance)->clock_out ? $visit->attendance->clock_out->format('h:i A') : '-' }}</span>
            </div>
        </div>
        @if ($visit->attendance && $visit->attendance->note)
            <div class="mt-4 text-sm">
                <span class="muted">Note:</span>
                <span class="text-[var(--text)]">{{ $visit->attendance->note }}</span>
            </div>
        @endif
    </div>

    <form action="{{ route('salesman.out-of-city-visits.store') }}" method="POST" enctype="multipart/form-data"
        id="visitForm">
        @csrf
        <input type="hidden" name="out_of_city_visit_id" value="{{ $visit->id }}">

        <div class="card !rounded-2xl p-6 mb-6">
            <h2 class="text-lg font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                <i data-lucide="sticky-note" class="w-5 h-5 text-[var(--accent)]"></i>
                Overall Notes
            </h2>
            <textarea name="notes" rows="2" class="textarea w-full resize-none"
                placeholder="Optional overall notes for this out of city visit...">{{ old('notes') }}</textarea>
        </div>

        <div class="card !rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between gap-3 mb-5">
                <h2 class="text-lg font-bold text-[var(--text)] flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-[var(--accent)]"></i>
                    Customer Visits
                </h2>
                <button type="button" onclick="addVisitItem()" class="btn btn-soft btn-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Customer
                </button>
            </div>

            <div id="visitItems" class="space-y-4">
                <div class="visit-item soft-panel !rounded-2xl p-4 relative" data-index="0">
                    <div class="flex items-center justify-between mb-3">
                        <span class="visit-item-title text-sm font-semibold text-[var(--text-soft)] flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] text-xs font-bold flex items-center justify-center border border-[var(--border)]">1</span>
                            Customer Visit 1
                        </span>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="label !text-xs">Select Customer <span class="text-red-500">*</span></label>
                            <select name="items[0][customer_id]" required class="select w-full customer-select">
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label !text-xs">Note</label>
                            <textarea name="items[0][note]" rows="2" class="textarea w-full resize-none"
                                placeholder="Note for this customer visit..."></textarea>
                        </div>
                        <div>
                            <label class="label !text-xs">Photos <span class="text-red-500">*</span></label>
                            <input type="file" name="items[0][images][]" multiple accept="image/*" required
                                class="input w-full text-sm
                                    file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[var(--accent-soft)]
                                    file:text-[var(--accent)] file:text-xs file:font-semibold file:cursor-pointer">
                            <p class="text-xs muted mt-1">Select one or more images (max 5MB each)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="send" class="w-5 h-5"></i>
                Submit Visit Details
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
const customers = @json($customers);

function addVisitItem() {
    const container = document.getElementById('visitItems');
    const div = document.createElement('div');
    div.className = 'visit-item soft-panel !rounded-2xl p-4 relative';
    div.dataset.index = itemIndex;

    let options = '<option value="">-- Select Customer --</option>';
    customers.forEach(c => {
        options += `<option value="${c.id}">${c.name}</option>`;
    });

    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <span class="visit-item-title text-sm font-semibold text-[var(--text-soft)] flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] text-xs font-bold flex items-center justify-center border border-[var(--border)]">${itemIndex + 1}</span>
                Customer Visit ${itemIndex + 1}
            </span>
            <button type="button" onclick="removeVisitItem(this)"
                class="w-7 h-7 rounded-full bg-red-500/10 text-red-500 text-xs flex items-center justify-center hover:bg-red-500/20 border border-[var(--border)] transition">
                <i data-lucide="x" class="w-3 h-3"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="label !text-xs">Select Customer <span class="text-red-500">*</span></label>
                <select name="items[${itemIndex}][customer_id]" required
                    class="select w-full customer-select">
                    ${options}
                </select>
            </div>
            <div>
                <label class="label !text-xs">Note</label>
                <textarea name="items[${itemIndex}][note]" rows="2"
                    class="textarea w-full resize-none"
                    placeholder="Note for this customer visit..."></textarea>
            </div>
            <div>
                <label class="label !text-xs">Photos <span class="text-red-500">*</span></label>
                <input type="file" name="items[${itemIndex}][images][]" multiple accept="image/*" required
                    class="input w-full text-sm
                        file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[var(--accent-soft)]
                        file:text-[var(--accent)] file:text-xs file:font-semibold file:cursor-pointer">
                <p class="text-xs muted mt-1">Select one or more images (max 5MB each)</p>
            </div>
        </div>
    `;

    container.appendChild(div);
    itemIndex++;
    initCustomerSearch();
}

function removeVisitItem(btn) {
    const item = btn.closest('.visit-item');
    if (document.querySelectorAll('.visit-item').length > 1) {
        item.remove();
        renumberItems();
    }
}

function renumberItems() {
    const items = document.querySelectorAll('.visit-item');
    items.forEach((item, idx) => {
        item.dataset.index = idx;
        const badge = item.querySelector('span.w-6');
        if (badge) badge.textContent = idx + 1;
        const label = item.querySelector('.visit-item-title');
        if (label) {
            const textNode = label.childNodes[label.childNodes.length - 1];
            if (textNode) textNode.textContent = ` Customer Visit ${idx + 1}`;
        }
    });
}
</script>
@endsection
