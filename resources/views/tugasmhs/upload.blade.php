<form id="formUploadTugas" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id_tugasmhs" value="{{ $tugas->id_tugasmhs }}">
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="lampiran">Pilih File Tugas</label>
                    <input type="file" name="lampiran" class="form-control" required>
                    <small id="error-lampiran" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.close, .btn-secondary').on('click', function() {
        $('#myModal').modal('hide');
    });

    $('#formUploadTugas').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "/tugasmhs/upload",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.status) {
                    $('#myModal').modal('hide');
                    swal("Berhasil", res.message, {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: "btn btn-success"
                            }
                        }
                    });
                    $('#table_tugasmhs').DataTable().ajax.reload(null, false);
                } else {
                    $('.error-text').text('');
                    if (res.msgField) {
                        $.each(res.msgField, function(key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                    }
                    swal("Gagal", res.message, {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: "btn btn-danger"
                            }
                        }
                    });
                }
            },
            error: function(xhr) {
                swal("Terjadi kesalahan", "Gagal mengirim data.", {
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: "btn btn-danger"
                        }
                    }
                });
            }
        });
    });
</script>
