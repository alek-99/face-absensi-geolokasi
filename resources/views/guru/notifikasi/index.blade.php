@extends('layouts.guru')
@section('page_title','Notifikasi')
@section('title', 'Notifikasi Saya')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-bell text-[#7367f0]"></i> Pusat Notifikasi
            </h1>
            <p class="text-sm text-[#626885] mt-1">Pantau status persetujuan izin dan berkas absensi Anda.</p>
        </div>
        
        <form action="{{ route('guru.notifikasi.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-xs font-semibold bg-[#242b4d] hover:bg-[#2e375e] text-[#c2c6dc] border border-[#333b61] px-4 py-2.5 rounded-xl transition-all cursor-pointer flex items-center justify-center gap-2">
                <i class="fa-solid fa-envelope-open"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <!-- List Notifikasi -->
    <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden p-4 space-y-3">
        @forelse($notifikasis as $notif)
            <div class="p-4 rounded-xl border {{ is_null($notif->read_at) ? 'bg-[#10163a]/60 border-[#7367f0]/30' : 'bg-[#10163a]/20 border-[#242b4d]' }} flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all">
                <div class="flex items-start gap-3">
                    @if($notif->status_persetujuan == 'disetujui')
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <i class="fa-solid fa-check text-base"></i>
                        </div>
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm {{ is_null($notif->read_at) ? 'font-semibold text-white' : 'text-[#c2c6dc]' }}">{{ $notif->pesan }}</p>
                        <span class="text-xs text-[#626885] block mt-1"><i class="fa-regular fa-clock mr-1"></i>{{ $notif->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
                
                <div class="text-right shrink-0">
                    <a href="{{ route('guru.notifikasi.read', $notif->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7367f0] hover:text-white bg-[#7367f0]/10 hover:bg-[#7367f0] px-3 py-2 rounded-xl transition-all">
                        Lihat Absensi <i class="fa-solid fa-angle-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-[#626885]">
                <i class="fa-regular fa-bell-slash text-4xl mb-3 block text-[#242b4d]"></i>
                <p class="text-sm">Belum ada riwayat notifikasi masuk.</p>
            </div>
        @endforelse

        @if($notifikasis->hasPages())
            <div class="pt-4 border-t border-[#242b4d]">
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection