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
            'status_hubungan' => 'required|in:kepala_keluarga,istri,anak,lainnya',
            'status_perkawinan' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'tanggal_perkawinan' => 'nullable|date',
            'no_paspor' => 'nullable|string',
            'no_kitap' => 'nullable|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
        ]);

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
            'status_perkawinan' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'tanggal_perkawinan' => 'nullable|date',
            'no_paspor' => 'nullable|string',
            'no_kitap' => 'nullable|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
        ]);

        $penduduk->update($request->all());

        return redirect()->route('admin.kependudukan.keluarga.show', $penduduk->keluarga_id)
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penduduk = AnggotaKeluarga::findOrFail($id);
        $penduduk->delete();

        return redirect()->back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
