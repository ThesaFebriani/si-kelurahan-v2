<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['jenisSurat', 'timeline'])
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

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'data_pemohon' => 'required|array',
            'data_pemohon.nama_lengkap' => 'required|string|max:255',
            'data_pemohon.nik' => 'required|string|max:16',
            'data_pemohon.tempat_lahir' => 'required|string|max:100',
            'data_pemohon.tanggal_lahir' => 'required|date',
            'data_pemohon.jenis_kelamin' => 'required|in:L,P',
            'data_pemohon.alamat' => 'required|string|max:500',
            'data_pemohon.agama' => 'required|string|max:50',
            'data_pemohon.status_perkawinan' => 'required|string|max:50',
            'data_pemohon.pekerjaan' => 'required|string|max:100',
            'data_pemohon.tujuan' => 'required|string|max:500',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        try {
            //generate nomet tiket
            $nomorTiket = 'TKT-' . date('Ymd') . '-' . str_pad(PermohonanSurat::count() + 1, 4, '0', STR_PAD_LEFT);
            $user = Auth::user();

            //bua permohonan
            $permohonan = PermohonanSurat::create([
                'user_id' => $user->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'nomor_tiket' => 'TKT-' . Str::upper(Str::random(6)) . '-' . date('Ymd'),
                'status' => PermohonanSurat::MENUNGGU_RT,
                'data_pemohon' => $request->data_pemohon,
                'tanggal_pengajuan' => now(),
            ]);

            // Buat timeline pertama
            $permohonan->timeline()->create([
                'status' => PermohonanSurat::MENUNGGU_RT,
                'keterangan' => 'Permohonan diajukan oleh masyarakat',
                'updated_by' => $user->id,
            ]);

            return redirect()->route('masyarakat.permohonan.index')
                ->with('success', 'Permohonan surat berhasil diajukan! Nomor tiket: ' . $permohonan->nomor_tiket);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengajukan permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with([
            'jenisSurat',
            'timeline',
            'approvalFlows.approvedBy',
            'lampirans'
        ])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('pages.masyarakat.permohonan.detail', compact('permohonan'));
    }
}
