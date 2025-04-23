<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use App\Models\TugasModel;
use Illuminate\Http\Request;
use App\Models\TugasMahasiswaModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tugas',
            'list' => ['Home', 'Tugas'],
        ];

        $page = (object) [
            'title' => 'Daftar Tugas yang terdaftar dalam sistem',
        ];

        $activeMenu = 'tugas';
        $tugas = TugasModel::all();

        return view('tugas.index', ['breadcrumb' => $breadcrumb,  'page' => $page, 'activeMenu' => $activeMenu, 'tugas' => $tugas]);
    }
    // Ambil data tugas dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $tugas = TugasModel::all();

        //Filter data berdasarkan id_tugas
        if ($request->id_tugas) {
            $tugas->where('id_tugas', $request->id_tugas);
        }

        return DataTables::of($tugas)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tugas) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/tugas/' . $tugas->id_tugas .
                    '/edit') . '\')" class="btn btn-warning btn-sm">E</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tugas/' . $tugas->id_tugas .
                    '/delete') . '\')" class="btn btn-danger btn-sm">H</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $page = (object)['title' => 'Tambah Tugas'];
        $kelas = KelasModel::all(); // Ambil data kelas untuk dropdown

        return view('tugas.create', compact('page', 'kelas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_kelas'          => 'required|exists:kelas,id_kelas',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'tanggal_diberikan' => 'required|date',
            'deadline'          => 'required|date|after_or_equal:tanggal_diberikan',
        ]);

        // Buat tugas baru
        $tugas = TugasModel::create([
            'id_kelas'          => $request->id_kelas,
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'tanggal_diberikan' => $request->tanggal_diberikan,
            'deadline'          => $request->deadline,
        ]);

        // Ambil semua mahasiswa dari kelas yang dipilih
        $kelas = KelasModel::with('mahasiswas')->findOrFail($request->id_kelas);

        // Assign tugas ke setiap mahasiswa dengan status default
        foreach ($kelas->mahasiswas as $mahasiswa) {
            TugasMahasiswaModel::create([
                'id_tugas' => $tugas->id_tugas,
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'status' => TugasMahasiswaModel::STATUS_BELUM
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tugas berhasil ditambahkan.'
        ]);
    }

    public function edit(string $id)
    {
        $tugas = TugasModel::find($id);
        $kelas = KelasModel::all();

        if (!$tugas) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tugas tidak ditemukan',
            ]);
        }

        return view('tugas.edit', ['tugas' => $tugas, 'kelas' => $kelas]);
    }

    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'id_kelas'          => 'required|exists:kelas,id_kelas',
                'judul'             => 'required|string|max:255',
                'deskripsi'         => 'nullable|string',
                'tanggal_diberikan' => 'required|date',
                'deadline'          => 'required|date|after_or_equal:tanggal_diberikan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $tugas = TugasModel::find($id);
            if (!$tugas) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tugas tidak ditemukan',
                ]);
            }

            $tugas->update($request->only('id_kelas', 'judul', 'deskripsi', 'tanggal_diberikan', 'deadline'));

            return response()->json([
                'status' => true,
                'message' => 'Data Tugas berhasil diubah',
            ]);
        }
        return redirect('/');
    }

    public function confirm(string $id)
    {
        $tugas = TugasModel::with('kelas')->find($id);
        return view('tugas.confirm', ['tugas' => $tugas]);
    }

    public function delete(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $tugas = TugasModel::find($id);

            if ($tugas) {
                // Hapus relasi tugas_mahasiswa terlebih dahulu
                \App\Models\TugasMahasiswaModel::where('id_tugas', $id)->delete();
                $tugas->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Tugas berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tugas tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
