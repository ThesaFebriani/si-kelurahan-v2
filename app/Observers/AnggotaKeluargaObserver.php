<?php

namespace App\Observers;

use App\Models\AnggotaKeluarga;

class AnggotaKeluargaObserver
{
    /**
     * Handle the AnggotaKeluarga "saved" event (created & updated).
     */
    public function saved(AnggotaKeluarga $anggotaKeluarga): void
    {
        $this->syncKepalaKeluarga($anggotaKeluarga);
    }

    /**
     * Handle the AnggotaKeluarga "deleted" event.
     */
    public function deleted(AnggotaKeluarga $anggotaKeluarga): void
    {
        $this->syncKepalaKeluarga($anggotaKeluarga);
    }

    /**
     * Sync Nama Kepala Keluarga to Parent Keluarga Table
     */
    private function syncKepalaKeluarga(AnggotaKeluarga $anggotaKeluarga)
    {
        // Cek jika status hubungan adalah kepala keluarga, atau yang diedit/dihapus dulunya kepala keluarga
        // Cara paling aman: Selalu cari ulang siapa kepala keluarganya di DB untuk keluarga ini
        
        $keluarga = $anggotaKeluarga->keluarga;
        
        if ($keluarga) {
            $kepala = $keluarga->anggotaKeluarga()
                ->where('status_hubungan', 'kepala_keluarga')
                ->first();

            if ($kepala) {
                $keluarga->update(['kepala_keluarga' => $kepala->nama_lengkap]);
            } else {
                // Warning: Tidak ada kepala keluarga? (Bisa jadi baru dihapus)
                // Biarkan atau set null/alert
            }
        }
    }
}
