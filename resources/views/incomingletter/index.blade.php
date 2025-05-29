<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">Manajemen Surat Masuk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

                {{-- Tombol Tambah Surat (hanya admin & staf) --}}
                @if(in_array(Auth::user()->role, ['admin', 'staf']))
                    <a href="{{ route('incomingletter.create') }}" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition mb-4 shadow">
                        Tambah Surat Masuk
                    </a>
                @endif

                {{-- Tabel Surat --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">Pengirim</th>
                                <th class="px-4 py-3 text-left">No. Surat</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-center">File</th>
                                <th class="px-4 py-3 text-left">Perihal</th>
                                <th class="px-4 py-3 text-left">Disposisi</th>
                                <th class="px-4 py-3 text-center">Sudah Dibaca</th>
                                <th class="px-4 py-3 text-left">User</th>
                                @if(in_array(Auth::user()->role, ['admin', 'staf']))
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($incomingletter as $letter)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 whitespace-nowrap">{{ $letter->sender }}</td>
                                <td class="px-4 py-2">{{ $letter->letter_number }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($letter->letter_date)->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if(in_array(Auth::user()->role, ['admin', 'staff_bidang']) && $letter->file_path)
                                        <a href="{{ route('incomingletter.viewFile', $letter->slug) }}" target="_blank" class="text-green-600 hover:text-green-800 font-semibold">Baca</a>
                                        <span class="mx-1">|</span>
                                        <a href="{{ route('incomingletter.downloadFile', $letter->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Unduh</a>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $letter->subject }}</td>
                                <td class="px-4 py-2">
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff_bidang')
                                        <form action="{{ route('incomingletter.updateDisposition', $letter->slug) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="disposition" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm w-full text-sm">
                                                <option value="known" {{ $letter->disposition === 'known' ? 'selected' : '' }}>Untuk Diketahui</option>
                                                <option value="actioned" {{ $letter->disposition === 'actioned' ? 'selected' : '' }}>Penting</option>
                                                <option value="archived" {{ $letter->disposition === 'archived' ? 'selected' : '' }}>Arsip</option>
                                            </select>
                                        </form>
                                    @else
                                        {{ ucfirst($letter->disposition) }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if($letter->read)
                                        <span class="text-green-600 font-bold" title="Sudah dibaca">✔️</span>
                                    @elseif(in_array(Auth::user()->role, ['admin', 'staff_bidang']))
                                        <form action="{{ route('incomingletter.markRead', $letter->slug) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-500 font-bold hover:text-green-600 transition" title="Tandai sudah dibaca">✘</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $letter->user->name ?? '-' }}</td>
                                @if(in_array(Auth::user()->role, ['admin', 'staf']))
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('incomingletter.edit', $letter->slug) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('incomingletter.destroy', $letter->slug) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus surat?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
