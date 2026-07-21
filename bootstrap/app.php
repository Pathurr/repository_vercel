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

        // Jika sudah login tapi akses halaman guest (login/register),
        // redirect ke dashboard guru (sementara, bisa disesuaikan per role nanti)
        $middleware->redirectUsersTo(fn() => route('guru.dashboard'));

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
