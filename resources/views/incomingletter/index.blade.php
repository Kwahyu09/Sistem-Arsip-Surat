<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
            {{ __('Manajemen Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-md">

                {{-- HEADER TOOLS --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <a href="{{ route('surat-masuk.create') }}"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Surat Masuk
                    </a>

                    <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex w-full sm:w-auto">
                        <input type="text" name="search" placeholder="Cari surat..."
                            class="rounded-l-lg border border-gray-300 px-4 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 w-1/6 border">Pengirim</th>
                                <th class="px-4 py-3 w-1/6 border">No. Surat</th>
                                <th class="px-4 py-3 w-1/6 border">Tanggal</th>
                                <th class="px-4 py-3 w-1/12 border text-center">File</th>
                                <th class="px-4 py-3 w-1/4 border">Perihal</th>
                                <th class="px-4 py-3 w-1/6 border">Disposisi</th>
                                <th class="px-4 py-3 w-1/12 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @forelse ($incomingletter as $letter)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="border px-4 py-2">{{ $letter->sender }}</td>
                                    <td class="border px-4 py-2">{{ $letter->letter_number }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($letter->letter_date)->format('d-m-Y') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @if ($letter->file_path)
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 mx-1" title="Lihat File">
                                                📄
                                            </a>
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" download
                                                class="text-green-600 hover:text-green-800 mx-1" title="Download File">
                                                ⬇️
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $letter->subject }}</td>
                                    <td class="border px-4 py-2 capitalize disposition">{{ $letter->disposition }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('surat-masuk.edit', $letter->slug) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition transform hover:scale-105 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 21H6v-6l11.414-11.414z" />
            </svg>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('surat-masuk.destroy', $letter->slug) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition transform hover:scale-105 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500 italic">Tidak ada surat masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6">
                    {{ $incomingletter->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script ubah teks Disposisi --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mappings = {
                known: 'Untuk Diketahui',
                actioned: 'Penting',
                archived: 'Arsip'
            };

            document.querySelectorAll('.disposition').forEach(cell => {
                const key = cell.textContent.trim().toLowerCase();
                if (mappings[key]) {
                    cell.textContent = mappings[key];
                }
            });
        });
    </script>
</x-app-layout>
