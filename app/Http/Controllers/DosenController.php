<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list' => ['Home', 'Dosen'],
        ];

        $page = (object) [
            'title' => 'Daftar Dosen yang terdaftar dalam sistem',
        ];

        $activeMenu = 'dosen';
        $dosen = DosenModel::all();

        return view('dosen.index', ['breadcrumb' => $breadcrumb,  'page' => $page, 'activeMenu' => $activeMenu, 'dosen' => $dosen]);
    }
    // Ambil data dosen dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $dosens = DosenModel::select('id_dosen', 'nip', 'nama');

        //Filter data berdasarkan id_dosen
        if ($request->id_dosen) {
            $dosens->where('id_dosen', $request->id_dosen);
        }

        return DataTables::of($dosens)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($dosen) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen .
                    '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nip'      => 'required|string|max:20|unique:dosen,nip',
                'nama'     => 'required|string|max:255',
                'username' => 'required|string|max:20'
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

            // Tambahkan password default 12345
            $data = $request->all();
            $data['password'] = Hash::make('12345');

            DosenModel::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function edit(string $id)
    {
        $dosens = DosenModel::find($id);
        return view('dosen.edit', ['dosen' => $dosens]);
    }

    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nip'   => 'required|string|max:20|unique:dosen,nip,' . $id . ',id_dosen',
                'nama'  => 'required|string|max:255',
                'username' => 'required|string|max:20',
            ];

            // Password hanya divalidasi jika diisi
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:5|max:20';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            // Cari data dosen berdasarkan id
            $check = DosenModel::find($id);
            if ($check) {
                // Ambil data dari request dan update
                $data = $request->only(['nip', 'nama', 'username']);  // Ambil hanya field yang diperlukan

                // Jika password diisi, update password-nya juga
                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $check->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data Dosen berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Dosen tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm(string $id)
    {
        $dosens = DosenModel::find($id);
        return view('dosen.confirm', ['dosen' => $dosens]);
    }

    public function delete(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $dosen = DosenModel::find($id);

            if ($dosen) {
                $dosen->delete();
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
        $dosens = DosenModel::find($id);

        return view('dosen.show', ['dosen' => $dosens]);
    }
}
