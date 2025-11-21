<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
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

    // HELPER METHODS
    public function isSigned()
    {
        return !is_null($this->signed_at);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
