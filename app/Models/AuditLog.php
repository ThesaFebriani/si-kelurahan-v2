<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory, MassPrunable;

    protected $guarded = ['id'];

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        // Hapus log yang lebih tua dari 1 tahun
        return static::where('created_at', '<=', now()->subYear());
    }

    // ACTION CONSTANTS
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_APPROVE = 'approve';
    const ACTION_REJECT = 'reject';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        if ($modelId) {
            $query = $query->where('model_id', $modelId);
        }
        return $query;
    }

    // HELPER METHODS
    public function getActionDisplayAttribute()
    {
        $actions = [
            self::ACTION_CREATE => 'Buat Data',
            self::ACTION_UPDATE => 'Update Data',
            self::ACTION_DELETE => 'Hapus Data',
            self::ACTION_APPROVE => 'Setujui',
            self::ACTION_REJECT => 'Tolak',
            self::ACTION_LOGIN => 'Login',
            self::ACTION_LOGOUT => 'Logout'
        ];

        return $actions[$this->action] ?? $this->action;
    }

    public function getActionColorAttribute()
    {
        $colors = [
            self::ACTION_CREATE => 'success',
            self::ACTION_UPDATE => 'info',
            self::ACTION_DELETE => 'danger',
            self::ACTION_APPROVE => 'success',
            self::ACTION_REJECT => 'danger',
            self::ACTION_LOGIN => 'primary',
            self::ACTION_LOGOUT => 'secondary'
        ];

        return $colors[$this->action] ?? 'secondary';
    }
}
