<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Edit Surat Keluar
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-md transition-shadow hover:shadow-lg">

                <a href="{{ route('surat-keluar.index') }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke daftar surat keluar
                </a>

                <form action="{{ route('surat-keluar.update', $outgoingletter->slug) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Penerima --}}
                    <div>
                        <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">Penerima</label>
                        <input type="text" name="recipient" id="recipient"
                            value="{{ old('recipient', $outgoingletter->recipient) }}" required
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        @error('recipient')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Surat --}}
                    <div>
                        <label for="letter_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
                        <input type="text" name="letter_number" id="letter_number"
                            value="{{ old('letter_number', $outgoingletter->letter_number) }}" required
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        @error('letter_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Surat --}}
                    <div>
                        <label for="letter_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                        <input type="date" name="letter_date" id="letter_date"
                            value="{{ old('letter_date', \Carbon\Carbon::parse($outgoingletter->letter_date)->format('Y-m-d')) }}"
                            required
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        @error('letter_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Perihal --}}
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                        <input type="text" name="subject" id="subject"
                            value="{{ old('subject', $outgoingletter->subject) }}" required
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload File --}}
                    <div>
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">Ganti File (Opsional)</label>
                        <input type="file" name="file_path" id="file_path"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        @error('file_path')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if ($outgoingletter->file_path)
                            <p class="text-sm mt-2">
                                File saat ini: 
                                <a href="{{ asset('storage/' . $outgoingletter->file_path) }}"
                                    target="_blank" class="text-indigo-600 underline hover:text-indigo-800">
                                    Lihat file
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- Dari Bidang --}}
                    <div>
                        @php $currentUser = Auth::user(); @endphp

                        @if ($currentUser->role === 'staff_bidang')
                            <input type="hidden" name="user_id" value="{{ $currentUser->user_id }}">
                        @else
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Bidang Tujuan</label>
                            <select name="user_id" id="user_id" required
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
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

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
