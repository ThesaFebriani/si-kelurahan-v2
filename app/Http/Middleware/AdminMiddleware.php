<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Debug: Cek user dan role
        if (!$user->role) {
            abort(500, 'User role not found');
        }

        if ($user->role->name !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
