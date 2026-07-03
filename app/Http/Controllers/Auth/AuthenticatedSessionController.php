<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Bypass status check for admin, or if status is not active, deny login
            if ($user->role !== 'admin' && $user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                $message = $user->status === 'pending' 
                    ? 'Akun Anda sedang menunggu persetujuan Admin.' 
                    : 'Akun Anda telah dinonaktifkan atau disuspend.';

                ActivityLog::create([
                    'user_id' => $user->id,
                    'email' => $credentials['email'],
                    'role' => $user->role,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'Blocked',
                ]);

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => $user->id,
                'email' => $credentials['email'],
                'role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'Berhasil',
            ]);

            // Redirect ke dashboard sesuai role
            $role = $user->role;
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($role === 'murid' || $role === 'siswa') {
                return redirect()->intended(route('siswa.dashboard'));
            } else {
                return redirect()->intended(route('guru.dashboard'));
            }
        }

        $failedUser = User::where('email', $credentials['email'])->first();
        
        ActivityLog::create([
            'user_id' => $failedUser ? $failedUser->id : null,
            'email' => $credentials['email'],
            'role' => $failedUser ? $failedUser->role : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'Gagal',
        ]);

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Logout.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
