<nav
    id="appNavbar"
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b shadow-sm transition-all duration-300"
    style="background-color: #ffffff; border-bottom-color: rgba(212, 165, 99, 0.4);"
>
    @php($user = Auth::user())
    @php($dashboardRoute = $user->dashboardRouteName())
    @php($isGuide = in_array($user->role, ['guide', 'tour_guide'], true))
    @php($viewProfileRoute = $isGuide ? 'dashboard.guide.profile.show' : 'profile.edit')
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('favicon.png') }}" alt="Trbltours logo" class="block h-9 w-9 rounded-full object-cover shadow-sm" />
                    </a>
                    <a href="{{ url('/') }}" class="hidden sm:block text-2xl font-bold text-[#6e4736]" style="font-family: 'Asimovian', sans-serif; letter-spacing: 0.02em; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.25);">
                        TrblTours
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-full border border-[#d4a563] bg-white px-4 py-2 text-sm font-semibold leading-4 text-[#3f2d22] shadow-sm transition duration-150 ease-in-out hover:bg-[#fdf3e5] hover:text-[#2f241a] focus:outline-none">
                            <svg class="h-4 w-4 text-[#3f2d22]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current text-[#3f2d22]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white">
                        <x-dropdown-link :href="route($viewProfileRoute)">
                            {{ __('View Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="#" onclick="alert('Messaging page - Under development for real-time messaging system')">
                            {{ __('Messages') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="#" onclick="alert('Notifications - Under development')">
                            {{ __('Notifications') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="#" onclick="alert('Settings - Under development')">
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <div class="border-t border-[#d4a563]"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-[#6e4736] hover:bg-[#f3e3c9] hover:text-[#2f241a] focus:bg-[#f3e3c9] focus:text-[#2f241a] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-[#d4a563]/40">
        <div class="pt-2 pb-3 space-y-1"></div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1">
            <div class="px-4">
                <div class="font-medium text-base text-[#3f2d22]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#6e4736]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route($viewProfileRoute)">
                    {{ __('View Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" onclick="alert('Messaging page - Under development')">
                    {{ __('Messages') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" onclick="alert('Notifications - Under development')">
                    {{ __('Notifications') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" onclick="alert('Settings - Under development')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    (() => {
        const nav = document.getElementById('appNavbar');

        if (!nav) {
            return;
        }

        const applyNavbarScrollState = () => {
            if (window.scrollY > 0) {
                nav.style.backgroundColor = 'rgba(212, 165, 99, 0.9)';
                nav.style.borderBottomColor = '#c69958';
                nav.classList.add('shadow-md', 'backdrop-blur-md');
                nav.classList.remove('shadow-sm');

                return;
            }

            nav.style.backgroundColor = '#ffffff';
            nav.style.borderBottomColor = 'rgba(212, 165, 99, 0.4)';
            nav.classList.add('shadow-sm');
            nav.classList.remove('shadow-md', 'backdrop-blur-md');
        };

        applyNavbarScrollState();
        window.addEventListener('scroll', applyNavbarScrollState, { passive: true });
    })();
</script>
