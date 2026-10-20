<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ theme: window.ThemeManager?.current() || 'light', glass: document.documentElement.classList.contains('glass-ui') }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sigma') }} · Operations Platform</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">

    <script>
        (function() {
            try {
                var t = localStorage.getItem('sigma-theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', t === 'dark');
            } catch (e) {}
        })();
    </script>

    {{-- Glass preference (guest pages have no session, so this reads localStorage).
         Defaults to ON. ?glass=1 / ?glass=0 forces a value. --}}
    <script>
        (function() {
            try {
                var q = new URLSearchParams(location.search).get('glass');
                var stored = localStorage.getItem('sigma-glass');
                var on = q === '1' ? true : q === '0' ? false : (stored === null ? true : stored === '1');
                document.documentElement.classList.toggle('glass-ui', on);
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-[100dvh] overflow-hidden antialiased font-sans text-[var(--text)]">
    <div class="auth-stage">

        {{-- Blurred gradient mesh + drifting colour blobs (glass mode only) --}}
        <div class="glass-bg" aria-hidden="true">
            <div class="auth-mesh"></div>
            <div class="auth-blob auth-blob--magenta auth-blob--1"></div>
            <div class="auth-blob auth-blob--violet auth-blob--2"></div>
            <div class="auth-blob auth-blob--cyan auth-blob--3"></div>
            <div class="auth-blob auth-blob--amber auth-blob--4"></div>
            <div class="auth-blob auth-blob--magenta auth-blob--5"></div>
            <div class="auth-vignette"></div>
            <div class="auth-grain"></div>
        </div>

        {{-- Appearance controls: (1) light/dark  (2) glassmorphism on/off --}}
        <div class="fixed right-4 top-4 z-20 flex items-center gap-1.5">
            <button @click="window.ThemeManager.toggle(); theme = window.ThemeManager.current()"
                class="glass-toggle-btn flex !h-9 !w-9 items-center justify-center rounded-xl border"
                :title="theme === 'dark' ? 'Light mode' : 'Dark mode'"
                :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'">
                <span x-show="theme !== 'dark'"><i data-lucide="moon" class="w-[1.05rem] h-[1.05rem]"></i></span>
                <span x-show="theme === 'dark'"><i data-lucide="sun" class="w-[1.05rem] h-[1.05rem]"></i></span>
            </button>

            <button @click="glass = !glass; document.documentElement.classList.toggle('glass-ui', glass); localStorage.setItem('sigma-glass', glass ? '1' : '0')"
                class="glass-toggle-btn relative flex !h-9 !w-9 items-center justify-center rounded-xl border"
                :title="glass ? 'Glassmorphism is on — turn off' : 'Glassmorphism is off — turn on'"
                :aria-pressed="glass">
                <i data-lucide="layers" class="w-[1.05rem] h-[1.05rem]"></i>
                <span class="glass-dot" x-show="glass"></span>
            </button>
        </div>

        <main class="relative z-10 flex w-full flex-1 items-center justify-center px-5 py-8">

            <div class="auth-enter w-[86%] min-w-[16.5rem] max-w-[32rem] sm:w-[74%] lg:w-[54%] xl:w-[42%] 2xl:w-[33%]">
                <div class="glass auth-card px-6 py-7 sm:px-9 sm:py-9">
                    <span class="auth-card-line" aria-hidden="true"></span>

                    <div class="flex justify-center">
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('images/logo.webp') }}" alt="Sigma Engineering Services"
                                class="h-9 w-auto max-w-[11rem] object-contain drop-shadow-[0_2px_10px_rgba(0,0,0,.25)]">
                        </a>
                    </div>

                    {{ $slot }}
                </div>

                <p class="mt-5 text-center text-[.68rem] text-[var(--text-muted)] opacity-80">
                    &copy; {{ date('Y') }} Sigma Engineering Services
                </p>
            </div>

        </main>
    </div>
</body>

</html>
