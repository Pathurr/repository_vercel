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

        // Mode Production: kirim email reset password jika SMTP sudah dikonfigurasi
        if (config('mail.default') !== 'log') {
            try {
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status === Password::RESET_LINK_SENT) {
                    return back()->with('status', __($status));
                }

                return back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
            } catch (\Exception $e) {
                // Jika pengiriman email gagal (SMTP error, kredensial salah, dll),
                // fallback ke mode tampilan link langsung di halaman
            }
        }

        // Mode Fallback: tampilkan link langsung di halaman
        // Aktif jika MAIL_MAILER=log ATAU jika pengiriman email gagal
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __('passwords.user')]);
        }

        $token = Password::broker()->createToken($user);
        $link = route('password.reset', ['token' => $token, 'email' => $request->email]);

        return back()->with('reset_link', $link)
                     ->with('status', 'Link reset password berhasil dibuat. Klik tombol di bawah untuk melanjutkan.');
    }
}
