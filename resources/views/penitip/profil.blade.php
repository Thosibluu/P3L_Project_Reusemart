<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Penitip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand { font-weight: bold; color: #00aa5b !important; }
        .card { background-color: #ffffff; border-radius: 1rem; transition: 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 0 25px rgba(0,0,0,0.05); }
        .btn-outline-primary, .btn-danger { min-width: 120px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/home">Reusemart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="/home" class="btn btn-outline-success me-2">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row g-4" id="profile-content">
            <!-- Profile Card will be loaded via AJAX -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            loadProfile();

            function loadProfile() {
                const urlParams = new URLSearchParams(window.location.search);
                const tokenFromUrl = urlParams.get('token');

                // Fallback to localStorage if URL parameter is missing (for direct access)
                const token = tokenFromUrl || localStorage.getItem('access_token');

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                $.ajax({
                    url: '/api/profile-penitip',
                    method: 'GET',
                    success: function(response) {
                        let profileHtml = `
                            <div class="col-md-5">
                                <div class="card shadow-lg p-4 rounded-4">
                                    <div class="text-center">
                                        <img src="${response.foto || '{{ asset('img/img1.jpg') }}'}" class="rounded-circle mb-3" width="120" height="120" alt="Foto Profil" style="object-fit: cover;">
                                        <h4 class="fw-bold">${response.nama}</h4>
                                        <p class="text-muted">${response.alamat}</p>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Nomor Telepon:</strong>
                                        <p>${response.no_hp}</p>
                                    </div>
                                    <div class="mb-3"><strong>Poin Reward:</strong><p>${response.total_poin}</p></div>
                                    <div class="mb-3"><strong>Saldo:</strong><p>Rp${response.saldo.toLocaleString('id-ID')}</p></div>
                                </div>
                            </div>
                        `;

                        if (response.transaksi && response.transaksi.length) {
                            let transaksiHtml = `
                                <div class="col-md-7">
                                    <div class="card shadow-lg p-4 rounded-4">
                                        <h5 class="fw-bold mb-3">Riwayat Transaksi Penjualan</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr><th>No</th><th>Nama Barang</th><th>Tanggal</th><th>Jumlah</th><th>Total</th></tr>
                                                </thead>
                                                <tbody>
                            `;
                            response.transaksi.forEach((item, index) => {
                                transaksiHtml += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.nama_barang}</td>
                                        <td>${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
                                        <td>${item.jumlah}</td>
                                        <td>Rp${item.total_harga.toLocaleString('id-ID')}</td>
                                    </tr>
                                `;
                            });
                            transaksiHtml += `
                                </tbody></table></div></div></div>
                            `;
                            profileHtml += transaksiHtml;
                        } else {
                            profileHtml += '<div class="col-md-7"><div class="card shadow-lg p-4 rounded-4"><p class="text-muted">Belum ada transaksi penjualan.</p></div></div>';
                        }

                        $('#profile-content').html(profileHtml);
                    },
                    error: function(xhr) {
                        alert('Gagal memuat profil. Silakan login ulang.');
                        window.location.href = '/login';
                    }
                });
            }

            function confirmLogout() {
                fetch('/api/penitip-logout', { method: 'POST', headers: { 'Accept': 'application/json' } })
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
        });
    </script>
</body>
</html>