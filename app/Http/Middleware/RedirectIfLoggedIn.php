<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (session('is_login')) {
            // kalau ada last_url di session, redirect ke situ
            if (session()->has('last_url')) {
                return redirect(session('last_url'));
            }

            // fallback kalau last_url belum ada
            return redirect()->route('menu.list-order');
        }

        return $next($request);
    }
}
