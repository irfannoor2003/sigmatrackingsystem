@extends('layouts.app')

@section('title', 'Edit Staff Member')

@section('content')

<div class="max-w-xl mx-auto mt-10">

    <div class="card !rounded-2xl p-8 shadow-card-lg">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="user-cog" class="w-6 h-6"></i></span>
                Edit Staff Member
            </h2>
        </div>

        <form method="POST" action="{{ route('hr.staff.update', $staff->id) }}">
            @csrf
            @method('PUT')

            <label class="label flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Full Name
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="scan-face" class="w-4 h-4"></i>
                <input
                    name="name"
                    type="text"
                    value="{{ old('name', $staff->name) }}"
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
                    value="{{ old('email', $staff->email) }}"
                    class="input"
                    required
                >
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="label flex items-center">
                <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i> Role
            </label>
            <div class="mb-4">
                <select name="role" class="select w-full" required>
                    <option value="admin" {{ $staff->role == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    <option value="salesman" {{ $staff->role == 'salesman' ? 'selected' : '' }}>Salesman</option>
                    <option value="it" {{ $staff->role == 'it' ? 'selected' : '' }}>IT Department</option>
                    <option value="account" {{ $staff->role == 'account' ? 'selected' : '' }}>Accounts</option>
                    <option value="store" {{ $staff->role == 'store' ? 'selected' : '' }}>Store</option>
                    <option value="office_boy" {{ $staff->role == 'office_boy' ? 'selected' : '' }}>Office_boy</option>
                    <option value="hr" {{ $staff->role == 'hr' ? 'selected' : '' }}>Hr</option>
                    <option value="saleshead" {{ $staff->role == 'saleshead' ? 'selected' : '' }}>Saleshead</option>
                </select>
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

            <label class="label flex items-center">
                <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
            </label>
            <div class="input-icon-wrap mb-6">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                <input
                    name="phone"
                    type="text"
                    value="{{ old('phone', $staff->phone) }}"
                    class="input"
                    placeholder="e.g. +923001234567"
                >
            </div>

            <button class="btn btn-primary w-full !py-3">
                <i data-lucide="save" class="w-5 h-5 mr-2"></i> Update Staff
            </button>
        </form>

    </div>
</div>

@endsection