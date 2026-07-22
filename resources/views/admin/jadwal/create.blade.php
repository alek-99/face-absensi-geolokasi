@extends('layouts.admin')
@section('title' ,'Tambah Jadwal')

@section('content')
<div class="space-y-8">
    
    {{-- Header Section --}}
    <div class="mb-6 flex items-center gap-4 bg-[#161d3f] p-5 rounded-2xl shadow-md border border-[#242b4d]">
        <a href="{{ route('admin.jadwal.index') }}" class="text-[#7367f0] hover:text-white bg-[#7367f0]/10 hover:bg-[#7367f0] w-10 h-10 rounded-xl flex items-center justify-center transition cursor-pointer" title="Kembali">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                Tambah Jadwal Pelajaran
            </h1>
            <p class="text-xs sm:text-sm text-[#626885]">Buat jadwal belajar mengajar baru untuk sistem</p>
        </div>
    </div>

    {{-- Error Handling Notification --}}
    @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-5 py-4 rounded-2xl mb-6 shadow-sm">
            <div class="font-bold mb-2 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-triangle"></i> Periksa kembali isian Anda:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1.5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden p-6 md:p-8">
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Select Guru --}}
            <div>
                <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-user-tie text-[#7367f0]"></i> Pilih Guru Pengajar <span class="text-[#ff9f43]">*</span>
                </label>
                <select name="guru_id" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition cursor-pointer" required>
                    <option value="" disabled selected class="text-[#626885]">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama ?? $guru->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Grid Hari & Waktu --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-calendar-day text-[#7367f0]"></i> Hari <span class="text-[#ff9f43]">*</span>
                    </label>
                    <select name="hari" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition cursor-pointer" required>
                        <option value="" disabled selected class="text-[#626885]">-- Hari --</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-sign-in-alt text-[#7367f0]"></i> Jam Masuk <span class="text-[#ff9f43]">*</span>
                    </label>
                    <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition" required>
                </div>
                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-sign-out-alt text-[#7367f0]"></i> Jam Pulang <span class="text-[#ff9f43]">*</span>
                    </label>
                    <input type="time" name="jam_pulang" value="{{ old('jam_pulang') }}" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition" required>
                </div>
            </div>

            {{-- Mata Pelajaran --}}
            <div>
                <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-book text-[#7367f0]"></i> Mata Pelajaran
                </label>
                <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition placeholder-[#626885]" placeholder="Contoh: Pemrograman Web / Bahasa Inggris">
            </div>

            {{-- Grid Kelas & Ruangan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-[#7367f0]"></i> Kelas
                    </label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition placeholder-[#626885]" placeholder="Contoh: XII RPL 2">
                </div>
                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-door-open text-[#7367f0]"></i> Ruangan
                    </label>
                    <input type="text" name="ruangan" value="{{ old('ruangan') }}" class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#7367f0] focus:border-transparent outline-hidden transition placeholder-[#626885]" placeholder="Contoh: Lab Komputer 1">
                </div>
            </div>

            {{-- Status Aktif (Toggle Switch Vuesax) --}}
            <div class="pt-2">
                <div class="flex items-center justify-between p-3.5 bg-[#10163a] border border-[#242b4d] rounded-xl max-w-xs">
                    <label class="text-sm font-semibold text-[#c2c6dc] flex items-center gap-2.5 cursor-pointer select-none" for="is_active">
                        <i class="fas fa-toggle-on text-[#626885] w-4 text-center"></i> Langsung Aktifkan Jadwal
                    </label>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-[#161d3f] border border-[#242b4d] peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#626885] after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-[#242b4d] mt-6">
                <a href="{{ route('admin.jadwal.index') }}" class="w-full sm:w-auto bg-[#242b4d] hover:bg-[#2e375e] text-[#c2c6dc] text-center font-bold py-2.5 px-5 rounded-xl text-xs transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto bg-[#7367f0] hover:bg-[#5b50d6] text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow transition duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection