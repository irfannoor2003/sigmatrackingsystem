@extends('layouts.app')

@section('title','Add Customer')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="page-head">
        <h1 class="page-title">
            <span class="page-ico"><i data-lucide="user-plus" class="w-6 h-6"></i></span>
            Add New Customer
        </h1>
    </div>

    <div class="card !rounded-2xl p-6">

        {{-- Note: For Admin panel, the route might be admin.customers.store --}}
        <form method="POST" action="{{ route('admin.customers.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="label">Customer Name *</label>
                <div class="input-icon-wrap">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter customer name"
                        value="{{ old('name') }}"
                        required
                        class="input @error('name') input-error @enderror"
                    >
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone1" class="label">Phone *</label>
                <div class="input-icon-wrap">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    <input
                        type="text"
                        id="phone1"
                        name="phone1"
                        placeholder="Enter phone number"
                        value="{{ old('phone1') }}"
                        required
                        class="input @error('phone1') input-error @enderror"
                    >
                </div>
                @error('phone1')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="city" class="label">City</label>
                <div class="input-icon-wrap">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        placeholder="Enter city"
                        value="{{ old('city') }}"
                        class="input @error('city') input-error @enderror"
                    >
                </div>
                @error('city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i data-lucide="save" class="w-5 h-5"></i>
                Save Customer
            </button>

        </form>

    </div>

</div>

@endsection