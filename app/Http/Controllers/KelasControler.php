<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Yajra\DataTables\Facades\DataTables;

class KelasControler extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kelas',
            'list' => ['Home', 'Kelas'],
        ];

        $page = (object) [
            'title' => 'Daftar Kelas yang terdaftar dalam sistem',
        ];

        $activeMenu = 'kelas';
        $kelas = KelasModel::all();

        return view('kelas.index', ['breadcrumb' => $breadcrumb,  'page' => $page, 'activeMenu' => $activeMenu, 'kelas' => $kelas]);
    }
    // Ambil data kelas dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kelass = KelasModel::select('id_kelas', 'id_dosen', 'nama_kelas');

        //Filter data berdasarkan id_kelas
        if ($request->id_kelas) {
            $kelass->where('id_kelas', $request->id_kelas);
        }

        return DataTables::of($kelass)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('nama', function ($kelas) {
                return $kelas->dosen ? $kelas->dosen->nama : '-';
            })
            ->addColumn('aksi', function ($kelas) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $page = (object)['title' => 'Tambah Kelas'];
        $mahasiswa = MahasiswaModel::all();

        return view('kelas.create', compact('page', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'   => 'required|string|max:255',
            'mahasiswa_id' => 'array|nullable',
        ]);

        $kelas = KelasModel::create([
            'id_dosen' => auth()->user()->id ?? 1,
            'nama_kelas' => $request->nama_kelas,
        ]);

        foreach ($request->mahasiswa_id ?? [] as $id_mhs) {
            \App\Models\KelasMahasiswaModel::create([
                'id_kelas' => $kelas->id_kelas,
                'id_mahasiswa' => $id_mhs,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil ditambahkan.'
        ]);
    }
}
