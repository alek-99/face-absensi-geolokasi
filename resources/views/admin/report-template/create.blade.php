@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="bg-[#161d3f] p-6 rounded-2xl border border-[#242b4d] shadow-md">
        <h4 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-file-medical text-[#7367f0]"></i> Tambah Template Laporan
        </h4>
        <p class="text-sm text-[#626885] mt-1">Buat template laporan baru untuk digunakan dalam sistem cetak PDF.</p>
    </div>

    <form action="{{ route('admin.report-template.store') }}" method="POST">
        @csrf
        
        @include('admin.report-template._form')

        <div class="flex items-center gap-3 pt-4 border-t border-[#242b4d]">
            <button type="submit" class="inline-flex items-center gap-2 bg-[#7367f0] hover:bg-[#5b50d6] text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 text-xs cursor-pointer">
                <i class="fas fa-save"></i> Simpan Template
            </button>
            <a href="{{ route('admin.report-template.index') }}" class="inline-flex items-center gap-2 bg-[#161d3f] border border-[#242b4d] hover:bg-[#242b4d] text-[#c2c6dc] font-semibold px-5 py-2.5 rounded-xl shadow-sm transition duration-200 text-xs">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection