<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // TYPE CONSTANTS
    const TYPE_EMAIL = 'email';
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_SYSTEM = 'system';

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeSystem($query)
    {
        return $query->where('type', self::TYPE_SYSTEM);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // HELPER METHODS
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            self::TYPE_EMAIL => 'Email',
            self::TYPE_WHATSAPP => 'WhatsApp',
            self::TYPE_SYSTEM => 'System'
        ];

        return $types[$this->type] ?? $this->type;
    }
}
