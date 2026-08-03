@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Header Banner StartAdmin Style --}}
    <div class="bg-white p-5 rounded-md border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
           
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-black">DASHBOARD ADMIN</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">SIAFI v1.0</span>
                </div>
                <p class="text-black text-sm mt-0.5">Ringkasan statistik dan aktivitas sistem absensi Face ID.</p>
            </div>
        </div>
        <div class="text-left md:text-right border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
            <p class="text-xs text-black font-medium">Hari Ini</p>
            <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Main Stat Cards Grid (AdminLTE Style / Sesuai Gambar) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        {{-- Total Siswa (Cyan / Info) --}}
        <div class="bg-cyan-500 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalSiswa) }}</h3>
                <p class="text-sm font-medium mt-1">Total Siswa</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('admin.siswa.index') ?? '#' }}" class="bg-black/10 hover:bg-black/20 text-white text-xs py-1.5 px-3 text-center flex items-center justify-center gap-1 transition-colors">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right text-xs"></i>
            </a>
        </div>

        {{-- Total Guru (Green / Success) --}}
        <div class="bg-emerald-500 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalGuru) }}</h3>
                <p class="text-sm font-medium mt-1">Total Guru</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('admin.guru.index') }}" class="bg-black/10 hover:bg-black/20 text-white text-xs py-1.5 px-3 text-center flex items-center justify-center gap-1 transition-colors">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right text-xs"></i>
            </a>
        </div>

        {{-- Total Kelas (Yellow / Warning) --}}
        <div class="bg-amber-400 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalKelas) }}</h3>
                <p class="text-sm font-medium mt-1">Total Kelas</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('admin.kelas.index') ?? '#' }}" class="bg-black/10 hover:bg-black/20 text-white text-xs py-1.5 px-3 text-center flex items-center justify-center gap-1 transition-colors">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right text-xs"></i>
            </a>
        </div>

        {{-- Total Mapel (Red / Danger) --}}
        <div class="bg-rose-500 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalMapel) }}</h3>
                <p class="text-sm font-medium mt-1">Mata Pelajaran</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('admin.mapel.index') ?? '#' }}" class="bg-black/10 hover:bg-black/20 text-white text-xs py-1.5 px-3 text-center flex items-center justify-center gap-1 transition-colors">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right text-xs"></i>
            </a>
        </div>

        {{-- Total Jadwal (Indigo / Primary) --}}
        <div class="bg-indigo-600 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalJadwal) }}</h3>
                <p class="text-sm font-medium mt-1">Jadwal Aktif</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ route('admin.jadwal.index') }}" class="bg-black/10 hover:bg-black/20 text-white text-xs py-1.5 px-3 text-center flex items-center justify-center gap-1 transition-colors">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right text-xs"></i>
            </a>
        </div>

        {{-- Total User (Purple) --}}
        <div class="bg-purple-600 text-white rounded shadow relative overflow-hidden flex flex-col justify-between">
            <div class="p-4 relative z-10">
                <h3 class="text-3xl font-bold leading-tight">{{ number_format($totalPengguna) }}</h3>
                <p class="text-sm font-medium mt-1">Pengguna App</p>
            </div>
            <div class="absolute right-2 top-2 text-black/15 text-6xl pointer-events-none">
                <i class="fas fa-users"></i>
            </div>
           
        </div>

    </div>

    {{-- Content Layout Grid: Quick Menu & Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Akses Cepat (3 Cols) --}}
        <div class="lg:col-span-3 space-y-6">
           {{-- Informasi Sistem (Menggantikan Navigasi Cepat) --}}
            <div class="bg-white rounded-md border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-black uppercase tracking-wider flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-2"></i> Informasi Sistem
                    </h3>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-black font-medium">Status Server</span>
                        <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded text-xs">Online</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-black font-medium">Engine Absensi</span>
                        <span class="text-black font-semibold text-xs">Face Recognition AI</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-black font-medium">Versi Aplikasi</span>
                        <span class="text-black font-semibold text-xs">v1.0.0</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                        <span class="text-black font-medium">Laravel Versi</span>
                        <span class="text-black font-semibold text-xs">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-black font-medium">Waktu Server</span>
                        <span class="text-black font-semibold text-xs">{{ \Carbon\Carbon::now()->format('H:i T') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pie Chart Rasio Absensi Hari Ini --}}
            <div class="bg-white rounded-md border border-slate-200 shadow-sm p-4">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center">
                    <i class="fas fa-chart-pie text-indigo-600 mr-2"></i> Rasio Status Hari Ini
                </h3>
                <div class="relative h-48">
                    <canvas id="pieChartHariIni"></canvas>
                </div>
            </div>
        </div>

        {{-- Main Bar Chart (9 Cols) --}}
        <div class="lg:col-span-9 bg-white rounded-md border border-slate-200 shadow-sm flex flex-col">
            <div class="bg-slate-50 border-b border-slate-200 px-5 py-3 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center">
                    <i class="fas fa-chart-bar text-indigo-600 mr-2"></i> Tren Kehadiran 7 Hari Terakhir
                </h3>
                <span class="text-xs text-slate-400">Diperbarui realtime</span>
            </div>
            <div class="p-5 flex-1 relative min-h-[350px]">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. BAR CHART 7 HARI ---
        const ctxBar = document.getElementById('absensiChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const dataMasuk = {!! json_encode($chartDataMasuk) !!};
        const dataAbsen = {!! json_encode($chartDataAbsen) !!};
        const dataAlpha = {!! json_encode($chartDataAlpha) !!};

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Hadir / Terlambat',
                        data: dataMasuk,
                        backgroundColor: '#4f46e5', // Indigo 600
                        borderRadius: 2,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Izin / Sakit / Cuti / TL',
                        data: dataAbsen,
                        backgroundColor: '#f59e0b', // Amber 500
                        borderRadius: 2,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Alpha',
                        data: dataAlpha,
                        backgroundColor: '#ef4444', // Red 500
                        borderRadius: 2,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            color: '#475569',
                            font: { size: 12, weight: '600' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#64748b',
                            stepSize: 1,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // --- 2. DOUGHNUT CHART HARI INI ---
        const ctxPie = document.getElementById('pieChartHariIni').getContext('2d');
        const pieData = {!! json_encode($chartPieData) !!};

        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Cuti', 'Tugas Luar', 'Alpha'],
                datasets: [{
                    data: pieData,
                    backgroundColor: [
                        '#22c55e', // Hadir - Green
                        '#eab308', // Terlambat - Yellow
                        '#3b82f6', // Izin - Blue
                        '#a855f7', // Sakit - Purple
                        '#06b6d4', // Cuti - Cyan
                        '#f97316', // Tugas Luar - Orange
                        '#ef4444'  // Alpha - Red
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 8, font: { size: 10 } }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection