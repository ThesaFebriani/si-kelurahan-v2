<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanSuratController extends Controller
{
    /**
     * Show the form to edit the Global Surat Pengantar RT.
     */
    public function index()
    {
        $template = SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->first();

        // If not exists (should be seeded, but just in case), create dummy
        // If not exists (should be seeded, but just in case), create dummy or load from file
        if (!$template) {
            $template = new SuratTemplate();
            // Load content from the actual blade file we just fixed
            $defaultContent = file_get_contents(resource_path('views/templates/surat-pengantar-rt.blade.php'));
            
            // EXTRACT BODY CONTENT ONLY to prevent messy editor
            if (preg_match('/<body>(.*?)<\/body>/s', $defaultContent, $matches)) {
                $template->template_content = $matches[1];
            } else {
                $template->template_content = $defaultContent;
            }
        }

        // Data for Preview Look & Feel
        $logo_path = public_path('images/logo-kota-bengkulu.png');
        $logo_b64 = '';
        if (file_exists($logo_path)) {
            $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
        }

        // Replace Variable with Real Image for Editor
        // This ensures the user sees the logo, and when saved, it's saved as Base64 (perfect for PDF)
            // Replace Variable with Real Image for Editor
        if ($template) {
            // 1. Ganti src gambar dengan Base64 agar tampil di editor
            // Cari pattern {{ public_path(...) }} atau src="..." yang mengarah ke logo
            // Ini PENTING karena editor WYSIWYG tidak bisa mengeksekusi fungsi blade public_path()
            // Jadi kita ganti dengan string Base64 gambar asli agar admin bisa lihat logonya.
            $pattern = '/src=["\']\{\{ public_path\([\'"]images\/logo_kota_bengkulu\.png[\'"]\)\s*\}\}["\']/';
            $replacement = 'src="'.$logo_b64.'"';
            $template->template_content = preg_replace($pattern, $replacement, $template->template_content);
        }

        $logo_src = $logo_b64; // For the side buttons if needed
        $logo_url = asset('images/logo-kota-bengkulu.png'); // Fallback
        
        // Dummy Data for Tags
        $rt_nomor = '001';
        $rw_nomor = '002';
        $nomor_surat = '.../RT-.../.../'.date('Y');
        $nama_warga = 'Contoh Warga';
        $nik = '1771xxxxxxxxxxxx';
        $ttl = 'Bengkulu, 01-01-1990';
        $jenis_kelamin = 'Laki-laki';
        $agama = 'Islam';
        $pekerjaan = 'Wiraswasta';
        $alamat = 'Jl. Contoh No. 1';
        $status_perkawinan = 'Kawin';
        $kepala_keluarga = 'Contoh Kepala Keluarga';
        $keperluan = '(Keperluan Warga)';
        $tanggal_surat = date('d F Y');
        
        return view('pages.admin.settings.surat-pengantar', get_defined_vars());
    }

    /**
     * Update the Global Surat Pengantar RT.
     */
    public function update(Request $request)
    {
        $request->validate([
            'template_content' => 'required|string',
        ]);

        $template = SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->first();

        if (!$template) {
            // Create if missing
            $template = SuratTemplate::create([
                'type' => 'pengantar_rt',
                'jenis_surat_id' => null,
                'rt_id' => null,
                'template_content' => $request->template_content,
                'is_active' => true
            ]);
        } else {
            $template->update([
                'template_content' => $request->template_content
            ]);
        }

        return redirect()->back()->with('success', 'Format Surat Pengantar RT berhasil diperbarui untuk seluruh sistem!');
    }
}
