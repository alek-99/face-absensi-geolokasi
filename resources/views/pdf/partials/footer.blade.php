<table style="margin-top: 30px;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            @if($setting->show_qrcode)
                {{-- Slot QR code. Integrasikan paket seperti simplesoftwareio/simple-qrcode
                     lalu render <img src="data:image/png;base64,...."> di sini bila diperlukan. --}}
            @endif
        </td>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            <div>{{ $setting->city }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>{{ $setting->signer_position }}</div>

            <div class="signature-block">
                @if($setting->show_school_stamp && $setting->stamp_file)
                    <div style="position: relative; height: 0;">
                        <img src="{{ public_path('storage/'.$setting->stamp_file) }}"
                             style="position: absolute; top: 15px; left: 60px; opacity: 0.85; max-height: 80px;">
                    </div>
                @endif

                @if($setting->show_signature && $setting->signature_file)
                    <img src="{{ public_path('storage/'.$setting->signature_file) }}">
                @else
                    <div style="height: 70px;"></div>
                @endif
            </div>

            <div style="font-weight: bold; text-decoration: underline;">{{ $setting->signer_name }}</div>
            @if($setting->signer_nip)
                <div>NIP. {{ $setting->signer_nip }}</div>
            @endif
        </td>
    </tr>
</table>

@if($setting->show_print_date)
<div class="footer-note text-center" style="margin-top: 15px;">
    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
</div>
@endif

@if($setting->show_page_number)
<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->getFont("{{ $setting->font_family }}", "normal");
    $pdf->page_text(520, 810, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, [0, 0, 0]);
}
</script>
@endif