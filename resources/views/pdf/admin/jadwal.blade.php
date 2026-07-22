<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran</title>
    <style>
        body {
            font-family: "{{ $setting->font_family }}", sans-serif;
            font-size: {{ $setting->font_size }}pt;
            line-height: 1.4;
            color: #333;
            margin-top: {{ $setting->margin_top }}mm;
            margin-bottom: {{ $setting->margin_bottom }}mm;
            margin-left: {{ $setting->margin_left }}mm;
            margin-right: {{ $setting->margin_right }}mm;
        }

        /* --- KOP SURAT --- */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .kop-logo {
            width: 80px;
            text-align: left;
            vertical-align: middle;
        }
        .kop-logo img {
            max-height: 75px;
            width: auto;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
        }
        .school-name {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .school-address {
            font-size: 9pt;
            font-style: italic;
            line-height: 1.3;
        }

        /* Garis Pembatas Kop dinamis */
        .header-line {
            margin-bottom: 20px;
            @if($setting->header_line_style === 'double')
                border-top: 3px double #000;
                border-bottom: 1px solid #000;
                height: 3px;
            @else
                border-top: 2px solid #000;
            @endif
        }

        /* --- ISI DOKUMEN --- */
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }
        .data-table td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }

        /* --- TANDA TANGAN (SIGNATURE AREA) --- */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .signer-col {
            width: 40%;
            text-align: left;
            vertical-align: top;
        }
        .spacer-col {
            width: 60%;
        }
        .signature-box {
            position: relative;
            height: 90px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .signature-img {
            position: absolute;
            z-index: 2;
            max-height: 80px;
            max-width: 150px;
        }
        .stamp-img {
            position: absolute;
            z-index: 1;
            left: -35px;
            top: -10px;
            max-height: 85px;
            max-width: 150px;
            opacity: 0.85;
        }
        .signer-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @if($setting->show_page_number)
        .page-number:before {
            content: "Halaman " counter(page);
        }
        .footer-page {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: right;
            font-size: 8pt;
            color: #777;
        }
        @endif
    </style>
</head>
<body>

    @if($sekolah && ($setting->show_logo || $setting->show_school_name || $setting->show_address || $setting->show_email || $setting->show_phone))
        <table class="kop-table">
            <tr>
                @if($setting->show_logo && $sekolah->logo_sekolah) 
                    <td class="kop-logo">
                        <img src="{{ public_path('storage/' . $sekolah->logo_sekolah) }}" alt="Logo Sekolah">
                    </td>
                @endif
                <td class="kop-text">
                    @if($setting->show_school_name)
                        <div class="school-name">{{ $sekolah->nama_sekolah ?? $sekolah->nama }}</div>
                    @endif
                    
                    <div class="school-address">
                        @if($setting->show_address && $sekolah->alamat)
                            {{ $sekolah->alamat }} <br>
                        @endif
                        @if($setting->show_email && $sekolah->email)
                            Email: {{ $sekolah->email }} 
                        @endif
                        @if($setting->show_phone && $sekolah->telepon)
                            {{ $setting->show_email && $sekolah->email ? ' | ' : '' }} Telp: {{ $sekolah->telepon }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if($setting->show_header_line)
        <div class="header-line"></div>
    @endif

    <div class="title">Jadwal Pelajaran Sekolah</div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Hari</th>
                <th width="15%">Waktu</th>
                <th width="25%">Guru</th>
                <th width="25%">Mata Pelajaran</th>
                <th width="10%">Kelas</th>
                <th width="8%">Ruang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $key => $jadwal)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center"><strong>{{ $jadwal->hari }}</strong></td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($jadwal->jam_masuk)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($jadwal->jam_pulang)->format('H:i') }}
                    </td>
                    <td>{{ $jadwal->guru->user->name ?? $jadwal->guru->nama ?? '-' }}</td>
                    <td>{{ $jadwal->mata_pelajaran ?? '-' }}</td>
                    <td class="text-center">{{ $jadwal->kelas ?? '-' }}</td>
                    <td class="text-center">{{ $jadwal->ruangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data jadwal pelajaran aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td class="spacer-col"></td>
            <td class="signer-col">
                <p>
                    {{ $setting->city }}, 
                    @if($setting->show_print_date)
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    @else
                        ........................
                    @endif
                    <br>
                    {{ $setting->signer_position }}
                </p>

                <div class="signature-box">
                    @if($setting->show_school_stamp && $setting->stamp_file)
                        <img class="stamp-img" src="{{ public_path('storage/' . $setting->stamp_file) }}" alt="Stempel">
                    @endif
                    
                    @if($setting->show_signature && $setting->signature_file)
                        <img class="signature-img" src="{{ public_path('storage/' . $setting->signature_file) }}" alt="Ttd">
                    @endif
                </div>

                <p>
                    <span class="signer-name">{{ $setting->signer_name }}</span>
                    @if($setting->signer_nip)
                        <br>NIP. {{ $setting->signer_nip }}
                    @endif
                </p>
            </td>
        </tr>
    </table>

    @if($setting->show_page_number)
        <div class="footer-page">
            <span class="page-number"></span>
        </div>
    @endif

</body>
</html>