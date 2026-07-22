@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="space-y-8">

        {{-- Welcome Header Card --}}
        <div class="bg-gray-900 p-6 shadow-xl rounded-2xl flex items-center border border-gray-800 transition-all duration-300">
            <div class="p-4 bg-blue-950/50 rounded-xl mr-4 text-blue-400 border border-blue-900/50">
                <i class="fas fa-th-large text-3xl"></i>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-blue-400 bg-blue-950/60 px-2.5 py-1 rounded-full border border-blue-900/30">Admin Panel</span>
                <h2 class="text-2xl font-bold text-white mt-1">Selamat Datang di SIAFI</h2>
                <p class="text-gray-400 text-sm mt-0.5">Sistem Absensi Face ID - Kelola data absensi dengan mudah dan akurat.</p>
            </div>
        </div>

        {{-- Statistik Data Section --}}
        <div class="space-y-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-chart-line text-gray-500 text-lg"></i>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Statistik Data</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Item 1: Guru --}}
                <div class="bg-gradient-to-br from-blue-700 to-indigo-900 text-white p-6 rounded-2xl shadow-lg border border-blue-800/30 relative overflow-hidden group">
                    <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-10 text-9xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-200 text-sm font-medium uppercase tracking-wider">Total Guru Aktif</p>
                            <h4 class="text-4xl font-extrabold mt-2">{{ $totalGuru }}</h4>
                        </div>
                        <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                            <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                {{-- Item 2: Jadwal --}}
                <div class="bg-gradient-to-br from-amber-600 to-orange-800 text-white p-6 rounded-2xl shadow-lg border border-amber-800/30 relative overflow-hidden group">
                    <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-10 text-9xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-amber-200 text-sm font-medium uppercase tracking-wider">Total Jadwal Aktif</p>
                            <h4 class="text-4xl font-extrabold mt-2">{{ $totalJadwal }}</h4>
                        </div>
                        <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                            <i class="fas fa-calendar-alt text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                {{-- Item 3: Pengguna --}}
                <div class="bg-gradient-to-br from-emerald-600 to-teal-850 text-white p-6 rounded-2xl shadow-lg border border-emerald-800/30 relative overflow-hidden group">
                    <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-10 text-9xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-emerald-200 text-sm font-medium uppercase tracking-wider">Total Pengguna</p>
                            <h4 class="text-4xl font-extrabold mt-2">{{ $totalPengguna }}</h4>
                        </div>
                        <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Cepat Dan Chart JS Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Menu Cepat --}}
            <div class="lg:col-span-1 bg-gray-900 p-6 rounded-2xl shadow-md border border-gray-800 flex flex-col">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-bolt text-amber-450 mr-2"></i> Akses Cepat
                </h3>
                <div class="space-y-3 flex-1">
                    <a href="{{ route('admin.absensi.index') }}"
                        class="flex items-center p-3 text-sm font-medium text-gray-300 bg-gray-950/50 hover:bg-gray-800 border border-gray-850 hover:border-blue-900/50 hover:text-blue-400 rounded-xl transition-all group">
                        <i class="fas fa-user-check mr-3 text-gray-500 group-hover:text-blue-400 w-5 text-center"></i>
                        <span>Kelola Absensi</span>
                    </a>
                    <a href="{{ route('admin.absensi.pengajuan') }}"
                        class="flex items-center p-3 text-sm font-medium text-gray-300 bg-gray-950/50 hover:bg-gray-800 border border-gray-850 hover:border-blue-900/50 hover:text-blue-400 rounded-xl transition-all group">
                        <i class="fas fa-envelope-open-text mr-3 text-gray-500 group-hover:text-blue-400 w-5 text-center"></i>
                        <span>Pengajuan Izin</span>
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                        class="flex items-center p-3 text-sm font-medium text-gray-300 bg-gray-950/50 hover:bg-gray-800 border border-gray-850 hover:border-blue-900/50 hover:text-blue-400 rounded-xl transition-all group">
                        <i class="fas fa-user-graduate mr-3 text-gray-500 group-hover:text-blue-400 w-5 text-center"></i>
                        <span>Data Master Guru</span>
                    </a>
                    <a href="{{ route('admin.jadwal.index') }}"
                        class="flex items-center p-3 text-sm font-medium text-gray-300 bg-gray-950/50 hover:bg-gray-800 border border-gray-850 hover:border-blue-900/50 hover:text-blue-400 rounded-xl transition-all group">
                        <i class="fas fa-calendar-alt mr-3 text-gray-500 group-hover:text-blue-400 w-5 text-center"></i>
                        <span>Jadwal Mengajar</span>
                    </a>
                </div>
            </div>

            {{-- Tempat Chart JS --}}
            <div class="lg:col-span-2 bg-gray-900 p-6 rounded-2xl shadow-md border border-gray-800 min-h-[380px] flex flex-col">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-400 mr-2"></i> Grafik Kehadiran 7 Hari Terakhir
                </h3>
                <div class="flex-1 w-full relative">
                    <!-- Canvas untuk Chart.js -->
                    <canvas id="absensiChart" class="w-full h-64"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Script Chart.js Terkonfigurasi Dark Mode --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('absensiChart').getContext('2d');

            const labels = {!! json_encode($chartLabels) !!};
            const dataMasuk = {!! json_encode($chartDataMasuk) !!};
            const dataAbsen = {!! json_encode($chartDataAbsen) !!};
            const dataAlpha = {!! json_encode($chartDataAlpha) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Hadir / Terlambat',
                            data: dataMasuk,
                            backgroundColor: 'rgba(59, 130, 246, 0.85)', // Blue 500 terakreditasi gelap
                            borderRadius: 6,
                        },
                        {
                            label: 'Izin / Sakit / Cuti / TL',
                            data: dataAbsen,
                            backgroundColor: 'rgba(245, 158, 11, 0.85)', // Amber 500
                            borderRadius: 6,
                        },
                        {
                            label: 'Alpha',
                            data: dataAlpha,
                            backgroundColor: 'rgba(239, 68, 68, 0.85)', // Red 500
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                color: '#9ca3af', // Gray 400 tekst legenda
                                font: {
                                    family: 'Plus Jakarta Sans, sans-serif',
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(75, 85, 99, 0.2)' // Garis grid X halus samar
                            },
                            ticks: {
                                color: '#9ca3af' // Warna teks hari
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(75, 85, 99, 0.2)' // Garis grid Y halus samar
                            },
                            ticks: {
                                color: '#9ca3af', // Warna teks nominal angka
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection