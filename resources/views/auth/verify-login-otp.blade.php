<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
 
        .otp-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }
 
        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: pulse 2s infinite;
        }
 
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(102, 126, 234, 0); }
        }
 
        .icon-wrapper svg { width: 40px; height: 40px; fill: white; }
 
        h4 { color: #2d3748; font-weight: 700; text-align: center; margin-bottom: 10px; font-size: 28px; }
 
        .email-text {
            color: #667eea;
            font-weight: 600;
            background: #f7f9ff;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            margin: 5px 0;
        }
 
        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 30px;
            font-size: 14px;
        }
 
        .otp-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
 
        .otp-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }
 
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
 
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
 
        .btn-verify:active { transform: translateY(0); }
 
        .resend-link {
            text-align: center;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            display: block;
            transition: all 0.3s ease;
            padding: 10px;
        }
 
        .resend-link:hover { color: #764ba2; text-decoration: underline; }
 
        .alert { border-radius: 12px; border: none; margin-bottom: 20px; animation: slideIn 0.4s ease; }
 
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
 
        .divider { text-align: center; margin: 20px 0; color: #cbd5e0; font-size: 14px; }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="icon-wrapper">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
 
        <h4>Verifikasi OTP Login</h4>
        <p class="subtitle">
            Kode OTP telah dikirim ke<br>
            <span class="email-text">{{ session('pending_email') }}</span>
        </p>
 
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>⚠️ Error:</strong> {{ $errors->first() }}
            </div>
        @endif
 
        <form action="{{ route('login.otp.verify') }}" method="POST">
            @csrf
            <input
                type="text"
                name="otp_code"
                class="form-control otp-input"
                maxlength="6"
                placeholder="••••••"
                required
                autocomplete="off"
                inputmode="numeric"
                pattern="[0-9]*">
           
            <button class="btn btn-verify" type="submit">
                Verifikasi Sekarang
            </button>
        </form>
 
        <div class="divider">atau</div>
 
        <a href="{{ url('/pembeli-login/resend-otp') }}" class="resend-link">
            🔄 Kirim Ulang Kode OTP
        </a>
    </div>
    <script>
        @if(session('sanctum_login'))
            // Simpan token ke localStorage (sama seperti login biasa)
            localStorage.setItem('access_token', '{{ session('sanctum_login.access_token') }}');
            localStorage.setItem('role', '{{ session('sanctum_login.role') }}');
            localStorage.setItem('nama', '{{ session('sanctum_login.user.nama_pembeli') }}');
            localStorage.setItem('profile_image', '{{ session('sanctum_login.user.profile_image') }}');
 
            // Langsung ke home tanpa reload halaman OTP lagi
            window.location.href = '/home';
        @endif
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus input OTP
        document.querySelector('.otp-input').focus();
 
        // Hanya angka
        document.querySelector('.otp-input').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
       
 
    </script>
</body>
</html>