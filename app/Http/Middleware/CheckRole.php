<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roleid = session('roleid');

        if (!$roleid) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role saat ini termasuk dalam role yang diizinkan
        if (!in_array($roleid, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
