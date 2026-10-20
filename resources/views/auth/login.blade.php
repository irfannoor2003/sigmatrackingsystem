<x-guest-layout>
    <div class="mt-6 text-center">
        <h1 class="font-display text-[1.35rem] font-semibold tracking-[-.02em] text-[var(--text)]">
            Welcome back
        </h1>
        <p class="mt-1 text-[.8rem] text-[var(--text-muted)]">Sign in to your workspace</p>
    </div>

    @if ($errors->any())
        <div class="auth-alert mt-4 flex items-start gap-2.5 px-3.5 py-2.5">
            <i data-lucide="alert-circle" class="mt-px h-4 w-4 shrink-0 text-[var(--alert-accent)]"></i>
            <span class="text-[.78rem] leading-5">
                @foreach ($errors->all() as $error)
                    {{ $error }}{{ !$loop->last ? ' · ' : '' }}
                @endforeach
            </span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-3 "  >
        @csrf

        <div class="glass-field">
            <i data-lucide="mail" class="glass-field__icon h-4 w-4"></i>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="Email address" class="input !pl-11">
        </div>

        <div class="glass-field" x-data="{ show: false }">
            <i data-lucide="lock-keyhole" class="glass-field__icon h-4 w-4"></i>
            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                autocomplete="current-password" placeholder="Password"
                class="input !py-3 !pl-11 !pr-12">
            <button type="button" @click="show = !show" title="Toggle password visibility"
                class="glass-field__toggle">
                <span x-show="!show"><i data-lucide="eye" class="h-4 w-4"></i></span>
                <span x-show="show"><i data-lucide="eye-off" class="h-4 w-4"></i></span>
            </button>
        </div>

        <button type="submit" class="btn btn-primary glass-btn mx-auto mt-1 flex !w-auto !rounded-full !px-8 !py-2.5">
            Sign In
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between gap-3 border-t border-[var(--glass-border)] pt-5">
        <div class="auth-mini">
            <span class="auth-mini__icon"><i data-lucide="fingerprint" class="h-3.5 w-3.5"></i></span>
            <span class="text-[.7rem] font-medium">Biometric</span>
        </div>
        <div class="auth-mini">
            <span class="auth-mini__icon"><i data-lucide="map-pin" class="h-3.5 w-3.5"></i></span>
            <span class="text-[.7rem] font-medium">Visits</span>
        </div>
        <div class="auth-mini">
            <span class="auth-mini__icon"><i data-lucide="bar-chart-3" class="h-3.5 w-3.5"></i></span>
            <span class="text-[.7rem] font-medium">Reports</span>
        </div>
    </div>
</x-guest-layout>
