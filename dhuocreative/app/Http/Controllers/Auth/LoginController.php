<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class LoginController extends Controller
{
    //tampilkan login
    public function ShowLogin()
    {
        return view ('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'password' => 'required',
            'role'    => 'required'

        ]);

        // cari akun dari email
        $user = User::where('email', $request->email)->first();

        // cek password
        if (! $user || ! Hash::check($request->password, $user->pasword)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->withInput($request->only('email', 'role'));
        }

        // cek status akun
        if ($user->status !== 'aktif') {
            return back()
                ->withErrors([
                    'email' => "Akun kamu tidak aktif.",
                ]);
        }

        // cek role
        if ($user->role !== $request->role) {
            return back()
                ->withErrors([
                    'role' => 'Role yang dipilih tidak sesuai dengan akun.',
                ])
                ->withInput($request->only('email', 'role'));
        }

        // login $ buat session baru
        Auth::login($user);
        $request->session()->regenerate();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'operator':
                return redirect('/operator/dashboard');
            case 'tentor':
                return redirect('/tentor/dashboard');
            case 'siswa':
                return redirect('/siswa/dashboard');
            default:
                Auth::logout();
                return redirect('/')
                ->eithErrors(['email' => 'Role akun tidak valid']);
        }
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
