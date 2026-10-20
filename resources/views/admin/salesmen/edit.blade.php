@extends('layouts.app')

@section('title','Edit Salesman')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="card !rounded-2xl p-8 shadow-card-lg">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="user-cog" class="w-6 h-6"></i></span>
                Edit Salesman
            </h2>
        </div>

        <form method="POST" action="{{ route('admin.salesmen.update', $salesman->id) }}">
            @csrf
            @method('PUT')

            <label class="label flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Name
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="scan-face" class="w-4 h-4"></i>
                <input
                    name="name"
                    type="text"
                    value="{{ old('name', $salesman->name) }}"
                    class="input"
                    required
                >
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="label flex items-center">
                <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="at-sign" class="w-4 h-4"></i>
                <input
                    name="email"
                    type="email"
                    value="{{ old('email', $salesman->email) }}"
                    class="input"
                    required
                >
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="label flex items-center">
                <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
            </label>
            <div class="input-icon-wrap mb-6">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                <input
                    name="phone"
                    type="text"
                    value="{{ old('phone', $salesman->phone) }}"
                    class="input"
                    placeholder="e.g. +923001234567"
                >
            </div>

            <label class="label flex items-center">
                <i data-lucide="lock" class="w-4 h-4 mr-2"></i> New Password (optional)
            </label>
            <div class="input-icon-wrap mb-6">
                <i data-lucide="key" class="w-4 h-4"></i>
                <input
                    name="password"
                    type="password"
                    class="input"
                    placeholder="Leave blank to keep current password"
                >
            </div>

            <button class="btn btn-primary w-full !py-3">
                <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Salesman
            </button>
        </form>

    </div>
</div>

@endsection