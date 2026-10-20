@extends('layouts.app')

@section('title', 'Start Visit')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-wrapper .ts-control {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        border-radius: .75rem !important;
        padding: .75rem 1rem !important;
        transition: all .2s ease;
    }

    .ts-wrapper.focus .ts-control {
        background: var(--surface) !important;
        box-shadow: 0 0 0 3px var(--accent-soft) !important;
    }

    .ts-dropdown {
        background: var(--surface) !important;
        color: var(--text) !important;
        border: 1px solid var(--border) !important;
        border-radius: .75rem !important;
        margin-top: 5px !important;
        box-shadow: var(--shadow-lg) !important;
    }

    .ts-dropdown .option {
        padding: .6rem .75rem !important;
        color: var(--text-soft) !important;
    }

    .ts-dropdown .active {
        background: var(--accent-soft) !important;
        color: var(--text) !important;
    }

    .ts-dropdown .active .item {
        background: var(--accent) !important;
        color: #fff !important;
    }

    .ts-control input {
        color: var(--text) !important;
    }

    .ts-wrapper.single .ts-control::after {
        border-color: var(--text-muted) transparent transparent transparent !important;
    }
</style>

<div class="max-w-xl mx-auto">

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="send-horizontal" class="w-6 h-6"></i></span>
            Start a Visit
        </h1>
    </div>


    <div class="card !rounded-2xl p-6 md:p-8">

        <form id="startVisitForm" method="POST" action="{{ route('salesman.visits.store') }}">
            @csrf

            <label for="customer_id" class="label flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-[var(--accent)]"></i>
                Select Customer
            </label>
            <div class="mb-5">
                <select name="customer_id" id="customer_search" required>
                    <option value="">Search or choose a customer...</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <label for="purpose" class="label flex items-center gap-2">
                <i data-lucide="target" class="w-4 h-4 text-[var(--accent)]"></i>
                Purpose of Visit
            </label>
            <select name="purpose" id="purpose" class="select w-full mb-6" required>
                <option value="">-- Select Purpose --</option>
                <option value="Complaint Visit">Complaint Visit</option>
                <option value="Delivery">Delivery </option>
                <option value="Follow-up">Follow-up</option>
                <option value="New Lead Visit">New Lead Visit</option>
                <option value="Order Taking">Order Taking</option>
                <option value="Office Work">Office Work</option>
                <option value="Product Details">Product Details</option>
                <option value="Payment Collection">Payment Collection</option>
                <option value="Recovery">Recovery</option>
            </select>

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <div id="gpsNotice"
                class="mb-4 hidden items-center gap-2 px-3 py-2.5 rounded-xl bg-amber-500/10 border border-amber-400/30 text-amber-700 dark:text-amber-300 text-xs">
                <i data-lucide="map-pin-off" class="w-3.5 h-3.5 shrink-0"></i>
                <span id="gpsNoticeText">Waiting for GPS…</span>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="navigation" class="w-4 h-4"></i>
                Start Visit
            </button>
        </form>
    </div>
</div>

<script>
// Initialize Searchable Dropdown
new TomSelect("#customer_search",{
    create: false,
    sortField: { field: "text", direction: "asc"}
});

/* ------------------------------------------------------------------
   GPS helper
   Geolocation is unreliable (and blocked outright) on plain-HTTP hosts
   such as http://salesmantrackingsystem.test, so we:
     1. try a cached / low-accuracy fix first (fast),
     2. then escalate to a high-accuracy fix with a generous timeout,
     3. and only surface an error if both fail.
------------------------------------------------------------------ */
window.SigmaGps = (function () {
    var lastFix = null;

    function remember(lat, lng) {
        lastFix = { lat: lat, lng: lng };
    }

    function isStale() {
        return !lastFix;
    }

    function request(options) {
        return new Promise(function (resolve, reject) {
            if (!navigator.geolocation) {
                reject({ code: 0, message: 'Geolocation is not supported by this browser.' });
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    remember(pos.coords.latitude, pos.coords.longitude);
                    resolve(pos.coords);
                },
                function (err) { reject(err); },
                options
            );
        });
    }

    return {
        isStale: isStale,
        get: function () {
            // 1) cheap cached / network-assisted fix
            return request({ enableHighAccuracy: false, timeout: 8000, maximumAge: 600000 })
                .catch(function () {
                    // 2) high accuracy retry
                    return request({ enableHighAccuracy: true, timeout: 20000, maximumAge: 0 });
                })
                .catch(function (err) {
                    // 3) last known fix from earlier in this session
                    if (lastFix) return { latitude: lastFix.lat, longitude: lastFix.lng, stale: true };
                    throw err;
                });
        },
        message: function (err) {
            if (!window.isSecureContext) {
                return 'GPS needs a secure connection (HTTPS or localhost). '
                    + (err && err.message ? err.message : '');
            }
            switch (err && err.code) {
                case 1: return 'GPS permission denied. Allow location access and try again.';
                case 2: return 'GPS signal not available. Move near a window and retry.';
                case 3: return 'GPS request timed out. Check your location settings and retry.';
                default: return (err && err.message) ? err.message : 'Unable to get your location.';
            }
        }
    };
})();

// GPS and Form Submission Logic
document.getElementById('startVisitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const notice = document.getElementById('gpsNotice');
    const noticeText = document.getElementById('gpsNoticeText');

    submitButton.innerHTML = 'Finding location...';
    submitButton.disabled = true;

    notice.classList.remove('hidden');
    notice.classList.add('flex');
    noticeText.textContent = 'Finding your location…';

    window.SigmaGps.get().then(function (coords) {
        document.getElementById('lat').value = coords.latitude;
        document.getElementById('lng').value = coords.longitude;
        noticeText.textContent = coords.stale
            ? 'Using your last known location — move outdoors for a live fix.'
            : 'GPS locked. Starting visit…';
        submitButton.innerHTML = 'Starting Visit...';
        form.submit();
    }).catch(function (error) {
        submitButton.innerHTML = '<i data-lucide="navigation" class="w-4 h-4"></i> Start Visit';
        submitButton.disabled = false;
        if (window.SigmaIcons) window.SigmaIcons.refresh();

        noticeText.textContent = window.SigmaGps.message(error);
        notice.classList.remove('hidden');
        notice.classList.add('flex');
    });
});
</script>
@endsection
