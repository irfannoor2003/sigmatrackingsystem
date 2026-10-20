import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;

/* ------------------------------------------------------------
   Theme manager (white / black / magenta, persists, respects OS)
------------------------------------------------------------ */
window.ThemeManager = {
    init() {
        const stored = localStorage.getItem('sigma-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        this.apply(stored ? stored : (prefersDark ? 'dark' : 'light'));
    },
    apply(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('sigma-theme', theme);
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: theme }));
    },
    toggle() {
        this.apply(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
    },
    current() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    },
};

/* ------------------------------------------------------------
   Alpine components
------------------------------------------------------------ */
document.addEventListener('alpine:init', () => {
    Alpine.directive('icons', (el) => {
        createIcons({ icons });
    });

    /* Sidebar + topbar shell state */
    Alpine.data('appShell', () => ({
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sigma-sidebar-collapsed') === 'true',
        theme: window.ThemeManager.current(),
        init() {
            this.$watch('theme', (v) => window.ThemeManager.apply(v));
            this.$watch('sidebarCollapsed', (value) => {
                localStorage.setItem('sigma-sidebar-collapsed', value ? 'true' : 'false');
            });
            window.addEventListener('theme-changed', (e) => (this.theme = e.detail));
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        },
        toggleTheme() {
            window.ThemeManager.toggle();
        },
    }));

    /* Real-time clock + live location widget */
    Alpine.data('liveClock', () => ({
        time: '',
        date: '',
        day: '',
        loc: null,
        locLoading: false,
        locError: null,
        interval: null,
        init() {
            this.tick();
            this.interval = setInterval(() => this.tick(), 1000);
        },
        tick() {
            const n = new Date();
            this.time = n.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            this.date = n.toLocaleDateString([], { weekday: 'short', day: '2-digit', month: 'short' });
            this.day = n.toLocaleDateString([], { year: 'numeric' });
        },
        locate() {
            if (!navigator.geolocation) {
                this.locError = 'Geolocation unavailable';
                return;
            }
            this.locLoading = true;
            this.locError = null;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.loc = {
                        lat: pos.coords.latitude.toFixed(5),
                        lng: pos.coords.longitude.toFixed(5),
                    };
                    this.locLoading = false;
                },
                (err) => {
                    this.locError = 'Location unavailable';
                    this.locLoading = false;
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 }
            );
        },
        openMaps() {
            if (!this.loc) return;
            window.open(`https://www.google.com/maps?q=${this.loc.lat},${this.loc.lng}`, '_blank');
        },
    }));

    /* Global role-scoped search */
    Alpine.data('globalSearch', () => ({
        q: '',
        scope: '',
        scopes: [],
        open: false,
        loading: false,
        navResults: [],
        results: [],
        timer: null,
        navLinks: [],
        init() {
            this.navLinks = window.SIGMA_NAV || [];
            this.scopes = window.SIGMA_SEARCH_SCOPES || [];
            this.scope = this.scopes[0]?.value || 'all';
            this.$watch('q', (val) => {
                clearTimeout(this.timer);
                if (!val.trim()) {
                    this.open = false;
                    this.navResults = [];
                    this.results = [];
                    return;
                }
                this.timer = setTimeout(() => this.run(), 220);
            });
        },
        run() {
            const term = this.q.trim().toLowerCase();
            this.loading = true;
            this.open = true;

            this.navResults = this.flattenNav(this.navLinks).filter(
                (l) => l.label.toLowerCase().includes(term)
            ).slice(0, 8);

            const params = new URLSearchParams({ q: this.q, scope: this.scope || 'all' });
            fetch(`${window.SIGMA_SEARCH_URL}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
            })
                .then((r) => r.json())
                .then((data) => {
                    this.results = data.items || [];
                })
                .catch(() => (this.results = []))
                .finally(() => (this.loading = false));
        },
        flattenNav(groups) {
            return (groups || []).flatMap((g) => g.items || []);
        },
        total() {
            return this.navResults.length + this.results.length;
        },
        placeholder() {
            const selected = this.scopes.find((option) => option.value === this.scope);
            return selected ? `Search ${selected.label.toLowerCase()}…` : 'Search workspace…';
        },
        close() {
            this.open = false;
        },
    }));
});

/* ------------------------------------------------------------
   Lucide icons + boot
   ------------------------------------------------------------ */
/* Re-render icons after injecting markup at runtime (modals, previews). */
window.SigmaIcons = {
    refresh: () => createIcons({ icons }),
};

/* ------------------------------------------------------------
   Flash toasts (success / error / warning / info)
   Server renders them inside #toast-host; here we reveal + dismiss.
   ------------------------------------------------------------ */
const TOAST_DEFAULT_TTL = 30000; // 30 seconds

function dismissToast(toast) {
    if (!toast || toast.dataset.toastDismissed === '1') return;
    toast.dataset.toastDismissed = '1';
    clearTimeout(Number(toast.dataset.toastTimer || 0));

    toast.classList.remove('is-visible');
    toast.classList.add('is-leaving');

    setTimeout(() => toast.remove(), 250);
}

function initToasts() {
    const host = document.getElementById('toast-host');
    if (!host) return;

    host.querySelectorAll('.toast').forEach((toast) => {
        const ttl = Number(toast.dataset.toastAutohide || TOAST_DEFAULT_TTL);

        toast.querySelectorAll('[data-toast-close]').forEach((btn) =>
            btn.addEventListener('click', () => dismissToast(toast))
        );

        if (ttl > 0) {
            toast.dataset.toastTimer = setTimeout(() => dismissToast(toast), ttl);
        }

        // Pause the countdown while the pointer is over the toast
        toast.addEventListener('mouseenter', () => clearTimeout(Number(toast.dataset.toastTimer || 0)));
        toast.addEventListener('mouseleave', () => {
            if (toast.dataset.toastDismissed === '1') return;
            toast.dataset.toastTimer = setTimeout(() => dismissToast(toast), 5000);
        });

        requestAnimationFrame(() => toast.classList.add('is-visible'));
    });
}

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
    initToasts();
});
document.addEventListener('alpine:init', () => {
    Alpine.effect(() => createIcons({ icons }));
});

/* Color-wheel hue helper: N hues spread across the wheel */
window.wheelColor = (i, sat = 78, light = 58) => {
    const hue = (i * 137.508) % 360;
    return `hsl(${hue}, ${sat}%, ${light}%)`;
};

Alpine.start();

/* Apply the stored (or OS-preferred) theme on every page, including guest/auth */
ThemeManager.init();