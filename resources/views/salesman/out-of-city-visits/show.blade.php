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

<div class="max-w-4xl mx-auto mt-10 p-0">
 <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 md:p-8">

        <div class="flex items-center mb-6 gap-4">
            <a href="{{ route('salesman.out-of-city-visits.index') }}"
                class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-white flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <h2 class="text-2xl md:text-3xl font-bold text-white tracking-wide flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3 text-[#ff2ba6]"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                Out of City Visit Details
            </h2>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-500/20 border border-green-400/40 text-green-100 rounded-xl backdrop-blur-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M20 6 9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
            <div class="w-full">

                <h3 class="text-xl md:text-2xl font-semibold text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-indigo-300"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ $visit->salesman->name ?? 'Visit' }}
                </h3>

                <div class="space-y-3 mt-4">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            Date
                        </span>
                        <span class="text-white">
                            {{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            Status
                        </span>
                        <span class="text-white capitalize">
                            @if ($visit->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-yellow-500/20 border border-yellow-400/40 text-yellow-200 uppercase">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                    {{ ucfirst($visit->status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-green-500/20 border border-green-400/40 text-green-200 uppercase">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    {{ ucfirst($visit->status) }}
                                </span>
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Clock In
                        </span>
                        <span class="text-white">
                            {{ optional($visit->attendance)->clock_in ? $visit->attendance->clock_in->format('h:i A') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Clock Out
                        </span>
                        <span class="text-white">
                            {{ optional($visit->attendance)->clock_out ? $visit->attendance->clock_out->format('h:i A') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M20 6 9 17l-5-5"/></svg>
                            Submitted At
                        </span>
                        <span class="text-white">
                            {{ optional($visit->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><path d="M4 20h16"/><path d="M16 8h-4"/><path d="M12 8h-2"/></svg>
                            Customer Visits
                        </span>
                        <span class="text-white font-semibold">{{ $visit->items->count() }}</span>
                    </div>

                </div>

                @if ($visit->notes)
                    <div class="bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base mt-3">
                        <div class="flex items-center mb-2 text-white/70">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/><path d="M15 2v4a2 2 0 0 0 2 2h4"/></svg>
                            Overall Notes
                        </div>
                        <div class="text-white break-words whitespace-pre-line max-h-40 overflow-y-auto pr-1">
                            {{ $visit->notes }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="w-full">
                <h3 class="text-xl md:text-2xl font-semibold text-white mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-[#ff2ba6]"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><path d="M4 20h16"/><path d="M16 8h-4"/><path d="M12 8h-2"/></svg>
                    Customer Visits ({{ $visit->items->count() }})
                </h3>

                @forelse ($visit->items as $idx => $item)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-4 last:mb-0">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-xs font-bold flex items-center justify-center border border-[#ff2ba6]/30 shrink-0">{{ $idx + 1 }}</span>
                            <h4 class="text-white font-semibold flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-pink-300"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ $item->customer->name ?? 'Unknown' }}
                            </h4>
                        </div>

                        @if ($item->note)
                            <div class="mb-3 flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 text-white/60 shrink-0"><path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/><path d="M15 2v4a2 2 0 0 0 2 2h4"/></svg>
                                <div>
                                    <span class="text-white/50 text-xs uppercase tracking-wide">Note:</span>
                                    <p class="text-white/80 text-sm mt-1">{{ $item->note }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($item->images && count($item->images) > 0)
                            <div class="flex items-center gap-2 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-400"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <span class="text-white/50 text-xs uppercase tracking-wide">Images</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($item->images as $image)
                                    <a href="{{ asset($image) }}" target="_blank" class="block group">
                                        <img src="{{ asset($image) }}" alt="Visit image"
                                            class="w-full h-32 object-cover rounded-lg border border-white/10 group-hover:border-[#ff2ba6]/50 transition">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-white/40 text-sm italic flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/><line x1="3" x2="21" y1="3" y2="21"/></svg>
                                No images uploaded
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 bg-white/5 rounded-xl">
                        <p class="text-white/50 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            No visit details have been added yet.
                        </p>
                        @if ($visit->isPending())
                            <a href="{{ route('salesman.out-of-city-visits.create', $visit->id) }}"
                                class="mt-4 inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-[#ff2ba6]/20 border border-[#ff2ba6]/40 text-[#ff2ba6] font-semibold hover:bg-[#ff2ba6]/30 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                Add Visit Details Now
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row gap-4">
            <button id="showImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-[#ff2ba6]/80 hover:bg-[#ff2ba6]
                       text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect width="18" height="20" x="3" y="2" rx="2"/><path d="M8.5 7.5h7"/><path d="M10.5 10.5h3"/><path d="M12 17v-6"/></svg>
                Preview Document
            </button>

            <button id="closeImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-red-600/70 hover:bg-red-600
                       text-white font-semibold rounded-xl shadow-lg transition hidden flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                Close Preview
            </button>
        </div>

        <div id="imagesSection" class="mt-6 hidden">
            <h3 class="text-2xl font-semibold text-white mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-white/80"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                Uploaded Images
            </h3>

            @if ($allImages->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5">
                    @foreach ($allImages as $img)
                        <div class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer preview-image" data-image="{{ asset($img) }}">
                            <img src="{{ asset($img) }}" class="w-full h-32 sm:h-40 object-cover">
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-lg">No images available</p>
            @endif
        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.out-of-city-visits.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg transition-all duration-200
                      flex items-center justify-center md:inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to Out of City Visits
            </a>
        </div>

    </div>
</div>

<!-- FULL SCREEN IMAGE MODAL -->
<div id="imagePreviewModal"
     class="hidden items-center justify-center bg-black/70 backdrop-blur-xl p-4"
     style="position:fixed !important;inset:0;z-index:9999;">

    <div class="relative w-full max-w-3xl mx-auto bg-white/10 border border-white/20 backdrop-blur-2xl rounded-3xl shadow-2xl p-4">
        <button id="closePreview"
            class="absolute top-3 right-3 bg-black/50 hover:bg-black/70 text-white px-3 py-1 rounded-lg text-xl transition">✕</button>
        <img id="previewImage" src="" class="w-full max-h-[85vh] object-contain rounded-2xl shadow-xl">
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }

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

@endsection
