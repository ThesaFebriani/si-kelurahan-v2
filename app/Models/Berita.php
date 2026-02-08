<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'gambar' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getGambarUrlAttribute()
    {
        if ($this->gambar && is_array($this->gambar) && count($this->gambar) > 0) {
            return asset('storage/' . $this->gambar[0]);
        }
        
        // Backward compatibility if still string (before migration fix fully applied to data)
        if ($this->gambar && is_string($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->judul) . '&background=random&color=fff&size=500';
    }

    // Helper to get all images
    public function getSemuaGambarAttribute()
    {
        if (!$this->gambar) return [];
        if (is_string($this->gambar)) return [asset('storage/' . $this->gambar)];
        
        return array_map(function($path) {
            return asset('storage/' . $path);
        }, $this->gambar);
    }
}
