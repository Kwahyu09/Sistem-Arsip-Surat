<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- TOMBOL TAMBAH & SEARCH --}}
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('surat-keluar.create') }}"
                        class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">+ Tambah Surat Keluar</a>

                    <form action="{{ route('surat-keluar.index') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Cari surat..."
                            class="border rounded-l px-4 py-2" value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-blue-500 text-black px-4 py-2 rounded-r hover:bg-blue-600">Cari</button>
                    </form>
                </div>

                {{-- TABEL SURAT keluar --}}
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Penerima</th>
                            <th class="px-4 py-2">Nomor Surat</th>
                            <th class="px-4 py-2">Tanggal Surat</th>
                            <th class="px-4 py-2">File</th>
                            <th class="px-4 py-2">Perihal</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outgoingletter as $letter)
                            <tr>
                                <td class="border px-4 py-2">{{ $letter->recipient }}</td>
                                <td class="border px-4 py-2">{{ $letter->letter_number }}</td>
                                <td class="border px-4 py-2">
                                    {{ \Carbon\Carbon::parse($letter->letter_date)->format('d-m-Y') }}</td>
                                <td class="border px-4 py-2">
                                    @if ($letter->file_path)
                                        <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank"
                                            class="text-blue-500 hover:text-blue-700" title="Lihat File">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ asset('storage/' . $letter->file_path) }}" download
                                            class="text-green-500 hover:text-green-700" title="Download File">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-400">Tidak ada file</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $letter->subject }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('surat-keluar.edit', $letter->slug) }}"
                                        class="text-blue-500">Edit</a>
                                    |
                                    <form action="{{ route('surat-keluar.destroy', $letter->slug) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500"
                                            onclick="return confirm('Yakin mau hapus surat ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada surat keluar ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-5">
                    {{ $outgoingletter->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
