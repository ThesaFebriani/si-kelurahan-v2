<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // CONSTANTS untuk bidang
    const BIDANG_KESRA = 'kesra';
    const BIDANG_PEMERINTAHAN = 'pemerintahan';
    const BIDANG_PEMBANGUNAN = 'pembangunan';

    // RELATIONS
    public function permohonanSurat()
    {
        return $this->hasMany(PermohonanSurat::class);
    }

    public function suratTemplates()
    {
        return $this->hasMany(SuratTemplate::class);
    }

    // SCOPES
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang', $bidang);
    }

    public function getBidangDisplayAttribute()
    {
        $bidang = [
            self::BIDANG_KESRA => 'Kesejahteraan Rakyat',
            self::BIDANG_PEMERINTAHAN => 'Pemerintahan',
            self::BIDANG_PEMBANGUNAN => 'Pembangunan'
        ];

        return $bidang[$this->bidang] ?? $this->bidang;
    }
}
