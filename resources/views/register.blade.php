<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun • Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00b359;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #b2dfdb 100%);
            min-height: 100vh;
            background-attachment: fixed;
        }
        .card-register {
            max-width: 480px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            background: white;
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary), #00aa5b);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
        }
        .card-header p {
            opacity: 0.9;
            margin-top: 0.5rem;
            font-size: 1rem;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 179, 89, 0.15);
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 1.2rem;
        }
        .strength-meter {
            height: 10px;
            border-radius: 5px;
            background: #eee;
            overflow: hidden;
            margin-top: 10px;
        }
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.4s ease;
            border-radius: 5px;
        }
        .strength-text {
            font-size: 0.9rem;
            margin-top: 8px;
            font-weight: 600;
        }
        .check-item {
            font-size: 0.9rem;
            color: #666;
            transition: all 0.3s;
        }
        .check-item.valid {
            color: var(--success);
        }
        .check-item i {
            margin-right: 8px;
        }
        .btn-register {
            background: linear-gradient(135deg, var(--primary), #00aa5b);
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 179, 89, 0.3);
        }
        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .login-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.95rem;
        }
        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

<div class="card-register">
    <div class="card-header">
        <h2>Reusemart</h2>
        <p>Bergabunglah dan mulai perjalanan hijau Anda!</p>
    </div>
    <div class="card-body p-5">
        <form id="registerForm" novalidate>
            @if (request()->is('register/pembeli'))
                <input type="hidden" id="role" value="pembeli">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_pembeli" placeholder="Masukkan nama Anda" required>
                    <div class="invalid-feedback" id="error-nama_pembeli"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="alamat_email" placeholder="contoh@email.com" required>
                    <div class="invalid-feedback" id="error-alamat_email"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" class="form-control" id="nomor_telepon_pembeli" placeholder="081234567890" required>
                    <div class="invalid-feedback" id="error-nomor_telepon_pembeli"></div>
                </div>

            @elseif (request()->is('register/penitip'))
                <input type="hidden" id="role" value="penitip">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Penitip</label>
                    <input type="text" class="form-control" id="nama_penitip" placeholder="Nama Anda / Usaha" required>
                    <div class="invalid-feedback" id="error-nama_penitip"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat Rumah</label>
                    <input type="text" class="form-control" id="alamat_penitip" placeholder="Jl. Contoh No.123" required>
                    <div class="invalid-feedback" id="error-alamat_penitip"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" class="form-control" id="nomor_telepon_penitip" placeholder="081234567890" required>
                    <div class="invalid-feedback" id="error-nomor_telepon_penitip"></div>
                </div>

            @elseif (request()->is('register/organisasi'))
                <input type="hidden" id="role" value="organisasi">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Organisasi</label>
                    <input type="text" class="form-control" id="nama_organisasi" placeholder="Nama lengkap organisasi" required>
                    <div class="invalid-feedback" id="error-nama_organisasi"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat Organisasi</label>
                    <input type="text" class="form-control" id="alamat_organisasi" placeholder="Alamat lengkap" required>
                    <div class="invalid-feedback" id="error-alamat_organisasi"></div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" class="form-control" id="nomor_telepon_organisasi" placeholder="081234567890" required>
                    <div class="invalid-feedback" id="error-nomor_telepon_organisasi"></div>
                </div>
            @endif

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="password-container">
                    <input type="password" class="form-control" id="password" placeholder="Buat password yang kuat" required>
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>
                
                <div class="strength-meter mt-3">
                    <div id="strengthBar" class="strength-bar"></div>
                </div>
                <div class="strength-text" id="strengthText">Masukkan password</div>

                <div class="mt-3">
                    <div class="check-item" id="check-length">Minimal 8 karakter</div>
                    <div class="check-item" id="check-letter">Huruf besar & kecil</div>
                    <div class="check-item" id="check-number">Mengandung angka</div>
                    <div class="check-item" id="check-special">Mengandung simbol (!@#$%^&*)</div>
                </div>
                <div class="invalid-feedback" id="error-password"></div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-5">
                <label class="form-label fw-semibold">Konfirmasi Password</label>
                <div class="password-container">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Ketik ulang password" required>
                    <i class="bi bi-eye-slash toggle-password" id="toggleConfirm"></i>
                </div>
                <div class="text-danger mt-2" id="confirmError" style="display:none;"></div>
            </div>

            <button type="submit" class="btn btn-register text-white w-100" id="submitBtn">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                Daftar Sekarang
            </button>

            <div class="login-link">
                Sudah punya akun? <a href="/login">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999">
    <div id="successToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert">
        <div class="d-flex">
            <div class="toast-body fs-5 fw-bold">
                Registrasi berhasil! Mengarahkan ke login...
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-bg-danger border-0 shadow-lg" role="alert">
        <div class="d-flex">
            <div class="toast-body fs-5 fw-bold" id="errorMessage">Terjadi kesalahan</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // === TOGGLE PASSWORD VISIBILITY ===
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const type = pwd.type === 'password' ? 'text' : 'password';
        pwd.type = type;
        this.classList.toggle('bi-eye', type === 'text');
        this.classList.toggle('bi-eye-slash', type === 'password');
    });

    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const pwd = document.getElementById('confirmPassword');
        const type = pwd.type === 'password' ? 'text' : 'password';
        pwd.type = type;
        this.classList.toggle('bi-eye', type === 'text');
        this.classList.toggle('bi-eye-slash', type === 'password');
    });

    // === PASSWORD STRENGTH METER ===
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const checks = {
        length: document.getElementById('check-length'),
        letter: document.getElementById('check-letter'),
        number: document.getElementById('check-number'),
        special: document.getElementById('check-special')
    };

    passwordInput.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        Object.values(checks).forEach(c => {
            c.innerHTML = c.innerHTML.replace(/check-circle-fill|x-circle/g, 'x-circle');
            c.classList.remove('valid');
        });

        if (val.length >= 8) { score++; checks.length.classList.add('valid'); checks.length.innerHTML = 'Minimal 8 karakter'; }
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) { score++; checks.letter.classList.add('valid'); checks.letter.innerHTML = 'Huruf besar & kecil'; }
        if (/\d/.test(val)) { score++; checks.number.classList.add('valid'); checks.number.innerHTML = 'Mengandung angka'; }
        if (/[^a-zA-Z0-9]/.test(val)) { score++; checks.special.classList.add('valid'); checks.special.innerHTML = 'Mengandung simbol'; }

        if (score === 4) { strengthBar.style.width = '100%'; strengthBar.style.background = '#28a745'; strengthText.textContent = 'Kuat sekali!'; strengthText.style.color = '#28a745'; }
        else if (score === 3) { strengthBar.style.width = '75%'; strengthBar.style.background = '#ffc107'; strengthText.textContent = 'Cukup kuat'; strengthText.style.color = '#ffc107'; }
        else if (score === 2) { strengthBar.style.width = '50%'; strengthBar.style.background = '#fd7e14'; strengthText.textContent = 'Sedang'; strengthText.style.color = '#fd7e14'; }
        else if (score === 1) { strengthBar.style.width = '25%'; strengthBar.style.background = '#dc3545'; strengthText.textContent = 'Lemah'; strengthText.style.color = '#dc3545'; }
        else { strengthBar.style.width = '0%'; strengthText.textContent = 'Masukkan password'; strengthText.style.color = '#666'; }
    });

    // === SUBMIT FORM – SESUAI ENDPOINT KAMU ===
    document.getElementById("registerForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const role = document.getElementById("role").value;
        const password = document.getElementById("password").value;
        let payload = { password };
        let endpoint = "";

        // Reset semua error dulu
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        if (role === "pembeli") {
            const nama = document.getElementById("nama_pembeli").value;
            const email = document.getElementById("alamat_email").value;
            const notelp = document.getElementById("nomor_telepon_pembeli").value;
            payload.nama_pembeli = nama;
            payload.alamat_email = email;
            payload.nomor_telepon_pembeli = notelp;
            endpoint = "/api/pembeli-register";

        } else if (role === "penitip") {
            const nama = document.getElementById("nama_penitip").value;
            const alamat = document.getElementById("alamat_penitip").value;
            const notelp = document.getElementById("nomor_telepon_penitip").value;
            payload.nama_penitip = nama;
            payload.alamat_penitip = alamat;
            payload.nomor_telepon_penitip = notelp;  // <-- SESUAI DENGAN CONTROLLER
            endpoint = "/api/penitip-register";

        } else if (role === "organisasi") {
            const nama = document.getElementById("nama_organisasi").value;
            const alamat = document.getElementById("alamat_organisasi").value;
            const notelp = document.getElementById("nomor_telepon_organisasi").value;
            payload.nama_organisasi = nama;
            payload.alamat_organisasi = alamat;
            payload.nomor_telepon_organisasi = notelp;  // <-- SESUAI DENGAN CONTROLLER
            endpoint = "/api/organisasi-register";

        } else {
            alert("Peran tidak dikenali.");
            return;
        }

        fetch(endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''  // Tambahan ini saja!
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const data = await response.json();

            if (response.ok || response.status === 201) {
                const toastElement = document.getElementById("successToast");
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                setTimeout(() => {
                    window.location.href = "/login";
                }, 2500);

            } else if (response.status === 422) {
                // Tampilkan error validasi
                Object.keys(data.errors || data).forEach(field => {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById('error-' + field);
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = data.errors?.[field]?.[0] || data[field][0];
                    }
                });

            } else {
                alert(data.message || "Terjadi kesalahan. Silakan coba lagi.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Gagal mengirim data. Periksa koneksi internet Anda.");
        });
    });
</script>
</body>
</html>