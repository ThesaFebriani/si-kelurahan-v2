<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // RELATIONS
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function getSlugNameAttribute()
    {
        return Str::slug($this->document_name, '_');
    }
}
