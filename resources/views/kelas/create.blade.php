<form action="{{ url('/kelas/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" required>
                    <small id="error-nama_kelas" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Mahasiswa</label>
                    <div class="row">
                        @foreach ($mahasiswa as $mhs)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mahasiswa_id[]"
                                        value="{{ $mhs->id_mahasiswa }}" id="mhs_{{ $mhs->id_mahasiswa }}">
                                    <label class="form-check-label" for="mhs_{{ $mhs->id_mahasiswa }}">
                                        {{ $mhs->nama }} ({{ $mhs->nim }})
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.close, .btn-secondary').on('click', function() {
        $('#myModal').modal('hide');
    });

    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                nama_kelas: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
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
                            $('#table_kelas').DataTable().ajax.reload();
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
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
