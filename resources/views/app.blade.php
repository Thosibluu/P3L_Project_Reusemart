<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar-brand {
            color: #00aa5b ;
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
        .footer ul {
            list-style: none;
            padding-left: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer a {
            color: #006633;
            text-decoration: none;
        }

        .footer a:hover {
            color: #004d26;
        }

        .footer {
            background-color: #ccf5d3; 
            color: #004d26;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">Reusemart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Produk Terbaru</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="img/img1.jpg" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <h5 class="card-title">Haye</h5>
                        <p class="card-text">Rp100.000</p>
                        <a href="/produk/1" class="btn btn-success w-100">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="img/img2.jpg" class="card-img-top" alt="Produk 2">
                    <div class="card-body">
                        <h5 class="card-title">Haye</h5>
                        <p class="card-text">Rp150.000</p>
                        <a href="/produk/2" class="btn btn-success w-100">Lihat Produk</a>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-success fw-bold">Reusemart</h5>
                <p>Reusemart adalah platform e-commerce untuk menjual dan membeli barang bekas berkualitas. Dukung gaya hidup berkelanjutan dan hemat dengan kami.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h5>Jelajahi</h5>
                <ul>
                    <li><a href="/">Beranda</a></li>
                    <li><a href="#">Produk</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Bantuan</h5>
                <ul>
                    <li><a href="#">Cara Belanja</a></li>
                    <li><a href="#">Pengembalian & Refund</a></li>
                    <li><a href="#">Akun Saya</a></li>
                    <li><a href="#">Pertanyaan Umum (FAQ)</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Hubungi Kami</h5>
                <p>Email: support@reusemart.com</p>
                <p>Telepon: +62 812 3456 7890</p>
                <p>Alamat: Jl. Hijau Lestari No.1, Yogyakarta</p>
            </div>
        </div>
        <hr>
        <div class="text-center text-muted py-3">
            &copy; 2025 Reusemart. Semua hak dilindungi.
        </div>
    </div>
</footer>
</html>