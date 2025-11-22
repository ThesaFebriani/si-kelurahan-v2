<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['jenisSurat'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('pages.masyarakat.permohonan.index', compact('permohonan'));
    }

    public function create()
    {
        $jenis_surats = JenisSurat::where('is_active', true)->get();
        return view('pages.masyarakat.permohonan.create', compact('jenis_surats'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['jenisSurat', 'lampirans', 'timeline', 'approvalFlows', 'surat'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('pages.masyarakat.permohonan.detail', compact('permohonan'));
    }
}
