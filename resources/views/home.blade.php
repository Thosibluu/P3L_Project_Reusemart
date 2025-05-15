<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; }
        .navbar-brand { color: #00aa5b !important; font-weight: bold; }
        .product-card { transition: transform 0.2s; }
        .product-card:hover { transform: scale(1.03); }
        .product-card .card-body { flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .product-card .card-title { min-height: 48px; overflow: hidden; }
        .kategori-scroll { overflow-x: auto; white-space: nowrap; }
        #kategori-filter { flex-wrap: nowrap; }
        .kategori-item { flex: 0 0 auto; width: 120px; }
        .rounded-circle { border-radius: 50%; object-fit: cover; }
        .kategori-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 2px solid transparent; transition: 0.3s ease; opacity: 0.5; cursor: pointer; }
        .kategori-img.active, .kategori-img:hover { border-color: #00aa5b; opacity: 1; }
        .kategori-label { font-size: 0.85rem; margin-top: 5px; white-space: normal; }
        .footer { background-color: #ccf5d3; color: #004d26; padding: 40px 0; }
        .footer a { color: #006633; text-decoration: none; }
        .footer a:hover { color: #004d26; }
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
                <form class="d-flex mx-lg-auto" role="search" action="/search" method="GET" style="max-width: 600px; width: 100%;">
                    <input class="form-control me-2 w-100" type="search" placeholder="Cari produk..." aria-label="Search" name="query">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <img id="user-image" src="{{ asset('img/img1.jpg') }}" alt="Profil" width="40" height="40" class="rounded-circle me-2">
                        <span id="user-name" class="me-3">Pengguna</span>
                        <button class="btn btn-outline-primary me-2" onclick="goToProfile()">Profil</button>
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Produk Terbaru Hanya Di Reusemart</h2>
        <div class="kategori-scroll mb-4 px-3">
            <div class="d-flex flex-nowrap gap-3" id="kategori-filter">
                <div class="kategori-item text-center" data-id="">
                    <img src="{{ asset('img/img1.jpg') }}" class="kategori-img active" alt="Semua">
                    <div class="kategori-label">Semua</div>
                </div>
                @foreach ($kategoris as $kategori)
                    <div class="kategori-item text-center" data-id="{{ $kategori->id }}">
                        <img src="{{ $kategori->gambar ? asset('storage/kategori/' . $kategori->gambar) : asset('img/img1.jpg') }}" class="kategori-img" alt="{{ $kategori->nama_kategori }}">
                        <div class="kategori-label">{{ $kategori->nama_kategori }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="produk-container" class="row g-4">
            @foreach ($barangs as $barang)
                <div class="col-md-3 produk-item" data-kategori="{{ $barang->kategori_id }}">
                    <div class="card product-card mb-4 h-100 d-flex flex-column">
                        <img src="{{ $barang->gambar ? asset('storage/barang/' . $barang->gambar) : asset('img/img1.jpg') }}" class="card-img-top" alt="{{ $barang->nama_produk }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $barang->nama_produk }}</h5>
                            <p class="card-text">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                            <a href="/produk/{{ $barang->kode_produk }}" class="btn btn-success w-100">Lihat Produk</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin Logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmLogout()">Keluar</button>
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
                const kategoriId = $(this).data('id');
                $('.kategori-img').removeClass('active').css('opacity', '0.5');
                $(this).find('.kategori-img').addClass('active').css('opacity', '1');

                if (kategoriId) {
                    $('.produk-item').hide();
                    $('.produk-item').filter(function () {
                        return $(this).data('kategori') == kategoriId;
                    }).show();
                } else {
                    $('.produk-item').show();
                }
            });

            const token = localStorage.getItem('access_token');
            const role = localStorage.getItem('role');
            const userName = localStorage.getItem('nama');
            const userImage = localStorage.getItem('profile_image');

            if (!token) {
                window.location.href = '/login';
                return;
            }

            if (userName) $('#user-name').text(userName);
            if (userImage) $('#user-image').attr('src', userImage);
        });

        function goToProfile() {
            const role = localStorage.getItem('role');
            const token = localStorage.getItem('access_token');

            if (!role) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '/login';
                return;
            }

            const route = role === 'pembeli' ? '/profil' : '/profil-penitip';

            // Use fetch to send the token and check authentication
            fetch(route, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(response => {
                if (response.redirected && response.url.includes('/login')) {
                    alert('Sesi kadaluarsa. Silakan login ulang.');
                    window.location.href = '/login';
                } else if (!response.ok) {
                    throw new Error('Gagal mengakses profil');
                } else {
                    // No need to parse as JSON; navigate directly
                    window.location.href = `${route}?token=${encodeURIComponent(token)}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '/login';
            });
        }

        function confirmLogout() {
            const token = localStorage.getItem('access_token');
            const role = localStorage.getItem('role');
            const endpoint = role === 'pembeli' ? '/api/pembeli-logout' : '/api/penitip-logout';

            fetch(endpoint, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                localStorage.clear();
                window.location.href = '/login';
            })
            .catch(error => {
                console.error('Logout error:', error);
                localStorage.clear();
                window.location.href = '/login';
            });
        }
    </script>
</body>
</html>