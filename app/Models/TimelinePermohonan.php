<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelinePermohonan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // RELATIONS
    public function permohonanSurat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // SCOPES
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getStatusDisplayAttribute()
    {
        $display = [
            'menunggu_rt' => 'Menunggu Persetujuan RT',
            'disetujui_rt' => 'Disetujui RT',
            'ditolak_rt' => 'Ditolak RT',
            'menunggu_kasi' => 'Menunggu Verifikasi Kasi',
            'disetujui_kasi' => 'Disetujui Kasi',
            'ditolak_kasi' => 'Ditolak Kasi',
            'menunggu_lurah' => 'Menunggu TTE Lurah',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $display[$this->status] ?? $this->status;
    }
}
