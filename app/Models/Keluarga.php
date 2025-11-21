<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // RELATIONS
    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function kepalaKeluarga()
    {
        return $this->hasOne(AnggotaKeluarga::class)->where('status_hubungan', 'kepala_keluarga');
    }

    // SCOPES
    public function scopeByRt($query, $rtId)
    {
        return $query->where('rt_id', $rtId);
    }
}
