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

        {{-- HORIZONTAL ROAD MAP --}}
        @if($visit->pitstops && count($visit->pitstops) > 0)
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 text-white flex items-center">
                <i data-lucide="route" class="w-6 h-6 mr-2 text-[#ff2ba6]"></i>
                Route Map
            </h2>

            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl px-4 py-5">
                <div id="roadTrack" class="flex items-center justify-between w-full" style="position:relative;">

                    <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="office-start">
                        <div class="w-8 h-8 rounded-full bg-green-500/20 border-2 border-green-400 flex items-center justify-center z-10 route-marker">
                            <i data-lucide="send" class="w-3.5 h-3.5 text-green-400"></i>
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
        roadModalData['office-start'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-green-500/20 border border-green-400/40 flex items-center justify-center"><i data-lucide="home" class="w-3 h-3 text-green-400"></i></div><div><div class="text-white font-semibold text-sm">Office</div><div class="text-white/40 text-[10px]">Start Point</div></div></div><p class="text-white/50 text-xs">Visit started from office location</p>';
        roadModalData['main-customer'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-blue-500/20 border border-blue-400/40 flex items-center justify-center"><i data-lucide="user" class="w-3 h-3 text-blue-400"></i></div><div><div class="text-white font-semibold text-sm">{!! addslashes($visit->customer->name) !!}</div><div class="text-white/40 text-[10px]">Main Visit</div></div></div><div class="space-y-1.5 text-xs"><div class="flex items-center gap-1.5 text-white/60"><i data-lucide="target" class="w-2.5 h-2.5 text-blue-400 shrink-0"></i>{!! addslashes($visit->purpose) !!}</div>@if($visit->notes)<div class="flex items-start gap-1.5 text-white/50"><i data-lucide="sticky-note" class="w-2.5 h-2.5 text-white/30 shrink-0 mt-0.5"></i><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($visit->notes, 80) !!}"</span></div>@endif @if($visit->distance_km)<div class="flex items-center gap-1.5 text-white/60"><i data-lucide="map-pin" class="w-2.5 h-2.5 text-blue-400 shrink-0"></i>{{ $visit->distance_km }} km</div>@endif</div>';
        roadModalData['office-end'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[#ff2ba6]/20 border border-[#ff2ba6]/40 flex items-center justify-center"><i data-lucide="building" class="w-3 h-3 text-[#ff2ba6]"></i></div><div><div class="text-white font-semibold text-sm">Office</div><div class="text-white/40 text-[10px]">End Point</div></div></div><p class="text-white/50 text-xs">Return to office after visit</p>';
        @foreach($visit->pitstops as $idx => $ps)
        roadModalData['stop-{{ $idx }}'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[#ff2ba6] text-white text-[10px] font-bold flex items-center justify-center shrink-0">{{ $idx + 1 }}</div><div><div class="text-white font-semibold text-sm">{!! addslashes($ps->customer->name ?? 'N/A') !!}</div><div class="text-white/40 text-[10px]">{{ $ps->visited_at ? $ps->visited_at->format('d M, h:i A') : '-' }}</div></div></div><div class="space-y-1.5 text-xs">@if($ps->purpose)<div class="flex items-center gap-1.5 text-white/60"><i data-lucide="target" class="w-2.5 h-2.5 text-[#ff2ba6] shrink-0"></i>{!! addslashes($ps->purpose) !!}</div>@endif @if($ps->notes)<div class="flex items-start gap-1.5 text-white/50"><i data-lucide="sticky-note" class="w-2.5 h-2.5 text-white/30 shrink-0 mt-0.5"></i><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($ps->notes, 80) !!}"</span></div>@endif @if($ps->distance_km)<div class="flex items-center gap-1.5 text-white/60"><i data-lucide="map-pin" class="w-2.5 h-2.5 text-[#ff2ba6] shrink-0"></i>{{ $ps->distance_km }} km</div>@endif @if($ps->images && count($ps->images) > 0)<div class="flex gap-1.5 mt-2 flex-wrap">@foreach($ps->images as $img)<img src="{{ asset($img) }}" class="w-10 h-10 object-cover rounded-lg border border-white/10 road-pitstop-img cursor-pointer" onclick="event.stopPropagation();openRoadImage(this.src)">@endforeach</div>@endif</div>';
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
        function positionRoadModal(node) {
            var box = document.getElementById('road-modal-box');
            var content = document.getElementById('road-modal-content');
            var overlay = document.getElementById('road-modal-overlay');
            var key = node.getAttribute('data-target');
            content.innerHTML = (typeof roadModalData !== 'undefined' && roadModalData[key]) ? roadModalData[key] : '';
            if (typeof lucide !== 'undefined') lucide.createIcons();
            box.classList.add('show');
            overlay.classList.add('show');
        }
        function closeRoadModal() {
            document.getElementById('road-modal-box').classList.remove('show');
            document.getElementById('road-modal-overlay').classList.remove('show');
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
                positionRoadModal(this);
            });
        });
        document.getElementById('road-modal-overlay').addEventListener('click', closeRoadModal);

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
            <a href="{{ route('salehead.reports.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg transition flex items-center justify-center md:inline-flex md:justify-end">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Visits
            </a>
        </div>

    </div>
</div>


{{-- SMALL IMAGE MODAL --}}
<div id="imageModal"
     class="bg-black/80 backdrop-blur-xl hidden
            flex justify-center items-center"
     style="position:fixed !important;inset:0;z-index:50;padding:1rem;">

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
     class="bg-black/90 hidden flex justify-center items-center"
     style="position:fixed !important;inset:0;z-index:60;padding:1rem;">

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
