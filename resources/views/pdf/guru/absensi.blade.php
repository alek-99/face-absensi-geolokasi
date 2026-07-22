<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Kerja Guru</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
        }
        .kop-surat { width: 100%; border-collapse: collapse; }
        .logo-container { width: 80px; }
        .logo { max-width: 70px; max-height: 70px; }
        .info-sekolah { text-align: center; padding-right: 70px; }
        .nama-sekolah { font-size: 16px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .garis-tebal { border-top: 2px solid #000; margin-top: 8px; margin-bottom: 2px; }
        .garis-tipis { border-top: 1px solid #000; margin-bottom: 15px; }
        
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h2 { font-size: 13px; margin: 0; text-transform: uppercase; }
        
        .biodata { margin-bottom: 15px; width: 100%; }
        .biodata td { padding: 2px 0; font-size: 11px; }

        .section-title { font-weight: bold; text-transform: uppercase; margin-bottom: 5px; font-size: 10px; color: #1e293b; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data-table th { background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 5px; font-size: 9px; text-transform: uppercase; }
        table.data-table td { border: 1px solid #cbd5e1; padding: 6px; text-align: center; }
        
        .ttd-container { width: 100%; margin-top: 25px; border-collapse: collapse; page-break-inside: avoid; }
        .ttd-wrapper { width: 40%; text-align: center; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="kop-surat">
        <tr>
            @if($sekolah && $sekolah->logo)
                <td class="logo-container">
                    <img src="{{ public_path('storage/' . $sekolah->logo) }}" class="logo">
                </td>
            @endif
            <td class="info-sekolah">
                <h1 class="nama-sekolah">{{ $sekolah->nama_sekolah ?? 'NAMA SEKOLAH' }}</h1>
                <p style="margin: 2px 0;">{{ $sekolah->alamat ?? '-' }}</p>
                <p style="margin: 0;">Telp: {{ $sekolah->telepon ?? '-' }} | Email: {{ $sekolah->email ?? '-' }}</p>
            </td>
        </tr>
    </table>
    <div class="garis-tebal"></div>
    <div class="garis-tipis"></div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <h2>Laporan Rekapitulasi Presensi Mandiri</h2>
        <p style="margin: 3px 0 0 0;">Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>
    </div>

    <!-- BIODATA GURU -->
    <table class="biodata">
        <tr>
            <td style="width: 12%;">Nama Guru</td>
            <td style="width: 2%;">:</td>
            <td style="font-weight: bold;">{{ $guru->nama }}</td>
            <td style="width: 12%;">NIP / NUPTK</td>
            <td style="width: 2%;">:</td>
            <td>{{ $guru->nip ?? '-' }}</td>
        </tr>
    </table>

    <!-- RINGKASAN STATISTIK KARYAWAN -->
    <div class="section-title">Ringkasan Kehadiran</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Cuti</th>
                <th>Tugas Luar</th>
                <th>Alpha</th>
                <th style="background-color: #e2e8f0;">Total Hari Kerja</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $statistik['hadir'] }}</td>
                <td>{{ $statistik['terlambat'] }}</td>
                <td>{{ $statistik['izin'] }}</td>
                <td>{{ $statistik['sakit'] }}</td>
                <td>{{ $statistik['cuti'] }}</td>
                <td>{{ $statistik['tugas_luar'] }}</td>
                <td style="color: red;">{{ $statistik['alpha'] }}</td>
                <td style="font-weight: bold; background-color: #f1f5f9;">{{ $statistik['total'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- LOG JURNAL HARIAN -->
    <div class="section-title">Rincian Log Presensi Harian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%; text-align: left;">Tanggal</th>
                <th style="width: 12%;">Jam Masuk</th>
                <th style="width: 12%;">Jam Pulang</th>
                <th style="width: 15%;">Status</th>
                <th style="text-align: left;">Keterangan Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarAbsensi as $idx => $absen)
                @php
                    $st = strtolower($absen->status);
                    $isIzin = in_array($st, ['izin', 'sakit', 'cuti', 'tugas_luar']);
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</td>
                    <td style="font-family: monospace;">{{ $isIzin ? '-' : ($absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-') }}</td>
                    <td style="font-family: monospace;">{{ $isIzin ? '-' : ($absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : '-') }}</td>
                    <td style="font-weight: bold;">{{ ucwords(str_replace('_', ' ', $absen->status)) }}</td>
                    <td style="text-align: left; color: #475569;">{{ $absen->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="color: #94a3b8; padding: 15px;">Tidak ada riwayat presensi tercatat untuk bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-container">
        <tr>
            <td></td>
            <td class="ttd-wrapper">
                <p>{{ $setting->kota_laporan ?? 'Serang' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="font-weight: bold; text-transform: uppercase; margin-bottom: 60px;">
                    {{ $setting->jabatan_penandatangan ?? 'Kepala Sekolah' }}
                </p>
                <p style="text-decoration: underline; font-weight: bold; margin: 0;">{{ $setting->nama_penandatangan ?? 'Nama Kepala Sekolah' }}</p>
                <p style="margin: 2px 0 0 0;">NIP. {{ $setting->nip_penandatangan ?? '-' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>