<table style="margin-bottom: 10px;">
    <tr>
        @if($setting->show_logo && $sekolah->logo_sekolah)
        <td style="width: 80px; vertical-align: middle;">
            <img src="{{ public_path('storage/'.$sekolah->logo_sekolah) }}" style="height: 70px;">
        </td>
        @endif
        <td style="vertical-align: middle; text-align: center;">
            @if($setting->show_school_name)
                <div style="font-size: {{ $setting->font_size + 3 }}pt; font-weight: bold; text-transform: uppercase;">
                    {{ $sekolah->nama_sekolah }}
                </div>
            @endif

            @if($setting->show_address)
                <div>{{ $sekolah->alamat }}</div>
            @endif

            @if($setting->show_phone || $setting->show_email)
                <div>
                    @if($setting->show_phone)
                        Telp: {{ $sekolah->no_telp }}
                    @endif
                    @if($setting->show_phone && $setting->show_email)
                        &nbsp;|&nbsp;
                    @endif
                    @if($setting->show_email)
                        Email: {{ $sekolah->email }}
                    @endif
                </div>
            @endif
        </td>
    </tr>
</table>

@if($setting->show_header_line)
    @if($setting->header_line_style === 'double')
        <hr style="border: none; border-top: 3px double #000; margin: 2px 0 10px 0;">
    @else
        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0 10px 0;">
    @endif
@endif