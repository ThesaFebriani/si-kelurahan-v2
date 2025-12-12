<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Display only Kelurahan Templates (Global)
        $templates = SuratTemplate::where('type', 'surat_kelurahan')
            ->whereNull('rt_id')
            ->with(['jenisSurat'])
            ->latest()
            ->get();

        return view('pages.admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisSurat = JenisSurat::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        $rt = $user->rt ?? (object)['nomor_rt' => '000']; 
        $nomor_surat = '001/KL/I/2025'; // Dummy nomor surat
        
        // Data untuk Preview Kop & TTD
        $lurah = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->first();
        $logo_url = asset('images/logo-kota-bengkulu.png');

        return view('pages.admin.templates.create', compact('jenisSurat', 'user', 'rt', 'nomor_surat', 'lurah', 'logo_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => [
                'required', 
                'exists:jenis_surats,id',
                // Ensure unique template for this type at kelurahan level
                function($attribute, $value, $fail) {
                    $exists = SuratTemplate::where('jenis_surat_id', $value)
                        ->where('type', 'surat_kelurahan')
                        ->whereNull('rt_id')
                        ->exists();
                    if ($exists) {
                        $fail('Template untuk jenis surat ini sudah ada. Silakan edit yang sudah ada.');
                    }
                }
            ],
            'template_content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        SuratTemplate::create([
            'jenis_surat_id' => $request->jenis_surat_id,
            'template_content' => $request->template_content,
            'type' => 'surat_kelurahan',
            'rt_id' => null,
            'fields_mapping' => [], // Nanti bisa dikembangkan untuk mapping field dinamis
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $template = SuratTemplate::findOrFail($id);
        $jenisSurat = JenisSurat::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        $rt = $user->rt ?? (object)['nomor_rt' => '000'];
        $nomor_surat = '001/KL/I/2025';

        // Data untuk Preview Kop & TTD
        $lurah = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->first();
        $logo_url = asset('images/logo-kota-bengkulu.png');

        return view('pages.admin.templates.edit', compact('template', 'jenisSurat', 'user', 'rt', 'nomor_surat', 'lurah', 'logo_url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $template = SuratTemplate::findOrFail($id);

        $request->validate([
            'template_content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $template->update([
            'template_content' => $request->template_content,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $template = SuratTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus');
    }
}
