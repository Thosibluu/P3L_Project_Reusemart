<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Add CSRF token meta tag -->
    <title>Login</title>
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
            <h2 class="mb-4 text-center">Login</h2>
            <form id="loginForm">
                @csrf <!-- Add CSRF token field -->
                <div class="mb-3">
                    <label for="role" class="form-label">Login sebagai</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="pembeli">Pembeli</option>
                        <option value="penitip">Penitip</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="emailOrId" class="form-label">Email / ID</label>
                    <input type="text" class="form-control" id="emailOrId" name="emailOrId" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 text-end">
                    <a href="{{ route('reset-password') }}" class="text-decoration-none text-success">Lupa password?</a>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
            <div class="alert alert-danger mt-3 d-none" id="loginError"></div>
            <div class="alert alert-success mt-3 d-none" id="loginSuccess"></div>
            <div class="text-center mt-3">
                <span>Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none text-success" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Sebagai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <a href="/register/pembeli" class="btn btn-success w-100 mb-3">Pembeli</a>
                    <a href="/register/penitip" class="btn btn-success w-100 mb-3">Penitip</a>
                    <a href="/register/organisasi" class="btn btn-success w-100 mb-3">Organisasi</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            
        });

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const role = $('#role').val();
            const emailOrId = $('#emailOrId').val();
            const password = $('#password').val();

            let endpoint = '';
            let payload = { password };

            if (role === 'pembeli') {
                endpoint = '/api/pembeli-login';
                payload.alamat_email = emailOrId;
            } else if (role === 'penitip') {
                endpoint = '/api/penitip-login';
                payload.id_penitip = emailOrId;
            } else {
                $('#loginError').text('Silakan pilih role.').removeClass('d-none');
                return;
            }

            $.ajax({
                url: endpoint,
                method: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                success: function(data) {
                    localStorage.setItem('access_token', data.access_token);
                    localStorage.setItem('role', data.role);
                    localStorage.setItem('nama', data.user.nama_pembeli || data.user.nama_penitip);
                    localStorage.setItem('profile_image', data.user.profile_image || '{{ asset('img/img1.jpg') }}');
                    console.log('Token saved:', localStorage.getItem('access_token'));

                    const successAlert = $('#loginSuccess');
                    successAlert.text(data.message || 'Login berhasil!').removeClass('d-none').addClass('alert-animate');
                    $('#loginError').addClass('d-none');

                    setTimeout(() => {
                        window.location.href = '/home';
                    }, 1500);
                },
                error: function(xhr) {
                    const errorAlert = $('#loginError');
                    errorAlert.text(xhr.responseJSON?.message || 'Login gagal').removeClass('d-none').addClass('alert-animate');
                    $('#loginSuccess').addClass('d-none');
                }
            });
        });
    </script>
</body>
</html>