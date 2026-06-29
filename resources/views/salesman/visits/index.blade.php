@extends('layouts.app')
@section('title', 'My Visits')

@section('content')

    <div class="p-0">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">

            <h1 class="text-3xl font-bold text-white tracking-wide flex items-center">
                {{-- Lucide Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-route mr-3 text-[#ff2ba6]">
                    <circle cx="6" cy="19" r="3" />
                    <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7H14a2 2 0 0 1-2-2V3" />
                    <circle cx="18" cy="5" r="3" />
                </svg>
                My Visits
            </h1>

      {{-- FILTER BAR --}}
<form method="GET"
    class="w-full sm:w-auto
           flex flex-col sm:flex-row sm:items-center gap-3
            backdrop-blur-xl
           rounded-xl
           print:hidden mb-0">

    {{-- Label --}}
    <div class="flex items-center gap-2 text-white/70 text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="text-pink-400">
            <polygon points="3 4 21 4 14 12 14 20 10 18 10 12 3 4" />
        </svg>
        Filter by month
    </div>

    {{-- Controls --}}
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

        <div class="relative w-full sm:w-auto">
            <select name="month"
                onchange="this.form.submit()"
                class="appearance-none
                       w-full sm:w-auto
                       bg-white/10 text-white
                       px-4 py-2 pr-9
                       rounded-lg
                       outline-none
                       focus:bg-white/20
                       transition">

                <option value="" class="text-black">All Visits</option>
                <option value="current" {{ request('month') == 'current' ? 'selected' : '' }} class="text-black">
                    Current Month
                </option>
                <option value="previous" {{ request('month') == 'previous' ? 'selected' : '' }} class="text-black">
                    Previous Month
                </option>
            </select>

            {{-- Chevron --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="absolute right-3 top-1/2 -translate-y-1/2
                       text-white/50 pointer-events-none">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </div>

        {{-- Clear --}}
        @if (request('month'))
            <a href="{{ route('salesman.visits.index') }}"
                class="w-full sm:w-auto
                       px-4 py-2
                       rounded-lg
                       text-sm text-red-300
                       bg-red-500/10
                       hover:bg-red-500/20
                       transition text-center">
                Clear
            </a>
        @endif
    </div>
</form>

            {{-- PRINT BUTTON --}}
            <div>
  <a href="{{ route('visits.export.monthly', request()->all()) }}"
   class="px-4 py-2 rounded-xl bg-green-500 text-white font-semibold shadow hover:bg-green-600 flex items-center justify-center">
    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
    Export Excel
</a>
</div>

        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="mb-4 p-4 bg-green-500/20 border border-green-400/40
                    text-green-100 rounded-xl backdrop-blur-lg flex items-center">
                {{-- Lucide Icon: check --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-check mr-2">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div id="print-header" class="hidden print:block mb-4">
    <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">
        Name: {{ auth()->user()->name }}
    </p>
    <p style="font-size: 12px; font-weight: 400; color: #555;">
        Date: {{ now()->format('d M Y') }}
    </p>
</div>

        {{-- Table Container --}}
        <div
            class="bg-white/10 backdrop-blur-xl border border-white/20
                  rounded-2xl shadow-xl overflow-hidden">

            <table class="w-full table-auto text-white hidden md:table">
                <thead>
                    <tr class="bg-white/10 text-white/80">
                        <th class="p-3 text-left ">
                            {{-- Icon for ID/Index --}}
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-hash mr-1">
                                    <line x1="4" x2="20" y1="9" y2="9" />
                                    <line x1="4" x2="20" y1="15" y2="15" />
                                    <line x1="10" x2="8" y1="3" y2="21" />
                                    <line x1="16" x2="14" y1="3" y2="21" />
                                </svg>
                                Id
                            </div>
                        </th>

                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: building --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-building mr-1">
                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                                    <path d="M9 22v-4h6v4" />
                                </svg>
                                Customer
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: list-checks (Purpose) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-list-checks mr-1">
                                    <path d="m3 16 2 2 4-4" />
                                    <path d="m3 12 2 2 4-4" />
                                    <path d="m3 8 2 2 4-4" />
                                    <path d="M11 6h9" />
                                    <path d="M11 10h9" />
                                    <path d="M11 14h9" />
                                </svg>
                                Purpose
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: activity (Status) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity mr-1">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                                Status
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: sticky-note (Notes) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-sticky-note mr-1">
                                    <path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z" />
                                    <path d="M15 2v4a2 2 0 0 0 2 2h4" />
                                </svg>
                                Notes
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: map-pin --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-1">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                KM
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: clock (Duration) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Duration
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: calendar --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-1">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                Started
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: map-pinned --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned mr-1">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Stops
                            </div>
                        </th>

                        <th class="p-3 text-left print:hidden">
                            <div class="flex items-center">
                                {{-- Lucide Icon: zap (Action) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap mr-1">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                </svg>
                                Action
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($visits as $v)
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">

                            <td class="p-3 ">{{ $v->id }}</td>
                            <td class="p-3">{{ $v->customer->name }}</td>

                            <td class="p-3 ">
                                {{ $v->purpose }}
                            </td>

                            <td class="p-3">
                                <span
                                    class="px-3 py-1 rounded-lg text-xs
                                @if ($v->status == 'started') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                                @elseif ($v->status == 'blocked') bg-red-500/20 border border-red-400/40 text-red-200
                                @else
                                    bg-green-500/20 border border-green-400/40 text-green-200 @endif
                            ">
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>

                            <td class="p-3 max-w-xs">
                                <div class="break-words whitespace-pre-line truncate max-w-[120px]" title="{{ $v->notes ?? '' }}">
                                    {{ \Illuminate\Support\Str::limit($v->notes ?? '-', 10) }}
                                </div>
                            </td>

                            <td class="p-3 ">
                                @if ($v->distance_km)
                                    {{ $v->distance_km }} km
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3 ">
                                @if ($v->status == 'completed' && $v->completed_at)
                                    {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 ">
                                {{ optional($v->started_at)->format('d M Y, h:i A') ?? '-' }}
                            </td>

                            <td class="p-3 ">
                                @if($v->pitstops && count($v->pitstops) > 0)
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#ff2ba6] text-white text-[10px] font-bold">{{ count($v->pitstops) }}</span>
                                        <span class="text-white/80 text-xs">stop{{ count($v->pitstops) > 1 ? 's' : '' }}</span>
                                    </div>
                                @else
                                    <span class="text-white/40 text-xs">-</span>
                                @endif
                            </td>

                            <td class="p-3 print:hidden">
                                @if ($v->status == 'started')
                                    @if($v->pitstops && count($v->pitstops) > 0)
                                        <div class="mb-2 space-y-1.5">
                                            @foreach($v->pitstops as $idx => $ps)
                                                <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-lg px-2 py-1.5">
                                                    <span class="shrink-0 w-5 h-5 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-[10px] font-bold flex items-center justify-center border border-[#ff2ba6]/30">{{ $idx + 1 }}</span>
                                                    <span class="truncate text-white/80 text-xs flex-1">{{ $ps->customer->name ?? 'N/A' }}</span>
                                                    <form action="{{ route('salesman.visits.pitstop.delete', [$v->id, $ps->id]) }}" method="POST" onsubmit="return confirm('Remove this stop?')">
                                                        @csrf @method('DELETE')
                                                        <button class="shrink-0 w-5 h-5 rounded-full bg-red-500/20 text-red-400 text-[10px] flex items-center justify-center hover:bg-red-500/30 border border-red-400/30 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <button type="button" onclick="openPitstopModal({{ $v->id }})"
                                        class="w-full mb-2 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1
                                            bg-[#ff2ba6]/10 border border-[#ff2ba6]/30 text-[#ff2ba6] hover:bg-[#ff2ba6]/20 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                        Add Stop
                                    </button>
                                    <form action="{{ route('salesman.visits.complete', $v->id) }}" method="POST"
                                        enctype="multipart/form-data" id="completeForm_{{ $v->id }}">
                                        @csrf

                                        <textarea name="notes"
                                            class="w-full bg-white/10 text-white placeholder-white/50
                                                p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                                            placeholder="Add notes" required></textarea>
                                        <input type="number" step="0.1" min="0" name="distance_km"
                                            placeholder="Enter distance in KM"
                                            class="w-full bg-white/10 text-white placeholder-white/50
           p-2 rounded-lg mb-2 focus:bg-white/20"
                                            required>

                                        {{-- File upload for images only --}}
                                        <input type="file" name="images[]" multiple accept="image/*"
                                            {{-- Suggested addition to hint for images only --}}
                                            class="w-full text-white mb-2 bg-white/10 p-2 rounded-lg" required>

                                        <input type="hidden" name="lat" id="complete_lat_{{ $v->id }}">
                                        <input type="hidden" name="lng" id="complete_lng_{{ $v->id }}">

                                        <button type="button" onclick="completeVisit('completeForm_{{ $v->id }}', 'complete_lat_{{ $v->id }}', 'complete_lng_{{ $v->id }}')"
                                            class="w-full py-2 rounded-xl text-white font-semibold flex items-center justify-center
                                                bg-gradient-to-r from-green-500 to-emerald-500
                                                shadow hover:opacity-90 transition">
                                            {{-- Lucide Icon: check-circle --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-check-circle mr-1">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                <path d="m9 11 2 2 4-4" />
                                            </svg>
                                            Complete
                                        </button>
                                    </form>
                                @else
<div class="flex items-center gap-3 justify-center">

    {{-- VIEW --}}
    <a href="{{ route('salesman.visits.show', $v->id) }}"
       title="View Visit"
       class="p-2 rounded-lg bg-blue-500/20 border border-blue-400/40
              text-blue-200 hover:bg-blue-500/30 transition">
        {{-- Lucide: eye --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
    </a>

    {{-- EDIT (only current + previous month) --}}
    @if(
        $v->status === 'completed' &&
        $v->started_at->gte(now()->subMonth()->startOfMonth())
    )
        <a href="{{ route('salesman.visits.edit', $v->id) }}"
           title="Edit Visit"
           class="p-2 rounded-lg bg-yellow-500/20 border border-yellow-400/40
                  text-yellow-200 hover:bg-yellow-500/30 transition">
            {{-- Lucide: pencil --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 3a2.85 2.85 0 1 1 4 4L7 21H3v-4L17 3Z"/>
            </svg>
        </a>
    @endif

</div>
@endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-white/70 bg-white/5">
                                No record found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>


            {{-- MOBILE VIEW --}}
            <div class="md:hidden p-4 space-y-4">

                @forelse ($visits as $v)
                    <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                        <h2 class="text-lg font-semibold text-white flex items-center">
                            {{-- Lucide Icon: building --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-building mr-2 text-indigo-300">
                                <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                                <path d="M9 22v-4h6v4" />
                            </svg>
                            {{ $v->customer->name }}
                        </h2>

                        <p class="text-white/70 text-sm mb-2 flex items-center ml-px">
                            {{-- Lucide Icon: list-checks (Purpose) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-list-checks mr-2 text-white/80">
                                <path d="m3 16 2 2 4-4" />
                                <path d="m3 12 2 2 4-4" />
                                <path d="m3 8 2 2 4-4" />
                                <path d="M11 6h9" />
                                <path d="M11 10h9" />
                                <path d="M11 14h9" />
                            </svg>
                            Purpose: {{ $v->purpose }}
                        </p>
                        <p class="text-white/70 text-sm mb-2 flex items-center ml-px">
                            {{-- Calendar Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-calendar mr-2 text-white/80">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>

                            Started:
                            <span class="ml-1">
                                {{ optional($v->started_at)->format('d M Y, h:i A') ?? '-' }}
                            </span>
                        </p>

                        <div class="flex items-center gap-2 mb-2 ml-px">
                            {{-- Lucide Icon: activity (Status) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-activity mr-0.5 text-white/80">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                            <span class="text-white/60 text-sm">Status:</span>

                            <span
                                class="px-3 py-1 rounded-lg text-xs
                            @if ($v->status == 'started') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                            @elseif ($v->status == 'blocked') bg-red-500/20 border border-red-400/40 text-red-200
                            @else
                                bg-green-500/20 border border-green-400/40 text-green-200 @endif
                        ">
                                {{ ucfirst($v->status) }}
                            </span>
                        </div>

                        <div class="text-white/60 text-sm mb-2 ml-px">
                            <div class="flex items-center mb-1">
                                {{-- Lucide Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-sticky-note mr-2 text-white/80">
                                    <path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z" />
                                    <path d="M15 2v4a2 2 0 0 0 2 2h4" />
                                </svg>
                                Notes
                            </div>

                            <div class="text-white/70 text-sm break-words whitespace-pre-line
    line-clamp-3 transition-all duration-300"
                                id="notes-{{ $v->id }}">
                                {{ $v->notes ?? '-' }}
                            </div>

                            @if ($v->notes && strlen($v->notes) > 120)
                                <button type="button" onclick="toggleNotes({{ $v->id }}, this)"
                                    class="mt-1 text-xs text-pink-400 hover:text-pink-300 transition">
                                    Read more
                                </button>
                            @endif




                        </div>


                        <p class="text-white/80 text-sm mb-3 flex items-center ml-px">
                            {{-- Lucide Icon: clock (Duration) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-clock mr-2 text-white/80">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            Duration:
                            @if ($v->status == 'completed' && $v->completed_at)
                                {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                            @else
                                -
                            @endif
                        </p>
                        @if ($v->distance_km)
                            <p class="text-white/80 text-sm mb-2 flex items-center ml-px">
                                {{-- Lucide Icon: map-pin --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-map-pin mr-2 text-white/80">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                Distance: <span class="ml-1 font-semibold">{{ $v->distance_km }} km</span>
                            </p>
                        @endif


                        {{-- If Visit Started → Show Form --}}
                        {{-- ACTION AREA --}}
                        <div class="mt-4">

                            {{-- IF VISIT IS STARTED --}}
                            @if ($v->status == 'started')
                                @if($v->pitstops && count($v->pitstops) > 0)
                                    <div class="mb-3 space-y-1.5">
                                        @foreach($v->pitstops as $idx => $ps)
                                            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-lg px-3 py-2">
                                                <span class="shrink-0 w-6 h-6 rounded-full bg-[#ff2ba6]/20 text-[#ff2ba6] text-[11px] font-bold flex items-center justify-center border border-[#ff2ba6]/30">{{ $idx + 1 }}</span>
                                                <span class="truncate text-white/80 text-sm flex-1">{{ $ps->customer->name ?? 'N/A' }}</span>
                                                <form action="{{ route('salesman.visits.pitstop.delete', [$v->id, $ps->id]) }}" method="POST" onsubmit="return confirm('Remove this stop?')">
                                                    @csrf @method('DELETE')
                                                    <button class="shrink-0 w-6 h-6 rounded-full bg-red-500/20 text-red-400 text-[11px] flex items-center justify-center hover:bg-red-500/30 border border-red-400/30 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <button type="button" onclick="openPitstopModal({{ $v->id }})"
                                    class="w-full mb-3 py-2 rounded-xl text-sm font-semibold flex items-center justify-center gap-1.5
                                        bg-[#ff2ba6]/10 border border-[#ff2ba6]/30 text-[#ff2ba6] hover:bg-[#ff2ba6]/20 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                    Add Stop
                                </button>
                                <form action="{{ route('salesman.visits.complete', $v->id) }}" method="POST"
                                    enctype="multipart/form-data" id="completeForm_{{ $v->id }}">
                                    @csrf

                                    <textarea name="notes"
                                        class="w-full bg-white/10 text-white placeholder-white/50
                       p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                                        placeholder="Add notes" required></textarea>

                                    {{-- KM Field --}}
                                    <input type="number" step="0.1" min="0" name="distance_km"
                                        placeholder="Enter distance in KM"
                                        class="w-full bg-white/10 text-white placeholder-white/50
                       p-2 rounded-lg mb-2 focus:bg-white/20"
                                        required>

                                    {{-- Images --}}
                                    <input type="file" name="images[]" multiple accept="image/*"
                                        class="w-full text-white mb-3 bg-white/10 p-2 rounded-lg text-sm" required>

                                    <input type="hidden" name="lat" id="complete_lat_{{ $v->id }}">
                                    <input type="hidden" name="lng" id="complete_lng_{{ $v->id }}">

                                    <button type="button" onclick="completeVisit('completeForm_{{ $v->id }}', 'complete_lat_{{ $v->id }}', 'complete_lng_{{ $v->id }}')"
                                        class="w-full py-2 rounded-xl text-white font-semibold
                       flex items-center justify-center
                       bg-gradient-to-r from-green-500 to-emerald-500
                       shadow hover:opacity-90 transition">
                                        ✔ Complete Visit
                                    </button>
                                </form>

                                {{-- IF VISIT IS COMPLETED --}}
                            @else
                                <a href="{{ route('salesman.visits.show', $v->id) }}"
                                    class="w-full mt-2 px-4 py-2 rounded-xl
                  bg-blue-500/30 border border-blue-400/40
                  text-blue-100 text-sm font-semibold
                  flex items-center justify-center
                  hover:bg-blue-500/40 transition">

                                    {{-- Eye Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>

                                    View Visit Details
                                </a>
                            @endif

                        </div>



                    </div>
                @empty
                    <p class="text-center text-white/50 p-6">No records found</p>
                @endforelse

            </div>

        </div>
        <div class="mt-6">
            {{ $visits->links() }}
        </div>
    </div>

@endsection

<!-- ADD PITSTOP MODAL -->
<div id="pitstopModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-[#ff2ba6]">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Add Stop
            </h3>
            <button onclick="closePitstopModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-red-500/40 transition text-white text-lg">&times;</button>
        </div>
        <form id="pitstopForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="relative">
                <label class="text-white/70 text-sm mb-1 block flex items-center">
                    <i data-lucide="building-2" class="w-4 h-4 mr-1.5 text-white/50"></i>
                    Customer *
                </label>
                <select name="customer_id" required
                    class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm focus:bg-white/20 outline-none">
                    <option value="" class="text-black">-- Select Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" class="text-black">{{ $c->name }}</option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-[2.35rem] text-white/60 pointer-events-none"></i>
                <i data-lucide="building-2" class="w-5 h-5 absolute left-3 top-[2.35rem] text-white/50 pointer-events-none"></i>
            </div>
            <div class="relative">
                <label class="text-white/70 text-sm mb-1 block flex items-center">
                    <i data-lucide="target" class="w-4 h-4 mr-1.5 text-white/50"></i>
                    Purpose
                </label>
                <select name="purpose"
                    class="bg-white/10 text-white border border-white/20 p-2.5 pl-10 rounded-xl w-full appearance-none text-sm focus:bg-white/20 outline-none">
                    <option value="" class="text-black">-- Select Purpose --</option>
                    <option value="Complaint Visit" class="text-black">Complaint Visit</option>
                    <option value="Delivery" class="text-black">Delivery</option>
                    <option value="Follow-up" class="text-black">Follow-up</option>
                    <option value="New Lead Visit" class="text-black">New Lead Visit</option>
                    <option value="Order Taking" class="text-black">Order Taking</option>
                    <option value="Office Work" class="text-black">Office Work</option>
                    <option value="Product Details" class="text-black">Product Details</option>
                    <option value="Payment Collection" class="text-black">Payment Collection</option>
                    <option value="Recovery" class="text-black">Recovery</option>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-[2.35rem] text-white/60 pointer-events-none"></i>
                <i data-lucide="target" class="w-5 h-5 absolute left-3 top-[2.35rem] text-white/50 pointer-events-none"></i>
            </div>
            <div>
                <label class="text-white/70 text-sm mb-1 block flex items-center">
                    <i data-lucide="sticky-note" class="w-4 h-4 mr-1.5 text-white/50"></i>
                    Notes
                </label>
                <textarea name="notes" rows="2" placeholder="Optional notes"
                    class="w-full bg-white/10 text-white placeholder-white/50 p-2.5 rounded-xl border border-white/20 outline-none focus:bg-white/20 text-sm"></textarea>
            </div>
            <div>
                <label class="text-white/70 text-sm mb-1 block flex items-center">
                    <i data-lucide="map" class="w-4 h-4 mr-1.5 text-white/50"></i>
                    Distance (KM)
                </label>
                <input type="number" step="0.1" min="0" name="distance_km" placeholder="Distance in KM"
                    class="w-full bg-white/10 text-white placeholder-white/50 p-2.5 rounded-xl border border-white/20 outline-none focus:bg-white/20 text-sm">
            </div>
            <div>
                <label class="text-white/70 text-sm mb-1 block flex items-center">
                    <i data-lucide="image" class="w-4 h-4 mr-1.5 text-white/50"></i>
                    Images
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                    class="w-full text-white bg-white/10 p-2.5 rounded-xl border border-white/20 text-sm">
            </div>
            <input type="hidden" name="lat" id="pitstopLat">
            <input type="hidden" name="lng" id="pitstopLng">
            <button type="submit"
                class="w-full py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow hover:opacity-90 transition flex items-center justify-center">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                Save Stop
            </button>
        </form>
    </div>
</div>

<script>
    function openPitstopModal(visitId) {
        const modal = document.getElementById('pitstopModal');
        const form = document.getElementById('pitstopForm');
        form.action = '/salesman/visits/' + visitId + '/pitstop';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('pitstopLat').value = pos.coords.latitude;
                document.getElementById('pitstopLng').value = pos.coords.longitude;
            });
        }
    }

    function closePitstopModal() {
        const modal = document.getElementById('pitstopModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('pitstopModal').addEventListener('click', function(e) {
        if (e.target === this) closePitstopModal();
    });
    function toggleNotes(id, btn) {
        const el = document.getElementById(`notes-${id}`);
        el.classList.toggle('line-clamp-3');

        btn.innerText = el.classList.contains('line-clamp-3') ?
            'Read more' :
            'Read less';
    }

    function completeVisit(formId, latId, lngId) {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById(latId).value = pos.coords.latitude;
                document.getElementById(lngId).value = pos.coords.longitude;
                document.getElementById(formId).submit();
            },
            err => alert('Unable to get location: ' + err.message),
            { enableHighAccuracy: true }
        );
    }
</script>

<style>
    @media print {

        /* Page setup */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            background: #fff !important;
            color: #000 !important;
            font-family: Arial, sans-serif;

        }

        /* Show print header */
       #print-header {
        display: block !important;
        margin-bottom: 10px;
    }

        /* Hide unwanted UI */
        button,
        form,
        input,
        textarea,
        select,
        .print\:hidden,
        .md\:hidden {
            display: none !important;
        }

        /* Table styling */
        table {
            display: table !important;
            width: 100%;
            border-collapse: collapse;
            color: #000 !important;
        }

        thead {
            background: #f2f2f2 !important;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px;
            border: 1px solid #ccc;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Remove glassmorphism */
        .bg-white\/10,
        .bg-white\/5,
        .backdrop-blur-xl {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Status badges simplified */
        span {
            background: none !important;
            border: none !important;
            color: #000 !important;
            padding: 0 !important;
        }
        table td, table th {
        font-size: 12px;
        color: #000;
    }
    }
</style>
