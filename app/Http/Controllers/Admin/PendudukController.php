<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\Keluarga;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'keluarga_id' => 'required|exists:keluargas,id',
            'nik' => 'required|string|size:16|unique:anggota_keluargas,nik',
            'nama_lengkap' => 'required|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'status_hubungan' => 'required',
            'status_perkawinan' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            // ... validasi lainnya ...
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
        ]);

        // LOGIKA PINTAR: Jika status hubungan yang baru adalah 'kepala_keluarga'
        if ($request->status_hubungan === 'kepala_keluarga') {
            // Turunkan jabatan Kepala Keluarga yang lama (jika ada)
            AnggotaKeluarga::where('keluarga_id', $request->keluarga_id)
                ->where('status_hubungan', 'kepala_keluarga')
                ->update(['status_hubungan' => 'lainnya']); // Demote to Lainnya
        }

        AnggotaKeluarga::create($request->all());

        return redirect()->back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penduduk = AnggotaKeluarga::with('keluarga')->findOrFail($id);
        return view('pages.admin.kependudukan.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, $id)
    {
        $penduduk = AnggotaKeluarga::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|size:16|unique:anggota_keluargas,nik,' . $id,
            'nama_lengkap' => 'required|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'status_hubungan' => 'required',
            // ... field lain ...
        ]);
        
        // LOGIKA PINTAR: Auto-Swap Kepala Keluarga
        if ($request->status_hubungan === 'kepala_keluarga' && $penduduk->status_hubungan !== 'kepala_keluarga') {
            // Cari Kepala Keluarga Lama di KK yang sama
            AnggotaKeluarga::where('keluarga_id', $penduduk->keluarga_id)
                ->where('status_hubungan', 'kepala_keluarga')
                ->where('id', '!=', $id) // Jangan update diri sendiri
                ->update(['status_hubungan' => 'lainnya']); // Turunkan jadi Lainnya
                
            // Update juga status Kepala Keluarga di tabel `keluargas` (opsional jika ada kolom nama di sana)
             \App\Models\Keluarga::where('id', $penduduk->keluarga_id)
                ->update(['kepala_keluarga' => $request->nama_lengkap]);
        }

        $penduduk->update($request->all());

        return redirect()->route('admin.kependudukan.keluarga.show', $penduduk->keluarga_id)
            ->with('success', 'Data penduduk berhasil diperbarui. Struktur Kepala Keluarga telah disesuaikan.');
    }

    public function destroy($id)
    {
        $penduduk = AnggotaKeluarga::findOrFail($id);
        
        // PENCEGAHAN: Jangan hapus jika statusnya Kepala Keluarga
        if ($penduduk->status_hubungan === 'kepala_keluarga') {
            // Cek apakah ada anggota lain di keluarga ini
            $anggotaLain = AnggotaKeluarga::where('keluarga_id', $penduduk->keluarga_id)
                                          ->where('id', '!=', $penduduk->id)
                                          ->count();
            
            if ($anggotaLain > 0) {
                return redirect()->back()->with('error', 'Gagal dihapus! Data ini adalah KEPALA KELUARGA. Harap ubah/angkat anggota keluarga lain menjadi Kepala Keluarga terlebih dahulu sebelum menghapus data ini.');
            }
        }

        $penduduk->delete();

        return redirect()->back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
