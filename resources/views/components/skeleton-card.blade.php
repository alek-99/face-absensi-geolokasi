@props(['count' => 3])

<div {{ $attributes->merge(['class' => 'space-y-4 animate-pulse']) }}>
    @for ($i = 0; $i < $count; $i++)
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            {{-- Header Skeleton: Badge Kelas, Judul Mapel, Status Badge --}}
            <div class="flex justify-between items-start mb-2">
                <div class="space-y-2 w-2/3">
                    {{-- Badge Nama Kelas --}}
                    <div class="h-4 w-16 bg-gray-200 dark:bg-gray-700 rounded-md"></div>
                    {{-- Nama Mapel --}}
                    <div class="h-5 w-3/4 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                </div>
                {{-- Status Badge (Selesai/Belum) --}}
                <div class="h-6 w-20 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
            </div>

            {{-- Info Jam & Total Siswa --}}
            <div class="flex items-center space-x-3 mb-4 mt-3">
                {{-- Icon + Jam --}}
                <div class="h-3.5 w-24 bg-gray-200 dark:bg-gray-700 rounded"></div>
                <div class="h-2 w-2 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                {{-- Info Siswa --}}
                <div class="h-3.5 w-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
            </div>

            {{-- Tombol Utama Skeleton --}}
            <div class="h-10 w-full bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
        </div>
    @endfor
</div>