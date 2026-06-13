@extends('layouts.app')

@section('title', 'Edit Staff Member')

@section('content')

<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
             p-8 rounded-2xl shadow-xl mt-10">

    {{-- Header --}}
    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        <i data-lucide="user-cog" class="w-7 h-7 mr-3 text-[#ff2ba6]"></i> Edit Staff Member
    </h2>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
        @csrf
        @method('PUT')

        <!-- Name -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Full Name
        </label>
        <div class="relative mb-4">
            <i data-lucide="scan-face" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/50"></i>
            <input
                name="name"
                type="text"
                value="{{ old('name', $staff->name) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                required
            >
            @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email
        </label>
        <div class="relative mb-4">
            <i data-lucide="at-sign" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/50"></i>
            <input
                name="email"
                type="email"
                value="{{ old('email', $staff->email) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                required
            >
            @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Role -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i> Role
        </label>
        <div class="relative mb-4">
            <select
                name="role"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white placeholder-white/50 focus:bg-white/20 outline-none"
                required
            >
                <option value="admin" {{ $staff->role == 'admin' ? 'selected' : '' }} class="text-black">Admin (Full Access)</option>
                <option value="salesman" {{ $staff->role == 'salesman' ? 'selected' : '' }} class="text-black">Salesman</option>
                <option value="it" {{ $staff->role == 'it' ? 'selected' : '' }} class="text-black">IT Department</option>
                <option value="account" {{ $staff->role == 'account' ? 'selected' : '' }} class="text-black">Accounts</option>
                <option value="store" {{ $staff->role == 'store' ? 'selected' : '' }} class="text-black">Store</option>
                <option value="office_boy" {{ $staff->role == 'office_boy' ? 'selected' : '' }} class="text-black">Office_boy</option>
                <option value="hr" {{ $staff->role == 'hr' ? 'selected' : '' }} class="text-black">Hr</option>
                <option value="saleshead" {{ $staff->role == 'saleshead' ? 'selected' : '' }} class="text-black">Saleshead</option>
            </select>
        </div>

        <!-- Password -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="lock" class="w-4 h-4 mr-2"></i> New Password (optional)
        </label>
        <div class="relative mb-6">
            <i data-lucide="key" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/50"></i>
            <input
                name="password"
                type="password"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Leave blank to keep current password"
            >
        </div>

        <!-- Phone -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
        </label>
        <div class="relative mb-6">
            <i data-lucide="smartphone" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/50"></i>
            <input
                name="phone"
                type="text"
                value="{{ old('phone', $staff->phone) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="e.g. +923001234567"
            >
        </div>

        <!-- Submit Button -->
        <button
            class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center
                   bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
                   hover:opacity-90 transition"
        >
            <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Staff
        </button>
    </form>

</div>

@endsection
