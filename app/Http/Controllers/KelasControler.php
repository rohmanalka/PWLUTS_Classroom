<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
        $kelass = KelasModel::with(['dosen', 'mahasiswas'])
            ->select('id_kelas', 'id_dosen', 'nama_kelas');

        //Filter data berdasarkan id_kelas
        if ($request->id_kelas) {
            $kelass->where('id_kelas', $request->id_kelas);
        }

        return DataTables::of($kelass)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('jumlah_mahasiswa', function ($kelas) {
                // Ambil semua nama mahasiswa dalam kelas tersebut
                $mahasiswaNames = $kelas->mahasiswas->pluck('nama')->toArray();
                // Hitung jumlah mahasiswa yang terdaftar
                $jumlahMahasiswa = $kelas->mahasiswas->count();
                // Gabungkan nama-nama mahasiswa menjadi satu string, dipisahkan koma
                $namaMahasiswaString = implode(', ', $mahasiswaNames);
                // Return tombol link dengan informasi jumlah mahasiswa dan daftar nama mahasiswa
                return '<a href="#" class="lihat-mahasiswa" data-mahasiswa="' . e($namaMahasiswaString) . '">'
                    . $jumlahMahasiswa . ' Mahasiswa</a>';
            })

            ->addColumn('aksi', function ($kelas) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $kelas->id_kelas .
                    '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi', 'jumlah_mahasiswa']) // memberitahu bahwa kolom aksi adalah html
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

    public function edit(string $id)
    {
        // Ambil data kelas berdasarkan id_kelas, beserta dosen dan mahasiswa yang terdaftar
        $kelas = KelasModel::with(['dosen', 'mahasiswas'])->find($id);

        // Pastikan kelas ditemukan
        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Data Kelas tidak ditemukan'
            ]);
        }

        // Ambil semua mahasiswa untuk dropdown pilihan mahasiswa
        $mahasiswa = MahasiswaModel::all();

        return view('kelas.edit', ['kelas' => $kelas, 'mahasiswa' => $mahasiswa]);
    }

    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kelas' => 'required|string|max:255',
                'mahasiswa_id' => 'array|nullable', // Daftar mahasiswa yang dipilih
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Cari kelas berdasarkan id
            $kelas = KelasModel::find($id);
            if (!$kelas) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Kelas tidak ditemukan',
                ]);
            }

            // Update data kelas
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'id_dosen' => auth()->user()->id ?? 1, // Misalnya ID dosen diambil dari pengguna yang login
            ]);

            // Hapus mahasiswa lama yang terhubung dengan kelas ini (opsional, bisa dipilih di logika)
            \App\Models\KelasMahasiswaModel::where('id_kelas', $id)->delete();

            // Tambahkan mahasiswa baru ke kelas
            foreach ($request->mahasiswa_id ?? [] as $id_mhs) {
                \App\Models\KelasMahasiswaModel::create([
                    'id_kelas' => $kelas->id_kelas,
                    'id_mahasiswa' => $id_mhs,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data Kelas berhasil diubah',
            ]);
        }
        return redirect('/');
    }


    public function confirm(string $id)
    {
        $kelas = KelasModel::find($id);

        return view('kelas.confirm', ['kelas' => $kelas]);
    }

    public function delete(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kelas = KelasModel::find($id);

            if ($kelas) {
                // Hapus relasi kelas dengan mahasiswa terlebih dahulu (pivot tabel kelas_mahasiswa)
                \App\Models\KelasMahasiswaModel::where('id_kelas', $id)->delete();
                $kelas->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data kelas berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kelas tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show(string $id)
    {
        $kelas = KelasModel::with(['dosen', 'mahasiswas'])->find($id);

        return view('kelas.show', ['kelas' => $kelas]);
    }
}
