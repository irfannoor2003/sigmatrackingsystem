<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-display font-bold text-[1.5rem] tracking-tight text-[var(--text)]">Forgot password?</h2>
        <p class="text-sm mt-1 muted">Enter your email and we&rsquo;ll send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-5">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mb-5">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-px"></i>
            <span>
                @foreach ($errors->all() as $error)
                    {{ $error }}{{ !$loop->last ? ' · ' : '' }}
                @endforeach
            </span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="you@company.com" class="input">
        </div>

        <button type="submit" class="btn btn-primary w-full !py-3">
            <i data-lucide="send" class="w-4 h-4"></i>
            Send Reset Link
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--accent)] hover:underline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Login
        </a>
    </div>
</x-guest-layout>