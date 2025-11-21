<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
    use HasFactory;
    protected $table = 'rt'; // Sesuaikan dengan nama tabel di database

    protected $guarded = ['id'];
    // RELATIONS
    public function rw()
    {
        return $this->belongsTo(Rw::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class);
    }

    // SCOPES
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function getAlamatLengkapAttribute()
    {
        return 'RT ' . $this->nomor_rt . ', RW ' . $this->rw->nomor_rw;
    }
}
