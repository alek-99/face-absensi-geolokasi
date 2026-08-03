@extends('layouts.guru')
@section('page_title','Notifikasi')
@section('title', 'Notifikasi Saya')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-bell text-emerald-600"></i> Pusat Notifikasi
            </h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status persetujuan izin dan berkas absensi Anda.</p>
        </div>
        
        <form action="{{ route('guru.notifikasi.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2.5 transition-all cursor-pointer flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-envelope-open"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <!-- List Notifikasi -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 space-y-3">
        @forelse($notifikasis as $notif)
            <div class="p-4 border rounded-lg {{ is_null($notif->read_at) ? 'bg-emerald-50/50 border-emerald-200' : 'bg-gray-50/50 border-gray-100' }} flex items-center justify-between gap-4 transition-all hover:border-gray-300">
                <div class="flex items-start gap-3">
                    @if($notif->status_persetujuan == 'disetujui')
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-emerald-100 text-emerald-600 rounded-full border border-emerald-200">
                            <i class="fa-solid fa-check text-base"></i>
                        </div>
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-rose-100 text-rose-600 rounded-full border border-rose-200">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm {{ is_null($notif->read_at) ? 'font-semibold text-gray-900' : 'text-gray-600' }}">{{ $notif->pesan }}</p>
                        <span class="text-xs text-gray-400 block mt-1">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $notif->created_at?->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <i class="fa-regular fa-bell-slash text-4xl mb-3 block text-gray-300"></i>
                <p class="text-sm">Belum ada riwayat notifikasi masuk.</p>
            </div>
        @endforelse

        @if($notifikasis->hasPages())
            <div class="pt-4 border-t border-gray-100">
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection