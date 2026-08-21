<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        
        // Sesuaikan role murid dengan siswa
        if ($role === 'siswa' && $userRole === 'murid') {
            $userRole = 'siswa';
        }

        // Superadmin bisa akses semua route admin
        if ($role === 'admin' && $userRole === 'superadmin') {
            return $next($request);
        }

        if ($userRole !== $role) {
            // Jika role tidak sesuai, redirect ke dashboard masing-masing
            if ($userRole === 'superadmin' || $userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'murid' || $userRole === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }
            return redirect()->route('guru.dashboard');
        }

        return $next($request);
    }
}
