@php
    $template ??= null;
@endphp

<div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden mb-6">
    <div class="bg-[#10163a]/50 border-b border-[#242b4d] px-5 py-4 flex items-center gap-2">
        <i class="fas fa-edit text-[#7367f0]"></i>
        <h5 class="font-bold text-[#c2c6dc] text-xs uppercase tracking-wider">Form Detail Template</h5>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-[#c2c6dc] mb-2 flex items-center gap-2">
                <i class="fas fa-key text-[#7367f0] w-4 text-center"></i> Kode Template
            </label>
            <input type="text" name="kode" 
                   placeholder="mis. JADWAL_GURU"
                   value="{{ old('kode', $template->kode ?? '') }}"
                   class="w-full bg-[#10163a] border @error('kode') border-rose-500/50 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#242b4d] focus:ring-[#7367f0]/50 focus:border-[#7367f0] @enderror text-white rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 transition font-mono uppercase placeholder-[#626885]">
            @error('kode') 
                <div class="text-rose-400 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle text-[10px]"></i> {{ $message }}
                </div> 
            @enderror
        </div>

        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-[#c2c6dc] mb-2 flex items-center gap-2">
                <i class="fas fa-paste text-[#7367f0] w-4 text-center"></i> Nama Laporan
            </label>
            <input type="text" name="nama_laporan" 
                   value="{{ old('nama_laporan', $template->nama_laporan ?? '') }}"
                   class="w-full bg-[#10163a] border @error('nama_laporan') border-rose-500/50 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#242b4d] focus:ring-[#7367f0]/50 focus:border-[#7367f0] @enderror text-white rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 transition">
            @error('nama_laporan') 
                <div class="text-rose-400 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle text-[10px]"></i> {{ $message }}
                </div> 
            @enderror
        </div>

        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-[#c2c6dc] mb-2 flex items-center gap-2">
                <i class="fas fa-heading text-[#7367f0] w-4 text-center"></i> Judul Laporan <span class="text-xs font-normal text-[#626885]">(Tampil di PDF)</span>
            </label>
            <input type="text" name="judul_laporan" 
                   value="{{ old('judul_laporan', $template->judul_laporan ?? '') }}"
                   class="w-full bg-[#10163a] border @error('judul_laporan') border-rose-500/50 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#242b4d] focus:ring-[#7367f0]/50 focus:border-[#7367f0] @enderror text-white rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 transition">
            @error('judul_laporan') 
                <div class="text-rose-400 text-xs mt-1.5 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle text-[10px]"></i> {{ $message }}
                </div> 
            @enderror
        </div>

        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-[#c2c6dc] mb-2 flex items-center gap-2">
                <i class="fas fa-paragraph text-[#7367f0] w-4 text-center"></i> Subjudul
            </label>
            <input type="text" name="subjudul" 
                   value="{{ old('subjudul', $template->subjudul ?? '') }}"
                   class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/50 focus:border-[#7367f0] transition">
        </div>

        <div class="md:col-span-12">
            <label class="block text-sm font-medium text-[#c2c6dc] mb-2 flex items-center gap-2">
                <i class="fas fa-align-left text-[#7367f0] w-4 text-center"></i> Deskripsi
            </label>
            <textarea name="deskripsi" rows="3" 
                      class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/50 focus:border-[#7367f0] transition resize-none">{{ old('deskripsi', $template->deskripsi ?? '') }}</textarea>
        </div>

        <div class="md:col-span-12 mt-2">
            <div class="flex items-center justify-between p-3.5 bg-[#10163a] border border-[#242b4d] rounded-xl max-w-xs">
                <label class="text-sm font-medium text-[#c2c6dc] flex items-center gap-2.5 cursor-pointer select-none" for="is_active">
                    <i class="fas fa-toggle-on text-[#626885] w-4 text-center"></i> Status Template Aktif
                </label>
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-[#161d3f] border border-[#242b4d] peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#626885] after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                </div>
            </div>
        </div>
    </div>
</div>