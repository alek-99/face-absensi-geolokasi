<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul_laporan ?? 'JADWAL MENGAJAR GURU' }}</title>
    <link rel="icon" href="{{ asset('storage/images/logo.png') }}">
    <style>
        @page {
            margin: {{ $setting->margin_top }}mm {{ $setting->margin_right }}mm {{ $setting->margin_bottom }}mm {{ $setting->margin_left }}mm;
        }

        body {
            font-family: {!! $setting->font_family ?? 'sans-serif' !!};
            font-size: {{ $setting->font_size ?? 11 }}pt;
            line-height: 1.4;
            color: #222;
            margin: 0;
            padding: 0;
        }

        /* --- Header / Kop Surat --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 75px;
            max-height: 75px;
            object-fit: contain;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .school-name {
            font-size: {{ ($setting->font_size ?? 11) + 4 }}pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .school-info {
            font-size: {{ ($setting->font_size ?? 11) - 2 }}pt;
            margin-top: 3px;
            color: #444;
        }
        .header-line {
            border-top: {{ ($setting->header_line_style ?? 'single') === 'double' ? '4px double #000' : '2px solid #000' }};
            margin-bottom: 20px;
        }

        /* --- Judul Laporan --- */
        .report-title-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            font-size: {{ ($setting->font_size ?? 11) + 2 }}pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .report-subtitle {
            font-size: {{ $setting->font_size ?? 11 }}pt;
            font-style: italic;
            margin-top: 4px;
            color: #555;
        }

        /* --- Meta Information (Profil Guru) --- */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        /* --- Data Tabel Jadwal --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, 
        .data-table td {
            border: 1px solid #333;
            padding: 7px 9px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: {{ ($setting->font_size ?? 11) - 1 }}pt;
        }
        .text-center { text-align: center; }

        /* --- Tanda Tangan & Footer --- */
        .footer-container {
            margin-top: 35px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            float: right;
            width: 230px;
            text-align: center;
        }
        .signature-space {
            height: 75px;
            position: relative;
            margin: 8px 0;
        }
        .img-signature {
            max-height: 70px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
        .img-stamp {
            max-height: 80px;
            position: absolute;
            left: 15%;
            top: -5px;
            opacity: 0.85;
            z-index: 2;
        }
        .clear { clear: both; }

        /* --- Info Cetak --- */
        .print-info {
            font-size: {{ ($setting->font_size ?? 11) - 3 }}pt;
            color: #777;
            margin-top: 25px;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH -->
    <table class="header-table">
        <tr>
            @if(($setting->show_logo ?? true) && !empty($sekolah->logo_sekolah))
                <td class="header-logo">
                    <img src="{{ public_path('storage/' . $sekolah->logo_sekolah) }}" alt="Logo Sekolah">
                </td>
            @endif
            <td class="header-text">
                @if($setting->show_school_name ?? true)
                    <div class="school-name">{{ $sekolah->nama_sekolah ?? 'NAMA SEKOLAH BELUM DISET' }}</div>
                @endif
                <div class="school-info">
                    @if(($setting->show_address ?? true) && !empty($sekolah->alamat))
                        {{ $sekolah->alamat }} <br>
                    @endif
                    @if(($setting->show_phone ?? true) && !empty($sekolah->no_telp))
                        Telp: {{ $sekolah->no_telp }} 
                    @endif
                    @if(($setting->show_email ?? true) && !empty($sekolah->email))
                        | Email: {{ $sekolah->email }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if($setting->show_header_line ?? true)
        <div class="header-line"></div>
    @endif

    <!-- JUDUL LAPORAN -->
    <div class="report-title-container">
        <h2 class="report-title">{{ $judul_laporan ?? 'JADWAL MENGAJAR GURU' }}</h2>
        @if(!empty($subjudul))
            <div class="report-subtitle">{{ $subjudul }}</div>
        @endif
    </div>

    <!-- METADATA GURU -->
    <table class="meta-table">
        <tr>
            <td style="width: 120px;">Nama Guru</td>
            <td style="width: 10px;">:</td>
            <td><strong>{{ $guru->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NIP / ID Guru</td>
            <td>:</td>
            <td>{{ $guru->nip ?? '-' }}</td>
        </tr>
    </table>

    <!-- TABEL DATA JADWAL -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 85px;">Hari</th>
                <th style="width: 110px;">Jam</th>
                <th>Mata Pelajaran</th>
                <th style="width: 90px;">Kelas</th>
                <th style="width: 90px;">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $index => $jadwal)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $jadwal->hari }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($jadwal->jam_masuk)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($jadwal->jam_pulang)->format('H:i') }}
                    </td>
                    <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                    <td class="text-center">{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                    <td class="text-center">{{ $jadwal->kelas->ruangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #777;">
                        <em>Tidak ada jadwal mengajar yang aktif.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BLOK TANDA TANGAN -->
    @if($setting->show_signature ?? true)
        <div class="footer-container">
            <div class="signature-table">
                <div>
                    {{ $setting->city ?? 'Kota' }}, 
                    {{ now()->translatedFormat('d F Y') }}
                </div>
                <div style="margin-bottom: 5px;">{{ $setting->signer_position ?? 'Kepala Sekolah' }}</div>
                
                <div class="signature-space">
                    @if(($setting->show_school_stamp ?? false) && !empty($setting->stamp_file))
                        <img src="{{ public_path('storage/' . $setting->stamp_file) }}" class="img-stamp" alt="Stempel">
                    @endif

                    @if(!empty($setting->signature_file))
                        <img src="{{ public_path('storage/' . $setting->signature_file) }}" class="img-signature" alt="Tanda Tangan">
                    @endif
                </div>

                <div style="text-decoration: underline; font-weight: bold;">
                    {{ $setting->signer_name ?? '........................' }}
                </div>
                <div>NIP. {{ $setting->signer_nip ?? '-' }}</div>
            </div>
            <div class="clear"></div>
        </div>
    @endif

    <!-- FOOTER INFO DICETAK -->
    <div class="print-info">
        @if($setting->show_print_date ?? true)
            <span style="float: left;">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</span>
        @endif
        @if($setting->show_qrcode ?? false)
            <span style="float: right; font-style: italic;">Dokumen Sah Digital</span>
        @endif
        <div class="clear"></div>
    </div>

</body>
</html>