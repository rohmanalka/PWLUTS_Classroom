<form action="{{ url('/tugas/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $kls)
                            <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kelas" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Judul Tugas</label>
                    <input type="text" name="judul" class="form-control" required>
                    <small id="error-judul" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Diberikan</label>
                    <input type="date" name="tanggal_diberikan" class="form-control" required>
                    <small id="error-tanggal_diberikan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Deadline</label>
                    <input type="date" name="deadline" class="form-control" required>
                    <small id="error-deadline" class="error-text form-text text-danger"></small>
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
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                id_kelas: {
                    required: true
                },
                judul: {
                    required: true,
                    minlength: 3,
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
                            swal("Berhasil", response.message, "success");
                            $('#table_tugas').DataTable().ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            swal("Gagal", response.message, "error");
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
