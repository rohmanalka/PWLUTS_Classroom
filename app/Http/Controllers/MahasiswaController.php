<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Mahasiswa',
            'list' => ['Home', 'Mahasiswa'],
        ];

        $page = (object) [
            'title' => 'Daftar Mahasiswa yang terdaftar dalam sistem',
        ];

        $activeMenu = 'mahasiswa';
        $mahasiswa = MahasiswaModel::all();

        return view('mahasiswa.index', ['breadcrumb' => $breadcrumb,  'page' => $page, 'activeMenu' => $activeMenu, 'mahasiswa' => $mahasiswa]);
    }
    // Ambil data mahasiswa dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $mahasiswas = MahasiswaModel::select('id_mahasiswa', 'nim', 'nama');

        //Filter data berdasarkan id_mahasiswa
        if ($request->id_mahasiswa) {
            $mahasiswas->where('id_mahasiswa', $request->id_mahasiswa);
        }

        return DataTables::of($mahasiswas)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mahasiswa) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mahasiswa->id_mahasiswa .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mahasiswa->id_mahasiswa .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mahasiswa->id_mahasiswa .
                    '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nim'   => 'required|string|max:20|unique:mahasiswa,nim',
                'nama'  => 'required|string|max:255',
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            // Tambahkan username = nim, dan password default 12345
            $data = $request->all();
            $data['username'] = $data['nim'];
            $data['password'] = Hash::make('12345');

            MahasiswaModel::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Data Mahasiswa berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function edit(string $id)
    {
        $mahasiswas = MahasiswaModel::find($id);
        return view('mahasiswa.edit', ['mahasiswa' => $mahasiswas]);
    }

    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nim'   => 'required|string|max:20|unique:mahasiswa,nim,' . $id . ',id_mahasiswa',
                'nama'  => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $check = MahasiswaModel::find($id);
            if ($check) {
                $data = $request->all();
                $data['username'] = $data['nim']; 

                $check->update($data);
                return response()->json([
                    'status' => true,
                    'message' => 'Data Mahasiswa berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Mahasiswa tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm(string $id)
    {
        $mahasiswas = MahasiswaModel::find($id);
        return view('mahasiswa.confirm', ['mahasiswa' => $mahasiswas]);
    }

    public function delete(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $mahasiswa = MahasiswaModel::find($id);

            if ($mahasiswa) {
                $mahasiswa->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show(string $id)
    {
        $mahasiswas = MahasiswaModel::find($id);

        return view('mahasiswa.show', ['mahasiswa' => $mahasiswas]);
    }
}
