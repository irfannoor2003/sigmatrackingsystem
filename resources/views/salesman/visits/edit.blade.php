@extends('layouts.app')

@section('title', 'Edit Visit')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-wrapper .ts-control {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        border-radius: .75rem !important;
        padding: .75rem 1rem !important;
        transition: all .2s ease;
    }

    .ts-wrapper.focus .ts-control {
        background: var(--surface) !important;
        box-shadow: 0 0 0 3px var(--accent-soft) !important;
    }

    .ts-dropdown {
        background: var(--surface) !important;
        color: var(--text) !important;
        border: 1px solid var(--border) !important;
        border-radius: .75rem !important;
        margin-top: 5px !important;
        box-shadow: var(--shadow-lg) !important;
    }

    .ts-dropdown .option {
        padding: .6rem .75rem !important;
        color: var(--text-soft) !important;
    }

    .ts-dropdown .active {
        background: var(--accent-soft) !important;
        color: var(--text) !important;
    }

    .ts-dropdown .active .item {
        background: var(--accent) !important;
        color: #fff !important;
    }

    .ts-control input {
        color: var(--text) !important;
    }

    .ts-wrapper.single .ts-control::after {
        border-color: var(--text-muted) transparent transparent transparent !important;
    }
</style>

<div class="max-w-xl mx-auto">

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="pencil" class="w-6 h-6"></i></span>
            Edit Visit
        </h1>
    </div>

    <div class="card !rounded-2xl p-6 md:p-8">

        <form method="POST" action="{{ route('salesman.visits.update', $visit->id) }}">
            @csrf
            @method('PUT')

            <label class="label flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-[var(--accent)]"></i>
                Select Customer
            </label>

            <div class="mb-5">
                <select name="customer_id" id="customer_search" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $visit->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label class="label flex items-center gap-2">
                <i data-lucide="target" class="w-4 h-4 text-[var(--accent)]"></i>
                Purpose of Visit
            </label>

            <select name="purpose" class="select w-full mb-4" required>
                @foreach([
                    'Complaint Visit','Delivery','Follow-up','New Lead Visit',
                    'Order Taking','Office Work','Product Details',
                    'Payment Collection','Recovery'
                ] as $purpose)
                    <option value="{{ $purpose }}"
                        {{ $visit->purpose === $purpose ? 'selected' : '' }}>
                        {{ $purpose }}
                    </option>
                @endforeach
            </select>

            <label class="label">KM / Distance</label>
            <input type="number" step="0.1" name="distance_km" value="{{ $visit->distance_km }}"
                class="input w-full mb-4">

            <label class="label">Visit Note</label>
            <textarea name="notes" rows="3" class="textarea w-full mb-6"
                placeholder="Enter visit notes...">{{ $visit->notes }}</textarea>

            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="check" class="w-4 h-4"></i>
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
