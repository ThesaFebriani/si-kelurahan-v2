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
        if (!$template) {
            $template = new SuratTemplate();
            $template->template_content = '<p>Template belum diatur. Silakan hubungi teknisi.</p>';
        }

        // Data for Preview Look & Feel
        $logo_path = public_path('images/logo-kota-bengkulu.png');
        $logo_b64 = '';
        if (file_exists($logo_path)) {
            $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
        }

        // Replace Variable with Real Image for Editor
        // This ensures the user sees the logo, and when saved, it's saved as Base64 (perfect for PDF)
        if ($template) {
            $template->template_content = str_replace('{{ $logo_src }}', $logo_b64, $template->template_content);
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
