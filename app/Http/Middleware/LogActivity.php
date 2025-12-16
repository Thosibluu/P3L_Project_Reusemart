<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\SecurityLog;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya log method yang mengubah data (POST, PUT, DELETE)
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            SecurityLog::create([
                'user_type' => auth()->check() ? class_basename(auth()->user()) : 'Guest',
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'action_description' => 'API Data Modification',
            ]);
        }

        return $response;
    }
}