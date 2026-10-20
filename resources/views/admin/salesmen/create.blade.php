@extends('layouts.app')

@section('title','Create Salesman')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="card !rounded-2xl p-8 shadow-card-lg">

        <div class="page-head !mb-6">
            <h2 class="page-title">
                <span class="page-ico"><i data-lucide="user-plus" class="w-6 h-6"></i></span>
                Create Salesman
            </h2>
        </div>

        <form method="POST" action="{{ route('admin.salesmen.store') }}">
            @csrf

            <label class="label flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Name
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="scan-face" class="w-4 h-4"></i>
                <input
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="input"
                    placeholder="Enter salesman's full name"
                    required
                >
            </div>

            <label class="label flex items-center">
                <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="at-sign" class="w-4 h-4"></i>
                <input
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="input"
                    placeholder="Enter email address"
                    required
                >
            </div>

            <label class="label flex items-center">
                <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Phone (WhatsApp)
            </label>
            <div class="input-icon-wrap mb-4">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                <input name="phone" type="text" value="{{ old('phone') }}" placeholder="e.g. +923001234567" class="input">
            </div>

            <label class="label flex items-center">
                <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Password
            </label>
            <div class="input-icon-wrap mb-6">
                <i data-lucide="key" class="w-4 h-4"></i>
                <input
                    name="password"
                    type="password"
                    class="input"
                    placeholder="Enter password"
                    required
                >
            </div>

            <button class="btn btn-primary w-full !py-3">
                <i data-lucide="save" class="w-5 h-5 mr-2"></i> Create Salesman
            </button>
        </form>

    </div>
</div>

@endsection