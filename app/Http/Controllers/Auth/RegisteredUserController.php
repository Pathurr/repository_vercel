<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman register.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:guru,murid'],
            'nis'      => ['required_if:role,murid', 'nullable', 'numeric'],
            'nrg'      => ['required_if:role,guru', 'nullable', 'numeric'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nis'      => $request->role === 'murid' ? $request->nis : null,
            'nrg'      => $request->role === 'guru' ? $request->nrg : null,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Admin sebelum dapat digunakan untuk login.');
    }
}
