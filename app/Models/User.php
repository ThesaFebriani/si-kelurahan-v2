<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==================== ROLE METHODS ====================
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isMasyarakat()
    {
        return $this->role->name === 'masyarakat';
    }

    public function isRT()
    {
        return $this->role->name === 'rt';
    }

    public function isKasi()
    {
        return $this->role->name === 'kasi';
    }

    public function isLurah()
    {
        return $this->role->name === 'lurah';
    }

    // ==================== PERMISSION METHODS ====================
    public function canApproveSurat()
    {
        return in_array($this->role->name, ['rt', 'kasi', 'lurah', 'admin']);
    }

    public function canGenerateSurat()
    {
        return in_array($this->role->name, ['kasi', 'admin']);
    }

    public function canTTE()
    {
        return in_array($this->role->name, ['lurah', 'admin']);
    }

    // ==================== RELATIONS ====================
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function permohonanSurat()
    {
        return $this->hasMany(PermohonanSurat::class);
    }

    public function approvals()
    {
        return $this->hasMany(ApprovalFlow::class, 'approved_by');
    }

    public function timelineUpdates()
    {
        return $this->hasMany(TimelinePermohonan::class, 'updated_by');
    }

    // ==================== OTHER METHODS ====================
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getRoleDisplayAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'masyarakat' => 'Masyarakat',
            'rt' => 'Ketua RT',
            'kasi' => 'Kepala Seksi',
            'lurah' => 'Lurah'
        ];

        return $roles[$this->role->name] ?? $this->role->name;
    }

    public function getAlamatLengkapAttribute()
    {
        if ($this->rt) {
            return $this->alamat . ', RT ' . $this->rt->nomor_rt . ', RW ' . $this->rt->rw->nomor_rw;
        }
        return $this->alamat;
    }
}
