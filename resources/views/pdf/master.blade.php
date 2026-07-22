<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $template->judul_laporan ?? 'Laporan' }}</title>
    <style>
        @page {
            margin: {{ $setting->margin_top }}mm {{ $setting->margin_right }}mm {{ $setting->margin_bottom }}mm {{ $setting->margin_left }}mm;
        }

        body {
            font-family: '{{ $setting->font_family }}', serif;
            font-size: {{ $setting->font_size }}pt;
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 4px 6px;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .mb-0 { margin-bottom: 0; }
        .mt-0 { margin-top: 0; }

        .report-title h3 {
            margin: 0;
            text-transform: uppercase;
        }

        .signature-block {
            width: 260px;
            margin: 0 auto;
            text-align: center;
        }

        .signature-block img {
            max-height: 70px;
        }

        .footer-note {
            font-size: {{ max($setting->font_size - 2, 7) }}pt;
            color: #444;
        }
    </style>
</head>
<body>
    @include('pdf.partials.header')

    <div class="report-title text-center" style="margin-bottom: 15px;">
        <h3 class="mb-0">{{ $template->judul_laporan }}</h3>
        @if($template->subjudul)
            <div class="mt-0">{{ $template->subjudul }}</div>
        @endif
    </div>

    @yield('content')

    @include('pdf.partials.footer')
</body>
</html>