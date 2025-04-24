<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasMahasiswaModel;
use App\Models\TugasMahasiswaModel;
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
            // Ambil data statistik untuk mahasiswa
            $mahasiswa = Auth::guard('mahasiswa')->user();
            $belumDikumpulkan = TugasMahasiswaModel::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('status', TugasMahasiswaModel::STATUS_BELUM)
                ->count();
            $sudahDikumpulkan = TugasMahasiswaModel::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('status', TugasMahasiswaModel::STATUS_SUDAH)
                ->count();
            $totalTugas = TugasMahasiswaModel::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->count();
            // Ambil daftar kelas yang diikuti mahasiswa
            $kelas = KelasMahasiswaModel::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->with('kelas') // Memuat relasi kelas
                ->get();
            // Ambil jumlah kelas yang diikuti mahasiswa dari KelasMahasiswaModel
            $totalKelas = KelasMahasiswaModel::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->count();


            return view('welcome_mahasiswa', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => $activeMenu,
                'belumDikumpulkan' => $belumDikumpulkan,
                'sudahDikumpulkan' => $sudahDikumpulkan,
                'totalTugas' => $totalTugas,
                'totalKelas' => $totalKelas,
                'kelas' => $kelas
            ]);
        } elseif (Auth::guard('dosen')->check()) {
            // Jika user adalah dosen, tampilkan view dosen
            return view('welcome_dosen', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
        }

        // Jika tidak ada role yang dikenali, arahkan ke login
        return redirect()->route('login');
    }
}
