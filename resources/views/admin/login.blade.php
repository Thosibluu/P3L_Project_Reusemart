<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ccf5d3;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            margin: auto;
            padding: 3rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }
        @keyframes fadeSlideIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-animate {
            animation: fadeSlideIn 0.6s ease-out;
        }
    </style>
</head>
<body style="height: 70vh;">
    <a href="/" class="d-flex justify-content-center align-items-center fw-bold fs-3" style="color: #00aa5b; height: 10vh; text-decoration: none;"><i class="bi bi-cart-check"></i>Reusemart</a>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="form-container">
            <h2 class="mb-4 text-center">Login Pegawai</h2>
            <form id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="id_pegawai" class="form-label">ID Pegawai</label>
                    <input type="text" class="form-control" id="id_pegawai" name="id_pegawai" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
                <div class="mb-3 text-end mt-3">
                    <button type="button" class="btn btn-link text-success p-0" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Reset Password</button>
                </div>
            </form>
            <div class="alert alert-danger mt-3 d-none" id="loginError"></div>
            <div class="alert alert-success mt-3 d-none" id="loginSuccess"></div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form id="resetPasswordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="reset_id_pegawai" class="form-label">ID Pegawai</label>
                            <input type="text" class="form-control" id="reset_id_pegawai" name="reset_id_pegawai" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Reset Password ke Tanggal Lahir</button>
                    </form>
                    <div class="alert alert-danger mt-3 d-none" id="resetError"></div>
                    <div class="alert alert-success mt-3 d-none" id="resetSuccess"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const id_pegawai = $('#id_pegawai').val();
                const password = $('#password').val();

                $.ajax({
                    url: '/api/pegawai-login',
                    method: 'POST',
                    data: JSON.stringify({ id_pegawai, password }),
                    contentType: 'application/json',
                    success: function(data) {
                        const successAlert = $('#loginSuccess');
                        successAlert.text(data.message || 'Login berhasil!').removeClass('d-none').addClass('alert-animate');
                        $('#loginError').addClass('d-none');

                        if (data.success && data.redirect) {
                            if (data.token) {
                                localStorage.setItem('access_token', data.token);
                                console.log('Token saved:', data.token);
                            }
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        const errorAlert = $('#loginError');
                        errorAlert.text(xhr.responseJSON?.message || 'Login gagal').removeClass('d-none').addClass('alert-animate');
                        $('#loginSuccess').addClass('d-none');
                    }
                });
            });

            $('#resetPasswordForm').on('submit', function(e) {
                e.preventDefault();

                const id_pegawai = $('#reset_id_pegawai').val();

                $.ajax({
                    url: '/api/reset-pegawai-password',
                    method: 'POST',
                    data: JSON.stringify({ id_pegawai }),
                    contentType: 'application/json',
                    success: function(data) {
                        const successAlert = $('#resetSuccess');
                        successAlert.text(data.message || 'Password berhasil direset ke tanggal lahir!').removeClass('d-none').addClass('alert-animate');
                        $('#resetError').addClass('d-none');
                    },
                    error: function(xhr) {
                        const errorAlert = $('#resetError');
                        errorAlert.text(xhr.responseJSON?.message || 'Gagal mereset password').removeClass('d-none').addClass('alert-animate');
                        $('#resetSuccess').addClass('d-none');
                    }
                });
            });
        });
    </script>
</body>
</html>