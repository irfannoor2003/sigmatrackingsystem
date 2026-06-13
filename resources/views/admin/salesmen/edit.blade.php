@extends('layouts.app')

@section('title','Edit Salesman')

@section('content')

<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
             p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        <i data-lucide="user-cog" class="w-7 h-7 mr-3 text-[#ff2ba6]"></i> Edit Salesman
    </h2>

    <form method="POST" action="{{ route('admin.salesmen.update', $salesman->id) }}">
        @csrf
        @method('PUT')

        <!-- Name -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Name
        </label>
        <div class="relative mb-4">
            <i data-lucide="scan-face" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/50"></i>
            <input
                name="name"
                type="text"
                value="{{ old('name', $salesman->name) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                required
            >
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
                value="{{ old('email', $salesman->email) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                required
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
                value="{{ old('phone', $salesman->phone) }}"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="e.g. +923001234567"
            >
        </div>

        <!-- Password (Optional) -->
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

        <button
            class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center
                   bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
                   hover:opacity-90 transition">
            <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Salesman
        </button>
    </form>

</div>

@endsection
