<!DOCTYPE html>
<html lang="en" x-data="{ theme: window.ThemeManager?.current() || 'dark' }" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 | Page Expired</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <script>
        (function() {
            try {
                var t = localStorage.getItem('sigma-theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', t === 'dark');
            } catch (e) {}
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased min-h-screen bg-[var(--bg)] text-[var(--text)] font-sans flex items-center justify-center overflow-hidden px-4">

    <div class="pointer-events-none fixed -top-40 -right-40 w-[28rem] h-[28rem] rounded-full opacity-25 blur-3xl animate-spin-slow"
        style="background: conic-gradient(#ff2ba6,#9333ea,#2563eb,#06b6d4,#22c55e,#facc15,#f97316,#ef4444,#ff2ba6)"></div>

    <div class="relative z-10 w-full max-w-md card card-hover !rounded-[2rem] shadow-card-lg p-9 text-center animate-fade-up">
        <p class="font-display font-black text-7xl gradient-text leading-none mb-2">419</p>
        <h2 class="font-display font-bold text-xl mt-3">Session Expired</h2>
        <p class="text-sm muted mt-2 leading-relaxed">
            Your session timed out because the page was left idle. Log in again &mdash; you will be taken
            straight to your dashboard and your previous device session is replaced automatically.
        </p>

        <a href="{{ route('login') }}" class="btn btn-primary w-full !py-3 mt-7">
            <i data-lucide="log-in" class="w-4 h-4"></i>
            Go to Login
        </a>

        <p class="text-xs muted mt-5">
            Need help? <a class="underline" href="mailto:{{ config('support.email') }}">{{ config('support.email') }}</a>
        </p>
    </div>
</body>

</html>