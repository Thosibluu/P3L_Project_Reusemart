<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar-brand {
            color: #00aa5b !important;
            font-weight: bold;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.03);
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="form-container mx-auto">
            <h3 class="text-center mb-4">Reset Password</h3>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            <form id="resetForm" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="registered_email_or_id" class="form-label">Email Terdaftar atau ID Penitip</label>
                    <input type="text" class="form-control" name="registered_email_or_id" id="registered_email_or_id" required>
                </div>
                <div class="mb-3">
                    <label for="send_to_email" class="form-label">Kirim Link ke Email Ini</label>
                    <input type="email" class="form-control" name="send_to_email" id="send_to_email" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Kirim Link Reset</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>