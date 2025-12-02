<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Pastikan user login
        if (!Auth::check()) {
            abort(403, 'Anda harus login.');
        }

        $user = Auth::user();

        // Pastikan user punya role
        if (!$user->role || !$user->role->name) {
            abort(403, 'Role user tidak ditemukan.');
        }

        // Role user
        $userRole = strtolower($user->role->name);

        // Normalize required roles (bisa lebih dari 1)
        $roles = array_map('strtolower', $roles);

        // Cek apakah role user sesuai
        if (!in_array($userRole, $roles)) {
            abort(403, "Akses ditolak untuk role: {$userRole}");
        }

        return $next($request);
    }
}
