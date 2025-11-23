<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KasiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // ✅ FIX: gunakan route()
        }

        $user = Auth::user();

        // ✅ FIX: Cek role exists
        if (!$user->role) {
            abort(500, 'User role not configured');
        }

        // ✅ FIX: Gunakan constant dari Role model
        if ($user->role->name !== \App\Models\Role::KASI) {
            abort(403, 'Akses ditolak. Hanya Kepala Seksi yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
