<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-12">
        <!-- Container diperlebar -->
        <div class="w-full max-w-6xl bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="flex flex-col md:flex-row">
                
                <!-- LOGO SECTION -->
                <div class="md:w-1/2 w-full bg-gray-50 flex flex-col items-center justify-center p-10">
                    <img src="{{ asset('images/logopemda.png') }}" alt="Logo Pemda" class="w-36 mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800 text-center">Sistem Arsip Surat</h1>
                </div>

                <!-- LOGIN FORM SECTION -->
                <div class="md:w-1/2 w-full p-10 flex items-center">
                    <div class="w-full">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login ke Akun Anda</h2>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div>
                                <x-primary-button class="w-full justify-center">
                                    {{ __('Login') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
