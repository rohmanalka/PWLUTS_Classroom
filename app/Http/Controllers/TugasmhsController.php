<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Models\TugasmhsModel;
use App\Models\TugasMahasiswaModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TugasmhsController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tugas Mahasiswa',
            'list' => ['Home', 'Tugasmhs'],
        ];

        $page = (object) [
            'title' => 'Daftar Tugas Mahasiswa',
        ];

        $activeMenu = 'tugasmhs';
        $tugasmhs = TugasMahasiswaModel::all();
        $kelas = KelasModel::all(); // Ambil semua kelas

        return view('tugasmhs.index', ['breadcrumb' => $breadcrumb,  'page' => $page, 'activeMenu' => $activeMenu, 'tugasmhs' => $tugasmhs, 'kelas' => $kelas]);
    }
    // Ambil data tugasmhs dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Cek apakah mahasiswa sedang login
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if (!$mahasiswa) {
            return response()->json([
                'data' => [],
                'error' => 'Unauthorized'
            ]);
        }

        // Ambil data tugas milik mahasiswa login
        $tugasmhs = TugasMahasiswaModel::with(['mahasiswa', 'tugas'])
            ->where('id_mahasiswa', $mahasiswa->id_mahasiswa);

        // Filter berdasarkan kelas (jika dipilih)
        if ($request->filled('id_kelas')) {
            $tugasmhs->whereHas('tugas', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        return DataTables::of($tugasmhs)
            ->addIndexColumn()
            ->make(true);
    }

    public function uploadForm($id)
    {
        $tugas = TugasMahasiswaModel::findOrFail($id);
        return view('tugasmhs.upload', compact('tugas'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'lampiran'    => 'required|file|max:10240', // max 10MB
            'id_tugasmhs' => 'required|exists:tugas_mahasiswa,id_tugasmhs'
        ]);

        $tugasMahasiswa = TugasMahasiswaModel::find($request->id_tugasmhs);

        if ($request->hasFile('lampiran') && $request->file('lampiran')->isValid()) {
            $path = $request->file('lampiran')->store('tugas_mahasiswa', 'public');
            // Update database
            $tugasMahasiswa->update([
                'lampiran' => $path,
                'status' => TugasMahasiswaModel::STATUS_SUDAH
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'File tidak valid.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tugas berhasil diupload.'
        ]);
    }
}
