<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KasiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Cek jika user memiliki role kasi
        if ($user->role->name !== 'kasi') {
            abort(403, 'Unauthorized access. Hanya Kasi yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
