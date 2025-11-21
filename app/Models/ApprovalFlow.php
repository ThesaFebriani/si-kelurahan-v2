<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // STEP CONSTANTS
    const STEP_RT = 'rt';
    const STEP_KASI = 'kasi';
    const STEP_LURAH = 'lurah';

    // STATUS CONSTANTS  
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // RELATIONS
    public function permohonanSurat()
    {
        return $this->belongsTo(PermohonanSurat::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // SCOPES
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForStep($query, $step)
    {
        return $query->where('step', $step);
    }

    // HELPER METHODS
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getStepDisplayAttribute()
    {
        return [
            self::STEP_RT => 'Persetujuan RT',
            self::STEP_KASI => 'Verifikasi Kasi',
            self::STEP_LURAH => 'Tanda Tangan Lurah'
        ][$this->step] ?? $this->step;
    }
}
