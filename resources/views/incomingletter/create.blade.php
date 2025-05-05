<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Surat Masuk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">

                <a href="{{ route('surat-masuk.index') }}" class="btn inline-block mb-6">
                    ← Kembali ke daftar surat
                </a>

                <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label for="sender" class="block font-medium text-sm text-gray-700">Pengirim:</label>
                        <input type="text" name="sender" id="sender" value="{{ old('sender') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('sender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_number" class="block font-medium text-sm text-gray-700">Nomor Surat:</label>
                        <input type="text" name="letter_number" id="letter_number" value="{{ old('letter_number') }}"
                            required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('letter_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_date" class="block font-medium text-sm text-gray-700">Tanggal Surat:</label>
                        <input type="date" name="letter_date" id="letter_date" value="{{ old('letter_date') }}"
                            required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('letter_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block font-medium text-sm text-gray-700">Perihal:</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="disposition" class="block font-medium text-sm text-gray-700">Disposition:</label>
                        <select name="disposition" id="disposition" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="">-- Pilih Disposition --</option>
                            <option value="known" {{ old('disposition') == 'known' ? 'selected' : '' }}>
                                Untuk Diketahui</option>
                            <option value="actioned" {{ old('disposition') == 'actioned' ? 'selected' : '' }}>
                                Penting</option>
                            <option value="archived" {{ old('disposition') == 'archived' ? 'selected' : '' }}>
                                Arsip</option>
                        </select>
                        @error('disposition')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload File -->
                    <div>
                        <label for="file_path" class="block font-medium text-sm text-gray-700">File Surat:</label>
                        <input type="file" name="file_path" id="file_path"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('file_path')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User -->
                    <div>
                        @php
                            $currentUser = Auth::user();
                        @endphp

                        @if ($currentUser->role === 'staff_bidang')
                            <input type="hidden" name="user_id" value="{{ $currentUser->user_id }}">
                        @else
                            <label for="user_id" class="block font-medium text-sm text-gray-700">Bidang tujuan:</label>
                            <select name="user_id" id="user_id" required
                                class="border:1px text-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="">-- Pilih Tujuan --</option>
                                @forelse ($users as $user)
                                    <option value="{{ $user->id }}" style="text-decoration-color: black"></option>
                                    {{ old('user_id') }}>
                                    {{ $user->name }}</option>
                                @empty
                                    <?php dd($user); ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Tidak ada Bidang Tujuan.</td>
                                    </tr>
                                @endforelse
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="btn">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
