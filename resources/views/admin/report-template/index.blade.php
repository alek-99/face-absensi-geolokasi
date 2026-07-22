@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
        <div>
            <h4 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-file-invoice text-[#7367f0]"></i> Template Laporan
            </h4>
            <p class="text-sm text-[#626885] mt-1">Kelola kerangka format dan struktur output laporan sistem.</p>
        </div>
        <a href="{{ route('admin.report-template.create') }}" class="inline-flex items-center gap-2 bg-[#7367f0] hover:bg-[#5b50d6] text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-xs">
            <i class="fas fa-plus text-[10px]"></i> Tambah Template
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-[#c2c6dc] whitespace-nowrap">
                <thead>
                    <tr class="bg-[#10163a]/50 border-b border-[#242b4d] text-xs font-semibold uppercase tracking-wider text-[#626885]">
                        <th class="px-5 py-4 text-center w-12">#</th>
                        <th class="px-6 py-4"><i class="fas fa-key text-[#7367f0] mr-1.5"></i> Kode</th>
                        <th class="px-6 py-4"><i class="fas fa-paste text-[#7367f0] mr-1.5"></i> Nama Laporan</th>
                        <th class="px-6 py-4"><i class="fas fa-heading text-[#7367f0] mr-1.5"></i> Judul Laporan</th>
                        <th class="px-6 py-4 text-center w-28"><i class="fas fa-toggle-on text-[#7367f0] mr-1.5"></i> Status</th>
                        <th class="px-6 py-4 text-center w-40"><i class="fas fa-cogs text-[#7367f0] mr-1.5"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#242b4d]">
                    @forelse($templates as $i => $template)
                        <tr class="hover:bg-[#10163a]/40 transition-colors duration-150">
                            <td class="px-5 py-4 text-center font-medium text-[#626885]">
                                {{ $templates->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-[#7367f0] bg-[#7367f0]/10 rounded-lg px-2.5 py-1 border border-[#7367f0]/20 uppercase">
                                    {{ $template->kode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $template->nama_laporan }}
                            </td>
                            <td class="px-6 py-4 text-[#c2c6dc] max-w-xs truncate">
                                {{ $template->judul_laporan }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($template->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#10163a] text-[#626885] border border-[#242b4d]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#626885]"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('admin.report-template.edit', $template) }}"
                                   class="inline-flex items-center gap-1 bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/20 text-amber-400 text-xs font-semibold px-2.5 py-1.5 rounded-xl transition">
                                    <i class="fas fa-pen text-[10px]"></i> Edit
                                </a>
                                <form action="{{ route('admin.report-template.destroy', $template) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 text-rose-400 text-xs font-semibold px-2.5 py-1.5 rounded-xl transition cursor-pointer">
                                        <i class="fas fa-trash-alt text-[10px]"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#626885]">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-folder-open text-4xl text-[#242b4d]"></i>
                                    <span class="text-sm">Belum ada data template laporan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($templates->hasPages())
        <div class="mt-4 dark-pagination">
            {{ $templates->links() }}
        </div>
    @endif
</div>
@endsection