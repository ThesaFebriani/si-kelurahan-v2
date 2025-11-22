<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $templates = SuratTemplate::with(['jenisSurat'])
            ->whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })
            ->latest()
            ->get();

        return view('pages.kasi.template.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = SuratTemplate::with(['jenisSurat'])->findOrFail($id);
        return view('pages.kasi.template.edit', compact('template'));
    }
}
