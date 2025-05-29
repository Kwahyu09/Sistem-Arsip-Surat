@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-md">

                {{-- Flash Message --}}
                @if (session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TOMBOL TAMBAH & SEARCH --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
                    <a href="{{ route('surat-keluar.create') }}"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Surat Keluar
                    </a>

                    <form action="{{ route('surat-keluar.index') }}" method="GET" class="flex rounded overflow-hidden border w-full md:w-auto">
                        <input type="text" name="search" placeholder="Cari surat..."
                            class="px-4 py-2 w-full md:w-64 outline-none"
                            value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 hover:bg-blue-700 transition">Cari</button>
                    </form>
                </div>

                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="border px-4 py-2">Penerima</th>
                                <th class="border px-4 py-2">Nomor Surat</th>
                                <th class="border px-4 py-2">Tanggal Surat</th>
                                <th class="border px-4 py-2 text-center">File</th>
                                <th class="border px-4 py-2">Perihal</th>
                                @if(in_array(Auth::user()->role, ['admin', 'staf']))
                                    <th class="border px-4 py-2 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($outgoingletter as $letter)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="border px-4 py-2">{{ $letter->recipient }}</td>
                                    <td class="border px-4 py-2">{{ $letter->letter_number }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($letter->letter_date)->format('d-m-Y') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @if ($letter->file_path)
                                            <div class="flex justify-center space-x-2">
                                                {{-- View --}}
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank"
                                                    class="text-green-600 hover:text-green-800" title="Lihat File">
                                                    Baca
                                                </a>
                                                <span>|</span>
                                                {{-- Download --}}
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" download
                                                    class="text-blue-600 hover:text-blue-800" title="Unduh File">
                                                    Unduh
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $letter->subject }}</td>

                                    @if(in_array(Auth::user()->role, ['admin', 'staf']))
                                        <td class="border px-4 py-2 text-center">
                                            <div class="flex justify-center space-x-2">
                                                {{-- Edit --}}
                                                <a href="{{ route('surat-keluar.edit', $letter->slug) }}"
                                                    class="text-indigo-600 hover:underline">Edit</a>

                                                {{-- Hapus --}}
                                                <form action="{{ route('surat-keluar.destroy', $letter->slug) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Yakin ingin hapus surat?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ in_array(Auth::user()->role, ['admin', 'staf']) ? 6 : 5 }}"
                                        class="text-center text-gray-500 py-5 italic">
                                        Tidak ada surat keluar ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $outgoingletter->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
