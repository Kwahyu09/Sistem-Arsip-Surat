<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Tambah Pengguna
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">

                <a href="{{ route('users.index') }}"
                    class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 font-semibold transition duration-200">
                    ← Kembali ke Daftar Pengguna
                </a>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                        <select name="role" id="role" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
