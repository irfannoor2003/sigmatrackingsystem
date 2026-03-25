@extends('layouts.app')

@section('title', 'Monthly Visit Report')

@section('content')

@php
    $displayMonth = \Carbon\Carbon::createFromFormat('Y-m', $monthInput)->format('F Y');
@endphp

<div class="max-w-6xl mx-auto mt-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-extrabold text-white">
            Monthly Visit Report – {{ $displayMonth }}
        </h1>

        <form method="GET" class="flex gap-3">
            <input type="month"
                   name="month"
                   value="{{ $monthInput }}"
                   class="px-4 py-2 rounded-xl bg-black/40 text-white border border-white/20">

            <button class="px-4 py-2 rounded-xl bg-pink-600 text-white">
                Filter
            </button>

            <button onclick="window.print()"
                    type="button"
                    class="px-4 py-2 rounded-xl bg-gray-700 text-white">
                Print
            </button>

        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="glass p-4 rounded-xl text-center">
            <p class="text-sm text-gray-300">Total Visits</p>
            <p class="text-3xl font-bold text-white">{{ $totalVisits }}</p>
        </div>

        <div class="glass p-4 rounded-xl text-center">
            <p class="text-sm text-gray-300">Total KM</p>
            <p class="text-3xl font-bold text-white">{{ $totalKm }} km</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto glass rounded-xl">
        <table class="w-full text-sm text-white">
            <thead class="bg-white/10">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-center">KM</th>
                    <th class="p-3 text-left">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $visit)
                    <tr class="border-b border-white/10">
                        <td class="p-3">
                            {{ $visit->started_at->format('d M Y') }}
                        </td>
                        <td class="p-3">
                            {{ $visit->customer->name ?? '—' }}
                        </td>
                        <td class="p-3 text-center">
                            {{ $visit->km ?? 0 }}
                        </td>
                        <td class="p-3">
                            {{ $visit->notes ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400">
                            No visits found for this month
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PRINT STYLES --}}
<style>
@media print {
    body {
        background: white !important;
        color: black !important;
    }
    button, form {
        display: none !important;
    }
    .glass {
        background: white !important;
        border: 1px solid #ccc;
    }
}
</style>

@endsection
