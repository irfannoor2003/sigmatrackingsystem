@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="p-0">

    <h1 class="text-2xl font-semibold mb-6 text-white">
        Profile Settings
    </h1>

    {{-- Update Profile Info --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6 hover:shadow-lg transition-all duration-300">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>
        <div class="mt-4">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Update Password --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6 hover:shadow-lg transition-all duration-300">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
        <div class="mt-4">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-red-100 hover:border-red-300 hover:shadow-red-200 transition-all duration-300">
        <h2 class="text-lg font-semibold text-red-600 mb-4">Delete Account</h2>
        <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all data will be lost.</p>
        <div class="mt-4">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>

@endsection
