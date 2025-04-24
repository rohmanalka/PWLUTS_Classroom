@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Pilih Kelas</label>
                        <div class="col-3">
                            <select name="id_kelas" id="id_kelas" class="form-control">
                                <option value="">- Pilih Kelas -</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id_kelas }}">{{ $item->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Kelas</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_tugasmhs">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Deskripsi</th>
                        <th>Tenggat</th>
                        <th>Lampiran</th>
                        <th>Status</th>
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
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        $(document).ready(function() {
            var dataTugasmhs = $('#table_tugasmhs').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('tugasmhs/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.id_kelas = $('#id_kelas').val(); // kirim id_kelas
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false
                    },
                    {
                        data: "tugas.judul",
                        className: ""
                    },
                    {
                        data: "tugas.deskripsi",
                        className: ""
                    },
                    {
                        data: "tugas.deadline",
                        className: ""
                    },
                    {
                        data: "lampiran",
                        className: "",
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="/storage/${data}" target="_blank">Lihat File</a>`;
                            } else {
                                return `<button class="btn btn-sm btn-primary" onclick="modalAction('/tugasmhs/upload/${row.id_tugasmhs}')">Upload</button>`;
                            }
                        }
                    },
                    {
                        data: "status",
                        className: "",
                        render: function(data, type, row) {
                            let statusText = '';
                            let statusColor = '';
                            switch (data) {
                                case 'belum_dikumpulkan':
                                    statusText = 'Ditugaskan';
                                    statusColor = 'bg-danger'; // Merah
                                    break;
                                case 'sudah_dikumpulkan':
                                    statusText = 'Dikumpulkan';
                                    statusColor = 'bg-success'; // Hijau
                                    break;
                                case 'telat':
                                    statusText = 'Terlambat';
                                    statusColor = 'bg-warning'; // Oranye
                                    break;
                                default:
                                    statusText = 'Tidak Dikenal';
                                    statusColor =
                                    'bg-secondary'; // Default warna jika tidak ditemukan
                            }
                            return `<span class="badge ${statusColor}">${statusText}</span>`;
                        }
                    }
                ],
                deferLoading: 0
            });

            $('#id_kelas').on('change', function() {
                dataTugasmhs.ajax.reload(); // reload DataTables begitu kelas dipilih
            });
        });
    </script>
@endpush
