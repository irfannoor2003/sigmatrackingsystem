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
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="card card-hover !rounded-3xl shadow-card-lg p-9 sm:p-12 text-center max-w-lg w-full animate-fade-up">
            <div class="mx-auto w-20 h-20 rounded-full gradient-surface flex items-center justify-center mb-6 shadow-glow">
                <i data-lucide="shield-alert" class="w-9 h-9 text-white"></i>
            </div>

            <h1 class="font-display font-bold text-2xl text-[var(--text)] mb-2">
                {{ $reason ?: 'Access Denied' }}
            </h1>

            <p class="text-sm muted mb-8 leading-relaxed">
                @if ($reason)
                    {{ $reason }}
                @else
                    You do not have permission to access this page.
                @endif
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="mailto:{{ config('support.email') }}"
                   class="btn btn-primary w-full sm:w-auto">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    Contact Admin
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="btn btn-outline w-full">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>
                </form>
            </div>

            <p class="text-xs muted mt-6">If you believe this is an error, contact your administrator.</p>
        </div>
    </div>
@endsection