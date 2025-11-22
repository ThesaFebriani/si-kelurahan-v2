<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LurahMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isLurah()) {
            abort(403, 'Akses ditolak. Hanya Lurah yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
