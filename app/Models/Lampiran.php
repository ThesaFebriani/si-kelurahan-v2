<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // RELATIONS
    public function permohonanSurat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }

    // HELPER METHODS
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

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function isImage()
    {
        return in_array($this->getFileExtensionAttribute(), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
    }

    public function isPdf()
    {
        return $this->getFileExtensionAttribute() === 'pdf';
    }
}
