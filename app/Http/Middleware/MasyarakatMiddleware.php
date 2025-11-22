<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MasyarakatMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isMasyarakat()) {
            abort(403, 'Akses ditolak. Hanya Masyarakat yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
