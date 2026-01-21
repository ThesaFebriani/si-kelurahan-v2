<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiKepuasan extends Model
{
    use HasFactory;

    protected $table = 'survei_kepuasan';
    protected $fillable = ['permohonan_surat_id', 'rating', 'kritik_saran'];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanSurat::class, 'permohonan_surat_id');
    }
}
