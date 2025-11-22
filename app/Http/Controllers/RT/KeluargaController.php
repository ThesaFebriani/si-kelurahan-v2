<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use Illuminate\Support\Facades\Auth;

class KeluargaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $keluarga = Keluarga::with(['kepalaKeluarga', 'anggotaKeluarga'])
            ->where('rt_id', $user->rt_id)
            ->latest()
            ->get();

        return view('pages.rt.keluarga.index', compact('keluarga'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $keluarga = Keluarga::with(['kepalaKeluarga', 'anggotaKeluarga'])
            ->where('rt_id', $user->rt_id)
            ->findOrFail($id);

        return view('pages.rt.keluarga.detail', compact('keluarga'));
    }
}
