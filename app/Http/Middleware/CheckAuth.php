<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user belum login
        if (!Auth::check()) {
            // hanya izinkan akses ke halaman login
            if (!$request->routeIs('login')) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
