<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rw extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI
    protected $table = 'rw'; // Sesuaikan dengan nama tabel di database

    protected $guarded = ['id'];

    // RELATIONS
    /* public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
        */

    public function rt()
    {
        return $this->hasMany(Rt::class);
    }

    // SCOPES
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
