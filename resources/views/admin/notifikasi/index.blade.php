@extends('layouts.admin')

@section('title', 'Notifikasi Admin')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2.5">
                    <i class="fas fa-bell text-[#7367f0]"></i> Notifikasi Pengajuan Izin
                </h1>
                <p class="text-xs sm:text-sm text-[#626885] mt-1">Lihat semua pengajuan izin guru dan tandai notifikasi yang sudah dibaca.</p>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-[#c2c6dc]">Belum dibaca: <span class="font-semibold text-white">{{ $unread_count }}</span></span>
                @if($unread_count > 0)
                    <form action="{{ route('admin.notifikasi.baca-semua') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#7367f0] hover:bg-[#5b50d6] text-white text-xs font-semibold px-4 py-2 transition">
                            <i class="fas fa-check-double text-[10px]"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success') || session('error'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-4 py-3 rounded-xl">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden">
            <div class="space-y-4 p-6">
                @forelse($notifikasi as $item)
                    <div class="rounded-2xl border p-5 {{ $item->read_at ? 'bg-[#10163a] border-[#242b4d]' : 'bg-[#141d42] border-[#7367f0]/30' }}">
                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-start">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2 text-xs text-[#626885]">
                                    <span>{{ $item->created_at->translatedFormat('d M Y H:i') }}</span>
                                    <span class="inline-flex items-center rounded-full bg-[#7367f0]/10 text-[#7367f0] px-2.5 py-1 text-[10px] uppercase tracking-wide">
                                        {{ $item->read_at ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                                    </span>
                                </div>
                                <p class="text-sm text-white font-semibold">{{ $item->pesan }}</p>
                                @if($item->absensi)
                                    <p class="mt-3 text-xs text-[#c2c6dc]">
                                        Guru: <span class="font-semibold text-white">{{ $item->absensi->guru?->nama ?? 'N/A' }}</span>
                                        • Tanggal: <span class="font-semibold text-white">{{ $item->absensi->tanggal->translatedFormat('d M Y') }}</span>
                                        • Status: <span class="font-semibold text-white capitalize">{{ str_replace('_', ' ', $item->absensi->status) }}</span>
                                    </p>
                                @endif
                            </div>

                            @if(!$item->read_at)
                                <form action="{{ route('admin.notifikasi.baca', $item) }}" method="POST" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#7367f0] hover:bg-[#5b50d6] text-white text-xs font-semibold px-4 py-2 transition">
                                        <i class="fas fa-check text-[10px]"></i> Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-[#626885]">
                        <i class="fas fa-bell-slash text-4xl mb-4 text-[#242b4d]"></i>
                        <p class="text-sm font-medium">Belum ada notifikasi pengajuan izin.</p>
                    </div>
                @endforelse
            </div>

            @if($notifikasi->hasPages())
                <div class="px-6 py-4 border-t border-[#242b4d] bg-[#10163a]/50">
                    {{ $notifikasi->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
