@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="max-w-4xl mx-auto mt-10 p-0"> <div class="bg-white/10 backdrop-blur-xl border border-white/20
                 rounded-3xl shadow-2xl p-6 md:p-8"> <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
            {{-- Lucide Icon: file-text --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-3 text-[#ff2ba6]">
                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/>
                <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                <path d="M10 9H8"/>
                <path d="M16 13H8"/>
                <path d="M16 17H8"/>
            </svg>
            Visit Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10"> <div class="w-full">

                <h3 class="text-xl md:text-2xl font-semibold text-white flex items-center">
                    {{-- Lucide Icon: user --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-2 text-indigo-300">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    {{ $visit->customer->name }}
                </h3>

                <p class="text-indigo-200 font-medium mb-4 capitalize flex items-center">
                    {{-- Lucide Icon: target --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target mr-1">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="6"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                    Visit Purpose: {{ $visit->purpose }}
                </p>

                <div class="space-y-3">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: activity --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity mr-2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                            Status
                        </span>
                        <span class="text-white capitalize">
                            {{ $visit->status }}
                        </span>
                    </div>

           <div class="bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
    <div class="flex items-center mb-2 text-white/70">
        {{-- Lucide Icon: sticky-note --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-sticky-note mr-2">
            <path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/>
            <path d="M15 2v4a2 2 0 0 0 2 2h4"/>
        </svg>
        Notes
    </div>

    <div class="text-white break-words whitespace-pre-line max-h-40 overflow-y-auto pr-1">
        {{ $visit->notes ?? 'N/A' }}
    </div>
</div>

<div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
    <span class="text-white/70 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-map-pin mr-2 ">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>
        Distance (KM)
    </span>
    <span class="text-white font-semibold">
        {{ $visit->distance_km }}
    </span>
</div>


                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70 flex items-center">
                            {{-- Lucide Icon: calendar-check --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check mr-2">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                                <path d="m9 16 2 2 4-4"/>
                            </svg>
                            Visited On
                        </span>
                        <span class="text-white">
                            {{ $visit->created_at->format('d M, Y h:i A') }}
                        </span>
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-10 flex flex-col md:flex-row gap-4"> <button id="showImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-[#ff2ba6]/80 hover:bg-[#ff2ba6]
                       text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center">
                {{-- Lucide Icon: gallery-vertical --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gallery-vertical mr-2">
                    <rect width="18" height="20" x="3" y="2" rx="2"/>
                    <path d="M8.5 7.5h7"/>
                    <path d="M10.5 10.5h3"/>
                    <path d="M12 17v-6"/>
                </svg>
                Preview Document
            </button>

            <button id="closeImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-red-600/70 hover:bg-red-600
                       text-white font-semibold rounded-xl shadow-lg transition hidden flex items-center justify-center">
                {{-- Lucide Icon: x-circle --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m15 9-6 6"/>
                    <path d="m9 9 6 6"/>
                </svg>
                Close Preview
            </button>
        </div>

        <div id="imagesSection" class="mt-6 hidden">

            <h3 class="text-2xl font-semibold text-white mb-4 flex items-center">
                {{-- Lucide Icon: image-down --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-down mr-2 text-white/80">
                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6.3"/>
                    <path d="M3 16l5-5c.9-.9 2.1-1.1 3.2-.7l1.7.6c.4.1.8.1 1.2 0l3.2-1.2"/>
                    <path d="m22 17-3 3-3-3"/>
                    <path d="M19 22v-5"/>
                    <circle cx="10" cy="8" r="2"/>
                </svg>
                Uploaded Images
            </h3>

            @if($visit->images && count($visit->images))
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5"> @foreach($visit->images as $img)
                        <div
                            class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer preview-image"
                            data-image="{{ asset($img) }}"
                        >
                            <img src="{{ asset($img) }}"
                                 class="w-full h-32 sm:h-40 object-cover"> </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-lg">No images available</p>
            @endif

        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.visits.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg
                      transition-all duration-200 flex items-center justify-center md:inline-flex">
                {{-- Lucide Icon: arrow-left --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left mr-2">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                Back to Visits
            </a>
        </div>

    </div>

</div>

<!-- FULL SCREEN IMAGE MODAL -->
<div id="imagePreviewModal"
     class="fixed inset-0 hidden items-center justify-center z-[9999]
            bg-black/70 backdrop-blur-xl p-4">

    <div class="relative w-full max-w-3xl mx-auto
                bg-white/10 border border-white/20 backdrop-blur-2xl
                rounded-3xl shadow-2xl p-4 animate-fadeIn">

        <!-- Close Button -->
        <button id="closePreview"
            class="absolute top-3 right-3 bg-black/50 hover:bg-black/70
                   text-white px-3 py-1 rounded-lg text-xl transition">
            ✕
        </button>

        <!-- Centered Image -->
        <img id="previewImage"
             src=""
             class="w-full max-h-[85vh] object-contain rounded-2xl shadow-xl">
    </div>
</div>


<script>

    const imagesSection = document.getElementById('imagesSection');
    const showBtn = document.getElementById('showImagesBtn');
    const closeBtn = document.getElementById('closeImagesBtn');

    // SHOW IMAGES
    showBtn.addEventListener('click', () => {
        imagesSection.classList.remove('hidden');
        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    });

    // CLOSE IMAGES
    closeBtn.addEventListener('click', () => {
        imagesSection.classList.add('hidden');
        showBtn.classList.remove('hidden');
        closeBtn.classList.add('hidden');
    });

    // FULL SCREEN PREVIEW
document.querySelectorAll(".preview-image").forEach(imgBox => {
    imgBox.addEventListener("click", () => {
        const src = imgBox.getAttribute("data-image");
        document.getElementById("previewImage").src = src;

        const modal = document.getElementById("imagePreviewModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    });
});

// CLOSE BUTTON
document.getElementById("closePreview").addEventListener("click", () => {
    const modal = document.getElementById("imagePreviewModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
});

// CLOSE WHEN CLICKING OUTSIDE IMAGE
document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
    if (e.target.id === "imagePreviewModal") {
        const modal = document.getElementById("imagePreviewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
});


    // CLOSE MODAL
    document.getElementById("closePreview").addEventListener("click", () => {
        const modal = document.getElementById("imagePreviewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });

    // CLOSE WHEN CLICKING OUTSIDE IMAGE
    document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
        if (e.target.id === "imagePreviewModal") {
            const modal = document.getElementById("imagePreviewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    });

</script>

@endsection
