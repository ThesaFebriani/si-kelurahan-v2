<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // CASTING untuk JSON options
    protected $casts = [
        'options' => 'array',
    ];

    // RELATIONS
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    // SCOPES
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('required', false);
    }

    // ACCESSORS
    public function getValidationRulesAttribute()
    {
        // Jika ada custom validation rules di database, pakai itu
        if (!empty($this->attributes['validation_rules'] ?? null)) {
            return $this->attributes['validation_rules'];
        }

        // Generate default rules berdasarkan tipe field
        $rules = $this->required ? 'required|' : 'nullable|';

        return match ($this->field_type) {
            'text'     => $rules . 'string|max:255',
            'number'   => $rules . 'numeric',
            'date'     => $rules . 'date',
            'textarea' => $rules . 'string|max:1000',
            'select'   => $rules . 'string',
            'file'     => $rules . 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            default    => $rules . 'string',
        };
    }

    public function getOptionsArrayAttribute()
    {
        if (empty($this->options)) {
            return [];
        }

        // Jika options sudah array, return langsung
        if (is_array($this->options)) {
            return $this->options;
        }

        // Jika string JSON, decode
        if (is_string($this->options)) {
            $decoded = json_decode($this->options, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        return [];
    }
}
