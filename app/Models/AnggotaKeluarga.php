<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // RELATIONS
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class);
    }

    // SCOPES
    public function scopeKepalaKeluarga($query)
    {
        return $query->where('status_hubungan', 'kepala_keluarga');
    }

    public function scopeByRt($query, $rtId)
    {
        return $query->whereHas('keluarga', function ($q) use ($rtId) {
            $q->where('rt_id', $rtId);
        });
    }
}
