<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Guru</title>
    <style>
        @page {
            margin: {{ $setting->margin_top ?? 15 }}mm {{ $setting->margin_right ?? 15 }}mm {{ $setting->margin_bottom ?? 15 }}mm {{ $setting->margin_left ?? 15 }}mm;
        }
        body {
            font-family: "{{ $setting->font_family ?? 'Times New Roman' }}", Times, serif;
            font-size: {{ $setting->font_size ?? 11 }}pt;
            line-height: 1.3;
            color: #000;
        }
        
        /* Kop Surat */
        .kop-surat {
            width: 100%;
            margin-bottom: 10px;
        }
        .table-kop {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-sekolah {
            width: 80px;
            text-align: center;
        }
        .info-sekolah {
            text-align: center;
            vertical-align: middle;
        }
        .nama-sekolah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .detail-sekolah {
            font-size: 10pt;
            margin: 2px 0;
        }
        
        /* Garis Kop Surat */
        .garis-kop-single { border-bottom: 2px solid #000; margin-bottom: 15px; }
        .garis-kop-double { border-bottom: 4px double #000; margin-bottom: 15px; }

        .judul-laporan {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        /* Kotak Statistik Kehadiran */
        .table-statistik {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-statistik td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 9pt;
            text-align: center;
            background-color: #fafafa;
        }
        .table-statistik th {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 9pt;
            background-color: #eaeaea;
            font-weight: bold;
        }

        /* Tabel Utama */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: {{ ($setting->font_size ?? 11) - 1 }}pt;
        }
        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center !important; }
        .text-capitalize { text-transform: capitalize; }

        /* Tanda Tangan */
        .footer-section { width: 100%; margin-top: 20px; }
        .table-tanda-tangan { width: 100%; border-collapse: collapse; }
        .tanda-tangan-box { width: 40%; text-align: center; position: relative; }
        .space-ttd { height: 65px; position: relative; }
        .img-ttd { position: absolute; max-height: 55px; left: 35%; top: 5px; z-index: 1; }
        .img-stempel { position: absolute; max-height: 65px; left: 20%; top: -5px; opacity: 0.8; z-index: 2; }
        .print-date { font-size: 8pt; font-style: italic; margin-top: 15px; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <table class="table-kop">
            <tr>
                @if($setting->show_logo && $sekolah->logo_sekolah)
                    <td class="logo-sekolah">
                        <img src="{{ public_path('storage/' . $sekolah->logo_sekolah) }}" alt="Logo" style="max-width: 75px;">
                    </td>
                @endif
                <td class="info-sekolah">
                    @if($setting->show_school_name)
                        <div class="nama-sekolah">{{ $sekolah->nama_sekolah ?? 'NAMA SEKOLAH' }}</div>
                    @endif
                    <div class="detail-sekolah">
                        @if($setting->show_address) {{ $sekolah->alamat ?? '' }} <br> @endif
                        @if($setting->show_phone) Telp: {{ $sekolah->no_telp ?? '-' }} @endif
                        @if($setting->show_email) | Email: {{ $sekolah->email ?? '-' }} @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($setting->show_header_line)
        <div class="garis-kop-{{ $setting->header_line_style == 'single' ? 'single' : 'double' }}"></div>
    @endif

    <div class="judul-laporan">Laporan Rekapitulasi Absensi Guru</div>

    <!-- BLOK STATISTIK RINGKASAN -->
    <div style="font-weight: bold; font-size: 10pt; margin-bottom: 5px;">Ringkasan Total Kehadiran:</div>
    <table class="table-statistik">
        <thead>
            <tr>
                <th>Hadir (+Terlambat)</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Cuti</th>
                <th>Tugas Luar</th>
                <th>Alpha</th>
                <th style="background-color: #d1d1d1;">Total Records</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: green; font-weight: bold;">{{ $statistik['hadir'] }}</td>
                <td>{{ $statistik['izin'] }}</td>
                <td>{{ $statistik['sakit'] }}</td>
                <td>{{ $statistik['cuti'] }}</td>
                <td>{{ $statistik['tugas_luar'] }}</td>
                <td style="color: red; font-weight: bold;">{{ $statistik['alpha'] }}</td>
                <td style="font-weight: bold; background-color: #e9e9e9;">{{ $statistik['total'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL UTAMA -->
    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th>Nama Guru</th>
                <th width="15%">Status</th>
                <th>Keterangan / Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d-m-Y') }}</td>
                    <td>{{ $item->guru->user->name ?? ($item->guru->nama ?? '-') }}</td>
                    <td class="text-center text-capitalize" style="font-weight: 500;">
                        {{ $item->status }}
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data absensi yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN & STEMPEL -->
    @if($setting->show_signature)
        <div class="footer-section">
            <table class="table-tanda-tangan">
                <tr>
                    <td width="60%"></td>
                    <td class="tanda-tangan-box">
                        <div>{{ $setting->city ?? 'Serang' }}, {{ now()->translatedFormat('d F Y') }}</div>
                        <div style="margin-bottom: 5px;">{{ $setting->signer_position ?? 'Kepala Sekolah' }}</div>
                        
                        <div class="space-ttd">
                            @if($setting->signature_file)
                                <img class="img-ttd" src="{{ public_path('storage/' . $setting->signature_file) }}" alt="Tanda Tangan">
                            @endif

                            @if($setting->show_school_stamp && $setting->stamp_file)
                                <img class="img-stempel" src="{{ public_path('storage/' . $setting->stamp_file) }}" alt="Stempel">
                            @endif
                        </div>

                        <div style="text-decoration: underline; font-weight: bold;">{{ $setting->signer_name }}</div>
                        @if($setting->signer_nip)
                            <div>NIP. {{ $setting->signer_nip }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endif

    @if($setting->show_print_date)
        <div class="print-date">
            Dicetak pada: {{ now()->translatedFormat('d-m-Y H:i:s') }}
        </div>
    @endif

</body>
</html>