<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportTemplateController extends Controller
{
    public function index()
    {
        $templates = ReportTemplate::latest()->paginate(10);

        return view('admin.report-template.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.report-template.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        ReportTemplate::create($validated);

        return redirect()
            ->route('admin.report-template.index')
            ->with('success', 'Template laporan berhasil ditambahkan.');
    }

    public function edit(ReportTemplate $reportTemplate)
    {
        return view('admin.report-template.edit', [
            'template' => $reportTemplate,
        ]);
    }

    public function update(Request $request, ReportTemplate $reportTemplate)
    {
        $validated = $this->validateData($request, $reportTemplate->id);

        $reportTemplate->update($validated);

        return redirect()
            ->route('admin.report-template.index')
            ->with('success', 'Template laporan berhasil diperbarui.');
    }

    public function destroy(ReportTemplate $reportTemplate)
    {
        $reportTemplate->delete();

        return back()->with('success', 'Template laporan berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:report_templates,kode'.($ignoreId ? ",{$ignoreId}" : '');

        $validated = $request->validate([
            'kode'          => "required|string|max:50|{$uniqueRule}",
            'nama_laporan'  => 'required|string|max:150',
            'judul_laporan' => 'required|string|max:255',
            'slug'=>[
    'required',
    'string',
    'max:100',
    Rule::unique('report_templates')
        ->ignore($ignoreId)
],
            'subjudul'      => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}