@empty($tugas)
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
                <a href="{{ url('/tugas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/tugas/' . $tugas->id_tugas . '/update') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}"
                                    {{ $k->id_kelas == $tugas->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-id_kelas" class="form-text text-danger error-text"></small>
                    </div>
                    <div class="form-group">
                        <label>Judul</label>
                        <input value="{{ $tugas->judul }}" type="text" name="judul" id="judul" class="form-control"
                            required>
                        <small id="error-judul" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>deskripsi</label>
                        <input value="{{ $tugas->deskripsi }}" type="text" name="deskripsi" id="deskripsi"
                            class="form-control" required>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Diberikan</label>
                        <input value="{{ $tugas->tanggal_diberikan }}" type="date" name="tanggal_diberikan"
                            class="form-control" required>
                        <small id="error-tanggal_diberikan" class="form-text text-danger error-text"></small>
                    </div>
                    <div class="form-group">
                        <label>Deadline</label>
                        <input value="{{ $tugas->deadline }}" type="date" name="deadline" class="form-control" required>
                        <small id="error-deadline" class="form-text text-danger error-text"></small>
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
                    id_kelas: {
                        required: true
                    },
                    judul: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    },
                    tanggal_diberikan: {
                        required: true,
                        date: true
                    },
                    deadline: {
                        required: true,
                        date: true
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
                                dataTugas.ajax.reload();
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
