<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-display font-bold text-[1.5rem] tracking-tight text-[var(--text)]">Verify your email</h2>
        <p class="text-sm mt-1 muted">Check your inbox for a verification link</p>
    </div>

    <div class="alert alert-info !text-sm">
        <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
        <span>{{ __('Thanks for signing up! Click the link we emailed you to activate your account.') }}</span>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mt-4">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
            <span>{{ __('A new verification link has been sent to your email.') }}</span>
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    {{ __('Resend Verification') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="btn btn-ghost btn-sm text-[var(--text-soft)]">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>