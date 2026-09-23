@extends('layouts.app')
@section('title', 'Add Out of City Visit Details')

@section('content')
<div class="p-0">
    <div class="flex items-center mb-6 gap-4">
        <a href="{{ route('salesman.out-of-city-visits.index') }}"
            class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-white">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-3xl font-bold text-white tracking-wide flex items-center">
            <i data-lucide="plus" class="w-8 h-8 mr-3 text-[#ff2ba6]"></i>
            Add Visit Details
        </h1>
    </div>

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-400/40 text-red-100 rounded-xl backdrop-blur-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Visit Info Card --}}
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-2">Attendance Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-white/50">Date:</span>
                <span class="text-white font-medium">{{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-white/50">Clock In:</span>
                <span class="text-white font-medium">{{ optional($visit->attendance)->clock_in ? $visit->attendance->clock_in->format('h:i A') : '-' }}</span>
            </div>
            <div>
                <span class="text-white/50">Clock Out:</span>
                <span class="text-white font-medium">{{ optional($visit->attendance)->clock_out ? $visit->attendance->clock_out->format('h:i A') : '-' }}</span>
            </div>
        </div>
        @if ($visit->attendance && $visit->attendance->note)
            <div class="mt-3 text-sm">
                <span class="text-white/50">Note:</span>
                <span class="text-white">{{ $visit->attendance->note }}</span>
            </div>
        @endif
    </div>

    {{-- Form --}}
    <form action="{{ route('salesman.out-of-city-visits.store') }}" method="POST" enctype="multipart/form-data" id="visitForm">
        @csrf
        <input type="hidden" name="out_of_city_visit_id" value="{{ $visit->id }}">

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-white mb-4">Overall Notes</h2>
            <textarea name="notes" rows="2"
                class="w-full bg-white/10 text-white placeholder-white/50 p-3 rounded-xl border border-white/20
                    outline-none focus:bg-white/20 text-sm transition resize-none"
                placeholder="Optional overall notes for this out of city visit...">{{ old('notes') }}</textarea>
        </div>

        {{-- Visit Items --}}
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Customer Visits</h2>
                <button type="button" onclick="addVisitItem()"
                    class="px-4 py-2 rounded-lg bg-[#ff2ba6]/20 border border-[#ff2ba6]/40
                        text-[#ff2ba6] text-sm font-semibold hover:bg-[#ff2ba6]/30 transition flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Customer
                </button>
            </div>

            <div id="visitItems" class="space-y-4">
                {{-- First item is always present --}}
                <div class="visit-item bg-white/5 border border-white/10 rounded-xl p-4 relative" data-index="0">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-white/70 text-sm font-semibold flex items-center gap-1.5">
                            <span class="w-6 h-6 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-xs font-bold flex items-center justify-center border border-[#ff2ba6]/30">1</span>
                            Customer Visit 1
                        </span>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="text-white/60 text-xs mb-1 block">Select Customer <span class="text-red-400">*</span></label>
                            <select name="items[0][customer_id]" required
                                class="w-full bg-white/10 text-white p-2.5 rounded-lg border border-white/20
                                    outline-none focus:bg-white/20 text-sm transition customer-select">
                                <option value="" class="text-black">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" class="text-black">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-white/60 text-xs mb-1 block">Note</label>
                            <textarea name="items[0][note]" rows="2"
                                class="w-full bg-white/10 text-white placeholder-white/50 p-2.5 rounded-lg border border-white/20
                                    outline-none focus:bg-white/20 text-sm transition resize-none"
                                placeholder="Note for this customer visit..."></textarea>
                        </div>
                        <div>
                            <label class="text-white/60 text-xs mb-1 block">Photos <span class="text-red-400">*</span></label>
                            <input type="file" name="items[0][images][]" multiple accept="image/*" required
                                class="w-full text-white bg-white/10 p-2.5 rounded-lg border border-white/20 text-sm
                                    file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                                    file:bg-[#ff2ba6]/30 file:text-[#ff2ba6] file:text-sm file:font-semibold
                                    hover:file:bg-[#ff2ba6]/40 file:cursor-pointer">
                            <p class="text-white/40 text-xs mt-1">Select one or more images (max 5MB each)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-8 py-3 rounded-xl text-white font-semibold
                    bg-gradient-to-r from-[#d6007b] to-[#ff2ba6]
                    shadow-lg hover:opacity-90 transition flex items-center gap-2">
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
    div.className = 'visit-item bg-white/5 border border-white/10 rounded-xl p-4 relative';
    div.dataset.index = itemIndex;

    let options = '<option value="" class="text-black">-- Select Customer --</option>';
    customers.forEach(c => {
        options += `<option value="${c.id}" class="text-black">${c.name}</option>`;
    });

    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <span class="text-white/70 text-sm font-semibold flex items-center gap-1.5">
                <span class="w-6 h-6 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-xs font-bold flex items-center justify-center border border-[#ff2ba6]/30">${itemIndex + 1}</span>
                Customer Visit ${itemIndex + 1}
            </span>
            <button type="button" onclick="removeVisitItem(this)"
                class="w-7 h-7 rounded-full bg-red-500/20 text-red-400 text-xs flex items-center justify-center hover:bg-red-500/30 border border-red-400/30 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="text-white/60 text-xs mb-1 block">Select Customer <span class="text-red-400">*</span></label>
                <select name="items[${itemIndex}][customer_id]" required
                    class="w-full bg-white/10 text-white p-2.5 rounded-lg border border-white/20
                        outline-none focus:bg-white/20 text-sm transition customer-select">
                    ${options}
                </select>
            </div>
            <div>
                <label class="text-white/60 text-xs mb-1 block">Note</label>
                <textarea name="items[${itemIndex}][note]" rows="2"
                    class="w-full bg-white/10 text-white placeholder-white/50 p-2.5 rounded-lg border border-white/20
                        outline-none focus:bg-white/20 text-sm transition resize-none"
                    placeholder="Note for this customer visit..."></textarea>
            </div>
            <div>
                <label class="text-white/60 text-xs mb-1 block">Photos <span class="text-red-400">*</span></label>
                <input type="file" name="items[${itemIndex}][images][]" multiple accept="image/*" required
                    class="w-full text-white bg-white/10 p-2.5 rounded-lg border border-white/20 text-sm
                        file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                        file:bg-[#ff2ba6]/30 file:text-[#ff2ba6] file:text-sm file:font-semibold
                        hover:file:bg-[#ff2ba6]/40 file:cursor-pointer">
                <p class="text-white/40 text-xs mt-1">Select one or more images (max 5MB each)</p>
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
        const label = item.querySelector('.text-white\\/70');
        if (label) {
            const textNode = label.childNodes[label.childNodes.length - 1];
            if (textNode) textNode.textContent = ` Customer Visit ${idx + 1}`;
        }
    });
}
</script>
@endsection
