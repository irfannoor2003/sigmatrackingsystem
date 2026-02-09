@extends('layouts.app')

@section('title','Login')

@section('content')

<div class="flex justify-center items-center py-10">

    <div class="w-full max-w-md p-8 glass rounded-2xl shadow-xl border border-white/10">

        <h2 class="text-3xl font-extrabold text-white text-center tracking-wide mb-2">
            Welcome Back
        </h2>

        <p class="text-center text-gray-300 mb-6">
            Login to continue
        </p>

        {{-- Validation / Auth Errors --}}
@if ($errors->any())
    <div class="mb-4 p-3 rounded-lg
                bg-red-500/10 border border-red-400/40
                text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <label class="block text-sm font-medium text-gray-200 mb-1">Email</label>
            <input
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white placeholder-gray-300
                       focus:bg-white/20 outline-none border border-white/10 mb-4"
                placeholder="Enter your email"
            />


            <!-- Password -->
<label class="block text-sm font-medium text-gray-200 mb-1">Password</label>

<div class="relative mb-4">
    <input
        id="password"
        name="password"
        type="password"
        required
        class="w-full px-4 py-3 pr-12 rounded-lg bg-white/10 text-white placeholder-gray-300
               focus:bg-white/20 outline-none border border-white/10"
        placeholder="Enter your password"
    />

    <!-- Eye Icon -->
    <button
        type="button"
        onclick="togglePassword()"
        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-300 hover:text-white focus:outline-none"
        aria-label="Toggle password visibility"
    >
        <!-- Eye -->
        <svg id="eye-open" xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

        <!-- Eye Slash -->
        <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.542-7
                     a9.956 9.956 0 012.042-3.368M6.18 6.18
                     A9.956 9.956 0 0112 5
                     c4.478 0 8.268 2.943 9.542 7
                     a9.978 9.978 0 01-4.043 5.511M15 12
                     a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3"/>
        </svg>
    </button>
</div>


            <!-- Remember + Forgot -->
            {{-- <div class="flex items-center justify-between mb-4 text-gray-200 text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="underline hover:text-white">
                    Forgot?
                </a>
            </div> --}}

            <!-- Button -->
            <button
                type="submit"
                class="w-full py-3 rounded-lg font-bold tracking-wide
                       bg-[var(--hf-magenta)] hover:bg-[var(--hf-magenta-light)]
                       transition-all shadow-lg shadow-[rgba(214,0,123,0.4)]">
                Sign In
            </button>
        </form>

        <p class="text-center text-gray-300 text-sm mt-6">
            Need an account? Contact admin.
        </p>

    </div>
</div>

@endsection


<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (password.type === 'password') {
            password.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            password.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
