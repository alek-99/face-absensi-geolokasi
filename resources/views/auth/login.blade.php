<x-guest-layout>
    <!-- Card Header / Branding -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Selamat Datang Kembali!
        </h2>
        <p class="text-sm text-gray-500 mt-2">
            Silakan masukkan akun Anda untuk melanjutkan
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input id="email" 
                    class="block w-full pl-11 pr-4 py-3 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shadow-sm" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="nama@email.com"
                    required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <!-- Password dengan fitur Show/Hide (Menggunakan Alpine.js) -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
            <div class="relative mt-1">
                <!-- Icon Kunci (Kiri) -->
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <!-- Input Password (tipe dinamis berdasarkan state 'show') -->
                <x-text-input id="password" 
                    class="block w-full pl-11 pr-12 py-3 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shadow-sm"
                    ::type="show ? 'text' : 'password'"
                    name="password"
                    placeholder="••••••••"
                    required autocomplete="current-password" />

                <!-- Tombol Show/Hide (Kanan) -->
                <button type="button" 
                    @click="show = !show" 
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-indigo-600 transition-colors focus:outline-none"
                    title="Tampilkan/Sembunyikan Password">
                    
                    <!-- Icon Mata Terbuka (Muncul saat password disembunyikan) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.632 5 12 5c4.368 0 8.268 2.943 9.542 7-1.274 4.057-5.174 7-9.542 7-4.368 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <!-- Icon Mata Tertutup (Muncul saat password ditampilkan) -->
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500/30 w-4 h-4 transition" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Ingat saya') }}</span>
            </label>

            {{-- Target WhatsApp Admin --}}
            @php
                $noAdmin = '6281234567890'; // 👈 GANTI DENGAN NOMOR WA ADMIN
                $pesan = urlencode('Halo Admin, saya lupa password akun saya. Mohon bantuannya untuk reset password.');
                $waUrl = "https://wa.me/{$noAdmin}?text={$pesan}";
            @endphp

            <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors hover:underline flex items-center gap-1.5" 
               href="{{ $waUrl }}" 
               target="_blank" 
               rel="noopener noreferrer">
                <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
                Lupa password?
            </a>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 focus:ring-4 focus:ring-indigo-500/20 text-base font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                {{ __('Masuk Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>