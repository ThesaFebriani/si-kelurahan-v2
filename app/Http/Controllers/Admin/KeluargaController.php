<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Rt;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;

use App\Models\Rw;

class KeluargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Keluarga::with(['rt.rw', 'kepalaKeluarga'])->latest();

        // Filter by RW
        if ($request->rw_id) {
            $query->whereHas('rt', function($q) use ($request) {
                $q->where('rw_id', $request->rw_id);
            });
        }

        // Filter by RT
        if ($request->rt_id) {
            $query->where('rt_id', $request->rt_id);
        }

        $keluargas = $query->get();
        
        $rws = Rw::all();
        // If RW is selected, filter RTs list. Else show all.
        $rts = $request->rw_id 
            ? Rt::where('rw_id', $request->rw_id)->aktif()->get() 
            : Rt::aktif()->get();

        return view('pages.admin.kependudukan.keluarga.index', compact('keluargas', 'rws', 'rts'));
    }

    public function create()
    {
        $rts = Rt::aktif()->with('rw')->get();
        return view('pages.admin.kependudukan.keluarga.create', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:keluargas,no_kk',
            'kepala_keluarga' => 'required|string', // Nama sementara, nanti relasi
            'rt_id' => 'required|exists:rt,id',
            'alamat_lengkap' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kodepos' => 'required|string|max:5',
        ]);

        $keluarga = Keluarga::create($request->all());

        return redirect()->route('admin.kependudukan.keluarga.show', $keluarga->id)
            ->with('success', 'Kartu Keluarga berhasil dibuat. Silakan tambah anggota keluarga.');
    }

    public function show($id)
    {
        $keluarga = Keluarga::with(['rt.rw', 'anggotaKeluarga'])->findOrFail($id);
        return view('pages.admin.kependudukan.keluarga.show', compact('keluarga'));
    }

    public function edit($id)
    {
        $keluarga = Keluarga::findOrFail($id);
        $rts = Rt::aktif()->get();
        return view('pages.admin.kependudukan.keluarga.edit', compact('keluarga', 'rts'));
    }

    public function update(Request $request, $id)
    {
        $keluarga = Keluarga::findOrFail($id);
        
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:keluargas,no_kk,' . $id,
            'kepala_keluarga' => 'required|string',
            'rt_id' => 'required|exists:rt,id',
            'alamat_lengkap' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'kodepos' => 'required|string|max:5',
        ]);

        $keluarga->update($request->all());

        return redirect()->route('admin.kependudukan.keluarga.show', $keluarga->id)
            ->with('success', 'Data Kartu Keluarga berhasil diperbarui.');
    }
}
