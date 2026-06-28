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
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
            'role'     => ['required', 'in:guru,murid'],
            'nis'      => ['exclude_unless:role,murid', 'required', 'digits_between:10,12', 'unique:users,nis'],
            'nrg'      => ['exclude_unless:role,guru', 'required', 'digits:12', 'unique:users,nrg'],
        ], [
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.',
            'nis.required' => 'NIS wajib diisi untuk akun murid.',
            'nis.digits_between' => 'NIS harus berupa angka dengan panjang 10 sampai 12 digit.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nrg.required' => 'NRG wajib diisi untuk akun guru.',
            'nrg.digits' => 'NRG harus berupa angka dengan panjang tepat 12 digit.',
            'nrg.unique' => 'NRG sudah terdaftar.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'nis'      => $validated['role'] === 'murid' ? $validated['nis'] : null,
            'nrg'      => $validated['role'] === 'guru' ? $validated['nrg'] : null,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Admin sebelum dapat digunakan untuk login.');
    }
}
