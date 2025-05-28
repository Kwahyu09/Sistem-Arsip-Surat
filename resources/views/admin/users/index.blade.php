<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- TOMBOL TAMBAH & SEARCH --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200 shadow">
                        + Tambah Pengguna
                    </a>

                    <form action="{{ route('users.index') }}" method="GET" class="flex shadow rounded-lg overflow-hidden">
                        <input type="text" name="search" placeholder="Cari user..."
                            class="border-0 px-4 py-2 focus:ring-0 focus:outline-none" value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 transition duration-200">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- TABEL USER --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border border-gray-200 shadow-sm rounded-lg">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama</th>
                                <th class="px-6 py-3 font-semibold">Email</th>
                                <th class="px-6 py-3 font-semibold">Role</th>
                                <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 border-b">{{ $user->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                                    <td class="px-6 py-4 border-b">{{ ucfirst($user->role) }}</td>
                                    <td class="px-6 py-4 border-b text-center">
    <div class="flex justify-center items-center space-x-2">

        {{-- ICON EDIT --}}
        <a href="{{ route('users.edit', $user) }}" title="Edit"
            class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition transform hover:scale-105 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 21H6v-6l11.414-11.414z" />
            </svg>
        </a>

        {{-- ICON DELETE --}}
        <form action="{{ route('users.destroy', $user) }}" method="POST"
            onsubmit="return confirm('Yakin mau hapus user ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" title="Hapus"
                class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition transform hover:scale-105 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </form>

    </div>
</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500">
                                        Tidak ada Pengguna ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
