<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticateWithSanctumToken
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->query('_token') ?: $request->bearerToken();

            if (!$token) {
                Log::warning('Authentication failed: No token provided in request.', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);
                return redirect()->route('login')->with('error', 'Token tidak ditemukan. Silakan login ulang.');
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                Log::warning('Authentication failed: Invalid token.', [
                    'token' => substr($token, 0, 10) . '...', // Log only part of the token for security
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);
                return redirect()->route('login')->with('error', 'Token tidak valid. Silakan login ulang.');
            }

            if (!$accessToken->tokenable) {
                Log::warning('Authentication failed: Token has no associated user.', [
                    'token_id' => $accessToken->id,
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);
                return redirect()->route('login')->with('error', 'Token tidak valid. Silakan login ulang.');
            }

            Auth::login($accessToken->tokenable);
            Log::info('User authenticated successfully via token.', [
                'user_id' => $accessToken->tokenable->id,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Unexpected error in AuthenticateWithSanctumToken middleware.', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat autentikasi. Silakan login ulang.');
        }
    }
}