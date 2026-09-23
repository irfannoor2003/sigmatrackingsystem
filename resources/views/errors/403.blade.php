@extends('layouts.app')

@php
    $reason = '';
    if (isset($exception) && $exception && method_exists($exception, 'getMessage')) {
        $reason = $exception->getMessage();
    } elseif (isset($message) && $message) {
        $reason = $message;
    }
@endphp

@section('title', $reason ?: 'Access Denied')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-2xl w-full glass rounded-2xl p-8 text-center border border-white/10">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-[#ff2ba6] to-[#ff2ba6] shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v2H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-1V6a4 4 0 00-4-4zm-2 6V6a2 2 0 114 0v2H8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-extrabold mb-2">{{ $reason ?: 'Access Denied' }}</h1>

            <p class="text-white/80 mb-6">
                @if($reason)
                    {{ $reason }}
                @else
                    You do not have permission to access this page.
                @endif
            </p>

            <div class="flex items-center justify-center gap-3">
                <a href="mailto:{{ config('mail.from.address', 'admin@example.com') }}" class="px-6 py-3 rounded-xl bg-[#ff2ba6] text-black font-semibold shadow hover:opacity-90">Contact Admin</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 rounded-xl bg-white/10 border border-white/20 text-white font-semibold hover:bg-white/20">Logout</button>
                </form>
            </div>

            <p class="text-xs text-white/50 mt-6">If you believe this is an error, contact your administrator with your user email.</p>
        </div>
    </div>
@endsection
