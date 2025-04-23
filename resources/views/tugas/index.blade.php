@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('tugas/create') }}')" class="btn btn-sm btn-success mt-1">
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
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select name="id_tugas" id="id_tugas" class="form-control" required>
                            <option value="">- Semua -</option>
                            @foreach ($tugas as $item)
                                <option value="{{ $item->id_tugas }}">{{ $item->judul }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Nama Tugas</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm"
        id="table_tugas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Diberikan</th>
                    <th>Tenggat</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
</div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        $(document).ready(function() {
            var dataTugas = $('#table_tugas').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('tugas/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.tugas_id = $('#tugas_id').val();
                    }
                },
                columns: [
                    {
                        // nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "judul",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "deskripsi",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "tanggal_diberikan",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "deadline",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#id_tugas').on('change', function() {
                dataTugas.ajax.reload();
            });
        });
    </script>
@endpush