@extends('layouts.admin')

@section('title', 'Konfigurasi Absensi')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fa-solid fa-gears text-blue-600"></i>
            Konfigurasi Absensi
        </h1>
        <p class="text-gray-500 mt-2">
            Pengaturan waktu absensi masuk, toleransi keterlambatan dan absensi pulang.
        </p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div
            class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if($errors->any())
        <div
            class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold mb-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Terjadi Kesalahan
            </div>

            <ul class="list-disc ml-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow border border-gray-200">

        <div class="border-b px-6 py-4">
            <h2 class="font-semibold text-lg text-gray-700">
                Pengaturan Sistem Absensi
            </h2>
        </div>

        <form
            action="{{ route('admin.konfigurasi-absen.update') }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                {{-- Buka Absen Masuk --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-door-open text-blue-500 mr-1"></i>
                        Buka Absensi Masuk
                    </label>

                    <div class="flex items-center">

                        <input
                            type="number"
                            name="buka_absen_masuk_menit"
                            value="{{ old('buka_absen_masuk_menit', $konfigurasi->buka_absen_masuk_menit) }}"
                            class="w-full rounded-l-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-3 rounded-r-lg">
                            Menit
                        </span>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Berapa menit sebelum jadwal mengajar guru diperbolehkan melakukan absensi masuk.
                    </p>

                </div>

                {{-- Toleransi --}}
                <div>

                    <label class="block font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-clock text-orange-500 mr-1"></i>
                        Toleransi Terlambat
                    </label>

                    <div class="flex items-center">

                        <input
                            type="number"
                            name="toleransi_terlambat_menit"
                            value="{{ old('toleransi_terlambat_menit', $konfigurasi->toleransi_terlambat_menit) }}"
                            class="w-full rounded-l-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-3 rounded-r-lg">
                            Menit
                        </span>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Guru masih dianggap tepat waktu selama berada dalam batas toleransi ini.
                    </p>

                </div>

                {{-- Absen Pulang --}}
                <div>

                    <label class="block font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-person-walking-arrow-right text-green-500 mr-1"></i>
                        Buka Absensi Pulang
                    </label>

                    <div class="flex items-center">

                        <input
                            type="number"
                            name="buka_absen_pulang_menit"
                            value="{{ old('buka_absen_pulang_menit', $konfigurasi->buka_absen_pulang_menit) }}"
                            class="w-full rounded-l-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-3 rounded-r-lg">
                            Menit
                        </span>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Berapa menit sebelum jadwal selesai guru dapat melakukan absensi pulang.
                    </p>

                </div>

            </div>

            {{-- Footer --}}
            <div
                class="border-t bg-gray-50 px-6 py-4 flex justify-end">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 transition">

                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Konfigurasi

                </button>

            </div>

        </form>

    </div>

</div>
@endsection
