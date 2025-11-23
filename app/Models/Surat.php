<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_surat' => 'datetime',
        'signed_at' => 'datetime',
    ];

    // RELATIONS
    public function permohonanSurat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
