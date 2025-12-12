<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    // Accessors for View Compatibility
    public function getFieldNameAttribute()
    {
        return $this->field_key;
    }

    public function getRequiredAttribute()
    {
        return $this->is_required;
    }

    public function getOptionsArrayAttribute()
    {
        if (empty($this->options)) return [];
        return explode(',', $this->options); // Assuming comma separated for now
    }
}
