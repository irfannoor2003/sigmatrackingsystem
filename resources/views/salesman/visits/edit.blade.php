@extends('layouts.app')

@section('title', 'Edit Visit')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-wrapper .ts-control {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-radius: 0.5rem !important;
        padding: 12px !important;
    }
    .ts-wrapper.focus .ts-control {
        background: rgba(255, 255, 255, 0.2) !important;
        box-shadow: none !important;
    }
    .ts-dropdown {
        background: #1e1e1e !important;
        color: white !important;
        border-radius: 0.5rem !important;
    }
    .ts-dropdown .active {
        background: #ff2ba6 !important;
    }
    .ts-control input {
        color: white !important;
    }
</style>

<div class="max-w-xl mx-auto p-0">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="mr-3 text-[#ff2ba6]">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
        </svg>
        Edit Visit
    </h1>

    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-2xl shadow-xl">

        <form method="POST" action="{{ route('salesman.visits.update', $visit->id) }}">
            @csrf
            @method('PUT')

            {{-- Customer --}}
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="mr-2">
                    <rect width="16" height="20" x="4" y="2" rx="2"/>
                    <path d="M9 22v-4h6v4"/>
                </svg>
                Select Customer
            </label>

            <div class="mb-4">
                <select name="customer_id" id="customer_search" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $visit->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Purpose --}}
            <label class="block text-sm text-white/80 mb-1 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="mr-2">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                Purpose of Visit
            </label>

            <select name="purpose"
                    class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white outline-none"
                    required>
                @foreach([
                    'Complaint Visit','Delivery','Follow-up','New Lead Visit',
                    'Order Taking','Office Work','Product Details',
                    'Payment Collection','Recovery'
                ] as $purpose)
                    <option value="{{ $purpose }}"
                        {{ $visit->purpose === $purpose ? 'selected' : '' }}
                        class="text-black">
                        {{ $purpose }}
                    </option>
                @endforeach
            </select>

            {{-- KM --}}
            <label class="block text-sm text-white/80 mb-1">
                KM / Distance
            </label>
            <input type="number" step="0.1" name="distance_km"
                   value="{{ $visit->distance_km }}"
                   class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white outline-none">

            {{-- Note --}}
            <label class="block text-sm text-white/80 mb-1">
                Visit Note
            </label>
            <textarea name="notes" rows="3"
                      class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white outline-none"
                      placeholder="Enter visit notes...">{{ $visit->notes }}</textarea>

            <button type="submit"
                    class="w-full py-3 rounded-xl text-white font-semibold tracking-wide
                           flex items-center justify-center
                           bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                           shadow-lg hover:opacity-90 transition">
                Update Visit
            </button>

        </form>
    </div>
</div>

<script>
new TomSelect("#customer_search",{
    create: false,
    sortField: { field: "text", direction: "asc" }
});
</script>
@endsection
