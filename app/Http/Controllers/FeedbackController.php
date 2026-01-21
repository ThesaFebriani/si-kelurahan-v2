<?php

namespace App\Http\Controllers;

use App\Models\SurveiKepuasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'permohonan_id' => 'required|exists:permohonan_surats,id',
            'rating' => 'required|integer|min:1|max:5',
            'kritik_saran' => 'nullable|string|max:1000',
        ]);

        // Ensure user owns the permohonan
        $permohonan = \App\Models\PermohonanSurat::findOrFail($request->permohonan_id);
        if ($permohonan->user_id !== Auth::id()) {
            abort(403);
        }

        // Check duplicate
        if (SurveiKepuasan::where('permohonan_surat_id', $permohonan->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk layanan ini.');
        }

        SurveiKepuasan::create([
            'permohonan_surat_id' => $permohonan->id,
            'rating' => $request->rating,
            'kritik_saran' => $request->kritik_saran,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas masukan Anda! Kepuasan Anda adalah prioritas kami.');
    }
}
