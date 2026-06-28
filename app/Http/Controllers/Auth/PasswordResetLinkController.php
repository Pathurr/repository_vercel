<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman forgot password.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __('passwords.user')]);
        }

        // Generate token manual
        $token = Password::broker()->createToken($user);
        
        // Buat URL lengkap
        $link = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Kembalikan ke halaman sebelumnya dengan membawa variabel link
        return back()->with('reset_link', $link)
                     ->with('status', 'Berhasil! Klik link reset password Anda di bawah ini.');
    }
}
