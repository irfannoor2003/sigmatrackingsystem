@extends('layouts.app')

@section('title', 'Out of City Visit Details')

@section('content')

<div class="p-0 text-white">

    <div class="flex items-center mb-6 gap-4">
        <a href="{{ route('salehead.out-of-city-visits.index') }}"
            class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-white flex items-center justify-center shrink-0">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-3xl font-bold tracking-wide flex items-center">
            <i data-lucide="plane" class="w-8 h-8 mr-3 text-pink-400"></i> Out of City Visit Details
        </h1>
    </div>

    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 p-6 rounded-2xl backdrop-blur-xl border border-white/10 shadow-xl">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white/90">

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2 text-pink-300"></i>
                    <strong class="text-white mr-2">Salesman:</strong> {{ $visit->salesman->name ?? '-' }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="calendar" class="w-5 h-5 mr-2 text-indigo-300"></i>
                    <strong class="text-white mr-2">Date:</strong>
                    {{ optional($visit->attendance)->date ? $visit->attendance->date->format('d M Y') : '-' }}
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="building-2" class="w-5 h-5 mr-2 text-sky-300"></i>
                    <strong class="text-white mr-2">Customer Visits:</strong> {{ $visit->items->count() }}
                </p>
            </div>

            <div>
                <p class="mb-3 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-2 text-gray-400"></i>
                    <strong class="text-white mr-2">Status:</strong>
                    @if ($visit->status === 'pending')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-yellow-500/30 bg-yellow-500/10 text-yellow-400">
                            <i data-lucide="loader-2" class="w-3 h-3 mr-1"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold uppercase border border-green-500/30 bg-green-500/10 text-green-400">
                            <i data-lucide="check-square" class="w-3 h-3 mr-1"></i> {{ ucfirst($visit->status) }}
                        </span>
                    @endif
                </p>
                <p class="mb-3 flex items-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2 text-white/70"></i>
                    <strong class="text-white mr-2">Submitted At:</strong>
                    {{ optional($visit->submitted_at)->format('d M Y, h:i A') ?? '-' }}
                </p>
            </div>

        </div>

        @if ($visit->notes)
            <div class="mt-6">
                <p class="text-white mb-2 font-semibold text-lg flex items-center">
                    <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-white/80"></i> Overall Notes:
                </p>
                <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                    {{ $visit->notes }}
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4 text-white flex items-center">
            <i data-lucide="building-2" class="w-6 h-6 mr-2 text-[#ff2ba6]"></i>
            Customer Visits ({{ $visit->items->count() }})
        </h2>

        @forelse ($visit->items as $idx => $item)
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 border border-white/10 rounded-2xl p-5 mb-4 shadow-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-7 h-7 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-xs font-bold flex items-center justify-center border border-[#ff2ba6]/30 shrink-0">{{ $idx + 1 }}</span>
                    <h3 class="text-white font-semibold flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-2 text-pink-300"></i>
                        {{ $item->customer->name ?? 'Unknown' }}
                    </h3>
                </div>

                @if ($item->note)
                    <div class="mb-3 flex items-start gap-2">
                        <i data-lucide="sticky-note" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"></i>
                        <div>
                            <span class="text-white/50 text-xs uppercase tracking-wide">Note:</span>
                            <p class="text-white/80 text-sm mt-1">{{ $item->note }}</p>
                        </div>
                    </div>
                @endif

                @if ($item->images && count($item->images) > 0)
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="image" class="w-4 h-4 text-sky-400"></i>
                        <span class="text-white/50 text-xs uppercase tracking-wide">Images</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($item->images as $image)
                            <a href="{{ asset($image) }}" target="_blank" class="block group">
                                <img src="{{ asset($image) }}" alt="Visit image"
                                    class="w-full h-32 object-cover rounded-lg border border-white/10 group-hover:border-[#ff2ba6]/50 transition">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-white/40 text-sm italic flex items-center gap-2">
                        <i data-lucide="image-off" class="w-4 h-4"></i> No images uploaded
                    </p>
                @endif
            </div>
        @empty
            <div class="text-center py-8 bg-white/5 rounded-xl">
                <p class="text-white/50 flex items-center justify-center gap-2">
                    <i data-lucide="search-x" class="w-5 h-5"></i> No visit details added yet.
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 text-center md:text-right">
        <a href="{{ route('salehead.out-of-city-visits.index') }}"
           class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                  text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center md:inline-flex md:justify-end">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Out of City Visits
        </a>
    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection
