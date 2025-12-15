<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuthLogger
{
    public function handleLogin(Login $event)
    {
        $this->log($event->user, AuditLog::ACTION_LOGIN, "User berhasil masuk ke sistem.");
    }

    public function handleLogout(Logout $event)
    {
        if ($event->user) {
            $this->log($event->user, AuditLog::ACTION_LOGOUT, "User keluar dari sistem.");
        }
    }

    public function handleFailed(Failed $event)
    {
        // Only log if user exists (Wrong Password)
        // If user is null (Wrong Email), we cannot log to audit_logs table because user_id is NOT NULL constraint.
        if ($event->user) {
             $this->log($event->user, 'login_failed', "Gagal login: Password salah.");
        }
    }

    protected function log($user, $action, $desc)
    {
        if (!$user) return;

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $desc,
            'model_type' => 'App\Models\User', // Context
            'model_id' => $user->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
