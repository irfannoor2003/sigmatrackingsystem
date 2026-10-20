<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 inline-block me-1"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">

                            <!-- User Icon -->
                            <i data-lucide="user" class="w-4 h-4 me-2"></i>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i data-lucide="settings" class="w-4 h-4 inline-block me-1"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i data-lucide="log-out" class="w-4 h-4 inline-block me-1"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500">

                    <i x-show="!open" data-lucide="menu" class="h-6 w-6"></i>
                    <i x-show="open" data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i data-lucide="layout-dashboard" class="w-4 h-4 inline-block me-1"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-gray-700"></i>
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            </div>
            <div class="px-4 font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i data-lucide="settings" class="w-4 h-4 inline-block me-1"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i data-lucide="log-out" class="w-4 h-4 inline-block me-1"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    if (window.SigmaIcons) window.SigmaIcons.refresh();
</script>
