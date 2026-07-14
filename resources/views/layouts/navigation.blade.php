<nav x-data="{ open: false }" class=" border-b-2 bg-[#F8F9FA] dark:bg-[#1A1A1A] border-black dark:border-[#E67E22]">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() && auth()->user()->role_id == '1' ? route('admin.admin.index') : route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-orange-400" />
                        <span class="text-2xl font-bold text-[#2C3E50] dark:text-[#E67E22]">Dunkers</span>
                    </a>
                </div>

            </div>

            <div class="flex">

                @guest
                <!-- Navigation Links -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            </div>
                        </button>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('produk')" :active="request()->routeIs('produk')">
                                    {{ __('Produk') }}
                                </x-nav-link>
                            </div>
                        </button>
                    </div>
                @endguest
                

                @auth
                    @if(auth()->user()->role_id == '1')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('admin.admin.index')" :active="request()->routeIs('admin.admin.index')">
                                    Admin
                                </x-nav-link>
                            </div>
                        </button>
                    </div>
                    @else
                    <!-- Navigation Links -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            </div>
                        </button>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('produk')" :active="request()->routeIs('produk')">
                                    {{ __('Produk') }}
                                </x-nav-link>
                            </div>
                        </button>
                    </div>
                    
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                                    <img src="{{ asset('img/produk/keranjang.png') }}" alt="keranjang" class=" w-[22px] h-[22px] ">
                                </x-nav-link>
                            </div>
                        </button>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#2C3E50] dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-200">
                            <div>
                                <x-nav-link :href="route('riwayat-belanja')" :active="request()->routeIs('riwayat-belanja')">
                                    {{ __('Riwayat Belanja') }}
                                </x-nav-link>
                            </div>
                        </button>
                    </div>
                    @endif
                @endauth

                <!-- END Navigation Links -->
    
                @auth
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-3 border border-transparent text-md leading-4 font-medium rounded-md text-white dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
    
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
    
                            <x-slot name="content">
                                {{-- <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link> --}}
    
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
    
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
    
                    <!-- Responsive Navigation Menu -->
                    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-responsive-nav-link>
                        </div>
    
                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-[#E67E22] dark:border-orange-600">
                            <div class="px-4">
                                <div class="font-medium text-base text-orange-600 dark:text-orange-200">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-orange-500">{{ Auth::user()->email }}</div>
                            </div>
    
                            <div class="mt-3 space-y-1">
                                <x-responsive-nav-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
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
                @else
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <button class="inline-flex items-center px-3 py-3 border border-transparent text-md leading-4 font-medium rounded-md text-white dark:text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22]  dark:hover:bg-[#E67E22] focus:outline-none transition ease-in-out duration-150">
                            <div>
                                <a href="{{ route('login') }}">Masuk</a>
                            </div>
                        </button>
                    </div>
                @endauth

                <!-- Theme Toggle -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <button id="theme-toggle" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] hover:dark:bg-[#E67E22] hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-200">
                        <svg id="sun-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                        </svg>
                        <svg id="moon-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] dark:hover:bg-[#D35400] transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>

<script>
    // Theme toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    const html = document.documentElement;

    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        html.classList.add('dark');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
    } else {
        html.classList.remove('dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
    }

    themeToggle.addEventListener('click', () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        }
    });
</script>
