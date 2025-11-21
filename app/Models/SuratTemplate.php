<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'fields_mapping' => 'array',
    ];

    // RELATIONS
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    // SCOPES
    public function scopeAktif($query)
    {
        return $this->where('is_active', true);
    }

    public function scopeByJenisSurat($query, $jenisSuratId)
    {
        return $query->where('jenis_surat_id', $jenisSuratId);
    }

    // HELPER METHODS
    public function getFieldMapping($fieldName)
    {
        return $this->fields_mapping[$fieldName] ?? $fieldName;
    }
}
