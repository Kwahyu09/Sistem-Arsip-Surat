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
            <div class="bg-white shadow rounded-lg p-6">

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
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Surat Keluar 
                    </a>

                    <form action="{{ route('surat-keluar.index') }}" method="GET"
                        class="flex rounded overflow-hidden border w-full md:w-auto">
                        <input type="text" name="search" placeholder="Cari surat..."
                            class="px-4 py-2 w-full md:w-64 outline-none"
                            value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 hover:bg-blue-700 transition">Cari</button>
                    </form>
                </div>

                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 border">Penerima</th>
                                <th class="px-4 py-3 border">Nomor Surat</th>
                                <th class="px-4 py-3 border">Tanggal Surat</th>
                                <th class="px-4 py-3 border">File</th>
                                <th class="px-4 py-3 border">Perihal</th>
                                <th class="px-4 py-3 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($outgoingletter as $letter)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">{{ $letter->recipient }}</td>
                                    <td class="px-4 py-3">{{ $letter->letter_number }}</td>
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($letter->letter_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($letter->file_path)
                                            <div class="flex space-x-2">
                                                {{-- Eye Icon --}}
                                                <a href="{{ asset('storage/' . $letter->file_path) }}"
                                                    target="_blank" class="text-blue-500 hover:text-blue-700"
                                                    title="Lihat File">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path
                                                            d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zM10 15c-2.76 0-5-2.24-5-5s2.24-5 5-5
                                                            5 2.24 5 5-2.24 5-5 5z" />
                                                        <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
                                                    </svg>
                                                </a>
                                                {{-- Download Icon --}}
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" download
                                                    class="text-green-500 hover:text-green-700" title="Download File">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $letter->subject }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center space-x-2">
                                            {{-- Edit Icon --}}
                                            <a href="{{ route('surat-keluar.edit', $letter->slug) }}"
                                                class="text-indigo-500 hover:text-indigo-700"
                                                title="Edit Surat">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 21H6v-6l11.414-11.414z" />
                                                </svg>
                                            </a>

                                            {{-- Delete Icon --}}
                                            <form action="{{ route('surat-keluar.destroy', $letter->slug) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin mau hapus surat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700"
                                                    title="Hapus Surat">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 py-5 italic">
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
