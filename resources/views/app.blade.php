<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar-brand {
            color: #00aa5b;
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
        .carousel-container {
            width: 95%;
            margin: validate
0 auto;
            overflow: hidden;
        }
        .carousel-inner {
            border-radius: 10px;
        }
        .carousel-item img {
            height: 500px;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 0.5rem;
            transition: opacity 0.3s ease;
        }
        .carousel-item:hover .carousel-caption {
            opacity: 1;
        }
        .kategori-scroll {
            overflow-x: auto;
            white-space: nowrap;
        }
        #kategori-filter {
            flex-wrap: nowrap;
        }
        .kategori-item {
            flex: 0 0 auto;
            width: 120px;
        }
        .kategori-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
            transition: 0.3s ease;
            opacity: 0.5;
            cursor: pointer;
        }
        .kategori-img.active, .kategori-img:hover {
            border-color: #00aa5b;
            opacity: 1;
        }
        .kategori-label {
            font-size: 0.85rem;
            margin-top: 5px;
            white-space: normal;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="bi bi-cart-check"></i>Reusemart</a>
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
        <!-- Carousel -->
        <div class="carousel-container">
            <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('img/carousel1.jpg') }}" class="d-block w-100" alt="Carousel 1">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Discover Sustainable Shopping</h5>
                            <p>Dapatkan Barang Bekas berkualitas hanya di Reusemart.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/carousel2.jpg') }}" class="d-block w-100" alt="Carousel 2">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Support Eco-Friendly Living</h5>
                            <p>Beli dan Jual Barang Bekas untuk mengurangi sampah.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/carousel3.jpg') }}" class="d-block w-100" alt="Carousel 3">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Join the Reusemart Community</h5>
                            <p>Belanja Cerdas, Hemat Uang, dan Selamatkan Bumi.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Category List -->
        <h2 class="mb-4">Jelajahi Kategori</h2>
        <div class="kategori-scroll mb-4 px-3">
            <div class="d-flex flex-nowrap gap-3" id="kategori-filter">
                @foreach ($kategoris as $kategori)
                    <div class="kategori-item text-center" data-id="{{ $kategori->id_kategori }}">
                        <img src="{{ asset('img/' . $kategori->id_kategori . '.jpg') }}" class="kategori-img" alt="{{ $kategori->nama_kategori }}" onerror="this.src='{{ asset('img/img1.jpg') }}';">
                        <div class="kategori-label">{{ $kategori->nama_kategori }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Products -->
        <h2 class="mb-4">Produk Terbaru</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="{{ asset('img/img1.jpg') }}" class="card-img-top" alt="Metal Table">
                    <div class="card-body">
                        <h5 class="card-title">Metal Table</h5>
                        <p class="card-text">Rp100.000</p>
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="{{ asset('img/img2.jpg') }}" class="card-img-top" alt="Modern Glass">
                    <div class="card-body">
                        <h5 class="card-title">Modern Glass</h5>
                        <p class="card-text">Rp150.000</p>
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="{{ asset('img/img1.jpg') }}" class="card-img-top" alt="Vintage Lamp">
                    <div class="card-body">
                        <h5 class="card-title">Vintage Lamp</h5>
                        <p class="card-text">Rp200.000</p>
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card product-card mb-4">
                    <img src="{{ asset('img/img2.jpg') }}" class="card-img-top" alt="Wooden Chair">
                    <div class="card-body">
                        <h5 class="card-title">Wooden Chair</h5>
                        <p class="card-text">Rp350.000</p>
                        <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Lihat Produk</a>
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

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Silahkan Login Terlebih Dahulu!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <a href="{{ route('login') }}" class="btn btn-success w-100 mb-3">Login</a>
                </div>
            </div>
        </div>
    </div>

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
                        <li><a href="/admin-login">Admin</a></li>
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
                © 2025 Reusemart. Semua hak dilindungi.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.kategori-item').on('click', function () {
                $('.kategori-img').removeClass('active').css('opacity', '0.5');
                $(this).find('.kategori-img').addClass('active').css('opacity', '1');
            });
        });
    </script>
</body>
</html>