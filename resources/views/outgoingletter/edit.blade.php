<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Surat Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">

                <a href="{{ route('surat-keluar.index') }}" class="btn inline-block mb-6">
                    ← Kembali ke daftar surat
                </a>

                <form action="{{ route('surat-keluar.update', $outgoingletter->slug) }}" method="POST" class="space-y-4"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="recipient" class="block font-medium text-sm text-gray-700">Pengirim:</label>
                        <input type="text" name="recipient" id="recipient"
                            value="{{ old('recipient', $outgoingletter->recipient) }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('recipient')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_number" class="block font-medium text-sm text-gray-700">Nomor Surat:</label>
                        <input type="text" name="letter_number" id="letter_number"
                            value="{{ old('letter_number', $outgoingletter->letter_number) }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('letter_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_date" class="block font-medium text-sm text-gray-700">Tanggal Surat:</label>
                        <input type="date" name="letter_date" id="letter_date"
                            value="{{ old('letter_date', \Carbon\Carbon::parse($outgoingletter->letter_date)->format('Y-m-d')) }}"
                            required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('letter_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block font-medium text-sm text-gray-700">Perihal:</label>
                        <input type="text" name="subject" id="subject"
                            value="{{ old('subject', $outgoingletter->subject) }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_path" class="block font-medium text-sm text-gray-700">File Surat (biarkan
                            kosong jika tidak ingin mengganti):</label>
                        <input type="file" name="file_path" id="file_path"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                        @error('file_path')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($outgoingletter->file_path)
                            <p class="text-sm mt-2">File saat ini:
                                <a href="{{ asset('storage/' . $outgoingletter->file_path) }}"
                                    class="text-indigo-600 underline" target="_blank">Lihat file</a>
                            </p>
                        @endif
                    </div>

                    <div>
                        @php
                            $currentUser = Auth::user();
                        @endphp

                        @if ($currentUser->role === 'staff_bidang')
                            <input type="hidden" name="user_id" value="{{ $currentUser->user_id }}">
                        @else
                            <label for="user_id" class="block font-medium text-sm text-gray-700">Bidang tujuan:</label>
                            <select name="user_id" id="user_id" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="">-- Pilih Tujuan --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $outgoingletter->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="btn">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
