<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'document_name',
        'is_required',
        'description',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    // Accessors for View Compatibility
    public function getDocumentLabelAttribute()
    {
        return $this->document_name;
    }

    public function getRequiredAttribute()
    {
        return $this->is_required;
    }
}
