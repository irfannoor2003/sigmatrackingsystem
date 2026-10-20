@extends('layouts.app')

@section('title', 'Monthly Visit Report')

@section('content')

@php
    $displayMonth = \Carbon\Carbon::createFromFormat('Y-m', $monthInput)->format('F Y');
@endphp

<div class="mt-10 p-0">

    {{-- Header --}}
    <div class="page-head !mb-0">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="calendar-range" class="w-6 h-6"></i></span>
            Monthly Visit Report – {{ $displayMonth }}
        </h1>
    </div>

    <x-filter-bar class="mt-4">
        <x-filter-field icon="calendar">
            <input type="month" name="month" value="{{ $monthInput }}" class="input">
        </x-filter-field>

        <x-filter-actions>
            <button onclick="window.print()" type="button" class="btn btn-outline w-full sm:w-auto">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Print
            </button>
        </x-filter-actions>
    </x-filter-bar>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="stat-card tinted tint-magenta text-center">
            <div class="stat-ico"><i data-lucide="map" class="w-5 h-5"></i></div>
            <div>
                <p class="stat-label">Total Visits</p>
                <p class="stat-value">{{ $totalVisits }}</p>
            </div>
        </div>

        <div class="stat-card tinted tint-blue text-center">
            <div class="stat-ico"><i data-lucide="route" class="w-5 h-5"></i></div>
            <div>
                <p class="stat-label">Total KM</p>
                <p class="stat-value">{{ $totalKm }} km</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card !rounded-xl overflow-x-auto">
        <div class="tbl-wrap !border-0 !shadow-none">
        <table class="tbl w-full text-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th class="text-center">KM</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $visit)
                    <tr>
                        <td class="text-[var(--text-soft)]">
                            {{ $visit->started_at->format('d M Y') }}
                        </td>
                        <td class="text-[var(--text)]">
                            {{ $visit->customer->name ?? '—' }}
                        </td>
                        <td class="text-center text-[var(--text-soft)]">
                            {{ $visit->km ?? 0 }}
                        </td>
                        <td class="text-[var(--text-muted)]">
                            {{ $visit->notes ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center muted">
                            No visits found for this month
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

{{-- PRINT STYLES --}}
<style>
@media print {
    body {
        background: white !important;
        color: black !important;
    }
    button, form, .page-head {
        display: none !important;
    }
    .card {
        background: white !important;
        border: 1px solid #ccc !important;
        box-shadow: none !important;
    }
}
</style>

@endsection