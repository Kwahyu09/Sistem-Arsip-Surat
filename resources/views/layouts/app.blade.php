<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .dropdown-enter { opacity: 0; transform: translateY(-5px); }
        .dropdown-enter-active { opacity: 1; transform: translateY(0); transition: all 0.2s ease-in-out; }

        /* Badge merah kecil */
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background-color: #ef4444;
            border-radius: 9999px;
            border: 2px solid white;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">

        <!-- Navbar -->
        <header class="bg-gray-800 shadow px-6 py-4 flex items-center justify-between">
            <!-- Logo & Judul -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logopemda.png') }}" alt="Logo Pemda" class="w-10 h-10 object-contain">
                <span class="text-lg font-bold text-white">ARSIP SURAT KECAMATAN PONDOK KELAPA BENGKULU TENGAH</span>
            </div>

            <!-- Notifikasi dan User -->
            <div class="flex items-center space-x-6">

                {{-- Dropdown Notifikasi --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative cursor-pointer text-white focus:outline-none" aria-label="Notifikasi">
                        <i class="fas fa-bell text-xl"></i>

                        {{-- Badge muncul jika ada notifikasi belum dibaca --}}
                        @if(collect($notifications ?? [])->where('read', false)->count() > 0)
                            <span class="notification-badge"></span>
                        @endif
                    </button>

                    {{-- Dropdown isi notifikasi --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-md py-2 z-50 max-h-60 overflow-y-auto">

                        <h3 class="px-4 py-2 font-semibold border-b">Notifikasi</h3>

                        @forelse ($notifications ?? [] as $note)
                            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer {{ $note['read'] ? 'text-gray-500' : 'font-semibold' }}">
                                {{ $note['message'] }}
                            </div>
                        @empty
                            <div class="px-4 py-2 text-gray-500">Tidak ada notifikasi.</div>
                        @endforelse

                    </div>
                </div>

                {{-- Dropdown User --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none text-white">
                        <i class="fas fa-user text-xl"></i>
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-40 bg-white shadow-md rounded-md py-2 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Kontainer Utama -->
        <div class="flex flex-1 overflow-hidden">

            <!-- Sidebar -->
            <aside class="w-67 bg-white shadow-md hidden lg:flex flex-col border-r border-gray-200">
                <nav class="flex-1 p-6 space-y-4">
                    <p class="text-gray-500 font-bold uppercase text-xs tracking-wider">Menu</p>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }} hover:bg-gray-100 transition">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i> Dashboard
                    </a>

                    @if(Auth::user()->role == 'admin')
    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('users.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }} hover:bg-gray-100 transition">
        <i class="fas fa-users-cog mr-3 text-gray-400"></i> Manajemen Pengguna
    </a>
@endif

                    <a href="{{ route('incomingletter.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-envelope-open-text mr-3 text-gray-400"></i> Surat Masuk
                    </a>

                    <a href="{{ route('outgoingletter.index') }}"class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-paper-plane mr-3 text-gray-400"></i> Surat Keluar
                    </a>
                </nav>
            </aside>

            <!-- Konten Utama -->
            <div class="flex flex-col flex-1 overflow-y-auto">
                @isset($header)
                    <div class="bg-gray-50 shadow-sm py-4 px-6">
                        {{ $header }}
                    </div>
                @endisset

                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Stack untuk script tambahan seperti Chart.js --}}
    @stack('scripts')
</body>
</html>
