@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Header Banner StartAdmin Style --}}
    <div class="bg-white p-5 rounded-md border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-indigo-600 text-white rounded-md flex items-center justify-center shadow-sm">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-800">Dashboard Admin</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">SIAFI v1.0</span>
                </div>
                <p class="text-slate-500 text-sm mt-0.5">Ringkasan statistik dan aktivitas sistem absensi Face ID.</p>
            </div>
        </div>
        <div class="text-left md:text-right border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
            <p class="text-xs text-slate-400 font-medium">Hari Ini</p>
            <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Main Stat Cards Grid (6 Cards Compact & Clean) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        {{-- Total Siswa --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-indigo-600 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalSiswa) }}</span>
                <i class="fas fa-user-graduate text-slate-300 text-lg"></i>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-blue-600 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Guru</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalGuru) }}</span>
                <i class="fas fa-chalkboard-teacher text-slate-300 text-lg"></i>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-emerald-600 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Kelas</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalKelas) }}</span>
                <i class="fas fa-school text-slate-300 text-lg"></i>
            </div>
        </div>

        {{-- Total Mapel --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-purple-600 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalMapel) }}</span>
                <i class="fas fa-book text-slate-300 text-lg"></i>
            </div>
        </div>

        {{-- Total Jadwal --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-amber-500 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Aktif</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalJadwal) }}</span>
                <i class="fas fa-calendar-alt text-slate-300 text-lg"></i>
            </div>
        </div>

        {{-- Total User --}}
        <div class="bg-white p-4 rounded-md border border-slate-200 border-l-4 border-l-rose-500 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna App</p>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($totalPengguna) }}</span>
                <i class="fas fa-users text-slate-300 text-lg"></i>
            </div>
        </div>

    </div>

    {{-- Content Layout Grid: Quick Menu & Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Akses Cepat (3 Cols) --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-md border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center">
                        <i class="fas fa-bolt text-amber-500 mr-2"></i> Navigasi Cepat
                    </h3>
                </div>
                <div class="p-3 divide-y divide-slate-100">
                    <a href="{{ route('admin.absensi.index') }}"
                        class="flex items-center justify-between py-2.5 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-check text-slate-400 group-hover:text-indigo-600 w-4"></i>
                            <span>Kelola Absensi</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600"></i>
                    </a>
                    <a href="{{ route('admin.absensi.pengajuan') }}"
                        class="flex items-center justify-between py-2.5 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope-open-text text-slate-400 group-hover:text-indigo-600 w-4"></i>
                            <span>Pengajuan Izin</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600"></i>
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                        class="flex items-center justify-between py-2.5 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chalkboard-teacher text-slate-400 group-hover:text-indigo-600 w-4"></i>
                            <span>Data Guru</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600"></i>
                    </a>
                    <a href="{{ route('admin.jadwal.index') }}"
                        class="flex items-center justify-between py-2.5 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded transition-colors group">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar-alt text-slate-400 group-hover:text-indigo-600 w-4"></i>
                            <span>Jadwal Mengajar</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600"></i>
                    </a>
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