<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreLastUrl
{
    public function handle(Request $request, Closure $next)
    {
        if (session('is_login')) {
            // Simpan hanya jika request GET dan bukan login/logout
            if ($request->isMethod('get') && !$request->is('login') && !$request->is('logout')) {
                session(['last_url' => $request->fullUrl()]);
            }
        }

        return $next($request);
    }
}
