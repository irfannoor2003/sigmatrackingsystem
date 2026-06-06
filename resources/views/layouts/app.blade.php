<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sigma Tracking System')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">


    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>


    <style>
        :root {
            --hf-magenta: #d6007b;
            --hf-magenta-light: #ff2ba6;
            --hf-purple: #6A00FF;
            --hf-dark: #000000;
        }

        body {
            background: linear-gradient(135deg, #000000, #333333, #000000, #666666);
            background-size: 300% 300%;
            color: white !important;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .sidebar-active {
            background: rgba(255, 0, 170, 0.2);
            border-left: 4px solid var(--hf-magenta-light);
            box-shadow: 0 0 12px rgba(255, 0, 170, 0.4);
            color: white !important;
        }

        .hf-heading {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 1px;
        }
    </style>

    @stack('head')
</head>

<body class="antialiased">

    <div class="min-h-screen flex">

        <aside class="w-72 hidden md:flex flex-col glass shadow-2xl border-r border-white/10">

            <div class="p-6 border-b border-white/10">
                <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="hf-heading text-white">

                    SES<span class="text-[var(--hf-magenta-light)]">.</span>
                </a>
                <p class="text-xs text-gray-300 mt-1">Salesman Tracking System</p>
            </div>

            <nav class="p-4 flex-1 text-gray-200 ">

                @auth
                    @php
                        $role = auth()->user()->role;
                    @endphp
                    <div class="mb-6 p-4 rounded-xl glass border border-white/10 ">
                        <div class="text-sm text-gray-300">Logged in as:</div>
                        <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ auth()->user()->email }}</div>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
                        {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.staff.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('admin.staff.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="users-round" class="w-5 h-5"></i>
                            All Staff
                        </a>
                        <a href="{{ route('admin.attendance.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('admin.attendance.index') ? 'sidebar-active' : '' }}">
                            <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                            Attendance Reports
                        </a>
                        <a href="{{ route('admin.attendance.leave-requests') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('admin.attendance.leave-requests') ? 'sidebar-active' : '' }}">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                            Leave Messages
                        </a>


                        <a href="{{ route('admin.salesmen.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('admin.salesmen.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Salesmen
                        </a>

                        <a href="{{ route('admin.reports.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
                        {{ request()->routeIs('admin.reports.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                            Visits Reports
                        </a>


                        <a href="{{ url('/admin/customers') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->is('admin/customers*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                            All Customers
                        </a>
                        <a href="https://expense.sessigmasoft.com/"
                            class="group relative flex items-center gap-3 px-4 py-2 rounded-lg mt-1
   hover:bg-white/10 transition-all duration-300
   {{ request()->fullUrlIs('https://expense.sessigmasoft.com/*') ? 'sidebar-active' : '' }}">

                            <i data-lucide="wallet" class="w-5 h-5 "></i>

                            <span>All Expenses</span>

                            <span
                                class="ml-auto text-[10px] font-bold px-2 py-[2px] rounded-full
        bg-[#ff2ba6] text-black shadow-[0_0_10px_#ff2ba6] animate-pulse">
                                NEW
                            </span>

                            <span class="absolute inset-0 rounded-lg overflow-hidden pointer-events-none">
                                <span
                                    class="absolute -left-full top-0 h-full w-1/2
            bg-gradient-to-r from-transparent via-white/20 to-transparent
            skew-x-[-20deg]
            group-hover:animate-[shine_0.8s_ease]"></span>
                            </span>
                        </a>

                        <div class="mt-4 text-xs uppercase text-gray-400 px-4">Old Customers</div>

                        <a href="{{ route('admin.old-customers.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('admin.old-customers.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="database" class="w-5 h-5"></i>
                            Search / View Old
                        </a>
                    @endif

                    @if ($role === 'saleshead')
                        {{-- Dashboard --}}
                        <a href="{{ route('salehead.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.dashboard') ? 'sidebar-active' : '' }}">
                            <i data-lucide="layout-dashboard"></i>
                            Dashboard
                        </a>

                        {{-- Salesman Visits --}}
                        <a href="{{ route('salehead.reports.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.reports.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="clipboard-list"></i>
                            Salesman Visits
                        </a>


                        {{-- All Customers --}}
                        <a href="{{ route('salehead.customers.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.customers.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="building-2"></i>
                            All Customers
                        </a>
                    @endif

                    @if (auth()->user()->role === 'hr')
                        <a href="{{ route('hr.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.dashboard') ? 'sidebar-active' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard
                        </a>

                        <a href="{{ route('hr.staff.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.staff.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="users-round" class="w-5 h-5"></i>
                            All Staff
                        </a>

                        <a href="{{ route('hr.attendance.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.attendance.index') ? 'sidebar-active' : '' }}">
                            <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                            Attendance Reports
                        </a>

                        <a href="{{ route('hr.attendance.leave-requests') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.attendance.leave-requests') ? 'sidebar-active' : '' }}">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                            Leave Messages
                        </a>
                        <a href="https://expense.sessigmasoft.com/"
                            class="group relative flex items-center gap-3 px-4 py-2 rounded-lg mt-1
   hover:bg-white/10 transition-all duration-300
   {{ request()->fullUrlIs('https://expense.sessigmasoft.com/*') ? 'sidebar-active' : '' }}">

                            <i data-lucide="wallet" class="w-5 h-5 "></i>

                            <span>All Expenses</span>

                            <span
                                class="ml-auto text-[10px] font-bold px-2 py-[2px] rounded-full
        bg-[#ff2ba6] text-black shadow-[0_0_10px_#ff2ba6] animate-pulse">
                                NEW
                            </span>

                            <span class="absolute inset-0 rounded-lg overflow-hidden pointer-events-none">
                                <span
                                    class="absolute -left-full top-0 h-full w-1/2
            bg-gradient-to-r from-transparent via-white/20 to-transparent
            skew-x-[-20deg]
            group-hover:animate-[shine_0.8s_ease]"></span>
                            </span>
                        </a>
                    @endif


                    @if (auth()->user()->role === 'salesman')
                        {{-- Dashboard --}}
                        <a href="{{ route('salesman.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.dashboard') ? 'sidebar-active' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard
                        </a>

                        {{-- Attendance --}}
                        <a href="{{ route('salesman.attendance.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.attendance.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                            Attendance
                        </a>

                        {{-- My Customers --}}
                        <a href="{{ route('salesman.customers.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.customers.index') ? 'sidebar-active' : '' }}">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            My Customers
                        </a>

                        {{-- Add Customer --}}
                        <a href="{{ route('salesman.customers.create') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.customers.create') ? 'sidebar-active' : '' }}">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            Add Customer
                        </a>

                        {{-- Start Visit --}}
                        <a href="{{ route('salesman.visits.create') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.visits.create') ? 'sidebar-active' : '' }}">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                            Start Visit
                        </a>

                        {{-- My Visits --}}
                        <a href="{{ route('salesman.visits.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.visits.index') ? 'sidebar-active' : '' }}">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i>
                            My Visits
                        </a>

                        {{-- Old Customers --}}
                        <div class="mt-4 text-xs uppercase text-gray-400 px-4">Old Customers</div>

                        {{-- Import Old Customers (URL-based active) --}}
                        <a href="{{ route('salesman.old-customers.import') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->is('salesman/old-customers/import*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="upload" class="w-5 h-5"></i>
                            Import Old Customers
                        </a>

                        {{-- View Old Customers --}}
                        <a href="{{ route('salesman.old-customers.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.old-customers.index') ? 'sidebar-active' : '' }}">
                            <i data-lucide="database" class="w-5 h-5"></i>
                            View Old Customers
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['it', 'account', 'store', 'office_boy']))
                        {{-- Staff Dashboard --}}
                        <a href="{{ route('staff.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('staff.dashboard') ? 'sidebar-active' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard
                        </a>

                        {{-- Attendance --}}
                        <a href="{{ route('staff.attendance.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('staff.attendance.*') ? 'sidebar-active' : '' }}">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                            Attendance
                        </a>
                    @endif




                    <div class="border-t border-white/10 mt-6 pt-4">
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                Profile
                            </a>
                        @endif


                        <form method="POST" action="{{ route('logout') }}" class="px-4 mt-3">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 text-sm text-red-400 hover:text-red-300">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                                Logout
                            </button>
                        </form>
                    </div>

                @endauth
            </nav>
        </aside>

        <div x-data="{ open: false }" class="flex-1">

            <header class="glass border-b border-white/10 shadow-xl relative z-20">
                <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

                    <div class="flex items-center gap-3">
                        <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-white/10">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('dashboard') }}"
                            class="text-xl md:text-2xl font-extrabold text-white tracking-wide">
                            Sigma Engineering Services<span class="text-[var(--hf-magenta-light)]">.</span>
                        </a>
                    </div>

                    @auth
                        <div
                            class="hidden md:flex items-center gap-3
            bg-white/10 backdrop-blur-md border border-white/20
            px-4 py-2 rounded-xl shadow-lg">

                            <!-- Avatar Icon -->
                            <div
                                class="w-9 h-9 flex items-center justify-center
                rounded-full bg-[#ff2ba6]/20 text-[#fff]">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>

                            <!-- Text -->
                            <div class="leading-tight">
                                <div class="text-sm font-semibold text-[#fff]">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="text-xs text-white/60 flex items-center gap-1">
                                    <i data-lucide="shield" class="w-3 h-3 text-[#ff2ba6]"></i>
                                    <span class="capitalize">{{ auth()->user()->role }}</span>
                                </div>
                            </div>
                        </div>

                    @endauth
                </div>

                <div x-show="open" x-cloak x-transition
                    class="md:hidden  border-t border-white/10 absolute top-16 left-0 w-full z-30 bg-black">
                    <div class="p-4">

                        @auth
                            <a href="{{ route('profile.edit') }}"
                                class="block p-3 mb-4 rounded-xl glass border border-white/10 hover:bg-white/10 transition">
                                <div class="text-sm text-gray-300">Logged in as:</div>
                                <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->email }}</div>
                            </a>

                            @if (auth()->user()->role === 'admin')
                                {{-- Dashboard --}}
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('admin.dashboard') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    Dashboard
                                </a>


                                {{-- All Staff --}}
                                <a href="{{ route('admin.staff.index') }}"
                                    class="flex items-center gap-3  py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('admin.staff.*') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="users-round" class="w-5 h-5"></i>
                                    All Staff
                                </a>

                                {{-- Attendance Reports --}}
                                <a href="{{ route('admin.attendance.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('admin.attendance.index') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                                    Attendance Reports
                                </a>

                                {{-- Leave Requests (FIXED – no conflict now) --}}
                                <a href="{{ route('admin.attendance.leave-requests') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('admin.attendance.leave-requests') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                    Leave Messages
                                </a>

                                {{-- Salesmen --}}
                                <a href="{{ route('admin.salesmen.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('admin.salesmen.*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                    Salesmen
                                </a>

                                {{-- Visit Reports --}}
                                <a href="{{ route('admin.reports.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('admin.reports.*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                    Visit Reports
                                </a>

                                {{-- All Customers (URL based) --}}
                                <a href="{{ url('/admin/customers') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->is('admin/customers*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                    All Customers
                                </a>
                                <a href="https://expense.sessigmasoft.com/"
   class="group relative flex items-center gap-3 py-2 rounded hover:bg-white/10
   {{ request()->fullUrlIs('https://expense.sessigmasoft.com/*') ? 'sidebar-active-mobile' : '' }}">

    <i data-lucide="wallet"
        class="w-5 h-5 "></i>

    <span>All Expenses</span>

    <span class="ml-auto text-[10px] font-bold px-2 py-[2px] rounded-full
        bg-[#ff2ba6] text-black shadow-[0_0_10px_#ff2ba6] animate-pulse">
        NEW
    </span>
</a>

                                <div class="mt-4 text-xs uppercase text-gray-400 px-4">Old Customers</div>

                                {{-- Old Customers --}}
                                <a href="{{ route('admin.old-customers.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('admin.old-customers.*') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="database" class="w-5 h-5"></i>
                                    Search / View Old
                                </a>
                            @endif


                            @if (auth()->user()->role === 'salesman')
                                {{-- Dashboard --}}
                                <a href="{{ route('salesman.dashboard') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.dashboard') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    Dashboard
                                </a>

                                {{-- Attendance --}}
                                <a href="{{ route('salesman.attendance.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.attendance.*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                    Attendance
                                </a>

                                {{-- My Customers --}}
                                <a href="{{ route('salesman.customers.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.customers.index') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                                    My Customers
                                </a>

                                {{-- Add Customer --}}
                                <a href="{{ route('salesman.customers.create') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.customers.create') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                                    Add Customer
                                </a>

                                {{-- Start Visit --}}
                                <a href="{{ route('salesman.visits.create') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.visits.create') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                    Start Visit
                                </a>

                                {{-- My Visits --}}
                                <a href="{{ route('salesman.visits.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('salesman.visits.index') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                                    My Visits
                                </a>

                                <div class="mt-4 text-xs uppercase text-gray-400 px-4">Old Customers</div>

                                {{-- Import Old Customers --}}
                                <a href="{{ route('salesman.old-customers.import') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->is('salesman/old-customers/import*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="upload" class="w-5 h-5"></i>
                                    Import Old Customers
                                </a>


                                {{-- View Old Customers --}}
                                <a href="{{ route('salesman.old-customers.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
       {{ request()->routeIs('salesman.old-customers.index') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="database" class="w-5 h-5"></i>
                                    View Old Customers
                                </a>
                            @endif
                            @if ($role === 'saleshead')
                                {{-- Dashboard --}}
                                <a href="{{ route('salehead.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.dashboard') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="layout-dashboard"></i>
                                    Dashboard
                                </a>


                                {{-- Salesman Visits --}}
                                <a href="{{ route('salehead.reports.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.reports.*') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="clipboard-list"></i>
                                    Salesman Visits
                                </a>



                                {{-- All Customers --}}
                                <a href="{{ route('salehead.customers.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
   {{ request()->routeIs('salehead.customers.*') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="building-2"></i>
                                    All Customers
                                </a>
                            @endif

                            @if (auth()->user()->role === 'hr')
                                <a href="{{ route('hr.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.dashboard') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    Dashboard
                                </a>

                                <a href="{{ route('hr.staff.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.staff.*') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="users-round" class="w-5 h-5"></i>
                                    All Staff
                                </a>

                                <a href="{{ route('hr.attendance.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.attendance.index') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                                    Attendance Reports
                                </a>

                                <a href="{{ route('hr.attendance.leave-requests') }}"
                                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 mt-1
        {{ request()->routeIs('hr.attendance.leave-requests') ? 'sidebar-active' : '' }}">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                    Leave Messages
                                </a>
                                <a href="https://expense.sessigmasoft.com/"
   class="group relative flex items-center gap-3 py-2 rounded hover:bg-white/10
   {{ request()->fullUrlIs('https://expense.sessigmasoft.com/*') ? 'sidebar-active-mobile' : '' }}">

    <i data-lucide="wallet"
        class="w-5 h-5 "></i>

    <span>All Expenses</span>

    <span class="ml-auto text-[10px] font-bold px-2 py-[2px] rounded-full
        bg-[#ff2ba6] text-black shadow-[0_0_10px_#ff2ba6] animate-pulse">
        NEW
    </span>
</a>
                            @endif

                            @if (in_array(auth()->user()->role, ['it', 'account', 'store', 'office_boy']))
                                {{-- Staff Dashboard --}}
                                <a href="{{ route('staff.dashboard') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('staff.dashboard') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    Dashboard
                                </a>

                                {{-- Attendance --}}
                                <a href="{{ route('staff.attendance.index') }}"
                                    class="flex items-center gap-3 py-2 rounded hover:bg-white/10
       {{ request()->routeIs('staff.attendance.*') ? 'sidebar-active-mobile' : '' }}">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                    Attendance
                                </a>
                            @endif



                            <form method="POST" action="{{ route('logout') }}"
                                class="mt-4 pt-4 border-t border-white/10">
                                @csrf
                                <button class="flex items-center gap-3 text-red-400 hover:text-red-300">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="p-6 max-w-7xl mx-auto">

                @if (session('success'))
                    <div class="mb-4 p-3 glass border border-green-400/40 text-green-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 glass border border-red-400/40 text-red-300 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>

    </div>

    @stack('scripts')
    @yield('scripts')

    <style>
        .sidebar-active-mobile {
            background: rgba(255, 0, 170, 0.2);
            /* border-left: 4px solid var(--hf-magenta-light); <-- Removed for mobile */
            box-shadow: 0 0 12px rgba(255, 0, 170, 0.4);
            color: white !important;
        }
    </style>

    <script>
        function renderLucide() {
            lucide.createIcons();
        }

        // Initial render when DOM loads
        document.addEventListener("DOMContentLoaded", renderLucide);

        // Re-render when Alpine changes DOM
        document.addEventListener("alpine:init", () => {
            Alpine.effect(() => {
                renderLucide();
            });
        });
    </script>

</body>

</html>
