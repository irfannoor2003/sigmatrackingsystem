<!doctype html>
<html lang="en" x-data="appShell()" class="{{ auth()->user()?->glass_mode ? 'glass-ui' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Sigma Operations</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">

    {{-- FOUC-safe theme bootstrap --}}
    <script>
        (function() {
            try {
                var t = localStorage.getItem('sigma-theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', t === 'dark');
            } catch (e) {}
        })();
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">

    {{-- Glass UI: preference is stored per-user. ?glass=1 / ?glass=0 previews it. --}}
    <script>
        (function() {
            try {
                var g = new URLSearchParams(location.search).get('glass');
                if (g === '1') document.documentElement.classList.add('glass-ui');
                if (g === '0') document.documentElement.classList.remove('glass-ui');

                // Seed the guest-page preference from the account default, once per browser.
                if (localStorage.getItem('sigma-glass') === null) {
                    localStorage.setItem('sigma-glass',
                        document.documentElement.classList.contains('glass-ui') ? '1' : '0');
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

@php
    $role = auth()->user()?->role;

    $isA = function (array $patterns) {
        foreach ($patterns as $p) {
            if (str_starts_with($p, 'url:')) {
                if (request()->is(substr($p, 4) . '*')) return true;
                continue;
            }
            if (request()->routeIs($p)) return true;
        }
        return false;
    };

    $link = fn ($label, $icon, $url, $active, $badge = null) => [
        'label' => $label, 'icon' => $icon, 'url' => $url,
        'active' => $active, 'badge' => $badge,
    ];

    $navGroups = [];

    if ($role === 'admin') {
        $navGroups[] = [
            'label' => 'Overview',
            'items' => [
                $link('Dashboard', 'layout-dashboard', route('admin.dashboard'), $isA(['admin.dashboard'])),
                $link('All Staff', 'users-round', route('admin.staff.index'), $isA(['admin.staff.*'])),
                $link('Salesmen', 'users', route('admin.salesmen.index'), $isA(['admin.salesmen.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Tracking',
            'items' => [
                $link('Attendance Reports', 'calendar-clock', route('admin.attendance.index'), $isA(['admin.attendance.index', 'admin.attendance.today', 'admin.attendance.late-staff', 'admin.attendance.staff'])),
                $link('Leave Messages', 'mail', route('admin.attendance.leave-requests'), $isA(['admin.attendance.leave-requests'])),
                $link('Visits Reports', 'clipboard-list', route('admin.reports.index'), $isA(['admin.reports.*'])),
                $link('Blocked Visits', 'shield-alert', route('admin.visits.blocked'), $isA(['admin.visits.blocked'])),
                $link('Out of City Visits', 'plane', route('admin.out-of-city-visits.index'), $isA(['admin.out-of-city-visits.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Business Data',
            'items' => [
                $link('All Customers', 'building-2', route('admin.customers.index'), $isA(['url:admin/customers'])),
                $link('Old Customers', 'database', route('admin.old-customers.index'), $isA(['admin.old-customers.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Apps',
            'items' => [
                $link('All Expenses', 'wallet', 'https://expense.sessigmasoft.com/', false, 'NEW'),
                $link('Delivery', 'truck', 'https://delivery.sessigmasoft.com/', false, 'NEW'),
            ],
        ];
    } elseif ($role === 'hr') {
        $navGroups[] = [
            'label' => 'Overview',
            'items' => [
                $link('Dashboard', 'layout-dashboard', route('hr.dashboard'), $isA(['hr.dashboard'])),
                $link('All Staff', 'users-round', route('hr.staff.index'), $isA(['hr.staff.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Tracking',
            'items' => [
                $link('Attendance Reports', 'calendar-clock', route('hr.attendance.index'), $isA(['hr.attendance.index', 'hr.attendance.staff', 'hr.attendance.today', 'hr.attendance.late-staff', 'hr.attendance.export.*', 'hr.holiday.*'])),
                $link('Leave Messages', 'mail', route('hr.attendance.leave-requests'), $isA(['hr.attendance.leave-requests'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Apps',
            'items' => [
                $link('All Expenses', 'wallet', 'https://expense.sessigmasoft.com/', false, 'NEW'),
            ],
        ];
    } elseif ($role === 'saleshead') {
        $navGroups[] = [
            'label' => 'Overview',
            'items' => [
                $link('Dashboard', 'layout-dashboard', route('salehead.dashboard'), $isA(['salehead.dashboard'])),
                $link('Salesman Visits', 'clipboard-list', route('salehead.reports.index'), $isA(['salehead.reports.*', 'salehead.visits.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Tracking',
            'items' => [
                $link('Out of City Visits', 'plane', route('salehead.out-of-city-visits.index'), $isA(['salehead.out-of-city-visits.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Business Data',
            'items' => [
                $link('Salesmen', 'users', route('salehead.salesmen.index'), $isA(['salehead.salesmen.*'])),
                $link('All Customers', 'building-2', route('salehead.customers.index'), $isA(['salehead.customers.*'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Apps',
            'items' => [
                $link('Delivery', 'truck', 'https://delivery.sessigmasoft.com/', false, 'NEW'),
            ],
        ];
    } elseif ($role === 'salesman') {
        $pendingOcCount = \App\Models\OutOfCityVisit::where('salesman_id', auth()->id())->where('status', 'pending')->count();
        $navGroups[] = [
            'label' => 'Overview',
            'items' => [
                $link('Dashboard', 'layout-dashboard', route('salesman.dashboard'), $isA(['salesman.dashboard'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Tracking',
            'items' => [
                $link('Attendance', 'clock', route('salesman.attendance.index'), $isA(['salesman.attendance.index', 'salesman.attendance.history', 'salesman.attendance.clockin', 'salesman.attendance.clockout'])),
                $link('My Lates', 'alarm-clock', route('salesman.attendance.lates'), $isA(['salesman.attendance.lates'])),
                $link('Start Visit', 'map-pin', route('salesman.visits.create'), $isA(['salesman.visits.create'])),
                $link('My Visits', 'calendar-check', route('salesman.visits.index'), $isA(['salesman.visits.index'])),
                $link('Out of City Visits', 'plane', route('salesman.out-of-city-visits.index'), $isA(['salesman.out-of-city-visits.*']), $pendingOcCount > 0 ? (string) $pendingOcCount : null),
            ],
        ];
        $navGroups[] = [
            'label' => 'Customers',
            'items' => [
                $link('My Customers', 'briefcase', route('salesman.customers.index'), $isA(['salesman.customers.index', 'salesman.customers.show', 'salesman.customers.edit'])),
                $link('Add Customer', 'user-plus', route('salesman.customers.create'), $isA(['salesman.customers.create'])),
            ],
        ];
        $navGroups[] = [
            'label' => 'Old Data',
            'items' => [
                $link('Import Old Customers', 'upload', route('salesman.old-customers.import'), $isA(['url:salesman/old-customers/import'])),
                $link('View Old Customers', 'database', route('salesman.old-customers.index'), $isA(['salesman.old-customers.index'])),
            ],
        ];
    } elseif (in_array($role, ['it', 'account', 'store', 'office_boy'])) {
        $navGroups[] = [
            'label' => 'Overview',
            'items' => [
                $link('Dashboard', 'layout-dashboard', route('staff.dashboard'), $isA(['staff.dashboard'])),
                $link('Attendance', 'clock', route('staff.attendance.index'), $isA(['staff.attendance.index', 'staff.attendance.history', 'staff.attendance.clockin', 'staff.attendance.clockout'])),
                $link('My Lates', 'alarm-clock', route('staff.attendance.lates'), $isA(['staff.attendance.lates'])),
            ],
        ];
        if ($role === 'store') {
            $navGroups[] = [
                'label' => 'Apps',
                'items' => [
                    $link('Delivery', 'truck', 'https://delivery.sessigmasoft.com/', false, 'NEW'),
                ],
            ];
        }
    }

    $searchScopes = match ($role) {
        'admin' => [
            ['value' => 'all', 'label' => 'All'],
            ['value' => 'customers', 'label' => 'Customers'],
            ['value' => 'staff', 'label' => 'Staff'],
        ],
        'hr' => [
            ['value' => 'staff', 'label' => 'Staff'],
        ],
        'saleshead' => [
            ['value' => 'customers', 'label' => 'Customers'],
        ],
        'salesman' => [
            ['value' => 'customers', 'label' => 'Customers'],
            ['value' => 'visits', 'label' => 'Visits'],
        ],
        default => [],
    };
@endphp

<body class="antialiased min-h-screen bg-[var(--bg)] text-[var(--text)] font-sans">

    <script>
        window.SIGMA_NAV = @json(array_values(array_filter(array_map(fn($g) => ['label' => $g['label'], 'items' => $g['items']], $navGroups))));
        window.SIGMA_SEARCH_URL = @json(route('global-search'));
        window.SIGMA_SEARCH_SCOPES = @json($searchScopes);
    </script>

    <div class="workspace-canvas relative min-h-screen">
        {{-- EXPERIMENTAL: blurred gradient mesh, only visible under .glass-ui --}}
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

        <div class="ambient-canvas" aria-hidden="true">
            <div class="ambient-orb ambient-orb--pink ambient-orb--one"></div>
            <div class="ambient-orb ambient-orb--blue ambient-orb--two"></div>
            <div class="ambient-orb ambient-orb--amber ambient-orb--three"></div>
            <div class="ambient-ring top-[22%] right-[12%]"></div>
        </div>
        {{-- ============================ SIDEBAR (desktop) ============================ --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 hidden lg:flex w-[17rem] flex-col border-r border-[var(--border)] transition-[width] duration-200 ease-out"
            :class="sidebarCollapsed ? 'lg:w-[5.5rem] app-sidebar-collapsed' : 'lg:w-[17rem]'"
            style="background: var(--sidebar-bg)">

            <div class="flex items-center gap-3 px-5 h-[4.75rem] border-b border-[var(--border)]"
                :class="sidebarCollapsed ? 'justify-center !px-0' : ''">
                <a href="{{ route('dashboard') }}" class="flex items-center min-w-0 flex-1"
                    title="Sigma Engineering Services" x-show="!sidebarCollapsed">
                    <img src="{{ asset('images/logo.webp') }}" alt="Sigma Engineering Services"
                        class="h-9 w-auto max-w-[11.5rem] object-contain object-left shrink-0">
                </a>
                <button class="icon-btn !w-8 !h-8 shrink-0" @click="toggleSidebar()"
                    :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                    <span x-show="!sidebarCollapsed">
                        <i data-lucide="panel-left-close" class="w-4 h-4"></i>
                    </span>
                    <span x-show="sidebarCollapsed">
                        <i data-lucide="panel-left-open" class="w-4 h-4"></i>
                    </span>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4">
                <template x-if="true" x-data="{}"></template>
                @foreach ($navGroups as $group)
                    <div class="side-label" x-show="!sidebarCollapsed">{{ $group['label'] }}</div>
                    @foreach ($group['items'] as $item)
                        <a href="{{ $item['url'] }}" title="{{ $item['label'] }}"
                            class="side-link {{ $item['active'] ? 'active' : '' }}">
                            <span class="side-ico">
                                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                            </span>
                            <span class="truncate" x-show="!sidebarCollapsed">{{ $item['label'] }}</span>
                            @if ($item['badge'])
                                <span
                                    class="ml-auto text-[.58rem] font-bold px-2 py-[2px] rounded-full badge {{ !is_numeric($item['badge']) ? 'badge-magenta' : 'badge-red' }}"
                                    x-show="!sidebarCollapsed">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                @endforeach
            </nav>

            {{-- User mini card --}}
            <div class="p-3 border-t border-[var(--border)]">
                <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="avatar avatar-md">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="min-w-0 flex-1" x-show="!sidebarCollapsed">
                        <p class="text-sm font-semibold truncate text-[var(--text)]">{{ auth()->user()->name }}</p>
                        <p class="text-[.68rem] uppercase tracking-wider muted truncate">
                            {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" x-show="!sidebarCollapsed">
                        @csrf
                        <button class="icon-btn !w-9 !h-9 text-red-500 border-red-300/40" title="Logout">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ============================ MOBILE DRAWER ============================ --}}
        <div x-show="sidebarOpen" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

        <div x-show="sidebarOpen" x-cloak x-transition
            class="fixed inset-y-0 left-0 z-50 w-[17rem] lg:hidden flex flex-col border-r border-[var(--border)]"
            style="background: var(--sidebar-bg)">
            <div class="flex items-center justify-between px-5 h-[4.75rem] border-b border-[var(--border)]">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.webp') }}" alt="Sigma Engineering Services" class="h-9 w-auto max-w-[12rem] object-contain object-left">
                </a>
                <button class="icon-btn" @click="sidebarOpen = false">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto px-3 py-4">
                @foreach ($navGroups as $group)
                    <div class="side-label">{{ $group['label'] }}</div>
                    @foreach ($group['items'] as $item)
                        <a href="{{ $item['url'] }}" @click="sidebarOpen = false"
                            class="side-link {{ $item['active'] ? 'active' : '' }}">
                            <span class="side-ico"><i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i></span>
                            <span class="truncate">{{ $item['label'] }}</span>
                            @if ($item['badge'])
                                <span
                                    class="ml-auto text-[.58rem] font-bold px-2 py-[2px] rounded-full badge {{ !is_numeric($item['badge']) ? 'badge-magenta animate-pulse' : 'badge-red' }}">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="mt-6 px-1">
                    @csrf
                    <button class="side-link text-red-500 w-full">
                        <span class="side-ico"><i data-lucide="log-out" class="w-4 h-4"></i></span>
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        {{-- ============================ MAIN ============================ --}}
        <div class="relative z-10 min-w-0 transition-[padding] duration-200 ease-out"
            :class="sidebarCollapsed ? 'lg:pl-[5.5rem]' : 'lg:pl-[17rem]'">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 glass-topbar border-b border-[var(--border)]">
                <div class="flex items-center gap-3 px-4 sm:px-6 h-[4.5rem]">

                    <button class="icon-btn lg:hidden" @click="sidebarOpen = true" title="Open navigation">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                    {{-- Page title / breadcrumb --}}
                    <div class="min-w-0">
                        <p class="text-[.65rem] uppercase tracking-[.16em] muted hidden sm:block">Operations Workspace</p>
                        <h1 class="font-display font-semibold text-[1.05rem] leading-tight truncate text-[var(--text)]">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>

                    {{-- Global search (desktop / tablet only) --}}
                    <div class="hidden md:flex ml-auto flex-1 justify-end">
                        <div class="relative w-full max-w-[15rem] md:max-w-[17rem] xl:max-w-[18rem]" x-data="globalSearch()"
                            @click.outside="close()" @keydown.escape="close()">

                            <div class="topbar-search">
                                <i data-lucide="search"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[var(--text-muted)] z-10 pointer-events-none"></i>
                                <input type="text" x-model="q" @focus="q.length && run()"
                                    :placeholder="placeholder()" aria-label="Global search"
                                    class="topbar-search__field !pl-9">
                                <select x-model="scope" @change="run()" x-show="scopes.length > 1"
                                    aria-label="Search scope" class="topbar-search__scope">
                                    <template x-for="option in scopes" :key="option.value">
                                        <option :value="option.value" x-text="option.label"></option>
                                    </template>
                                </select>
                                <button x-show="q" x-cloak @click="q=''" x-transition
                                    class="topbar-search__clear"
                                    :class="scopes.length > 1 ? 'right-[7.1rem]' : 'right-2'"
                                    aria-label="Clear search">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            {{-- Search overlay --}}
                            <div x-show="open" x-cloak x-transition.opacity
                                class="menu-panel absolute right-0 mt-2 w-full md:w-[26rem]">
                                <div class="p-2.5 max-h-[24rem] overflow-y-auto">
                                    <template x-if="loading">
                                        <div class="flex items-center gap-2 px-3 py-4 text-sm muted">
                                            <span
                                                class="w-4 h-4 border-2 border-[var(--accent)] border-t-transparent rounded-full animate-spin"></span>
                                            Searching…
                                        </div>
                                    </template>

                                    <template x-if="!loading && total() === 0">
                                        <div class="px-3 py-6 text-center text-sm muted">
                                            <i data-lucide="search-x" class="w-6 h-6 mx-auto mb-2 text-[var(--text-muted)]"></i>
                                            No matches for “<span x-text="q"></span>”
                                        </div>
                                    </template>

                                    <template x-if="navResults.length > 0">
                                        <div>
                                            <p class="px-3 py-1.5 text-[.62rem] font-bold uppercase tracking-widest muted">
                                                Pages</p>
                                            <template x-for="l in navResults" :key="'n'+l.url+l.label">
                                                <a :href="l.url" class="search-result-link">
                                                    <span class="side-ico"><span
                                                            class="w-6 h-6 rounded-md bg-[var(--accent-soft)] flex items-center justify-center"><i
                                                                :data-lucide="l.icon" class="w-3.5 h-3.5 text-[var(--accent)]"></i></span></span>
                                                    <span class="min-w-0">
                                                        <span class="block text-sm font-semibold text-[var(--text)] truncate"
                                                            x-text="l.label"></span>
                                                    </span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="results.length > 0">
                                        <div>
                                            <p class="px-3 py-1.5 text-[.62rem] font-bold uppercase tracking-widest muted"
                                                x-show="navResults.length > 0">Records</p>
                                            <template x-for="r in results" :key="'r'+r.url+r.title">
                                                <a :href="r.url" class="search-result-link">
                                                    <span class="side-ico"><span
                                                            class="w-6 h-6 rounded-md bg-[var(--accent-soft)] flex items-center justify-center"><i
                                                                :data-lucide="r.icon" class="w-3.5 h-3.5 text-[var(--accent)]"></i></span></span>
                                                    <span class="min-w-0">
                                                        <span class="block text-sm font-semibold text-[var(--text)] truncate"
                                                            x-text="r.title"></span>
                                                        <span class="block text-xs muted truncate" x-text="r.sub"></span>
                                                    </span>
                                                    <span class="ml-auto text-[.6rem] uppercase font-bold badge badge-gray"
                                                        x-text="r.type"></span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Live clock --}}
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl border border-[var(--border)] bg-[var(--surface)]"
                        x-data="liveClock()">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-[var(--accent)] opacity-75 animate-ping"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[var(--accent)]"></span>
                        </span>
                        <div class="leading-none text-right">
                            <span class="block font-mono font-bold text-sm text-[var(--text)] tabular-nums"
                                x-text="time"></span>
                            <span class="block text-[.58rem] uppercase tracking-wider muted" x-text="date + ' ' + day"></span>
                        </div>
                    </div>

                    {{-- Appearance: (1) light/dark  (2) glassmorphism on/off --}}
                    <div class="ml-auto flex items-center gap-1.5 md:ml-0">

                        {{-- 1. Simple light / dark mode --}}
                        <button class="icon-btn !h-9 !w-9 !rounded-xl" @click="toggleTheme()"
                            :title="theme === 'dark' ? 'Light mode' : 'Dark mode'"
                            :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'">
                            <span x-show="theme === 'dark'">
                                <i data-lucide="sun" class="w-[1.05rem] h-[1.05rem]"></i>
                            </span>
                            <span x-show="theme !== 'dark'">
                                <i data-lucide="moon" class="w-[1.05rem] h-[1.05rem]"></i>
                            </span>
                        </button>

                        {{-- 2. Glassmorphism on / off (saved to your account) --}}
                        <form method="POST" action="{{ route('profile.glass-mode') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="glass-toggle-btn relative flex !h-9 !w-9 items-center justify-center rounded-xl border"
                                title="{{ auth()->user()?->glass_mode ? 'Glassmorphism is on — turn off' : 'Glassmorphism is off — turn on' }}"
                                aria-label="Toggle glassmorphism"
                                aria-pressed="{{ auth()->user()?->glass_mode ? 'true' : 'false' }}"
                                onclick="localStorage.setItem('sigma-glass', {{ auth()->user()?->glass_mode ? "'0'" : "'1'" }})">
                                <i data-lucide="layers" class="w-[1.05rem] h-[1.05rem]"></i>
                                @if (auth()->user()?->glass_mode)
                                    <span class="glass-dot"></span>
                                @endif
                            </button>
                        </form>

                    </div>

                    {{-- User menu --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2.5 pl-1.5 pr-2.5 py-1.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] hover:border-[var(--accent)] transition">
                            <div class="avatar avatar-md !w-8 !h-8 !text-[.7rem]">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                            <span class="hidden sm:flex flex-col items-start leading-tight">
                                <span class="text-xs font-semibold text-[var(--text)] max-w-[7rem] truncate">{{ auth()->user()->name }}</span>
                                <span class="text-[.6rem] uppercase tracking-wider muted">{{ str_replace('_', ' ', $role) }}</span>
                            </span>
                            <i data-lucide="chevron-down"
                                class="w-3.5 h-3.5 text-[var(--text-muted)] transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-cloak x-transition.scale.origin.top.right
                            class="menu-panel absolute right-0 mt-2 w-52">
                            <div class="px-4 py-3 border-b border-[var(--border)]">
                                <p class="text-sm font-semibold text-[var(--text)] truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs muted truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                @if (auth()->user()?->role === 'admin')
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-[var(--text-soft)] hover:bg-[var(--accent-softer)] hover:text-[var(--accent)] transition">
                                        <i data-lucide="settings" class="w-4 h-4"></i> Profile &amp; Settings
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-500/10 transition">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="w-full max-w-none px-4 sm:px-6 xl:px-8 py-6 animate-fade-up">
                @yield('content')
            </main>

            <footer class="px-6 pb-8 text-center text-xs muted">
                © {{ date('Y') }} Sigma Engineering Services · Operations Platform
            </footer>
        </div>
    </div>

    <div id="modal-host"></div>

    {{-- Flash messages render as toasts (auto-dismiss after 30s) --}}
    <div id="toast-host" class="toast-host" aria-live="polite" aria-atomic="false">
        @foreach (['success', 'error', 'warning', 'info'] as $flashKey)
            @if (session($flashKey))
                @php
                    $flashIcons = [
                        'success' => 'check',
                        'error'   => 'x',
                        'warning' => 'triangle-alert',
                        'info'    => 'info',
                    ];
                @endphp
                <div class="toast toast--{{ $flashKey }}" role="status"
                    data-toast-autohide="30000">
                    <span class="toast__icon">
                        <i data-lucide="{{ $flashIcons[$flashKey] }}" class="w-3.5 h-3.5"></i>
                    </span>
                    <span class="toast__body">{{ session($flashKey) }}</span>
                    <button type="button" class="toast__close" data-toast-close
                        aria-label="Dismiss">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            @endif
        @endforeach
    </div>

    @stack('scripts')
    @yield('scripts')
</body>

</html>