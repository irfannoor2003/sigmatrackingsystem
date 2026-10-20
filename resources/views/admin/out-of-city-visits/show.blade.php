@extends('layouts.app')

@section('title', 'Out of City Visit Details')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
            Out of City Visit Details
        </h1>
    </div>

    <div class="card !rounded-2xl p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="mb-3 flex items-center text-[var(--text-soft)]">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                    <strong class="text-[var(--text)] mr-2">Salesman:</strong> {{ $visit->salesman->name ?? '-' }}
                </p>
                <p class="mb-3 flex items-center text-[var(--text-soft)]">
                    <i data-lucide="calendar" class="w-5 h-5 mr-2 text-sky-500"></i>
                    <strong class="text-[var(--text)] mr-2">Date:</strong>
                    {{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}
                </p>
                <p class="mb-3 flex items-center text-[var(--text-soft)]">
                    <i data-lucide="info" class="w-5 h-5 mr-2 muted"></i>
                    <strong class="text-[var(--text)] mr-2">Status:</strong>
                    @if ($visit->status === 'pending')
                        <span class="badge badge-amber">
                            <i data-lucide="loader-2" class="w-3 h-3"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @else
                        <span class="badge badge-emerald">
                            <i data-lucide="check-square" class="w-3 h-3"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <p class="mb-3 flex items-center text-[var(--text-soft)]">
                    <i data-lucide="clock" class="w-5 h-5 mr-2 muted"></i>
                    <strong class="text-[var(--text)] mr-2">Submitted At:</strong>
                    {{ optional($visit->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                </p>
                <p class="mb-3 flex items-center text-[var(--text-soft)]">
                    <i data-lucide="building-2" class="w-5 h-5 mr-2 text-violet-500"></i>
                    <strong class="text-[var(--text)] mr-2">Customers Visited:</strong>
                    {{ $visit->items->count() }}
                </p>
            </div>

        </div>

        @if ($visit->notes)
            <div class="mt-6">
                <p class="text-[var(--text)] mb-2 font-semibold text-lg flex items-center">
                    <i data-lucide="sticky-note" class="w-5 h-5 mr-2 muted"></i> Overall Notes:
                </p>
                <div class="soft-panel p-4 rounded-xl">
                    {{ $visit->notes }}
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8">
        <h2 class="font-display font-semibold text-lg mb-3 text-[var(--text)] flex items-center">
            <i data-lucide="building-2" class="w-6 h-6 mr-2 text-[var(--accent)]"></i>
            Customer Visits ({{ $visit->items->count() }})
        </h2>

        @forelse ($visit->items as $idx => $item)
            <div class="card !rounded-2xl p-4 mb-4">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-7 h-7 rounded-full gradient-surface text-white text-xs font-bold flex items-center justify-center">
                        {{ $idx + 1 }}
                    </span>
                    <h3 class="text-[var(--text)] font-semibold">{{ $item->customer->name ?? 'Unknown' }}</h3>
                </div>
                @if ($item->note)
                    <p class="mb-3 flex items-center text-[var(--text-soft)] text-sm">
                        <i data-lucide="sticky-note" class="w-4 h-4 mr-2 muted"></i>
                        {{ $item->note }}
                    </p>
                @endif
                @if ($item->images && count($item->images) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($item->images as $image)
                            <a href="{{ asset($image) }}" target="_blank" class="block group">
                                <img src="{{ asset($image) }}" alt="Visit image"
                                    class="w-full h-32 object-cover rounded-lg border border-[var(--border)] group-hover:border-[var(--accent)] transition">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="muted text-sm italic">No images uploaded</p>
                @endif
            </div>
        @empty
            <div class="card !rounded-2xl p-6 text-center">
                <p class="muted">No visit details added yet. The salesman hasn't submitted details for this visit.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 text-center md:text-right">
        <a href="{{ route('admin.out-of-city-visits.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Out of City Visits
        </a>
    </div>

</div>

@endsection