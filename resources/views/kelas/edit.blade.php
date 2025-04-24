@empty($kelas)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
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
    <form action="{{ url('/kelas/' . $kelas->id_kelas . '/update') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input value="{{ $kelas->nama_kelas }}" type="text" name="nama_kelas" id="nama_kelas"
                            class="form-control" required>
                        <small id="error-nama_kelas" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Dosen Pengampu</label>
                        <input value="{{ $kelas->dosen->nama }}" type="text" class="form-control" disabled>
                        <input type="hidden" name="id_dosen" value="{{ $kelas->dosen->id_dosen }}">
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Daftar Mahasiswa</label>
                        <div>
                            @foreach ($mahasiswa as $mhs)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mahasiswa_id[]"
                                        value="{{ $mhs->id_mahasiswa }}" @if ($kelas->mahasiswas->contains('id_mahasiswa', $mhs->id_mahasiswa)) checked @endif>
                                    <label class="form-check-label">
                                        {{ $mhs->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $('.close, .btn-warning').on('click', function() {
            $('#myModal').modal('hide');
        });
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    nama_kelas: {
                        required: true,
                        minlength: 5,
                        maxlength: 100
                    },
                    'mahasiswa_id[]': {
                        required: true,
                        minlength: 1
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                swal("Berhasil", response.message, {
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-success"
                                        }
                                    }
                                });
                                dataKelas.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                swal("Terjadi Kesalahan", response.message, {
                                    icon: "error",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-danger"
                                        }
                                    }
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
