@extends('layouts.app')

@section('title', 'Start Visit')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    /* Styling to match your existing glassmorphism theme */
    .ts-wrapper .ts-control {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-radius: 0.5rem !important;
        padding: 12px !important;
        transition: all 0.3s ease;
    }
    .ts-wrapper.focus .ts-control {
        background: rgba(255, 255, 255, 0.2) !important;
        box-shadow: none !important;
    }
    .ts-dropdown {
        background: #1e1e1e !important; /* Dark background for dropdown */
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.5rem !important;
        margin-top: 5px !important;
    }
    .ts-dropdown .option {
        padding: 10px 12px !important;
    }
    .ts-dropdown .active {
        background: #ff2ba6 !important; /* Your signature pink color */
        color: white !important;
    }
    .ts-control input {
        color: white !important;
    }
    .ts-wrapper.single .ts-control::after {
        border-color: white transparent transparent transparent !important;
    }
</style>

<div class="max-w-xl mx-auto p-0 md:p-6">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal mr-3 text-[#ff2ba6]">
            <path d="m3 3 3 9-3 9 19-9Z"/>
            <path d="M6 12h16"/>
        </svg>
        Start a Visit
    </h1>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-400/40 text-red-100 rounded-xl backdrop-blur-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-2">
                <circle cx="12" cy="12" r="10"/>
                <path d="m15 9-6 6"/>
                <path d="m9 9 6 6"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-2xl shadow-xl">

        <form id="startVisitForm" method="POST" action="{{ route('salesman.visits.store') }}">
            @csrf

            {{-- Customer Selection with Search --}}
            <label for="customer_id" class="block text-sm text-white/80 mb-1 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building mr-2">
                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                    <path d="M9 22v-4h6v4"/>
                </svg>
                Select Customer
            </label>
            <div class="mb-4">
                <select name="customer_id" id="customer_search" required>
                    <option value="">Search or choose a customer...</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Purpose --}}
            <label for="purpose" class="block text-sm text-white/80 mb-1 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target mr-2">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                Purpose of Visit
            </label>
            <select name="purpose" id="purpose"
                    class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white
                           placeholder-white/50 focus:bg-white/20 outline-none appearance-none"
                    required>
                <option value="" class="text-black">-- Select Purpose --</option>
                <option value="Complaint Visit" class="text-black">Complaint Visit</option>
                <option value="Delivery" class="text-black">Delivery </option>
                <option value="Follow-up" class="text-black">Follow-up</option>
                <option value="New Lead Visit" class="text-black">New Lead Visit</option>
                <option value="Order Taking" class="text-black">Order Taking</option>
                <option value="Office Work" class="text-black">Office Work</option>
                <option value="Product Details" class="text-black">Product Details</option>
                <option value="Payment Collection" class="text-black">Payment Collection</option>
                <option value="Recovery" class="text-black">Recovery</option>

            </select>

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <button type="submit" class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg hover:opacity-90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation mr-2">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
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

// GPS and Form Submission Logic
document.getElementById('startVisitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    submitButton.innerHTML = 'Finding location...';
    submitButton.disabled = true;

    if (!navigator.geolocation) {
        alert("Your browser does not support geolocation.");
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('lat').value = position.coords.latitude;
        document.getElementById('lng').value = position.coords.longitude;
        submitButton.innerHTML = 'Starting Visit...';
        e.target.submit();
    }, function(error) {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        let errorMessage = "Unable to get your location.";
        switch(error.code) {
            case error.PERMISSION_DENIED: errorMessage = "GPS permission denied."; break;
            case error.POSITION_UNAVAILABLE: errorMessage = "GPS signal not available."; break;
            case error.TIMEOUT: errorMessage = "GPS request timed out."; break;
        }
        alert(errorMessage);
    }, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
});
</script>
@endsection
