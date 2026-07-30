@extends('layouts.admin')

@section('title', 'Notifikasi Admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 shadow-sm border-l-4 border-green-600">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2.5">
                    <i class="fas fa-bell text-indigo-600"></i> Notifikasi Pengajuan Izin
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Lihat semua pengajuan izin guru dan tandai notifikasi yang sudah dibaca.</p>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    Belum dibaca: <span class="font-bold text-indigo-600">{{ $unread_count }}</span>
                </span>
                @if($unread_count > 0)
                    <form action="{{ route('admin.notifikasi.baca-semua') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                            <i class="fas fa-check-double text-[10px]"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Flash Alert -->
        @if(session('success') || session('error'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <!-- Main Content Box -->
        <div class="bg-white shadow-sm border-l-4 border-green-600">
            <div class="divide-y divide-gray-100">
                @forelse($notifikasi as $item)
                    {{-- Card tanpa rounded, menyatu dengan aksen border-l-4 --}}
                    <div class="p-5 border-l-4 transition-colors {{ $item->read_at ? 'border-emerald-600 bg-white' : 'border-indigo-600 bg-indigo-50/40' }}">
                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-start">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1 text-xs text-gray-500">
                                    <span>{{ $item->created_at->translatedFormat('d M Y H:i') }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $item->read_at ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $item->read_at ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-900 font-semibold mt-1">{{ $item->pesan }}</p>

                                @if($item->absensi)
                                    <div class="mt-2 text-xs text-gray-600 flex flex-wrap gap-x-3 gap-y-1">
                                        <span>Guru: <strong class="text-gray-800">{{ $item->absensi->guru?->nama ?? 'N/A' }}</strong></span>
                                        <span>• Tanggal: <strong class="text-gray-800">{{ $item->absensi->tanggal->translatedFormat('d M Y') }}</strong></span>
                                        <span>• Status: <strong class="text-gray-800 capitalize">{{ str_replace('_', ' ', $item->absensi->status) }}</strong></span>
                                    </div>
                                @endif
                            </div>

                            @if(!$item->read_at)
                                <form action="{{ route('admin.notifikasi.baca', $item) }}" method="POST" class="shrink-0 mt-2 sm:mt-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 transition">
                                        <i class="fas fa-check text-[10px]"></i> Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-bell-slash text-4xl mb-3 text-gray-300"></i>
                        <p class="text-sm font-medium text-gray-500">Belum ada notifikasi pengajuan izin.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Footer -->
            @if($notifikasi->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $notifikasi->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection