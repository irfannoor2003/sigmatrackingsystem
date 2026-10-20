@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="space-y-6" style="--brand: #eb268f">
    <div class="page-head">
        <div>
            <h1 class="page-title">
                <span class="page-ico"><i data-lucide="user-cog" class="w-6 h-6"></i></span>
                Profile Settings
            </h1>
            <p class="mt-2 text-sm muted">Manage your account information and security.</p>
        </div>
    </div>

    <div class="card p-5 sm:p-7">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="card p-5 sm:p-7">
        @include('profile.partials.update-password-form')
    </div>

    <div class="card p-5 sm:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3.5">
                <span class="page-ico shrink-0"><i data-lucide="layers" class="w-5 h-5"></i></span>
                <div>
                    <h2 class="font-display font-semibold text-[var(--text)]">Glass interface</h2>
                    <p class="mt-1 text-sm muted">
                        Switch the whole app between the frosted glass look and the classic layout.
                        Saved to your account.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.glass-mode') }}" class="shrink-0">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ auth()->user()?->glass_mode ? 'btn-primary' : 'btn-outline' }} !rounded-full !px-5">
                    <i data-lucide="{{ auth()->user()?->glass_mode ? 'check' : 'layers' }}" class="w-4 h-4"></i>
                    {{ auth()->user()?->glass_mode ? 'Glass is on' : 'Try glass UI' }}
                </button>
            </form>
        </div>
    </div>

    <div class="card p-5 sm:p-7 border-red-200/80 dark:border-red-500/25">
        @include('profile.partials.delete-user-form')
    </div>
</div>

@endsection
