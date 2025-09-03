<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'username' => 'Silakan login terlebih dahulu.'
            ]);
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Anda tidak memiliki akses sebagai admin.'
            ]);
        }

        return $next($request);
    }
}
