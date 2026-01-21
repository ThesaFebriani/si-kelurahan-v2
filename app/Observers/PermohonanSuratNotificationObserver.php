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
        // Cek User -> RT -> Ketua RT User
        $user = $permohonanSurat->user;
        if ($user && $user->rt) {
            $ketuaRt = $user->rt->ketuaRt; // Relasi ke User Ketua RT
            if ($ketuaRt) {
                // Info Lonceng (Website)
                NotificationService::send(
                    $ketuaRt->id,
                    'Permohonan Baru',
                    "Warga {$user->name} mengajukan permohonan surat baru.",
                    'info',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );

                // Info WhatsApp (KEBUTUHAN RT: STANDBY DI RUMAH)
                // MOVED TO CONTROLLER (Masyarakat/PermohonanController@store)
                /*
                if ($ketuaRt->telepon) {
                    $msg = "Halo Ketua RT {$user->rt->nomor_rt}, Ada permohonan surat baru dari warga Anda ({$user->name}). Mohon segera dicek di sistem.";
                    \App\Services\WhatsAppService::sendMessage($ketuaRt->telepon, $msg);
                }
                */
            }
        }
    }

    /**
     * Handle the PermohonanSurat "updated" event.
     */
    public function updated(PermohonanSurat $permohonanSurat): void
    {
        if ($permohonanSurat->isDirty('status')) {
            $newStatus = $permohonanSurat->status;
            
            // A. MENUNGGU RT -> DISETUJUI RT (Masuk ke Kasi)
            if ($newStatus == PermohonanSurat::MENUNGGU_KASI) { // Alias: Disetujui RT
                // Info ke Warga
                NotificationService::send(
                    $permohonanSurat->user_id,
                    'Disetujui RT',
                    'Permohonan Anda telah disetujui RT dan diteruskan ke Kelurahan.',
                    'info',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );

                // WA Notification (Warga)
                // MOVED TO CONTROLLER (RT/PermohonanController@processApproval/approve)
                /*
                if ($permohonanSurat->user && $permohonanSurat->user->telepon) {
                    $msg = "Halo {$permohonanSurat->user->name}, Permohonan surat Anda ({$permohonanSurat->jenisSurat->name}) telah DISETUJUI RT. Saat ini sedang diteruskan ke Kelurahan untuk verifikasi.";
                    \App\Services\WhatsAppService::sendMessage($permohonanSurat->user->telepon, $msg);
                }
                */
                
                // Info ke Kasi (Website Only - Standby di Kantor)
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
                // Info ke Lurah (Website)
                NotificationService::notifyLurah(
                    'Permohonan TTE',
                    'Surat baru menunggu Tanda Tangan Elektronik Anda.',
                    $permohonanSurat->id,
                    'PermohonanSurat'
                );

                // Info WhatsApp (KEBUTUHAN LURAH: DINAS LUAR)
                // MOVED TO CONTROLLER (Kasi/PermohonanController@processVerification)
                /*
                $lurahUsers = \App\Models\User::whereHas('role', function($q) {
                    $q->where('name', 'lurah');
                })->where('status', 'active')->get();

                foreach ($lurahUsers as $lurah) {
                    if ($lurah->telepon) {
                        $msg = "Yth. Pak Lurah, Terdapat permohonan surat ({$permohonanSurat->jenisSurat->name}) yang telah diverifikasi dan menunggu Tanda Tangan Elektronik (TTE) Anda.";
                        \App\Services\WhatsAppService::sendMessage($lurah->telepon, $msg);
                    }
                }
                */

                // WA Notification (Warga - Tetap Dapat)
                // MOVED TO CONTROLLER (Kasi/PermohonanController@processVerification)
                /*
                if ($permohonanSurat->user && $permohonanSurat->user->telepon) {
                    $msg = "Halo {$permohonanSurat->user->name}, Permohonan surat Anda ({$permohonanSurat->jenisSurat->name}) telah DISETUJUI KASI. Saat ini sedang menunggu Tanda Tangan Elektronik Lurah.";
                    \App\Services\WhatsAppService::sendMessage($permohonanSurat->user->telepon, $msg);
                }
                */
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

                // WA Notification
                // MOVED TO CONTROLLER (Lurah/PermohonanController@processSign)
                /*
                if ($permohonanSurat->user && $permohonanSurat->user->telepon) {
                    $msg = "Halo {$permohonanSurat->user->name}, Surat permohonan Anda ({$permohonanSurat->jenisSurat->name}) telah SELESAI. Silakan login ke aplikasi SI-KELURAHAN untuk mengunduh dokumen.";
                    \App\Services\WhatsAppService::sendMessage($permohonanSurat->user->telepon, $msg);
                }
                */
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

                // WA Notification
                // MOVED TO CONTROLLER (Reject actions)
                /*
                if ($permohonanSurat->user && $permohonanSurat->user->telepon) {
                    $msg = "Halo {$permohonanSurat->user->name}, Mohon maaf, permohonan surat Anda ({$permohonanSurat->jenisSurat->name}) DITOLAK. Silakan cek aplikasi untuk melihat alasan dan melakukan revisi secepatnya.";
                    \App\Services\WhatsAppService::sendMessage($permohonanSurat->user->telepon, $msg);
                }
                */
            }
        }
    }
}
