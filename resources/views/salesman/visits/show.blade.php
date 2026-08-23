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

@if($visit->status === 'cancelled' && $visit->cancelled_reason)
<div class="bg-red-500/10 border border-red-400/30 py-3 px-4 rounded-xl text-sm md:text-base">
    <div class="flex items-center mb-2 text-red-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-2">
            <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
        </svg>
        Cancellation Reason
    </div>
    <div class="text-red-200 break-words whitespace-pre-line">
        {{ $visit->cancelled_reason }}
    </div>
    @if($visit->cancelled_at)
        <div class="mt-2 text-xs text-white/50">
            Cancelled on {{ $visit->cancelled_at->format('d M Y, h:i A') }}
        </div>
    @endif
</div>
@endif

<div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
    <span class="text-white/70 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-map-pin mr-2 ">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>
        @if($visit->pitstops && count($visit->pitstops) > 0)
            Total KM Traveled
        @else
            Distance (KM)
        @endif
    </span>
    <span class="text-white font-semibold">
        @if($visit->pitstops && count($visit->pitstops) > 0 && $visit->pitstop_total_km)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-[#ff2ba6]/20 text-[#ff2ba6] text-sm font-bold border border-[#ff2ba6]/30">
                {{ $visit->pitstop_total_km }} km
            </span>
        @else
            {{ $visit->distance_km ?? 'N/A' }}
        @endif
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

        {{-- HORIZONTAL ROAD MAP --}}
        @if($visit->pitstops && count($visit->pitstops) > 0)
        <div class="mt-10">
            <h3 class="text-xl md:text-2xl font-semibold text-white mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-[#ff2ba6]">
                    <circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7H14a2 2 0 0 1-2-2V3"/><circle cx="18" cy="5" r="3"/>
                </svg>
                Route Map
            </h3>

            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl px-4 py-5">
                <div id="roadTrack" class="flex items-center justify-between w-full" style="position:relative;">

                    <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="office-start">
                        <div class="w-8 h-8 rounded-full bg-green-500/20 border-2 border-green-400 flex items-center justify-center z-10 route-marker">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                        </div>
                        <span class="route-label-h absolute -bottom-4 text-[9px] text-white/40 font-medium whitespace-nowrap">Office</span>
                    </div>

                    <div class="flex-1 h-[2px] route-line-h mx-1"></div>

                    <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="main-customer">
                        <div class="w-9 h-9 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center z-10 route-marker shadow-lg shadow-blue-500/20">
                            <i data-lucide="user" class="w-4 h-4 text-blue-400"></i>
                        </div>
                        <span class="route-label-h absolute -bottom-4 text-[9px] text-white/50 font-semibold whitespace-nowrap">{{ \Illuminate\Support\Str::limit($visit->customer->name, 14) }}</span>
                    </div>

                    @foreach($visit->pitstops as $idx => $ps)
                        <div class="flex-1 h-[2px] route-line-h mx-1"></div>
                        <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="stop-{{ $idx }}">
                            <div class="w-8 h-8 rounded-full bg-[#ff2ba6] text-white text-xs font-bold flex items-center justify-center z-10 route-marker shadow-lg shadow-[#ff2ba6]/30 border-2 border-white/20">{{ $idx + 1 }}</div>
                            <span class="route-label-h absolute -bottom-4 text-[9px] text-white/50 font-medium whitespace-nowrap">{{ \Illuminate\Support\Str::limit($ps->customer->name ?? 'N/A', 14) }}</span>
                        </div>
                    @endforeach

                    <div class="flex-1 h-[2px] route-line-h mx-1"></div>

                    <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="office-end">
                        <div class="w-8 h-8 rounded-full bg-[#ff2ba6]/20 border-2 border-[#ff2ba6]/50 flex items-center justify-center z-10 route-car">
                            <i data-lucide="building" class="w-3.5 h-3.5 text-[#ff2ba6]"></i>
                        </div>
                        <span class="route-label-h absolute -bottom-4 text-[9px] text-white/40 font-medium whitespace-nowrap">Office</span>
                    </div>
                    <div id="roadCar" style="position:absolute;top:50%;left:0;z-index:5;transform:translateY(calc(-50% - 5px));pointer-events:none;opacity:0;will-change:left,opacity;">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 90 70" fill="white" style="filter:drop-shadow(0 2px 6px rgba(255,255,255,0.3));">
                                <path d="M75.48 36.05l-7.99-1.22-2.35-2.57c-5.6-6.13-13.57-9.65-21.87-9.65h-6.25c-1.36 0-2.7.11-4.02.3-.02 0-.04.01-.06.01-7.8 1.13-14.8 5.47-19.29 12.11C5.71 37.91 0 45.36 0 52.95c0 3.25 2.65 5.9 5.9 5.9h3.45c.97 4.87 5.27 8.55 10.42 8.55s9.45-3.68 10.42-8.55h30.14c.97 4.87 5.27 8.55 10.42 8.55s9.45-3.68 10.42-8.55H84.1c3.25 0 5.9-2.65 5.9-5.9 0-7.6-6.11-14.71-14.52-15.99zm-32.21-9.44c7.07 0 13.85 2.95 18.68 8.09H39.46l-3.27-8.07c.28-.01.55-.03.83-.03h6.25zm-11.19.52l3.07 7.58H18.97c3.46-3.88 8.05-6.53 13.11-7.58zm-12.31 36.28c-3.65 0-6.62-2.97-6.62-6.62s2.97-6.62 6.62-6.62 6.62 2.97 6.62 6.62-2.97 6.62-6.62 6.62zm50.97 0c-3.65 0-6.62-2.97-6.62-6.62s2.97-6.62 6.62-6.62 6.62 2.97 6.62 6.62-2.97 6.62-6.62 6.62z"/>
                                <circle cx="19.77" cy="56.78" r="1.96" fill="rgba(0,0,0,0.4)"/>
                                <circle cx="70.74" cy="56.78" r="1.96" fill="rgba(0,0,0,0.4)"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        var roadModalData = roadModalData || {};
        roadModalData['office-start'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-green-500/20 border border-green-400/40 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div><div><div class="text-white font-semibold text-sm">Office</div><div class="text-white/40 text-[10px]">Start Point</div></div></div><p class="text-white/50 text-xs">Visit started from office location</p>';
        roadModalData['main-customer'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-blue-500/20 border border-blue-400/40 flex items-center justify-center"><i data-lucide="user" class="w-3 h-3 text-blue-400"></i></div><div><div class="text-white font-semibold text-sm">{!! addslashes($visit->customer->name) !!}</div><div class="text-white/40 text-[10px]">Main Visit</div></div></div><div class="space-y-1.5 text-xs"><div class="flex items-center gap-1.5 text-white/60"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400 shrink-0"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>{!! addslashes($visit->purpose) !!}</div>@if($visit->notes)<div class="flex items-start gap-1.5 text-white/50"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/30 shrink-0 mt-0.5"><path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/></svg><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($visit->notes, 80) !!}"</span></div>@endif @if($visit->distance_km)<div class="flex items-center gap-1.5 text-white/60"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400 shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>{{ $visit->distance_km }} km</div>@endif</div>';
        roadModalData['office-end'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[#ff2ba6]/20 border border-[#ff2ba6]/40 flex items-center justify-center"><i data-lucide="building" class="w-3 h-3 text-[#ff2ba6]"></i></div><div><div class="text-white font-semibold text-sm">Office</div><div class="text-white/40 text-[10px]">End Point</div></div></div><p class="text-white/50 text-xs">Return to office after visit</p>@if($visit->pitstops && count($visit->pitstops) > 0 && $visit->pitstop_total_km)<div class="mt-2 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-[#ff2ba6]/10 border border-[#ff2ba6]/30"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#ff2ba6] shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2.5 2.5"/></svg><span class="text-[#ff2ba6] text-xs font-bold">Total Trip: {{ $visit->pitstop_total_km }} km</span></div>@endif';
        @foreach($visit->pitstops as $idx => $ps)
        roadModalData['stop-{{ $idx }}'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[#ff2ba6] text-white text-[10px] font-bold flex items-center justify-center shrink-0">{{ $idx + 1 }}</div><div><div class="text-white font-semibold text-sm">{!! addslashes($ps->customer->name ?? 'N/A') !!}</div><div class="text-white/40 text-[10px]">{{ $ps->visited_at ? $ps->visited_at->format('d M, h:i A') : '-' }}</div></div></div><div class="space-y-1.5 text-xs">@if($ps->purpose)<div class="flex items-center gap-1.5 text-white/60"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#ff2ba6] shrink-0"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>{!! addslashes($ps->purpose) !!}</div>@endif @if($ps->notes)<div class="flex items-start gap-1.5 text-white/50"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/30 shrink-0 mt-0.5"><path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z"/></svg><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($ps->notes, 80) !!}"</span></div>@endif @if($ps->distance_km)<div class="flex items-center gap-1.5 text-white/60"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#ff2ba6] shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>{{ $ps->distance_km }} km</div>@endif @if($ps->images && count($ps->images) > 0)<div class="flex gap-1.5 mt-2">@foreach($ps->images as $img)<img src="{{ asset($img) }}" class="w-10 h-10 object-cover rounded-lg border border-white/10 road-pitstop-img cursor-pointer">@endforeach</div>@endif</div>';
        @endforeach
        </script>
        @endif

        {{-- ROAD MODAL (always rendered) --}}
        <div id="road-modal-overlay" onclick="closeRoadModal()" style="position:fixed !important;top:0;left:0;right:0;bottom:0;z-index:9998;"></div>
        <div id="road-modal-box" style="position:fixed !important;z-index:9999;max-width:calc(100vw - 2rem);width:max-content;min-width:16rem;">
            <div class="bg-[#0f172a] border border-white/20 rounded-xl p-4 shadow-2xl">
                <div id="road-modal-content"></div>
            </div>
        </div>

        <!-- Road Image Viewer -->
        <div id="roadImageModal" onclick="closeRoadImage()" style="position:fixed !important;inset:0;z-index:10000;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,0.8);backdrop-filter:blur(8px);padding:1rem;">
            <button onclick="closeRoadImage()" style="position:absolute;top:1rem;right:1rem;background:rgba(255,255,255,0.1);border:none;color:white;width:2.5rem;height:2.5rem;border-radius:50%;font-size:1.25rem;cursor:pointer;">&#x2715;</button>
            <img id="roadImagePreview" src="" style="max-height:85vh;max-width:90vw;object:contain;border-radius:1rem;box-shadow:0 0 40px rgba(0,0,0,0.5);">
        </div>

        <script>
        function openRoadModal(key) {
            var content = document.getElementById('road-modal-content');
            var box = document.getElementById('road-modal-box');
            var overlay = document.getElementById('road-modal-overlay');
            if (!content || !box || !overlay) return;
            content.innerHTML = (typeof roadModalData !== 'undefined' && roadModalData[key]) ? roadModalData[key] : '';
            if (typeof lucide !== 'undefined') lucide.createIcons();
            box.classList.add('show');
            overlay.classList.add('show');
        }
        function closeRoadModal() {
            var box = document.getElementById('road-modal-box');
            var overlay = document.getElementById('road-modal-overlay');
            if (box) box.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
        }
        function openRoadImage(src) {
            var img = document.getElementById('roadImagePreview');
            var modal = document.getElementById('roadImageModal');
            if (!img || !modal) return;
            img.src = src;
            modal.style.display = 'flex';
        }
        function closeRoadImage() {
            var modal = document.getElementById('roadImageModal');
            if (modal) modal.style.display = 'none';
        }
        document.querySelectorAll('.road-node[data-target]').forEach(function(node) {
            node.addEventListener('click', function(e) {
                e.stopPropagation();
                openRoadModal(this.getAttribute('data-target'));
            });
        });
        document.getElementById('road-modal-overlay').addEventListener('click', closeRoadModal);
        document.getElementById('road-modal-content').addEventListener('click', function(e) {
            if (e.target.classList.contains('road-pitstop-img')) {
                openRoadImage(e.target.src);
            }
        });

        // Car animation
        (function() {
            var track = document.getElementById('roadTrack');
            var car = document.getElementById('roadCar');
            if (!track || !car) return;

            var segIndex = 0, carW = 34, timeoutId = null, running = false;

            function getSegments() {
                var segs = track.querySelectorAll('.route-line-h');
                var trackRect = track.getBoundingClientRect();
                var result = [];
                segs.forEach(function(s) {
                    var r = s.getBoundingClientRect();
                    result.push({
                        left: r.left - trackRect.left,
                        right: r.right - trackRect.left,
                        width: r.width
                    });
                });
                return result;
            }

            function createBlast(nodeEl) {
                var nr = nodeEl.getBoundingClientRect();
                var tr = track.getBoundingClientRect();
                var cx = nr.left - tr.left + nr.width / 2;
                var cy = nr.top - tr.top + nr.height / 2;

                var ring = document.createElement('div');
                ring.style.cssText = 'position:absolute;left:' + cx + 'px;top:' + cy + 'px;width:6px;height:6px;border-radius:50%;border:2px solid rgba(255,255,255,0.9);z-index:20;pointer-events:none;transform:translate(-50%,-50%);opacity:1;';
                track.appendChild(ring);
                void ring.offsetHeight;
                ring.style.transition = 'transform 500ms ease-out, opacity 500ms ease-out';
                ring.style.transform = 'translate(-50%,-50%) scale(8)';
                ring.style.opacity = '0';
                setTimeout(function() { if (ring.parentNode) ring.parentNode.removeChild(ring); }, 550);

                for (var i = 0; i < 10; i++) {
                    (function(idx) {
                        var p = document.createElement('div');
                        var a = (idx / 10) * Math.PI * 2 + Math.random() * 0.3;
                        var sz = 2 + (idx % 3);
                        p.style.cssText = 'position:absolute;left:' + cx + 'px;top:' + cy + 'px;width:' + sz + 'px;height:' + sz + 'px;border-radius:50%;background:rgba(255,255,255,0.9);z-index:20;pointer-events:none;transform:translate(-50%,-50%);opacity:1;';
                        track.appendChild(p);
                        void p.offsetHeight;
                        var dist = 12 + idx * 3 + Math.random() * 8;
                        var dur = 350 + Math.random() * 150;
                        p.style.transition = 'transform ' + dur + 'ms ease-out, opacity ' + dur + 'ms ease-out';
                        p.style.transform = 'translate(' + (Math.cos(a) * dist) + 'px, ' + (Math.sin(a) * dist) + 'px)';
                        p.style.opacity = '0';
                        setTimeout(function() { if (p.parentNode) p.parentNode.removeChild(p); }, dur + 50);
                    })(i);
                }
            }

            function runCycle() {
                if (!running) return;
                var segs = getSegments();
                if (segs.length === 0) { running = false; return; }
                if (segIndex >= segs.length) segIndex = 0;
                var seg = segs[segIndex];
                var duration = Math.max(3500, seg.width / 30 * 1000);
                var hideAt = duration - Math.min(300, duration * 0.25);

                car.style.transition = 'none';
                car.style.left = (seg.left - carW / 2) + 'px';
                car.style.opacity = '1';
                void car.offsetHeight;

                car.style.transition = 'left ' + duration + 'ms cubic-bezier(0.25, 0.1, 0.25, 1.0)';
                car.style.left = (seg.right - carW / 2) + 'px';

                timeoutId = setTimeout(function() {
                    car.style.opacity = '0';
                    var nodes = track.querySelectorAll('.road-node');
                    var targetIdx = segIndex + 1;
                    if (targetIdx < nodes.length) createBlast(nodes[targetIdx]);
                    timeoutId = setTimeout(function() {
                        segIndex++;
                        runCycle();
                    }, 500);
                }, hideAt);
            }

            function startAnimation() {
                if (running) return;
                running = true;
                segIndex = 0;
                runCycle();
            }

            function stopAnimation() {
                running = false;
                if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
                car.style.opacity = '0';
            }

            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (running) { stopAnimation(); setTimeout(startAnimation, 500); }
                }, 300);
            });

            setTimeout(startAnimation, 1500);
        })();
        </script>

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
     class="hidden items-center justify-center
            bg-black/70 backdrop-blur-xl p-4"
     style="position:fixed !important;inset:0;z-index:9999;">

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
