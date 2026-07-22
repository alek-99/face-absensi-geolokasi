<?php

namespace App\Services;

use App\Models\ReportTemplate;

class ReportResolver
{
    public function resolve(string $slug): ?ReportTemplate
    {
        return ReportTemplate::where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}