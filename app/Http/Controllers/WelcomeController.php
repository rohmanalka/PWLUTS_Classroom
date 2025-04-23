<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list'  => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';

        // Mengecek role pengguna (mahasiswa atau dosen)
        if (Auth::guard('mahasiswa')->check()) {
            // Jika user adalah mahasiswa, tampilkan view mahasiswa
            return view('welcome_mahasiswa', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
        } elseif (Auth::guard('dosen')->check()) {
            // Jika user adalah dosen, tampilkan view dosen
            return view('welcome_dosen', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
        }

        // Jika tidak ada role yang dikenali, arahkan ke login
        return redirect()->route('login');
    }
}
