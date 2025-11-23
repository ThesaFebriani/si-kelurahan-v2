<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RTMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // ✅ FIX
        }

        $user = Auth::user();

        if (!$user->role) {
            abort(500, 'User role not found');
        }

        // ✅ FIX: Gunakan constant
        if ($user->role->name !== \App\Models\Role::RT) {
            abort(403, 'Akses ditolak. Hanya Ketua RT yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
