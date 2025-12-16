<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Pembeli • Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #00b359; --success: #28a745; --danger: #dc3545; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #e8f5e9 0%, #b2dfdb 100%); min-height: 100vh; background-attachment: fixed; }
        .card-register { max-width: 480px; border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.2); background: white; }
        .card-header { background: linear-gradient(135deg, var(--primary), #00aa5b); color: white; padding: 3rem 2rem; text-align: center; position: relative; }
        .card-header::after { content: ''; position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%); width: 100px; height: 20px; background: white; border-radius: 50%; }
        .card-header h1 { font-size: 2.8rem; font-weight: 700; margin: 0; }
        .card-header p { opacity: 0.9; font-size: 1.1rem; margin-top: 0.5rem; }
        .form-control { border-radius: 16px; padding: 0.9rem 1.2rem; border: 2px solid #e0e0e0; font-size: 1rem; transition: all 0.3s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.25rem rgba(0, 179, 89, 0.15); }
        .input-group-text { border-radius: 16px 0 0 16px; background: #f8f9fa; border: 2px solid #e0e0e0; border-right: none; }
        .password-container { position: relative; }
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 1.3rem; z-index: 10; }
        .strength-meter { height: 10px; border-radius: 5px; background: #eee; overflow: hidden; margin-top: 10px; }
        .strength-bar { height: 100%; width: 0%; transition: all 0.4s ease; border-radius: 5px; }
        .check-item { font-size: 0.9rem; color: #666; transition: all 0.3s; }
        .check-item.valid { color: var(--success); }
        .check-item i { margin-right: 8px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), #00aa5b); border: none; border-radius: 50px; padding: 16px; font-weight: 600; font-size: 1.2rem; box-shadow: 0 8px 20px rgba(0, 179, 89, 0.3); }
        .btn-primary:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0, 179, 89, 0.4); }
        .step { display: none; }
        .step.active { display: block; animation: fadeIn 0.6s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .otp-box { width: 60px; height: 70px; font-size: 2rem; text-align: center; border: 2px solid #ddd; border-radius: 16px; transition: all 0.3s; }
        .otp-box:focus { border-color: var(--primary); box-shadow: 0 0 0 0.25rem rgba(0, 179, 89, 0.15); }
        .timer { font-size: 1.4rem; font-weight: bold; color: var(--primary); }
        .resend-btn { cursor: pointer; color: var(--primary); font-weight: 600; text-decoration: underline; }
        .resend-btn:disabled { color: #999; cursor: not-allowed; text-decoration: none; }
        .back-btn { color: #666; text-decoration: none; font-size: 1rem; }
        .back-btn:hover { color: var(--primary); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

<div class="card-register mx-auto">
    <div class="card-header">
        <h1>Reusemart</h1>
        <p id="stepTitle">Daftar sebagai Pembeli</p>
    </div>

    <div class="card-body p-5 pt-5">

        <!-- STEP 1: Form Registrasi -->
        <div class="step active" id="step1">
            <form id="formStep1">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">Person</span>
                        <input type="text" class="form-control" id="nama_pembeli" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">Envelope</span>
                        <input type="email" class="form-control" id="alamat_email" placeholder="contoh@email.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <div class="input-group">
                        <span class="input-group-text">Phone</span>
                        <input type="text" class="form-control" id="nomor_telepon_pembeli" placeholder="081234567890" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="password-container">
                        <div class="input-group">
                            <span class="input-group-text">Lock</span>
                            <input type="password" class="form-control" id="password" placeholder="Min. 8 karakter" required>
                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                    </div>
                    <div class="strength-meter mt-3"><div id="strengthBar" class="strength-bar"></div></div>
                    <div class="strength-text mt-2" id="strengthText">Masukkan password</div>
                    <div class="mt-3">
                        <div class="check-item" id="check-length">Minimal 8 karakter</div>
                        <div class="check-item" id="check-letter">Huruf besar & kecil</div>
                        <div class="check-item" id="check-number">Mengandung angka</div>
                        <div class="check-item" id="check-special">Mengandung simbol</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <div class="password-container">
                        <div class="input-group">
                            <span class="input-group-text">Lock</span>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Ketik ulang password" required>
                            <i class="bi bi-eye-slash toggle-password" id="toggleConfirm"></i>
                        </div>
                    </div>
                    <div class="text-danger mt-2" id="passError" style="display:none;"></div>
                </div>

                <button type="submit" class="btn btn-primary text-white w-100">
                    Kirim Kode OTP ke Email
                </button>
            </form>
        </div>

        <!-- STEP 2: Verifikasi OTP -->
        <div class="step" id="step2">
            <div class="text-center mb-5">
                <i class="bi bi-envelope-check-fill fs-1 text-success"></i>
                <h4 class="mt-4 fw-bold">Verifikasi Email Anda</h4>
                <p class="text-muted">Kami telah mengirimkan kode 6 digit ke:</p>
                <h5 class="fw-bold text-primary" id="emailDisplay"></h5>
            </div>

            <form id="formOtp" class="text-center">
                <div class="d-flex justify-content-center gap-3 mb-5">
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                    <input type="text" maxlength="1" class="form-control otp-box" required>
                </div>

                <div class="mb-4">
                    <div class="timer" id="timer">05:00</div>
                </div>

                <button type="submit" class="btn btn-primary text-white w-100 mb-3">
                    Verifikasi & Daftar
                </button>

                <div class="text-center mb-3">
                    <small class="text-muted">Kode tidak sampai? </small>
                    <span class="resend-btn" id="resendBtn">Kirim Ulang</span>
                </div>

                <div class="text-center">
                    <a href="#" id="backToForm" class="back-btn">Kembali ke Form</a>
                </div>
            </form>
        </div>

        <div class="alert alert-danger mt-4 d-none" id="alertError"></div>
        <div class="alert alert-success mt-4 d-none" id="alertSuccess"></div>
    </div>
</div>

<!-- Toast Sukses -->
<div class="toast-container position-fixed bottom-0 end-0 p-4">
    <div id="successToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert">
        <div class="d-flex">
            <div class="toast-body fs-5 fw-bold">
                Registrasi berhasil! Mengarahkan ke login...
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // CARA PALING AMAN & 100% JALAN DI LARAVEL 11
    function apiPost(url, data = {}) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        Object.keys(data).forEach(key => formData.append(key, data[key]));

        return fetch(url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    let countdown;
    const data = { nama_pembeli: '', alamat_email: '', nomor_telepon_pembeli: '', password: '' };

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirm = document.getElementById('toggleConfirm');

    togglePassword.onclick = () => toggleEye(password, togglePassword);
    toggleConfirm.onclick = () => toggleEye(confirmPassword, toggleConfirm);
    function toggleEye(input, icon) {
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    }

    // Password Strength
    password.addEventListener('input', () => {
        const val = password.value;
        let score = 0;
        document.querySelectorAll('.check-item').forEach(el => {
            el.innerHTML = el.textContent.trim();
            el.classList.remove('valid');
        });

        if (val.length >= 8) { score++; document.getElementById('check-length').classList.add('valid'); document.getElementById('check-length').innerHTML = 'Minimal 8 karakter'; }
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) { score++; document.getElementById('check-letter').classList.add('valid'); document.getElementById('check-letter').innerHTML = 'Huruf besar & kecil'; }
        if (/\d/.test(val)) { score++; document.getElementById('check-number').classList.add('valid'); document.getElementById('check-number').innerHTML = 'Mengandung angka'; }
        if (/[^a-zA-Z0-9]/.test(val)) { score++; document.getElementById('check-special').classList.add('valid'); document.getElementById('check-special').innerHTML = 'Mengandung simbol'; }

        document.getElementById('strengthBar').style.width = `${score * 25}%`;
        document.getElementById('strengthBar').style.background = score === 4 ? '#28a745' : score >= 2 ? '#ffc107' : '#dc3545';
        document.getElementById('strengthText').textContent = score === 4 ? 'Kuat sekali!' : score === 3 ? 'Cukup kuat' : score === 2 ? 'Sedang' : 'Lemah';
    });

    // STEP 1: Kirim OTP
    document.getElementById('formStep1').onsubmit = async e => {
        e.preventDefault();
        document.getElementById('passError').style.display = 'none';

        if (password.value !== confirmPassword.value) return showError('Password tidak cocok!');
        if (password.value.length < 8 || !/[A-Z]/.test(password.value) || !/\d/.test(password.value) || !/[^A-Za-z0-9]/.test(password.value))
            return showError('Password harus kuat!');

        data.nama_pembeli = document.getElementById('nama_pembeli').value.trim();
        data.alamat_email = document.getElementById('alamat_email').value.trim();
        data.nomor_telepon_pembeli = document.getElementById('nomor_telepon_pembeli').value.trim();
        data.password = password.value;

        try {
            const res = await apiPost('/pembeli/send-otp', {
                alamat_email: data.alamat_email,
                nama_pembeli: data.nama_pembeli
            });
            const json = await res.json();

            if (res.ok) {
                document.getElementById('step1').classList.remove('active');
                document.getElementById('step2').classList.add('active');
                document.getElementById('emailDisplay').textContent = data.alamat_email;
                document.getElementById('stepTitle').textContent = 'Verifikasi Kode OTP';
                startTimer(300);
                document.querySelector('.otp-box').focus();
                showSuccess('Kode OTP telah dikirim!');
            } else {
                showError(json.message || 'Gagal mengirim OTP');
            }
        } catch (err) {
            showError('Koneksi bermasalah');
        }
    };

    // OTP Auto Focus
    document.querySelectorAll('.otp-box').forEach((box, i, arr) => {
        box.oninput = () => { if (box.value && i < 5) arr[i+1].focus(); };
        box.onkeydown = e => { if (e.key === 'Backspace' && !box.value && i > 0) arr[i-1].focus(); };
    });

    // Timer
    function startTimer(sec) {
        clearInterval(countdown);
        const timer = document.getElementById('timer');
        const resend = document.getElementById('resendBtn');
        resend.style.pointerEvents = 'none';

        countdown = setInterval(() => {
            const m = String(Math.floor(sec / 60)).padStart(2, '0');
            const s = String(sec % 60).padStart(2, '0');
            timer.textContent = `${m}:${s}`;
            if (--sec < 0) {
                clearInterval(countdown);
                timer.textContent = 'Waktu habis';
                resend.style.pointerEvents = 'auto';
            }
        }, 1000);

        resend.onclick = async () => {
            const res = await apiPost('/pembeli/send-otp', { alamat_email: data.alamat_email, nama_pembeli: data.nama_pembeli });
            if (res.ok) { startTimer(300); showSuccess('OTP baru dikirim!'); }
        };
    }

    // STEP 2: Register
    document.getElementById('formOtp').onsubmit = async e => {
        e.preventDefault();
        const code = Array.from(document.querySelectorAll('.otp-box')).map(b => b.value).join('');
        if (code.length !== 6) return showError('Masukkan 6 digit kode');

        try {
            const res = await apiPost('/pembeli-register', {
                nama_pembeli: data.nama_pembeli,
                alamat_email: data.alamat_email,
                nomor_telepon_pembeli: data.nomor_telepon_pembeli,
                password: data.password,
                password_confirmation: data.password,
                otp: code
            });
            const json = await res.json();

            if (res.ok) {
                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
                setTimeout(() => location.href = '/login', 2500);
            } else {
                showError(json.message || 'Registrasi gagal');
            }
        } catch (err) {
            showError('Koneksi error');
        }
    };

    document.getElementById('backToForm').onclick = e => {
        e.preventDefault();
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
        document.getElementById('stepTitle').textContent = 'Daftar sebagai Pembeli';
        clearInterval(countdown);
    };

    function showError(msg) {
        const el = document.getElementById('alertError');
        el.textContent = msg; el.classList.remove('d-none');
        document.getElementById('alertSuccess').classList.add('d-none');
    }
    function showSuccess(msg) {
        const el = document.getElementById('alertSuccess');
        el.textContent = msg; el.classList.remove('d-none');
        document.getElementById('alertError').classList.add('d-none');
    }
</script>
</body>
</html>