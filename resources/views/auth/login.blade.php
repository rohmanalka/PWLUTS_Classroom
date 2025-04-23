<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Pengguna</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts and Icons -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["{{ asset('kaiadmin/assets/css/fonts.min.css') }}"],
            },
            active: () => sessionStorage.fonts = true,
        });
    </script>

    <!-- KaiAdmin CSS -->
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}">
</head>

<body class="login bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Login Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <form id="form-login" action="{{ url('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="input-group">
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Username">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                                <small id="error-username" class="error-text text-danger"></small>
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                                <small id="error-password" class="error-text text-danger"></small>
                            </div>

                            <div class="form-group mt-3">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="mahasiswa">Mahasiswa</option>
                                    <option value="dosen">Dosen</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery.validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/kaiadmin.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#form-login').validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: $('#form-login').attr('action'),
                        method: 'POST',
                        data: $('#form-login').serialize(),
                        success: function(response) {
                            console.log(response);
                            if (response.status) {
                                swal({
                                    title: "Berhasil!",
                                    text: response.message,
                                    icon: "success",
                                    button: "OK"
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField || {}, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });

                                swal({
                                    title: "Gagal!",
                                    text: response.message,
                                    icon: "error",
                                    button: "OK"
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
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
</body>

</html>
