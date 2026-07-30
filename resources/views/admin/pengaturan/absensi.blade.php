@extends('layouts.admin')

@section('title', 'Konfigurasi Absensi')

@section('content')
<div class="space-y-6">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="rounded border border-green-200 bg-green-50 px-4 py-3 text-green-700 flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold mb-2 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Terjadi Kesalahan
            </div>

            <ul class="list-disc ml-5 space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card Utama (Menyatukan Header & Form) --}}
    <div class="bg-white shadow-sm border border-gray-200 border-l-4 border-l-green-500 rounded-none">

        {{-- Header Card --}}
        <div class="border-b border-gray-200 px-6 py-5">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fa-solid fa-gears text-blue-600"></i>
                Konfigurasi Absensi
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Pengaturan waktu absensi masuk, toleransi keterlambatan, dan absensi pulang.
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.konfigurasi-absen.update') }}" method="POST">
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
                            class="w-full rounded-none border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-2.5 rounded-none text-gray-600 font-medium whitespace-nowrap">
                            Menit
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 mt-1.5">
                        Berapa menit sebelum jadwal mengajar guru diperbolehkan melakukan absensi masuk.
                    </p>
                </div>

                {{-- Toleransi Terlambat --}}
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
                            class="w-full rounded-none border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-2.5 rounded-none text-gray-600 font-medium whitespace-nowrap">
                            Menit
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 mt-1.5">
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
                            class="w-full rounded-none border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            min="0"
                            max="180">

                        <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-2.5 rounded-none text-gray-600 font-medium whitespace-nowrap">
                            Menit
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 mt-1.5">
                        Berapa menit sebelum jadwal selesai guru dapat melakukan absensi pulang.
                    </p>
                </div>

            </div>

            {{-- Footer Button --}}
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 font-medium transition duration-150">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Konfigurasi
                </button>
            </div>

        </form>

    </div>

</div>
@endsection