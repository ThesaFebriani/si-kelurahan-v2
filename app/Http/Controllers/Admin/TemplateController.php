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

        // PREPARE DEFAULT EDITABLE CONTENT
        $logo_b64 = '';
        if (file_exists(public_path('images/logo-kota-bengkulu.png'))) {
            $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kota-bengkulu.png')));
        }
        
        $default_content = '
        <div style="font-family: \'Times New Roman\', serif; color: #000; padding: 20px;">
            <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px;">
                <tr>
                    <td style="width: 15%; text-align: center; vertical-align: middle;">
                        <img src="' . $logo_b64 . '" alt="Logo" style="height: 90px;">
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <h3 style="margin: 0; font-size: 14pt; font-weight: normal;">PEMERINTAH KOTA BENGKULU</h3>
                        <h2 style="margin: 0; font-size: 16pt; font-weight: bold;">KECAMATAN RATU SAMBAN</h2>
                        <h1 style="margin: 0; font-size: 18pt; font-weight: bold;">KELURAHAN PADANG JATI</h1>
                        <p style="margin: 0; font-size: 10pt; font-style: italic;">Jl. Jati No. ... Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu</p>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; margin-bottom: 30px;">
                <h3 style="text-decoration: underline; margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">
                    (JUDUL SURAT)
                </h3>
                <p style="margin: 2px 0 0 0; font-size: 12pt;">NOMOR: ... / ... / ... / ' . date('Y') . '</p>
            </div>

            <div style="font-size: 12pt; line-height: 1.5;">
                <p>Isi surat dimulai di sini...</p>
            </div>

            <div style="margin-top: 50px; float: right; width: 45%; text-align: center; font-size: 12pt;">
                <p>Bengkulu, [TANGGAL_SURAT]</p>
                <p style="margin-bottom: 10px;">LURAH PEMATANG GUBERNUR</p>
                
                <div style="margin: 10px auto; height: 80px;">
                    [QR_CODE]
                </div>

                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0;">[NAMA_LURAH]</p>
                <p style="margin-top: 2px;">NIP. [NIP_LURAH]</p>
            </div>
            <div style="clear: both;"></div>
        </div>';

        return view('pages.admin.templates.create', compact('jenisSurat', 'user', 'rt', 'nomor_surat', 'lurah', 'logo_url', 'default_content'));
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

        // MIGRATION LOGIC:
        // Jika template lama belum punya Kop Surat di dalam kontennya (karena dulu hardcode),
        // kita tambahkan Kop Surat otomatis saat Edit agar Admin bisa melihat dan mengeditnya mulai sekarang.
        if (!str_contains($template->template_content, 'PEMERINTAH KOTA') && !str_contains($template->template_content, 'Area Kop Surat')) {
            $logo_b64 = '';
            if (file_exists(public_path('images/logo-kota-bengkulu.png'))) {
                $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kota-bengkulu.png')));
            }

            $kop_html = '
            <div style="font-family: \'Times New Roman\', serif; color: #000; padding: 20px;">
                <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px;">
                    <tr>
                        <td style="width: 15%; text-align: center; vertical-align: middle;">
                            <img src="' . $logo_b64 . '" alt="Logo" style="height: 90px;">
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <h3 style="margin: 0; font-size: 14pt; font-weight: normal;">PEMERINTAH KOTA BENGKULU</h3>
                            <h2 style="margin: 0; font-size: 16pt; font-weight: bold;">KECAMATAN RATU SAMBAN</h2>
                            <h1 style="margin: 0; font-size: 18pt; font-weight: bold;">KELURAHAN PADANG JATI</h1>
                            <p style="margin: 0; font-size: 10pt; font-style: italic;">Jl. Jati No. ... Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu</p>
                        </td>
                    </tr>
                </table>
                <div style="font-size: 12pt; line-height: 1.5;">';
            
            // Tutup div di akhir konten jika perlu, tapi karena HTML editor permissive, prepend saja cukup
            // Namun sebaiknya kita wrap konten lama
            $template->template_content = $kop_html . $template->template_content . '</div></div>';
        }

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
