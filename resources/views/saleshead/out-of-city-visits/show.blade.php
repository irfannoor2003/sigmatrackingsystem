@extends('layouts.app')

@section('title', 'Out of City Visit Details')

@section('content')

<div class="p-0">

    <div class="page-head !mb-6">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
            Out of City Visit Details
        </h1>
        <a href="{{ route('salehead.out-of-city-visits.index') }}"
            class="btn btn-ghost btn-sm shrink-0 !mt-3 md:!mt-0">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back
        </a>
    </div>

    <div class="card !rounded-2xl p-6 shadow-card-lg">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-[var(--text-soft)]">

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-[var(--accent)]"></i>
                    <strong class="text-[var(--text)] mr-2">Salesman:</strong> {{ $visit->salesman->name ?? '-' }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="calendar" class="w-5 h-5 mr-2 text-indigo-400"></i>
                    <strong class="text-[var(--text)] mr-2">Date:</strong>
                    {{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="building-2" class="w-5 h-5 mr-2 text-sky-400"></i>
                    <strong class="text-[var(--text)] mr-2">Customer Visits:</strong> {{ $visit->items->count() }}
                </p>
            </div>

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-2 text-[var(--text-soft)]"></i>
                    <strong class="text-[var(--text)] mr-2">Status:</strong>
                    @if ($visit->status === 'pending')
                        <span class="badge badge-amber">
                            <i data-lucide="loader-2" class="w-3 h-3 mr-1"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @else
                        <span class="badge badge-emerald">
                            <i data-lucide="check-square" class="w-3 h-3 mr-1"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @endif
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2 text-[var(--text-soft)]"></i>
                    <strong class="text-[var(--text)] mr-2">Submitted At:</strong>
                    {{ optional($visit->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                </p>
            </div>

        </div>

        @if ($visit->notes)
            <div class="mt-6">
                <p class="text-[var(--text)] mb-2 font-semibold text-lg flex items-center">
                    <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-[var(--text-soft)]"></i> Overall Notes:
                </p>
                <div class="soft-panel p-4 rounded-xl border border-[var(--border)]">
                    {{ $visit->notes }}
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4 text-[var(--text)] flex items-center">
            <i data-lucide="building-2" class="w-6 h-6 mr-2 text-[var(--accent)]"></i>
            Customer Visits ({{ $visit->items->count() }})
        </h2>

        @forelse ($visit->items as $idx => $item)
            <div class="card !rounded-2xl p-5 mb-4 border border-[var(--border)] shadow-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-7 h-7 rounded-full bg-[var(--accent-softer)] text-[var(--accent)] text-xs font-bold flex items-center justify-center border border-[var(--accent-soft)] shrink-0">{{ $idx + 1 }}</span>
                    <h3 class="text-[var(--text)] font-semibold flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-2 text-[var(--accent)]"></i>
                        {{ $item->customer->name ?? 'Unknown' }}
                    </h3>
                </div>

                @if ($item->note)
                    <div class="mb-3 flex items-start gap-2">
                        <i data-lucide="sticky-note" class="w-4 h-4 mt-0.5 text-[var(--text-soft)] shrink-0"></i>
                        <div>
                            <span class="text-[var(--text-muted)] text-xs uppercase tracking-wide">Note:</span>
                            <p class="text-[var(--text-soft)] text-sm mt-1">{{ $item->note }}</p>
                        </div>
                    </div>
                @endif

                @if ($item->images && count($item->images) > 0)
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="image" class="w-4 h-4 text-sky-400"></i>
                        <span class="text-[var(--text-muted)] text-xs uppercase tracking-wide">Images</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($item->images as $image)
                            <a href="{{ asset($image) }}" target="_blank" class="block group">
                                <img src="{{ asset($image) }}" alt="Visit image"
                                    class="w-full h-32 object-cover rounded-lg border border-[var(--border)] group-hover:border-[var(--accent-soft)] transition">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-muted)] text-sm italic flex items-center gap-2">
                        <i data-lucide="image-off" class="w-4 h-4"></i> No images uploaded
                    </p>
                @endif
            </div>
        @empty
            <div class="text-center py-8 soft-panel rounded-xl">
                <p class="muted flex items-center justify-center gap-2">
                    <i data-lucide="search-x" class="w-5 h-5"></i> No visit details added yet.
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 text-center md:text-right">
        <a href="{{ route('salehead.out-of-city-visits.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Out of City Visits
        </a>
    </div>

</div>

@endsection