<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanSurat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    // STATUS CONSTANTS
    const MENUNGGU_RT = 'menunggu_rt';
    const DISETUJUI_RT = 'disetujui_rt';
    const DITOLAK_RT = 'ditolak_rt';
    const MENUNGGU_KASI = 'menunggu_kasi';
    const DISETUJUI_KASI = 'disetujui_kasi';
    const DITOLAK_KASI = 'ditolak_kasi';
    const MENUNGGU_LURAH = 'menunggu_lurah';
    const SELESAI = 'selesai';
    const DIBATALKAN = 'dibatalkan';
    const MENUNGGU_TTE = 'menunggu_tte'; // Untuk Phase 4
    const DITOLAK_LURAH = 'ditolak_lurah'; // Untuk Phase 4

    protected $casts = [
        'data_pemohon' => 'array',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];
    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'nomor_tiket',
        'status',
        'data_pemohon',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'file_surat_pengantar_rt',
        'nomor_surat_pengantar_rt',
    ];


    // Accessor untuk handle data_pemohon dengan aman
    public function getDataPemohonAttribute($value)
    {
        // Jika sudah array, return langsung
        if (is_array($value)) {
            return $value;
        }

        // Jika string, coba decode JSON
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        // Fallback ke array kosong
        return [];
    }

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function approvalFlows()
    {
        return $this->hasMany(ApprovalFlow::class);
    }

    public function timeline()
    {
        return $this->hasMany(TimelinePermohonan::class);
    }

    public function surat()
    {
        return $this->hasOne(Surat::class);
    }

    public function lampirans()
    {
        return $this->hasMany(Lampiran::class);
    }

    public function survei()
    {
        return $this->hasOne(SurveiKepuasan::class, 'permohonan_surat_id');
    }

    // STATUS METHODS
    public function isMenungguRT()
    {
        return $this->status === self::MENUNGGU_RT;
    }

    public function isDisetujuiRT()
    {
        return $this->status === self::DISETUJUI_RT;
    }

    public function isDitolakRT()
    {
        return $this->status === self::DITOLAK_RT;
    }

    public function isMenungguKasi()
    {
        return $this->status === self::MENUNGGU_KASI;
    }

    public function isDisetujuiKasi()
    {
        return $this->status === self::DISETUJUI_KASI;
    }

    public function isDitolakKasi()
    {
        return $this->status === self::DITOLAK_KASI;
    }

    public function isMenungguLurah()
    {
        return $this->status === self::MENUNGGU_LURAH;
    }

    public function isSelesai()
    {
        return $this->status === self::SELESAI;
    }

    public function isDibatalkan()
    {
        return $this->status === self::DIBATALKAN;
    }

    // WORKFLOW METHODS
    public function getCurrentStep()
    {
        $stepMap = [
            self::MENUNGGU_RT => 'rt',
            self::DISETUJUI_RT => 'rt',
            self::DITOLAK_RT => 'rt',
            self::MENUNGGU_KASI => 'kasi',
            self::DISETUJUI_KASI => 'kasi',
            self::DITOLAK_KASI => 'kasi',
            self::MENUNGGU_LURAH => 'lurah',
            self::SELESAI => 'selesai'
        ];

        return $stepMap[$this->status] ?? 'unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::MENUNGGU_RT => 'warning',
            self::DISETUJUI_RT => 'success',
            self::DITOLAK_RT => 'danger',
            self::MENUNGGU_KASI => 'warning',
            self::DISETUJUI_KASI => 'success',
            self::DITOLAK_KASI => 'danger',
            self::MENUNGGU_LURAH => 'info',
            self::SELESAI => 'success',
            self::DIBATALKAN => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusDisplayAttribute()
    {
        $display = [
            self::MENUNGGU_RT => 'Menunggu Persetujuan RT',
            self::DISETUJUI_RT => 'Disetujui RT',
            self::DITOLAK_RT => 'Ditolak RT',
            self::MENUNGGU_KASI => 'Menunggu Verifikasi Kasi',
            self::DISETUJUI_KASI => 'Disetujui Kasi',
            self::DITOLAK_KASI => 'Ditolak Kasi',
            self::MENUNGGU_LURAH => 'Menunggu TTE Lurah',
            self::SELESAI => 'Selesai',
            self::DIBATALKAN => 'Dibatalkan'
        ];

        return $display[$this->status] ?? $this->status;
    }

    // PERMISSION METHODS
    public function canBeApprovedBy($user)
    {
        if (!$user->canApproveSurat()) {
            return false;
        }

        $stepPermission = [
            'rt' => $user->isRT() && $this->isMenungguRT(),
            'kasi' => $user->isKasi() && $this->isMenungguKasi(),
            'lurah' => $user->isLurah() && $this->isMenungguLurah()
        ];

        return $stepPermission[$this->getCurrentStep()] ?? false;
    }
    public function isMenungguTTE()
    {
        return $this->status === self::MENUNGGU_TTE;
    }

    public function isDitolakLurah()
    {
        return $this->status === self::DITOLAK_LURAH;
    }
}
