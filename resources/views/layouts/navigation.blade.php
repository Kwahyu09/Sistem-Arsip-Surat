<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                {{ __('Akun Pengguna') }}
                            </x-nav-link>
                        @endif
                    @endauth
                    <x-nav-link :href="route('surat-masuk.index')" :active="request()->routeIs('surat-masuk.*')">
                        {{ __('Surat Masuk') }}
                    </x-nav-link>
                    <x-nav-link :href="route('surat-keluar.index')" :active="request()->routeIs('surat-keluar.*')">
                        {{ __('Surat Keluar') }}
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->role !== 'staff_bidang')
                            <x-nav-link :href="route('rekap.index')" :active="request()->routeIs('rekap.*')">
                                {{ __('Rekap') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                @auth
                    @if (Auth::user()->role === 'staff_bidang')
                        @php
                            $suratBelumDibaca = \App\Models\IncomingLetter::where('user_id', Auth::user()->id)
                                ->where('read', false)
                                ->count();
                            $daftarSurat = \App\Models\IncomingLetter::where('user_id', Auth::user()->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

                        @endphp
                        <!-- Notification Icon -->
                        <div class="relative mr-4">
                            <button id="notificationButton" onclick="toggleDropdown()" class="relative focus:outline-none">
                                <!-- Icon surat -->
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>

                                <!-- Titik Merah -->
                                @if ($suratBelumDibaca > 0)
                                    <span
                                        class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                                @endif
                            </button>

                            <!-- Dropdown Surat -->
                            <div id="notificationDropdown"
                                class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-md shadow-lg hidden z-50">
                                <div class="p-4 font-bold text-gray-700 border-b">Surat Masuk</div>
                                <ul>
                                    @forelse ($daftarSurat as $surat)
                                        <li class="px-4 py-2 border-b {{ $surat->read ? 'bg-white' : 'bg-gray-100' }}">
                                            <a href="{{ route('surat-masuk.show', $surat->slug) }}" class="block">
                                                <div class="text-sm font-semibold">{{ $surat->sender }}</div>
                                                <div class="text-sm text-gray-600">{{ $surat->subject }}</div>
                                                <div class="text-xs text-gray-400">No: {{ $surat->letter_number }}</div>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="px-4 py-2 text-center text-gray-500">Tidak ada surat</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    @endif
                @endauth

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @auth
                @if (Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Akun Pengguna') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
            <x-responsive-nav-link :href="route('surat-masuk.index')" :active="request()->routeIs('surat-masuk.*')">
                {{ __('Surat Masuk') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('surat-keluar.index')" :active="request()->routeIs('surat-keluar.*')">
                {{ __('Surat Keluar') }}
            </x-responsive-nav-link>
            @auth
                @if (Auth::user()->role !== 'staff_bidang')
                    <x-responsive-nav-link :href="route('rekap.index')" :active="request()->routeIs('rekap.*')">
                        {{ __('Rekap') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
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
    function toggleDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown jika klik di luar
    window.addEventListener('click', function(e) {
        const button = document.getElementById('notificationButton');
        const dropdown = document.getElementById('notificationDropdown');
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
