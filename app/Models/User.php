<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// IMPORT MODEL YANG DIBUTUHKAN
use App\Models\Role;
use App\Models\Rt;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use App\Models\Notification;

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

    // ==================== ROLE HELPERS ====================
    public function isAdmin()
    {
        return $this->role && $this->role->name === Role::ADMIN;
    }

    public function isMasyarakat()
    {
        return $this->role && $this->role->name === Role::MASYARAKAT;
    }

    public function isRT()
    {
        return $this->role && $this->role->name === Role::RT;
    }

    public function isKasi()
    {
        return $this->role && $this->role->name === Role::KASI;
    }

    public function isLurah()
    {
        return $this->role && $this->role->name === Role::LURAH;
    }

    // ==================== PERMISSION HELPERS ====================
    public function canApproveSurat()
    {
        return $this->role &&
            in_array($this->role->name, [Role::RT, Role::KASI, Role::LURAH, Role::ADMIN]);
    }

    public function canGenerateSurat()
    {
        return $this->role &&
            in_array($this->role->name, [Role::KASI, Role::ADMIN]);
    }

    public function canTTE()
    {
        return $this->role &&
            in_array($this->role->name, [Role::LURAH, Role::ADMIN]);
    }

    // ==================== RELASI ====================
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    // ==================== OTHER HELPERS ====================
    public function setPasswordAttribute($password)
    {
        // Jika password sudah hashed, tidak perlu hash ulang
        if (strlen($password) !== 60 || !preg_match('/^\$2y\$/', $password)) {
            $password = bcrypt($password);
        }

        $this->attributes['password'] = $password;
    }

    public function getRoleDisplayAttribute()
    {
        if (!$this->role) {
            return '-';
        }

        $roles = [
            Role::ADMIN => 'Administrator',
            Role::MASYARAKAT => 'Masyarakat',
            Role::RT => 'Ketua RT',
            Role::KASI => 'Kepala Seksi',
            Role::LURAH => 'Lurah'
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
