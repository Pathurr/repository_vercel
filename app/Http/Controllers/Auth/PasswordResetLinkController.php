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

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                session(['reset_email' => $request->email]);
                return back()->with('status', __($status));
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('Gagal mengirim link reset password. Pastikan konfigurasi mail di .env sudah benar.')]);
        }
    }
}
