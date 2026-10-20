@extends('layouts.app')
@section('title', 'Out of City Visit Details')

@section('content')

@php
    $allImages = collect();
    foreach ($visit->items as $it) {
        if (!empty($it->images)) {
            foreach ($it->images as $img) { $allImages->push($img); }
        }
    }
@endphp

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="plane" class="w-6 h-6"></i></span>
            Out of City Visit Details
        </h1>
        <a href="{{ route('salesman.out-of-city-visits.index') }}" class="icon-btn" title="Back">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
    </div>


    <div class="card !rounded-3xl p-6 md:p-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">

            <div class="w-full">
                <h2 class="text-lg md:text-xl font-bold text-[var(--text)] flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[var(--accent)]"></i>
                    {{ $visit->salesman->name ?? 'Visit' }}
                </h2>

                <div class="space-y-3 mt-4">

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            Date
                        </span>
                        <span class="text-[var(--text)]">
                            {{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                            Status
                        </span>
                        <span>
                            @if ($visit->status === 'pending')
                                <span class="badge badge-amber uppercase">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    {{ ucfirst($visit->status) }}
                                </span>
                            @else
                                <span class="badge badge-emerald uppercase">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    {{ ucfirst($visit->status) }}
                                </span>
                            @endif
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            Clock In
                        </span>
                        <span class="text-[var(--text)]">
                            {{ optional($visit->attendance)->clock_in ? $visit->attendance->clock_in->format('h:i A') : '-' }}
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            Clock Out
                        </span>
                        <span class="text-[var(--text)]">
                            {{ optional($visit->attendance)->clock_out ? $visit->attendance->clock_out->format('h:i A') : '-' }}
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Submitted At
                        </span>
                        <span class="text-[var(--text)]">
                            {{ optional($visit->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            Customer Visits
                        </span>
                        <span class="badge badge-magenta">{{ $visit->items->count() }}</span>
                    </div>

                </div>

                @if ($visit->notes)
                    <div class="soft-panel px-4 py-3 text-sm md:text-base mt-3">
                        <div class="flex items-center gap-2 mb-2 text-[var(--text-muted)] font-semibold">
                            <i data-lucide="sticky-note" class="w-4 h-4"></i>
                            Overall Notes
                        </div>
                        <div class="text-[var(--text)] break-words whitespace-pre-line max-h-40 overflow-y-auto pr-1">
                            {{ $visit->notes }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="w-full">
                <h2 class="text-lg md:text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-[var(--accent)]"></i>
                    Customer Visits ({{ $visit->items->count() }})
                </h2>

                @forelse ($visit->items as $idx => $item)
                    <div class="soft-panel !rounded-2xl p-4 mb-4 last:mb-0">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] text-xs font-bold flex items-center justify-center border border-[var(--border)] shrink-0">{{ $idx + 1 }}</span>
                            <h3 class="text-[var(--text)] font-semibold flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-[var(--accent)]"></i>
                                {{ $item->customer->name ?? 'Unknown' }}
                            </h3>
                        </div>

                        @if ($item->note)
                            <div class="mb-3 flex items-start gap-2">
                                <i data-lucide="sticky-note"
                                    class="w-4 h-4 mt-0.5 text-[var(--text-muted)] shrink-0"></i>
                                <div>
                                    <span class="muted text-xs uppercase tracking-wide">Note:</span>
                                    <p class="text-[var(--text-soft)] text-sm mt-1">{{ $item->note }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($item->images && count($item->images) > 0)
                            <div class="flex items-center gap-2 mb-3">
                                <i data-lucide="image" class="w-4 h-4 text-sky-500"></i>
                                <span class="muted text-xs uppercase tracking-wide">Images</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($item->images as $image)
                                    <a href="{{ asset($image) }}" target="_blank" class="block group">
                                        <img src="{{ asset($image) }}" alt="Visit image"
                                            class="w-full h-32 object-cover !rounded-lg border border-[var(--border)] group-hover:!border-[var(--accent)] transition">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="muted text-sm italic flex items-center gap-2">
                                <i data-lucide="image-off" class="w-4 h-4"></i>
                                No images uploaded
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="soft-panel !rounded-2xl text-center py-8">
                        <p class="muted flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            No visit details have been added yet.
                        </p>
                        @if ($visit->isPending())
                            <a href="{{ route('salesman.out-of-city-visits.create', $visit->id) }}" class="btn btn-soft mt-4">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Visit Details Now
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8 flex flex-col md:flex-row gap-3">
            <button id="showImagesBtn" class="btn btn-primary w-full md:w-auto">
                <i data-lucide="gallery-vertical" class="w-4 h-4"></i>
                Preview Document
            </button>

            <button id="closeImagesBtn" class="btn btn-danger w-full md:w-auto hidden">
                <i data-lucide="x-circle" class="w-4 h-4"></i>
                Close Preview
            </button>
        </div>

        <div id="imagesSection" class="mt-6 hidden">
            <h3 class="text-lg font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-[var(--accent)]"></i>
                Uploaded Images
            </h3>

            @if ($allImages->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5">
                    @foreach ($allImages as $img)
                        <div
                            class="!rounded-xl overflow-hidden bg-[var(--surface-2)] border border-[var(--border)] cursor-pointer preview-image"
                            data-image="{{ asset($img) }}">
                            <img src="{{ asset($img) }}" class="w-full h-32 sm:h-40 object-cover">
                        </div>
                    @endforeach
                </div>
            @else
                <p class="muted text-lg">No images available</p>
            @endif
        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.out-of-city-visits.index') }}" class="btn btn-dark">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Out of City Visits
            </a>
        </div>

    </div>

    <div id="imagePreviewModal" class="hidden items-center justify-center bg-black/70 backdrop-blur-xl p-4"
        style="position:fixed !important;inset:0;z-index:9999;">

        <div class="relative w-full max-w-3xl mx-auto card !rounded-3xl p-4 shadow-[var(--shadow-lg)]">
            <button id="closePreview" class="icon-btn absolute top-3 right-3 !bg-black/50 !text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <img id="previewImage" src="" class="w-full max-h-[85vh] object-contain !rounded-2xl">
        </div>
    </div>

    <script>
        const imagesSection = document.getElementById('imagesSection');
        const showBtn = document.getElementById('showImagesBtn');
        const closeBtn = document.getElementById('closeImagesBtn');

        showBtn && showBtn.addEventListener('click', () => {
            imagesSection.classList.remove('hidden');
            showBtn.classList.add('hidden');
            closeBtn.classList.remove('hidden');
        });
        closeBtn && closeBtn.addEventListener('click', () => {
            imagesSection.classList.add('hidden');
            showBtn.classList.remove('hidden');
            closeBtn.classList.add('hidden');
        });

        document.querySelectorAll(".preview-image").forEach(imgBox => {
            imgBox.addEventListener("click", () => {
                document.getElementById("previewImage").src = imgBox.getAttribute("data-image");
                const modal = document.getElementById("imagePreviewModal");
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        });
        document.getElementById("closePreview").addEventListener("click", () => {
            const modal = document.getElementById("imagePreviewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });
        document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
            if (e.target.id === "imagePreviewModal") {
                const modal = document.getElementById("imagePreviewModal");
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        });
    </script>

</div>

@endsection
