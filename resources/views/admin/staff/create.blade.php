@extends('layouts.app')

@section('title', 'Create Staff')

@section('content')

    <div class="max-w-xl mx-auto mt-10">

        <div class="card !rounded-2xl p-8 shadow-card-lg">

            <div class="page-head !mb-6">
                <h2 class="page-title">
                    <span class="page-ico"><i data-lucide="user-plus" class="w-6 h-6"></i></span>
                    Create Staff
                </h2>
            </div>

            <form method="POST" action="{{ route('admin.staff.store') }}">
                @csrf

                <label class="label flex items-center">
                    <i data-lucide="user" class="w-4 h-4 mr-2"></i> Full Name
                </label>
                <div class="input-icon-wrap mb-4">
                    <i data-lucide="scan-face" class="w-4 h-4"></i>
                    <input name="name" type="text" value="{{ old('name') }}" placeholder="Enter full name"
                        class="input"
                        required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="label flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email
                </label>
                <div class="input-icon-wrap mb-4">
                    <i data-lucide="at-sign" class="w-4 h-4"></i>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="Enter email address"
                        class="input"
                        required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="label flex items-center">
                    <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
                </label>
                <div class="input-icon-wrap mb-4">
                    <i data-lucide="smartphone" class="w-4 h-4"></i>
                    <input name="phone" type="text" value="{{ old('phone') }}" placeholder="e.g. +923001234567"
                        class="input">
                </div>

                <label class="label flex items-center">
                    <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Password
                </label>
                <div class="input-icon-wrap mb-4">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    <input name="password" type="password" placeholder="Enter password"
                        class="input"
                        required>
                </div>

                <label class="label flex items-center">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i> Role
                </label>

                <div class="mb-6">
                    <select name="role" class="select w-full" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>

                        {{-- ADMIN & MANAGEMENT --}}
                        <optgroup label="Management" class="font-semibold">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                            <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="saleshead" {{ old('role') == 'saleshead' ? 'selected' : '' }}>Sales Head</option>
                        </optgroup>

                        {{-- SALES --}}
                        <optgroup label="Sales Team" class="font-semibold">
                            <option value="salesman" {{ old('role') == 'salesman' ? 'selected' : '' }}>Salesman</option>
                        </optgroup>

                        {{-- STAFF --}}
                        <optgroup label="Staff" class="font-semibold">
                            <option value="it" {{ old('role') == 'it' ? 'selected' : '' }}>IT Department</option>
                            <option value="account" {{ old('role') == 'account' ? 'selected' : '' }}>Accounts</option>
                            <option value="store" {{ old('role') == 'store' ? 'selected' : '' }}>Store</option>
                            <option value="office_boy" {{ old('role') == 'office_boy' ? 'selected' : '' }}>Office Boy</option>
                        </optgroup>
                    </select>
                </div>

                <button class="btn btn-primary w-full !py-3">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i> Create Staff
                </button>

            </form>
        </div>
    </div>

@endsection