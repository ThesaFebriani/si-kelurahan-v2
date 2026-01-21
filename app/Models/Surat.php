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

    /**
     * Alias untuk permohonanSurat (mengatasi RelationNotFoundException di PublicController)
     */
    public function permohonan()
    {
        return $this->belongsTo(PermohonanSurat::class, 'permohonan_surat_id');
    }



    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
