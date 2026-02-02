<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    public function indexVerification()
    {
        $user = Auth::user();

        // Get Pending Users in THIS RT
        $pendingUsers = User::where('rt_id', $user->rt_id)
            ->where('status', User::STATUS_PENDING)
            ->where('role_id', '!=', 1) // Just in case, don't show admins
            ->latest()
            ->get();

        return view('pages.rt.warga.verification', compact('pendingUsers'));
    }

    public function processVerification(Request $request, $id)
    {
        $currentUser = Auth::user();
        
        $targetUser = User::where('rt_id', $currentUser->rt_id)
            ->where('status', User::STATUS_PENDING)
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($request->action === 'approve') {
            $updateData = ['status' => User::STATUS_ACTIVE];
            
            // Coba sinkronisasi data penduduk saat diapprove, barangkali baru diinput
            $penduduk = \App\Models\AnggotaKeluarga::where('nik', $targetUser->nik)->first();
            if ($penduduk) {
                $updateData['tempat_lahir'] = $penduduk->tempat_lahir;
                $updateData['tanggal_lahir'] = $penduduk->tanggal_lahir;
                $updateData['agama'] = $penduduk->agama;
                $updateData['status_perkawinan'] = $penduduk->status_perkawinan;
                $updateData['pekerjaan'] = $penduduk->pekerjaan;
                
                 if ($targetUser->alamat == '-') {
                    $updateData['alamat'] = $penduduk->keluarga->alamat_lengkap;
                }
            }

            $targetUser->update($updateData);
            $message = 'Warga berhasil diverifikasi dan diaktifkan.';
        } else {
            $targetUser->update([
                'status' => User::STATUS_REJECTED
            ]);
            $message = 'Registrasi warga ditolak.';
        }

        return redirect()->back()->with('success', $message);
    }
}

/*
File belum atau tidak jadi dipakai, karena saat ini tidak ada fitur verifikasi warga
Warga langhsung active(Auto aproved by system) karena NIK nya cocok dengan datavse penduduk
*/