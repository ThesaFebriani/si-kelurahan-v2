<?php

namespace App\Observers;

use App\Models\PermohonanSurat;
use App\Services\NotificationService;
use App\Models\User;

class PermohonanSuratNotificationObserver
{
    /**
     * Handle the PermohonanSurat "created" event.
     */
    public function created(PermohonanSurat $permohonanSurat): void
    {
        // 1. Notifikasi ke Pemohon (Konfirmasi)
        NotificationService::send(
            $permohonanSurat->user_id,
            'Permohonan Terkirim',
            'Surat permohonan Anda berhasil dikirim dan menunggu verifikasi RT.',
            'success',
            $permohonanSurat->id,
            'PermohonanSurat'
        );

        // 2. Notifikasi ke RT (Ada surat baru)
        // Ambil RT dari data pemohon (relasi user->rt_id)
        if ($permohonanSurat->user && $permohonanSurat->user->rt_id) {
            NotificationService::notifyRT(
                $permohonanSurat->user->rt_id,
                'Permohonan Surat Baru',
                'Warga ' . $permohonanSurat->user->name . ' mengajukan surat baru.',
                $permohonanSurat->id,
                'PermohonanSurat'
            );
        }
    }

    /**
     * Handle the PermohonanSurat "updated" event.
     */
    public function updated(PermohonanSurat $permohonanSurat): void
    {
        if ($permohonanSurat->isDirty('status')) {
            $newStatus = $permohonanSurat->status;
            
            // A. DISETUJUI RT -> MENUNGGU KASI
            if ($newStatus == PermohonanSurat::MENUNGGU_KASI) {
                // Info ke Warga
                NotificationService::send(
                    $permohonanSurat->user_id,
                    'Disetujui RT',
                    'Permohonan Anda telah disetujui RT dan diteruskan ke Kelurahan.',
                    'info',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );
                
                // Info ke Kasi (Sesuai Bidang)
                $bidang = $permohonanSurat->jenisSurat->bidang ?? null;
                NotificationService::notifyKasi(
                    $bidang,
                    'Verifikasi Surat Masuk',
                    'Ada permohonan surat baru dari RT menunggu verifikasi Anda.',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );
            }

            // B. DISETUJUI KASI -> MENUNGGU LURAH
            elseif ($newStatus == PermohonanSurat::MENUNGGU_LURAH) {
                NotificationService::notifyLurah(
                    'Permohonan TTE',
                    'Surat baru menunggu Tanda Tangan Elektronik Anda.',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );
            }

            // C. SELESAI
            elseif ($newStatus == PermohonanSurat::SELESAI) {
                NotificationService::send(
                    $permohonanSurat->user_id,
                    'Surat Selesai',
                    'Hore! Surat Anda telah selesai dan diterbitkan. Silakan unduh.',
                    'success',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );
            }

            // D. DITOLAK (RT/Kasi/Lurah)
            elseif (in_array($newStatus, [PermohonanSurat::DITOLAK_RT, PermohonanSurat::DITOLAK_KASI, PermohonanSurat::DITOLAK_LURAH])) {
                NotificationService::send(
                    $permohonanSurat->user_id,
                    'Permohonan Ditolak',
                    'Mohon maaf, permohonan surat Anda ditolak. Cek detail alasan di dashboard.',
                    'danger',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );
            }
        }
    }
}
