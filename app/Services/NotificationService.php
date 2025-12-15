<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Rt;

class NotificationService
{
    /**
     * Kirim notifikasi ke satu user
     */
    public static function send($userId, $title, $message, $type = 'info', $relatedId = null, $relatedType = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type, // info, success, warning, danger
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'sent_at' => now(),
        ]);
    }

    /**
     * Kirim notifikasi ke Ketua RT tertentu (berdasarkan ID RT)
     */
    public static function notifyRT($rtId, $title, $message, $relatedId = null, $relatedType = null)
    {
        // Cari user yang rolenya 'rt' dan rt_id = $rtId
        $rtUsers = User::where('rt_id', $rtId)
            ->whereHas('role', function($q) {
                $q->where('name', 'rt');
            })->get();

        foreach ($rtUsers as $user) {
            self::send($user->id, $title, $message, 'info', $relatedId, $relatedType);
        }
    }

    /**
     * Kirim notifikasi ke Kasi terkait (berdasarkan Bidang Surat)
     */
    public static function notifyKasi($bidang, $title, $message, $relatedId = null, $relatedType = null)
    {
        // Kasi Pemerintahan (Umum & Kependudukan)
        // Kasi Pembangunan (Pembangunan)
        // Kasi Kesos (Kesejahteraan Sosial)
        
        // Mapping bidang ke role/user condition (sesuai UserManagement)
        $kasiUsers = User::whereHas('role', function($q) {
                $q->where('name', 'kasi');
            })
            ->where('bidang', $bidang) // Asumsi di tabel user ada kolom 'bidang' atau logic lain
            ->get();
            
        // Fallback: Jika tidak ada filtering bidang, kirim ke semua Kasi (Not ideal but safe)
        if ($kasiUsers->isEmpty()) {
             $kasiUsers = User::whereHas('role', function($q) {
                $q->where('name', 'kasi');
            })->get();
        }

        foreach ($kasiUsers as $user) {
            self::send($user->id, $title, $message, 'info', $relatedId, $relatedType);
        }
    }

    /**
     * Kirim notifikasi ke Lurah
     */
    public static function notifyLurah($title, $message, $relatedId = null, $relatedType = null)
    {
        $lurahUsers = User::whereHas('role', function($q) {
            $q->where('name', 'lurah');
        })->get();

        foreach ($lurahUsers as $user) {
            self::send($user->id, $title, $message, 'info', $relatedId, $relatedType);
        }
    }
}
