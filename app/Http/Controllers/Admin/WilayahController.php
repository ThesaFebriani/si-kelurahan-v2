<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use App\Models\Rt;

class WilayahController extends Controller
{
    public function rwIndex()
    {
        $rw = Rw::withCount('rt')->latest()->get();
        return view('pages.admin.wilayah.rw-index', compact('rw'));
    }

    public function rtIndex()
    {
        $rt = Rt::with(['rw'])->latest()->get();
        return view('pages.admin.wilayah.rt-index', compact('rt'));
    }

    public function rwCreate()
    {
        return view('pages.admin.wilayah.rw-create');
    }

    public function rwStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nomor_rw' => 'required|string|max:5|unique:rw,nomor_rw',
            'is_active' => 'boolean'
        ]);

        Rw::create([
            'nomor_rw' => $request->nomor_rw,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.wilayah.rw.index')->with('success', 'Data RW berhasil ditambahkan.');
    }

    public function rwEdit(Rw $rw)
    {
        return view('pages.admin.wilayah.rw-edit', compact('rw'));
    }

    public function rwUpdate(\Illuminate\Http\Request $request, Rw $rw)
    {
        $request->validate([
            'nomor_rw' => 'required|string|max:5|unique:rw,nomor_rw,' . $rw->id,
            'is_active' => 'boolean'
        ]);

        $rw->update([
            'nomor_rw' => $request->nomor_rw,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.wilayah.rw.index')->with('success', 'Data RW berhasil diperbarui.');
    }

    public function rwDestroy(Rw $rw)
    {
        // Cek apakah ada RT yang terkait
        if ($rw->rt()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus RW karena masih memiliki RT aktif.');
        }
        
        $rw->delete();
        return redirect()->route('admin.wilayah.rw.index')->with('success', 'Data RW berhasil dihapus.');
    }

    public function rtCreate()
    {
        $rw = Rw::where('is_active', true)->get();
        return view('pages.admin.wilayah.rt-create', compact('rw'));
    }

    public function rtStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nomor_rt' => 'required|string|max:5',
            'rw_id' => 'required|exists:rw,id',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'warna_wilayah' => 'nullable|string|max:7'
        ]);

        // Cek unikan kombinasi RW + RT (opsional, tapi bagus)
        $exists = Rt::where('rw_id', $request->rw_id)->where('nomor_rt', $request->nomor_rt)->exists();
        if ($exists) {
            return back()->withErrors(['nomor_rt' => 'Nomor RT sudah ada di RW ini.'])->withInput();
        }

        Rt::create([
            'nomor_rt' => $request->nomor_rt,
            'rw_id' => $request->rw_id,
            'is_active' => $request->has('is_active') ? true : false,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'warna_wilayah' => $request->warna_wilayah,
        ]);

        return redirect()->route('admin.wilayah.rt.index')->with('success', 'Data RT berhasil ditambahkan.');
    }

    public function rtEdit(Rt $rt)
    {
        $rw = Rw::where('is_active', true)->get();
        return view('pages.admin.wilayah.rt-edit', compact('rt', 'rw'));
    }

    public function rtUpdate(\Illuminate\Http\Request $request, Rt $rt)
    {
        $request->validate([
            'nomor_rt' => 'required|string|max:5',
            'rw_id' => 'required|exists:rw,id',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'warna_wilayah' => 'nullable|string|max:7'
        ]);

         // Cek unikan kombinasi RW + RT jika berubah
         if ($rt->nomor_rt != $request->nomor_rt || $rt->rw_id != $request->rw_id) {
            $exists = Rt::where('rw_id', $request->rw_id)->where('nomor_rt', $request->nomor_rt)->exists();
            if ($exists) {
                return back()->withErrors(['nomor_rt' => 'Nomor RT sudah ada di RW ini.'])->withInput();
            }
         }

        $rt->update([
            'nomor_rt' => $request->nomor_rt,
            'rw_id' => $request->rw_id,
            'is_active' => $request->has('is_active') ? true : false,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'warna_wilayah' => $request->warna_wilayah,
        ]);

        return redirect()->route('admin.wilayah.rt.index')->with('success', 'Data RT berhasil diperbarui.');
    }

    public function rtDestroy(Rt $rt)
    {
        // Cek data terkait (misal penduduk/keluarga)
        if ($rt->users()->count() > 0 || $rt->keluargas()->count() > 0) {
             return back()->with('error', 'Tidak dapat menghapus RT karena masih memiliki data warga/user terkait.');
        }

        $rt->delete();
        return redirect()->route('admin.wilayah.rt.index')->with('success', 'Data RT berhasil dihapus.');
    }
}
