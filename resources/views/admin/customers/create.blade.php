@extends('layouts.app')

@section('title','Add Customer')

@section('content')

<div class="max-w-xl mx-auto p-0">

    <h1 class="text-3xl font-extrabold text-white mb-6 tracking-wide flex items-center gap-3">
        <i data-lucide="user-plus" class="w-7 h-7 text-[var(--hf-magenta-light)]"></i>
        Add New Customer
    </h1>

    <div class="glass p-6 rounded-2xl border border-white/20 shadow-xl">

        {{-- Note: For Admin panel, the route might be admin.customers.store --}}
        <form method="POST" action="{{ route('admin.customers.store') }}">
            @csrf

            <label for="name" class="block text-white/80 text-sm mb-1 font-medium">Customer Name *</label>
            <div class="relative mb-4">
                <i data-lucide="building-2" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/50 pointer-events-none"></i>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter customer name"
                    value="{{ old('name') }}"
                    required
                    class="w-full p-3 pl-10 rounded-lg bg-white/10 text-white placeholder-white/50
                        focus:bg-white/20 outline-none transition @error('name') border border-red-500/40 @enderror"
                >
            </div>
            @error('name')
                <p class="text-red-400 text-sm mb-4 -mt-3">{{ $message }}</p>
            @enderror


            <label for="phone1" class="block text-white/80 text-sm mb-1 font-medium">Phone *</label>
            <div class="relative mb-4">
                <i data-lucide="phone" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/50 pointer-events-none"></i>
                <input
                    type="text"
                    id="phone1"
                    name="phone1"
                    placeholder="Enter phone number"
                    value="{{ old('phone1') }}"
                    required
                    class="w-full p-3 pl-10 rounded-lg bg-white/10 text-white placeholder-white/50
                        focus:bg-white/20 outline-none transition @error('phone1') border border-red-500/40 @enderror"
                >
            </div>
            @error('phone1')
                <p class="text-red-400 text-sm mb-4 -mt-3">{{ $message }}</p>
            @enderror


            <label for="city" class="block text-white/80 text-sm mb-1 font-medium">City</label>
            <div class="relative mb-6">
                <i data-lucide="map-pin" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/50 pointer-events-none"></i>
                <input
                    type="text"
                    id="city"
                    name="city"
                    placeholder="Enter city"
                    value="{{ old('city') }}"
                    class="w-full p-3 pl-10 rounded-lg bg-white/10 text-white placeholder-white/50
                        focus:bg-white/20 outline-none transition @error('city') border border-red-500/40 @enderror"
                >
            </div>
            @error('city')
                <p class="text-red-400 text-sm mb-4 -mt-3">{{ $message }}</p>
            @enderror


            <button type="submit"
                class="w-full py-3 rounded-xl text-white font-bold tracking-wide flex items-center justify-center gap-2
                    shadow-lg transition-all duration-300
                    bg-gradient-to-r from-[var(--hf-magenta)] to-[var(--hf-magenta-light)] hover:opacity-90">
                <i data-lucide="save" class="w-5 h-5"></i>
                Save Customer
            </button>

        </form>

    </div>

</div>

@endsection
