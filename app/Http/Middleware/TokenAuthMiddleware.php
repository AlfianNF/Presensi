<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');
        
        if (empty($token)) {
            $token = session('access_token');
        }
        
        if (empty($token)) {
            Log::info('User tidak terautentikasi: Token tidak ditemukan');
            return redirect()->route('loginPage')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        try {
            $request->attributes->add(['access_token' => $token]);
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error validasi token: ' . $e->getMessage());
            return redirect()->route('loginPage')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }
    }
}