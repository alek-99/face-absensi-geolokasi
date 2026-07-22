<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $template->judul_laporan }}</title>
    <style>
        /* Menggunakan font_family dan font_size dari setting admin */
        body {
            font-family: {!! $setting->font_family !!}, sans-serif;
            font-size: {{ $setting->font_size }}pt;
            line-height: 1.4;
            color: #333;
            /* Mengatur margin halaman secara dinamis */
            margin-top: {{ $setting->margin_top }}mm;
            margin-bottom: {{ $setting->margin_bottom }}mm;
            margin-left: {{ $setting->margin_left }}mm;
            margin-right: {{ $setting->margin_right }}mm;
        }

        /* --- Kop Surat / Header --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 75px;
            max-height: 75px;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .school-name {
            font-size: {{ $setting->font_size + 4 }}pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .school-info {
            font-size: {{ $setting->font_size - 2 }}pt;
            margin-top: 2px;
        }
        .header-line {
            /* Gaya garis dinamis: solid, double, dll */
            border-top: {{ $setting->header_line_style === 'double' ? '4px double #000' : '2px solid #000' }};
            margin-bottom: 20px;
        }

        /* --- Judul Laporan --- */
        .report-title-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-title {
            font-size: {{ $setting->font_size + 2 }}pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .report-subtitle {
            font-size: {{ $setting->font_size }}pt;
            font-style: italic;
            margin: 5px 0 0 0;
        }

        /* --- Meta Data (Informasi Guru) --- */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* --- Data Tabel Jadwal --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }

        /* --- Tanda Tangan / Footer --- */
        .footer-container {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-space {
            height: 70px;
            position: relative;
            margin: 10px 0;
        }
        .img-signature {
            max-height: 65px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
        .img-stamp {
            max-height: 75px;
            position: absolute;
            left: 20%;
            top: -5px;
            opacity: 0.85;
            z-index: 2;
        }
        .clear { clear: both; }

        /* --- Halaman / Cetak Tanggal --- */
        .print-info {
            font-size: {{ $setting->font_size - 3 }}pt;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            @if($setting->show_logo && $sekolah->logo_sekolah)
                <td class="header-logo">
                    <img src="{{ public_path('storage/' . $sekolah->logo_sekolah) }}" alt="Logo">
                </td>
            @endif
            <td class="header-text">
                @if($setting->show_school_name)
                    <div class="school-name">{{ $sekolah->nama_sekolah }}</div>
                @endif
                <div class="school-info">
                    @if($setting->show_address)
                        {{ $sekolah->alamat }} <br>
                    @endif
                    @if($setting->show_phone)
                        Telp: {{ $sekolah->no_telp }} 
                    @endif
                    @if($setting->show_email)
                        | Email: {{ $sekolah->email }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if($setting->show_header_line)
        <div class="header-line"></div>
    @endif

    <div class="report-title-container">
        <h2 class="report-title">{{ $template->judul_laporan }}</h2>
        @if($template->subjudul)
            <p class="report-subtitle">{{ $template->subjudul }}</p>
        @endif
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 120px;">Nama Guru</td>
            <td style="width: 10px;">:</td>
            <td><strong>{{ $guru->nama ?? 'NAMA GURU' }}</strong></td>
        </tr>
        <tr>
            <td>NIP / ID Guru</td>
            <td>:</td>
            <td>{{ $guru->nip ?? '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4px;">No</th>
                <th style="width: 90px;">Hari</th>
                <th style="width: 110px;">Jam</th>
                <th>Mata Pelajaran</th>
                <th style="width: 80px;">Kelas</th>
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
                    <td>{{ $jadwal->mata_pelajaran }}</td>
                    <td class="text-center">{{ $jadwal->kelas }}</td>
                    <td class="text-center">{{ $jadwal->ruangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada jadwal mengajar yang aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($setting->show_signature)
        <div class="footer-container">
            <div class="signature-table">
                <div>
                    {{ $setting->city ?? 'Jakarta' }}, 
                    {{ now()->translatedFormat('d F Y') }}
                </div>
                <div style="margin-bottom: 5px;">{{ $setting->signer_position ?? 'Kepala Sekolah' }}</div>
                
                <div class="signature-space">
                    @if($setting->show_school_stamp && $setting->stamp_file)
                        <img src="{{ public_path('storage/' . $setting->stamp_file) }}" class="img-stamp" alt="Stempel">
                    @endif

                    @if($setting->signature_file)
                        <img src="{{ public_path('storage/' . $setting->signature_file) }}" class="img-signature" alt="Tanda Tangan">
                    @endif
                </div>

                <div style="text-decoration: underline; font-weight: bold;">
                    {{ $setting->signer_name }}
                </div>
                <div>NIP. {{ $setting->signer_nip ?? '-' }}</div>
            </div>
            <div class="clear"></div>
        </div>
    @endif

    <div class="print-info">
        @if($setting->show_print_date)
            <span style="float: left;">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</span>
        @endif
        @if($setting->show_qrcode)
            <span style="float: right; font-style: italic;">Dokumen Sah Digital</span>
        @endif
        <div class="clear"></div>
    </div>

</body>
</html>