<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Tambah Surat Keluar
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">

                <a href="{{ route('surat-keluar.index') }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:underline mb-6">
                    ← Kembali ke daftar surat keluar
                </a>

                <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div>
                        <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">Penerima</label>
                        <input type="text" name="recipient" id="recipient"
                            value="{{ old('recipient') }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('recipient')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
                        <input type="text" name="letter_number" id="letter_number"
                            value="{{ old('letter_number') }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('letter_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="letter_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                        <input type="date" name="letter_date" id="letter_date"
                            value="{{ old('letter_date') }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('letter_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                        <input type="text" name="subject" id="subject"
                            value="{{ old('subject') }}" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">File Surat (PDF)</label>
                        <input type="file" name="file_path" id="file_path"
                            class="w-full border rounded-lg border-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('file_path')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        @php $currentUser = Auth::user(); @endphp

                        @if ($currentUser->role === 'staff_bidang')
                            <input type="hidden" name="user_id" value="{{ $currentUser->user_id }}">
                        @else
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Dari Bidang</label>
                            <select name="user_id" id="user_id" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Bidang --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
                            Simpan Surat
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
