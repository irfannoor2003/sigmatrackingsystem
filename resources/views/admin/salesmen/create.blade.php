@extends('layouts.app')

@section('title','Create Salesman')

@section('content')

<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
             p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide flex items-center">
        <i data-lucide="user-plus" class="w-7 h-7 mr-3 text-pink-400"></i> Create Salesman
    </h2>

    <form method="POST" action="{{ route('admin.salesmen.store') ?? '#' }}">
        @csrf

        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Name
        </label>
        <div class="relative mb-4">
            <i data-lucide="scan-face" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-white/50"></i>
            <input
                name="name"
                type="text"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter salesman's full name"
                required
            >
        </div>

        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email
        </label>
        <div class="relative mb-4">
            <i data-lucide="at-sign" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-white/50"></i>
            <input
                name="email"
                type="email"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter email address"
                required
            >
        </div>

        <!-- Phone -->
        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
        </label>
        <div class="relative mb-4">
            <i data-lucide="smartphone" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-white/50"></i>
            <input name="phone" type="text" value="{{ old('phone') }}" placeholder="e.g. +923001234567"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none">
        </div>

        <label class="block text-sm text-white/80 mb-1 flex items-center">
            <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Password
        </label>
        <div class="relative mb-6">
            <i data-lucide="key" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-white/50"></i>
            <input
                name="password"
                type="password"
                class="w-full px-4 py-3 pl-10 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter password"
                required
            >
        </div>

        <button
            class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center
                   bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
                   hover:opacity-90 transition">
            <i data-lucide="save" class="w-5 h-5 mr-2"></i> Create Salesman
        </button>
    </form>

</div>

@endsection
