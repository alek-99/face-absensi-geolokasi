<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-2">
                        {{ __("Kamu Sudah Login") }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        Sesi login Anda masih aktif. Silakan log out terlebih dahulu untuk masuk kembali ke dalam sistem dengan akun lain.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>