<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Surat Masuk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-md">

                <a href="{{ route('surat-masuk.index') }}"
                    class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition">
                    ← Kembali ke daftar surat masuk
                </a>

                <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="sender" class="block text-sm font-semibold text-gray-700 mb-1">Pengirim</label>
                            <input type="text" name="sender" id="sender" value="{{ old('sender') }}" required
                                class="form-input w-full">
                            @error('sender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="letter_number" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Surat</label>
                            <input type="text" name="letter_number" id="letter_number" value="{{ old('letter_number') }}" required
                                class="form-input w-full">
                            @error('letter_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="letter_date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Surat</label>
                            <input type="date" name="letter_date" id="letter_date" value="{{ old('letter_date') }}" required
                                class="form-input w-full">
                            @error('letter_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1">Perihal</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                class="form-input w-full">
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="disposition" class="block text-sm font-semibold text-gray-700 mb-1">Disposition</label>
                            <select name="disposition" id="disposition" required class="form-select w-full">
                                <option value="">-- Pilih Disposition --</option>
                                <option value="known" {{ old('disposition') == 'known' ? 'selected' : '' }}>Untuk Diketahui</option>
                                <option value="actioned" {{ old('disposition') == 'actioned' ? 'selected' : '' }}>Penting</option>
                                <option value="archived" {{ old('disposition') == 'archived' ? 'selected' : '' }}>Arsip</option>
                            </select>
                            @error('disposition')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file_path" class="block text-sm font-semibold text-gray-700 mb-1">File Surat</label>
                            <input type="file" name="file_path" id="file_path" class="form-input w-full">
                            @error('file_path')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            @php $currentUser = Auth::user(); @endphp

                            @if ($currentUser->role === 'staff_bidang')
                                <input type="hidden" name="user_id" value="{{ $currentUser->user_id }}">
                            @else
                                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Bidang Tujuan</label>
                                <select name="user_id" id="user_id" required class="form-select w-full">
                                    <option value="">-- Pilih Tujuan --</option>
                                    @forelse ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @empty
                                        <option disabled>⚠ Tidak ada bidang tersedia</option>
                                    @endforelse
                                </select>
                                @error('user_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                    </div>

                    <div class="pt-6 text-right">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition-all">
                            Simpan Surat
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        .form-input, .form-select {
            @apply border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm;
        }
    </style>
</x-app-layout>
