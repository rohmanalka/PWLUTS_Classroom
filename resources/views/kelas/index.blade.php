@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('kelas/create') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kelas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kelas</th>
                        <th>Nama Dosen</th>
                        <th>Jumlah Mahasiswa</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        var dataKelas;
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        $(document).on('click', '.lihat-mahasiswa', function(e) {
            e.preventDefault();
            let daftar = $(this).data('mahasiswa');

            swal({
                title: "Daftar Mahasiswa",
                text: daftar.split(', ').join('\n'),
                icon: "info",
                buttons: {
                    confirm: {
                        text: "Tutup",
                        className: "btn btn-primary"
                    }
                }
            });
        });
        $(document).ready(function() {
             dataKelas = $('#table_kelas').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('kelas/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.kelas_id = $('#kelas_id').val();
                    }
                },
                columns: [{
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "nama_kelas",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "dosen.nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "jumlah_mahasiswa",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }]
            });

            $('#id_kelas').on('change', function() {
                dataKelas.ajax.reload();
            });
        });
    </script>
@endpush
