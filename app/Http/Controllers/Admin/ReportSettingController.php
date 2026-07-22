<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportSettingController extends Controller
{
    /**
     * Halaman ringkasan / preview pengaturan aktif.
     */
    public function index()
    {
        $setting = ReportSetting::current();

        return view('admin.report-setting.index', compact('setting'));
    }

    public function edit()
    {
        $setting = ReportSetting::current();

        return view('admin.report-setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = ReportSetting::current();

        $validated = $request->validate([
            // Header
            'header_line_style' => 'required|in:single,double',

            // Body
            'font_family'   => 'required|string|max:100',
            'font_size'     => 'required|integer|min:8|max:20',
            'paper_size'    => 'required|in:A4,F4,LETTER',
            'orientation'   => 'required|in:portrait,landscape',
            'margin_top'    => 'required|integer|min:0|max:50',
            'margin_bottom' => 'required|integer|min:0|max:50',
            'margin_left'   => 'required|integer|min:0|max:50',
            'margin_right'  => 'required|integer|min:0|max:50',

            // Footer
            'city'            => 'required|string|max:100',
            'signer_name'     => 'required|string|max:150',
            'signer_position' => 'required|string|max:150',
            'signer_nip'      => 'nullable|string|max:50',
            'signature_file'  => 'nullable|image|max:2048',
            'stamp_file'      => 'nullable|image|max:2048',
        ]);

        // Checkbox yang tidak dicentang tidak dikirim browser, jadi kita
        // baca eksplisit dengan $request->boolean() supaya bisa jadi false.
        foreach ([
            'show_logo', 'show_school_name', 'show_address', 'show_email', 'show_phone',
            'show_header_line', 'show_signature', 'show_school_stamp',
            'show_print_date', 'show_page_number', 'show_qrcode',
        ] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        if ($request->hasFile('signature_file')) {
            if ($setting->signature_file) {
                Storage::disk('public')->delete($setting->signature_file);
            }
            $validated['signature_file'] = $request->file('signature_file')->store('report-setting', 'public');
        }

        if ($request->hasFile('stamp_file')) {
            if ($setting->stamp_file) {
                Storage::disk('public')->delete($setting->stamp_file);
            }
            $validated['stamp_file'] = $request->file('stamp_file')->store('report-setting', 'public');
        }

        $setting->update($validated);

        return redirect()
            ->route('admin.report-setting.index')
            ->with('success', 'Pengaturan format laporan berhasil disimpan.');
    }
}