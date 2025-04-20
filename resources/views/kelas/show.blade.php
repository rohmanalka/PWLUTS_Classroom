@empty($kelas)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kelas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi Data Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi</h5>
                    Berikut adalah detail data kelas:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Kelas :</th>
                        <td class="col-9">{{ $kelas->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Dosen Pengampu :</th>
                        <td class="col-9">{{ $kelas->dosen->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Daftar Mahasiswa :</th>
                        <td class="col-9">
                            <ul>
                                @foreach ($kelas->mahasiswas as $mahasiswa)
                                    <li>{{ $mahasiswa->nama }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
    <script>
        $('.close, .btn-secondary').on('click', function() {
            $('#myModal').modal('hide');
        });
    </script>
@endempty
