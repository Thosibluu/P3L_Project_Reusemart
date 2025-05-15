<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatePembeli
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pembeli')->check()) {
            return redirect('/login')->with('error', 'Silakan login sebagai pembeli.');
        }
        return $next($request);
    }
}