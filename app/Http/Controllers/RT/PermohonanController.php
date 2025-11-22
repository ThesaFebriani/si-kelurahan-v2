<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->latest()
            ->get();

        return view('pages.rt.permohonan.index', compact('permohonan'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat', 'lampirans', 'timeline'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->findOrFail($id);

        return view('pages.rt.permohonan.detail', compact('permohonan'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', 'menunggu_rt')
            ->findOrFail($id);

        return view('pages.rt.permohonan.approve', compact('permohonan'));
    }
}
