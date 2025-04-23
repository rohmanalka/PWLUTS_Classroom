<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return redirect('/');
        } elseif (Auth::guard('dosen')->check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        // Validasi input
        $credentials = $request->only('username', 'password');
        $guard = $request->role; // Ambil role dari form login

        if (!$guard) {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak dipilih',
            ]);
        }

        // Autentikasi sesuai dengan role
        if (Auth::guard($guard)->attempt($credentials)) {
            // Menyimpan session berdasarkan role yang login
            if ($guard == 'mahasiswa') {
                session(['mahasiswa' => true]);
            } elseif ($guard == 'dosen') {
                session(['dosen' => true]);
            }

            // Arahkan ke WelcomeController setelah login berhasil
            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'redirect' => url('/'), // Redirect ke welcome page
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Username atau password salah',
        ]);
    }


    public function logout(Request $request)
    {
        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } elseif (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
