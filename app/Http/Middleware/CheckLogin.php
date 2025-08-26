<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // cek session buatan sendiri
        if (!session('is_login')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        return $next($request);
    }
}
