@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="p-0 text-white">

    <h1 class="text-3xl font-bold mb-6 tracking-wide flex items-center">
        <i data-lucide="notebook-tabs" class="w-8 h-8 mr-3 text-pink-400"></i> Visit Details
    </h1>

    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 p-6 rounded-2xl backdrop-blur-xl border border-white/10 shadow-xl">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white/90">

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-pink-300"></i>
                    <strong class="text-white mr-2">Salesman:</strong> {{ $visit->salesman->name }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="building" class="w-5 h-5 mr-2 text-indigo-300"></i>
                    <strong class="text-white mr-2">Customer:</strong> {{ $visit->customer->name }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="target" class="w-5 h-5 mr-2 text-sky-300"></i>
                    <strong class="text-white mr-2">Purpose:</strong> {{ $visit->purpose }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-2 text-gray-400"></i>
                    <strong class="text-white mr-2">Status:</strong>

                    @if($visit->status == 'started')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-yellow-500/30 bg-yellow-500/10 text-yellow-400">
                            <i data-lucide="loader-2" class="w-3 h-3 mr-1"></i>
                            {{ ucfirst($visit->status) }}
                        </span>
                    @elseif($visit->status == 'completed')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-green-500/30 bg-green-500/10 text-green-400">
                            <i data-lucide="check-square" class="w-3 h-3 mr-1"></i>
                            {{ ucfirst($visit->status) }}
                        </span>
                    @elseif($visit->status == 'blocked')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-red-500/30 bg-red-500/10 text-red-400">
                            <i data-lucide="shield-off" class="w-3 h-3 mr-1"></i>
                            {{ ucfirst($visit->status) }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-gray-500/30 bg-gray-500/10 text-gray-400">
                            {{ ucfirst($visit->status) }}
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="clock-start" class="w-5 h-5 mr-2 text-white/70"></i>
                    <strong class="text-white mr-2">Started At:</strong>
                    {{ $visit->started_at ? $visit->started_at->format('Y-m-d H:i') : '-' }}
                </p>

                <p class="mb-3 flex items-center">
                    <i data-lucide="clock-end" class="w-5 h-5 mr-2 text-white/70"></i>
                    <strong class="text-white mr-2">Completed At:</strong>
                    {{ $visit->completed_at ? $visit->completed_at->format('Y-m-d H:i') : '-' }}
                </p>

                <p class="mb-3 flex items-center">
                    <i data-lucide="timer" class="w-5 h-5 mr-2 text-white/70"></i>
                    <strong class="text-white mr-2">Duration:</strong>

                    @if($visit->started_at && $visit->completed_at)
                        @php
                            $signed = $visit->completed_at->diffInMinutes($visit->started_at, false);
                            $minutes = abs($signed);
                            $hours = intdiv($minutes, 60);
                            $mins  = $minutes % 60;
                            $pretty = $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
                        @endphp

                        <span class="text-white">{{ $pretty }}</span>

                        @if($signed < 0)
                            <span class="ml-2 inline-flex items-center text-sm text-yellow-300 bg-yellow-900/40 px-2 py-0.5 rounded-lg">
                                <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> timestamps inconsistent
                            </span>
                        @endif

                    @else
                        <span class="text-white/60">-</span>
                    @endif
                </p>
<p class="mb-3 flex items-center">
                    <i data-lucide="map" class="w-5 h-5 mr-2 text-white/70"></i>
                    <strong class="text-white mr-2">Km:</strong>
                    {{ $visit->distance_km }}
                </p>
            </div>

        </div>

        <div class="mt-6">
            <p class="text-white mb-2 font-semibold text-lg flex items-center">
                <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-white/80"></i> Notes:
            </p>
            <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                {{ $visit->notes ?? 'No notes added.' }}
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-3 text-white flex items-center">
                <i data-lucide="image" class="w-6 h-6 mr-2 text-sky-400"></i> Uploaded Images
            </h2>

            <button
                onclick="toggleImageModal()"
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                       text-white font-semibold shadow hover:opacity-90 transition flex items-center">
                <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Preview Documents
            </button>

            @if(!$visit->images || count($visit->images) == 0)
                <p class="text-white/60 mt-3 flex items-center">
                    <i data-lucide="image-off" class="w-4 h-4 mr-2"></i> No images available
                </p>
            @endif
        </div>

        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('admin.reports.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center md:inline-flex md:justify-end">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Visits
            </a>
        </div>

    </div>
</div>


{{-- SMALL IMAGE MODAL --}}
<div id="imageModal"
     class="fixed inset-0 bg-black/80 backdrop-blur-xl hidden z-50 p-4
            flex justify-center items-center">

    <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 w-full max-w-3xl shadow-2xl
                transform transition-all duration-300 scale-95 opacity-0"
         id="imageModalContent">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-white flex items-center tracking-wide">
                <i data-lucide="gallery-vertical-end" class="w-6 h-6 mr-2 text-pink-400"></i>
                Documents Preview
            </h3>

            <button onclick="toggleImageModal()"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-red-500/40
                       transition text-white shadow-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        @if($visit->images && count($visit->images))
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5"> @foreach($visit->images as $img)
                        <div
    class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer preview-image flex itmes-center justify-center"
    data-image="{{ asset($img) }}"
>
                              <div class="w-full aspect-square overflow-hidden">
        <img src="{{ asset($img) }}" class="object-cover w-full h-full">
    </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-lg">No images available</p>
            @endif

    </div>
</div>


{{-- FULL-SCREEN IMAGE VIEWER --}}
<div id="fullscreenModal"
     class="fixed inset-0 bg-black/90 hidden z-[60] flex justify-center items-center p-4">

    <button onclick="closeFullscreen()"
        class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center
               rounded-full bg-white/10 hover:bg-red-500/40 text-white text-xl">
        ✕
    </button>

    <img id="fullscreenImage"
         class="max-h-[90vh] max-w-[90vw] object-contain rounded-xl shadow-2xl mx-auto">
</div>




<script>
    function toggleImageModal() {
        const modal = document.getElementById('imageModal');
        const content = document.getElementById('imageModalContent');

document.querySelectorAll('.preview-image').forEach(el => {
    el.addEventListener('click', () => openFullscreen(el.dataset.image));
});

        modal.classList.toggle('hidden');

        if (!modal.classList.contains('hidden')) {
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 50);
        } else {
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
        }
    }

    function openFullscreen(src) {
    const modal = document.getElementById('fullscreenModal');
    const img = document.getElementById('fullscreenImage');

    img.src = src;
    modal.classList.remove('hidden');
}

function closeFullscreen() {
    document.getElementById('fullscreenModal').classList.add('hidden');
}

</script>

@endsection
