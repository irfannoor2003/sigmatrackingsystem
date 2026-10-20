<x-guest-layout>
    <div class="mt-6 text-center">
        <h1 class="font-display text-[1.45rem] font-semibold leading-snug tracking-[-.02em] text-[var(--text)]">
            One clear view of your business.
        </h1>
        <p class="mx-auto mt-1.5 max-w-[19rem] text-[.8rem] leading-5 text-[var(--text-muted)]">
            Visits, attendance, and staff records in one workspace.
        </p>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-2.5">
        <div class="auth-feature p-3.5 text-center">
            <span class="glass-chip mx-auto h-8 w-8 text-[var(--accent)]">
                <i data-lucide="fingerprint" class="h-4 w-4"></i>
            </span>
            <p class="mt-2.5 text-[.72rem] font-semibold text-[var(--text)]">Biometric</p>
            <p class="mt-0.5 text-[.65rem] leading-4 text-[var(--text-muted)]">Attendance</p>
        </div>
        <div class="auth-feature p-3.5 text-center">
            <span class="glass-chip mx-auto h-8 w-8 text-sky-600 dark:text-sky-400">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
            </span>
            <p class="mt-2.5 text-[.72rem] font-semibold text-[var(--text)]">Visits</p>
            <p class="mt-0.5 text-[.65rem] leading-4 text-[var(--text-muted)]">Field tracking</p>
        </div>
        <div class="auth-feature p-3.5 text-center">
            <span class="glass-chip mx-auto h-8 w-8 text-emerald-600 dark:text-emerald-400">
                <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
            </span>
            <p class="mt-2.5 text-[.72rem] font-semibold text-[var(--text)]">Reports</p>
            <p class="mt-0.5 text-[.65rem] leading-4 text-[var(--text-muted)]">Live insight</p>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        <a href="{{ route('login') }}"
            class="btn btn-primary glass-btn !w-auto !rounded-full !px-7 !py-2.5">
            Get Started
        </a>
    </div>
</x-guest-layout>
