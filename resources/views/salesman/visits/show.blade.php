@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div>

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="file-text" class="w-6 h-6"></i></span>
            Visit Details
        </h1>
        <a href="{{ route('salesman.visits.index') }}" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Visits
        </a>
    </div>

    <div class="card !rounded-3xl p-6 md:p-8">

        <div class="grid grid-cols-1 gap-6">

            <div class="w-full">
                <h2 class="text-xl md:text-2xl font-bold text-[var(--text)] flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[var(--accent)]"></i>
                    {{ $visit->customer->name }}
                </h2>

                <p class="text-sm font-semibold text-[var(--text-soft)] mb-5 capitalize flex items-center gap-2">
                    <i data-lucide="target" class="w-4 h-4 text-[var(--accent)]"></i>
                    Visit Purpose: {{ $visit->purpose }}
                </p>

                <div class="space-y-3">

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                            Status
                        </span>
                        <span>
                            @if ($visit->status === 'completed')
                                <span class="badge badge-emerald">{{ ucfirst($visit->status) }}</span>
                            @elseif ($visit->status === 'started')
                                <span class="badge badge-amber">{{ ucfirst($visit->status) }}</span>
                            @elseif ($visit->status === 'blocked')
                                <span class="badge badge-red">{{ ucfirst($visit->status) }}</span>
                            @elseif ($visit->status === 'cancelled')
                                <span class="badge badge-gray">{{ ucfirst($visit->status) }}</span>
                            @else
                                <span class="badge badge-sky">{{ ucfirst($visit->status) }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 text-sm md:text-base">
                        <div class="flex items-center gap-2 mb-2 text-[var(--text-muted)] font-semibold">
                            <i data-lucide="sticky-note" class="w-4 h-4"></i>
                            Notes
                        </div>
                        <div class="text-[var(--text)] break-words whitespace-pre-line max-h-40 overflow-y-auto pr-1">
                            {{ $visit->notes ?? 'N/A' }}
                        </div>
                    </div>

                    @if($visit->status === 'cancelled' && $visit->cancelled_reason)
                        <div class="alert alert-error !py-3 flex-col !items-start">
                            <div class="flex items-center gap-2 font-semibold w-full">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Cancellation Reason
                            </div>
                            <div class="text-sm break-words whitespace-pre-line w-full">
                                {{ $visit->cancelled_reason }}
                            </div>
                            @if($visit->cancelled_at)
                                <div class="mt-1 text-xs opacity-75">
                                    Cancelled on {{ $visit->cancelled_at->format('d M Y, h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            @if($visit->pitstops && count($visit->pitstops) > 0)
                                Total KM Traveled
                            @else
                                Distance (KM)
                            @endif
                        </span>
                        <span>
                            @if($visit->pitstops && count($visit->pitstops) > 0 && $visit->pitstop_total_km)
                                <span class="badge badge-magenta !text-sm !px-3 !py-1">
                                    {{ $visit->pitstop_total_km }} km
                                </span>
                            @else
                                <span class="font-semibold text-[var(--text)]">{{ $visit->distance_km ?? 'N/A' }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="soft-panel px-4 py-3 flex items-center justify-between gap-3 text-sm md:text-base">
                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                            Visited On
                        </span>
                        <span class="text-[var(--text)]">{{ $visit->created_at->format('d M, Y h:i A') }}</span>
                    </div>

                </div>
            </div>
        </div>

        @if($visit->pitstops && count($visit->pitstops) > 0)
            <div class="mt-8">
                <h3 class="text-lg font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                    <i data-lucide="route" class="w-5 h-5 text-[var(--accent)]"></i>
                    Route Map
                </h3>

                <div class="soft-panel !rounded-2xl px-4 py-6">
                    <div id="roadTrack" class="flex items-center justify-between w-full" style="position:relative;">

                        <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="office-start">
                            <div
                                class="w-8 h-8 rounded-full bg-emerald-500/15 border-2 border-emerald-500 flex items-center justify-center z-10 route-marker">
                                <i data-lucide="send" class="w-3.5 h-3.5 text-emerald-500"></i>
                            </div>
                            <span
                                class="route-label-h absolute -bottom-4 text-[9px] text-[var(--text-muted)] font-medium whitespace-nowrap">Office</span>
                        </div>

                        <div class="flex-1 h-[2px] route-line-h mx-1"></div>

                        <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="main-customer">
                            <div
                                class="w-9 h-9 rounded-full bg-sky-500/15 border-2 border-sky-500 flex items-center justify-center z-10 route-marker">
                                <i data-lucide="user" class="w-4 h-4 text-sky-500"></i>
                            </div>
                            <span
                                class="route-label-h absolute -bottom-4 text-[9px] text-[var(--text-soft)] font-semibold whitespace-nowrap">{{ \Illuminate\Support\Str::limit($visit->customer->name, 14) }}</span>
                        </div>

                        @foreach($visit->pitstops as $idx => $ps)
                            <div class="flex-1 h-[2px] route-line-h mx-1"></div>
                            <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="stop-{{ $idx }}">
                                <div
                                    class="w-8 h-8 rounded-full bg-[var(--accent)] text-white text-xs font-bold flex items-center justify-center z-10 route-marker border-2 border-[var(--border)]">{{ $idx + 1 }}</div>
                                <span
                                    class="route-label-h absolute -bottom-4 text-[9px] text-[var(--text-soft)] font-medium whitespace-nowrap">{{ \Illuminate\Support\Str::limit($ps->customer->name ?? 'N/A', 14) }}</span>
                            </div>
                        @endforeach

                        <div class="flex-1 h-[2px] route-line-h mx-1"></div>

                        <div class="road-node relative flex flex-col items-center cursor-pointer" data-target="office-end">
                            <div
                                class="w-8 h-8 rounded-full bg-[var(--accent-soft)] border-2 border-[var(--accent)] flex items-center justify-center z-10 route-car">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-[var(--accent)]"></i>
                            </div>
                            <span
                                class="route-label-h absolute -bottom-4 text-[9px] text-[var(--text-muted)] font-medium whitespace-nowrap">Office</span>
                        </div>

                        <div id="roadCar"
                            style="position:absolute;top:50%;left:0;z-index:5;transform:translateY(calc(-50% - 5px));pointer-events:none;opacity:0;will-change:left,opacity;">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 90 70"
                                    fill="var(--accent)">
                                    <path
                                        d="M75.48 36.05l-7.99-1.22-2.35-2.57c-5.6-6.13-13.57-9.65-21.87-9.65h-6.25c-1.36 0-2.7.11-4.02.3-.02 0-.04.01-.06.01-7.8 1.13-14.8 5.47-19.29 12.11C5.71 37.91 0 45.36 0 52.95c0 3.25 2.65 5.9 5.9 5.9h3.45c.97 4.87 5.27 8.55 10.42 8.55s9.45-3.68 10.42-8.55h30.14c.97 4.87 5.27 8.55 10.42 8.55s9.45-3.68 10.42-8.55H84.1c3.25 0 5.9-2.65 5.9-5.9 0-7.6-6.11-14.71-14.52-15.99zm-32.21-9.44c7.07 0 13.85 2.95 18.68 8.09H39.46l-3.27-8.07c.28-.01.55-.03.83-.03h6.25zm-11.19.52l3.07 7.58H18.97c3.46-3.88 8.05-6.53 13.11-7.58zm-12.31 36.28c-3.65 0-6.62-2.97-6.62-6.62s2.97-6.62 6.62-6.62 6.62 2.97 6.62 6.62-2.97 6.62-6.62 6.62zm50.97 0c-3.65 0-6.62-2.97-6.62-6.62s2.97-6.62 6.62-6.62 6.62 2.97 6.62 6.62-2.97 6.62-6.62 6.62z"
                                    />
                                    <circle cx="19.77" cy="56.78" r="1.96" fill="rgba(0,0,0,0.4)" />
                                    <circle cx="70.74" cy="56.78" r="1.96" fill="rgba(0,0,0,0.4)" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var roadModalData = roadModalData || {};
                roadModalData['office-start'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center"><i data-lucide="building-2" class="w-3.5 h-3.5 text-emerald-500"></i></div><div><div class="text-[var(--text)] font-semibold text-sm">Office</div><div class="text-[var(--text-muted)] text-[10px]">Start Point</div></div></div><p class="text-[var(--text-soft)] text-xs">Visit started from office location</p>';
                roadModalData['main-customer'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-sky-500/15 border border-sky-500/30 flex items-center justify-center"><i data-lucide="user" class="w-3 h-3 text-sky-500"></i></div><div><div class="text-[var(--text)] font-semibold text-sm">{!! addslashes($visit->customer->name) !!}</div><div class="text-[var(--text-muted)] text-[10px]">Main Visit</div></div></div><div class="space-y-1.5 text-xs"><div class="flex items-center gap-1.5 text-[var(--text-soft)]"><i data-lucide="target" class="w-3 h-3 text-sky-500 shrink-0"></i>{!! addslashes($visit->purpose) !!}</div>@if($visit->notes)<div class="flex items-start gap-1.5 text-[var(--text-soft)]"><i data-lucide="sticky-note" class="w-3 h-3 text-[var(--text-muted)] shrink-0 mt-0.5"></i><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($visit->notes, 80) !!}"</span></div>@endif @if($visit->distance_km)<div class="flex items-center gap-1.5 text-[var(--text-soft)]"><i data-lucide="map-pin" class="w-3 h-3 text-sky-500 shrink-0"></i>{{ $visit->distance_km }} km</div>@endif</div>';
                roadModalData['office-end'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[var(--accent-soft)] border border-[var(--accent)] flex items-center justify-center"><i data-lucide="building-2" class="w-3 h-3 text-[var(--accent)]"></i></div><div><div class="text-[var(--text)] font-semibold text-sm">Office</div><div class="text-[var(--text-muted)] text-[10px]">End Point</div></div></div><p class="text-[var(--text-soft)] text-xs">Return to office after visit</p>@if($visit->pitstops && count($visit->pitstops) > 0 && $visit->pitstop_total_km)<div class="mt-2 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-[var(--accent-soft)] border border-[var(--accent)]/30"><i data-lucide="clock" class="w-3 h-3 text-[var(--accent)] shrink-0"></i><span class="text-[var(--accent)] text-xs font-bold">Total Trip: {{ $visit->pitstop_total_km }} km</span></div>@endif';
                @foreach($visit->pitstops as $idx => $ps)
                roadModalData['stop-{{ $idx }}'] = '<div class="flex items-center gap-2 mb-2"><div class="w-7 h-7 rounded-full bg-[var(--accent)] text-white text-[10px] font-bold flex items-center justify-center shrink-0">{{ $idx + 1 }}</div><div><div class="text-[var(--text)] font-semibold text-sm">{!! addslashes($ps->customer->name ?? 'N/A') !!}</div><div class="text-[var(--text-muted)] text-[10px]">{{ $ps->visited_at ? $ps->visited_at->format('d M, h:i A') : '-' }}</div></div></div><div class="space-y-1.5 text-xs">@if($ps->purpose)<div class="flex items-center gap-1.5 text-[var(--text-soft)]"><i data-lucide="target" class="w-3 h-3 text-[var(--accent)] shrink-0"></i>{!! addslashes($ps->purpose) !!}</div>@endif @if($ps->notes)<div class="flex items-start gap-1.5 text-[var(--text-soft)]"><i data-lucide="sticky-note" class="w-3 h-3 text-[var(--text-muted)] shrink-0 mt-0.5"></i><span class="italic break-words">"{!! \Illuminate\Support\Str::limit($ps->notes, 80) !!}"</span></div>@endif @if($ps->distance_km)<div class="flex items-center gap-1.5 text-[var(--text-soft)]"><i data-lucide="map-pin" class="w-3 h-3 text-[var(--accent)] shrink-0"></i>{{ $ps->distance_km }} km</div>@endif @if($ps->images && count($ps->images) > 0)<div class="flex gap-1.5 mt-2">@foreach($ps->images as $img)<img src="{{ asset($img) }}" class="w-10 h-10 object-cover rounded-lg border border-[var(--border)] road-pitstop-img cursor-pointer">@endforeach</div>@endif</div>';
                @endforeach
            </script>
        @endif

        <div id="road-modal-overlay" onclick="closeRoadModal()"></div>
        <div id="road-modal-box">
            <div class="card !rounded-2xl p-4 shadow-[var(--shadow-lg)]">
                <div id="road-modal-content" class="text-[var(--text-soft)]"></div>
            </div>
        </div>

        <div id="roadImageModal" onclick="closeRoadImage()"
            style="position:fixed !important;inset:0;z-index:10000;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,0.8);backdrop-filter:blur(8px);padding:1rem;">
            <button onclick="closeRoadImage()" class="icon-btn"
                style="position:absolute;top:1rem;right:1rem;background:rgba(0,0,0,0.5);border:none;color:white;width:2.5rem;height:2.5rem;cursor:pointer;">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <img id="roadImagePreview" src="" style="max-height:85vh;max-width:90vw;object:contain;border-radius:1rem;">
        </div>

        <script>
            function openRoadModal(key) {
                var content = document.getElementById('road-modal-content');
                var box = document.getElementById('road-modal-box');
                var overlay = document.getElementById('road-modal-overlay');
                if (!content || !box || !overlay) return;
                content.innerHTML = (typeof roadModalData !== 'undefined' && roadModalData[key]) ? roadModalData[key] : '';
                if (window.SigmaIcons) window.SigmaIcons.refresh();
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
                    ring.style.cssText = 'position:absolute;left:' + cx + 'px;top:' + cy + 'px;width:6px;height:6px;border-radius:50%;border:2px solid var(--accent);z-index:20;pointer-events:none;transform:translate(-50%,-50%);opacity:1;';
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
                            p.style.cssText = 'position:absolute;left:' + cx + 'px;top:' + cy + 'px;width:' + sz + 'px;height:' + sz + 'px;border-radius:50%;background:var(--accent);z-index:20;pointer-events:none;transform:translate(-50%,-50%);opacity:1;';
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
                <i data-lucide="image-down" class="w-5 h-5 text-[var(--accent)]"></i>
                Uploaded Images
            </h3>

            @if($visit->images && count($visit->images))
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5">
                    @foreach($visit->images as $img)
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
            <a href="{{ route('salesman.visits.index') }}" class="btn btn-dark">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Visits
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
    </script>

</div>

@endsection
