<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Login page — selalu dapat diakses (publik)
Route::get('login', fn() => view('auth.login'))->name('login');

// ─── Guest Routes (hanya untuk yang belum login) ──────────────
Route::middleware('guest')->group(function () {

    // Login (POST)
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Register
    Route::get('register', fn() => view('auth.register'))->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Forgot Password
    Route::get('forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// ─── Authenticated Routes ─────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Email Verification
    Route::get('verify-email', fn() => view('auth.verify-email'))->name('verification.notice');
    
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', function(\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('verification.send');
});
