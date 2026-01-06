<?php

namespace App\Observers;

use App\Models\PermohonanSurat;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PermohonanObserver
{
    /**
     * Handle the PermohonanSurat "updated" event.
     */
    public function updated(PermohonanSurat $permohonan): void
    {
        // Cek jika status berubah
        if ($permohonan->isDirty('status')) {
            $oldStatus = $permohonan->getOriginal('status');
            $newStatus = $permohonan->status;
            $user = Auth::user();
            $userName = $user ? $user->name : 'System';

            // Buat narasi log yang mudah dibaca
            $description = "User [{$userName}] mengubah status surat {$permohonan->nomor_surat} dari [{$oldStatus}] menjadi [{$newStatus}]";

            // Simpan ke AuditLog dengan tipe aksi khusus 'STATUS_UPDATE' atau tetap 'UPDATE'
            // Kita gunakan 'UPDATE' tapi deskripsinya lebih spesifik.
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'STATUS_CHANGE', // Custom action agar mudah difilter
                'description' => $description,
                'model_type' => get_class($permohonan),
                'model_id' => $permohonan->id,
                'old_data' => ['status' => $oldStatus],
                'new_data' => ['status' => $newStatus],
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }
}
