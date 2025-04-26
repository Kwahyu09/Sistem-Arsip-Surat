<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- TOMBOL TAMBAH & SEARCH --}}
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">+ Tambah User</a>

                    <form action="{{ route('users.index') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Cari user..."
                            class="border rounded-l px-4 py-2" value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-blue-500 text-black px-4 py-2 rounded-r hover:bg-blue-600">Cari</button>
                    </form>
                </div>

                {{-- TABEL USER --}}
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('users.edit', $user) }}" class="text-blue-500">Edit</a>
                                    |
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500"
                                            onclick="return confirm('Yakin mau hapus user ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada user ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
