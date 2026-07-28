<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Tambahkan baris ini agar Laravel mempercayai ngrok
        $middleware->trustProxies(at: '*');
        
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Jika sudah login tapi akses halaman guest (login/register),
        // redirect ke dashboard sesuai role
        $middleware->redirectUsersTo(function () {
            $role = auth()->user()->role ?? '';
            if ($role === 'admin') {
                return route('admin.dashboard');
            } elseif ($role === 'murid' || $role === 'siswa') {
                return route('siswa.dashboard');
            }
            return route('guru.dashboard');
        });

        // Jika belum login tapi akses halaman protected,
        // redirect ke halaman login
        $middleware->redirectGuestsTo(fn() => route('login'));
        
        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
