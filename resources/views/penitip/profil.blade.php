<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Penitip | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; font-size: 1.1rem; }
        .navbar-brand { font-weight: bold; color: #00aa5b !important; }
        .navbar-brand i { margin-right: 8px; }
        .card { background-color: #ffffff; border-radius: 0.8rem; transition: 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .btn-outline-primary, .btn-danger { min-width: 100px; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1055; }
        .profile-card { min-height: 250px; padding: 1.5rem; }
        .history-card { min-height: 350px; padding: 1.5rem; }
        .profile-img { width: 120px; height: 120px; object-fit: cover; }
        .profile-name { font-size: 1.8rem; font-weight: bold; }
        .profile-id { font-size: 1.1rem; color: #6c757d; }
        .profile-contact { margin-top: 1rem; }
        .profile-contact-item { display: flex; align-items: center; margin-bottom: 8px; }
        .profile-contact-label { font-weight: 700; min-width: 80px; font-size: 1.15rem; }
        .profile-contact-value { flex: 1; }
        .spotlight-card { background-color: #ffffff; border-radius: 0.5rem; padding: 0.5rem 1rem; margin-bottom: 0.5rem; text-align: left; }
        .spotlight-label { font-size: 0.9rem; font-weight: 700; color: #000000; }
        .spotlight-value { font-size: 2.5rem; font-weight: bold; margin-top: 0.2rem; }
        .history-items { display: flex; overflow-x: auto; gap: 1rem; padding-bottom: 1rem; }
        .history-items::-webkit-scrollbar { height: 8px; }
        .history-items::-webkit-scrollbar-thumb { background-color: #00aa5b; border-radius: 4px; }
        .history-items::-webkit-scrollbar-track { background: #f1f1f1; }
        .history-item { flex: 0 0 auto; width: 300px; }
        .history-item .card-body { padding: 1rem; }
        .history-item .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .history-item .detail-label { font-weight: bold; text-align: left; flex: 1; }
        .history-item .detail-value { text-align: right; flex: 1; }
        .no-history { text-align: center; color: #6c757d; padding: 1.5rem; }
        @media (max-width: 767px) {
            .profile-card, .history-card { min-height: auto; }
            .history-item { width: 250px; }
            .profile-img { width: 100px; height: 100px; }
            .profile-name { font-size: 1.5rem; }
            .profile-id { font-size: 1rem; }
            .profile-contact-label { font-size: 1rem; }
            .spotlight-label { font-size: 0.8rem; }
            .spotlight-value { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container"></div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/home"><i class="bi bi-cart-check"></i>Reusemart</a>
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
        <div class="row g-4">
            <!-- Profile Section (Top) -->
            <div class="col-12" id="profile-content">
                <!-- Profile Card will be loaded via AJAX -->
            </div>

            <!-- Penitipan History Section (Bottom) -->
            <div class="col-12 mt-4" id="penitipan-history">
                <!-- Penitipan History will be loaded via AJAX -->
            </div>
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
        // Function to get the token
        function getToken() {
            const urlParams = new URLSearchParams(window.location.search);
            const tokenFromUrl = urlParams.get('token');
            return tokenFromUrl || localStorage.getItem('access_token') || '';
        }

        // Function to show toast notifications
        function showToast(message, type = 'success') {
            const toastContainer = $('.toast-container');
            const toastId = 'toast-' + new Date().getTime();
            const toastClass = type === 'success' ? 'bg-success text-white' : 'bg-danger text-white';

            const toastHtml = `
                <div id="${toastId}" class="toast ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">${type === 'success' ? 'Sukses' : 'Error'}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;

            toastContainer.append(toastHtml);
            const toastElement = $(`#${toastId}`);
            const toast = new bootstrap.Toast(toastElement[0], { delay: 3000 });
            toast.show();

            toastElement.on('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }

        $(document).ready(function () {
            loadProfile();

            function loadProfile() {
                const token = getToken();

                if (!token) {
                    showToast('Sesi tidak ditemukan. Silakan login ulang.', 'error');
                    setTimeout(() => window.location.href = '/login', 2000);
                    return;
                }

                $.ajax({
                    url: '/api/profile-penitip',
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                    success: function(response) {
                        // Profile Section (Top)
                        let profileHtml = `
                            <div class="card shadow-lg rounded-4 profile-card">
                                <div class="row">
                                    <!-- Column 1: Photo, Name, ID, Email, Phone (4 parts) -->
                                    <div class="col-md-4 text-center text-md-start">
                                        <img src="${response.foto || '{{ asset('img/img1.jpg') }}'}" class="rounded-circle mb-3 profile-img" alt="Foto Profil">
                                        <h5 class="profile-name">${response.nama} <span class="profile-id"></span></h5>
                                        <div class="profile-contact">
                                            <div class="profile-contact-item">
                                                <span class="profile-contact-label">ID Penitip</span>
                                                <span class="profile-contact-value">${response.id_penitip}</span>
                                            </div>
                                            <div class="profile-contact-item">
                                                <span class="profile-contact-label">Nomor Telepon</span>
                                                <span class="profile-contact-value">${response.no_hp}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column 2: Saldo and Total Poin (8 parts, 4 rows) -->
                                    <div class="col-md-8 d-flex flex-column justify-content-center">
                                        <!-- Row 1: Saldo -->
                                        <div class="spotlight-card">
                                            <div class="spotlight-label">Saldo</div>
                                            <div class="spotlight-value">Rp. ${response.saldo.toLocaleString('id-ID')},00</div>
                                        </div>
                                        <!-- Row 2: Total Poin -->
                                        <div class="spotlight-card">
                                            <div class="spotlight-label">Total Poin</div>
                                            <div class="spotlight-value">${response.total_poin}</div>
                                        </div>
                                        <!-- Row 3: Placeholder -->
                                        <div class="spotlight-card">
                                            <div class="spotlight-label"></div>
                                            <div class="spotlight-value"></div>
                                        </div>
                                        <!-- Row 4: Placeholder -->
                                        <div class="spotlight-card">
                                            <div class="spotlight-label"></div>
                                            <div class="spotlight-value"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#profile-content').html(profileHtml);

                        // Penitipan History Section (Bottom)
                        let penitipanHtml = `
                            <div class="card shadow-lg rounded-4 history-card">
                                <h5 class="fw-bold mb-3 text-center">Riwayat Penitipan</h5>
                                <div class="history-items">
                        `;
                        if (response.penitipan_history && response.penitipan_history.length) {
                            response.penitipan_history.forEach((item, index) => {
                                penitipanHtml += `
                                    <div class="card history-item shadow-sm">
                                        <div class="card-body">
                                            <div class="detail-row">
                                                <span class="detail-label">No</span>
                                                <span class="detail-value">${index + 1}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">ID Transaksi</span>
                                                <span class="detail-value">${item.id_transaksi}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Tanggal Penitipan</span>
                                                <span class="detail-value">${new Date(item.tanggal_penitipan).toLocaleDateString('id-ID')}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Nama Barang</span>
                                                <span class="detail-value">${item.nama_barang}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Harga</span>
                                                <span class="detail-value">Rp. ${item.harga.toLocaleString('id-ID')}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Status Perpanjang</span>
                                                <span class="detail-value">${item.status_perpanjang}</span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Batas Penitipan</span>
                                                <span class="detail-value">${item.batas_penitipan ? new Date(item.batas_penitipan).toLocaleDateString('id-ID') : 'N/A'}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            penitipanHtml += `
                                <div class="no-history">Belum ada riwayat penitipan.</div>
                            `;
                        }
                        penitipanHtml += `
                                </div>
                            </div>
                        `;
                        $('#penitipan-history').html(penitipanHtml);
                    },
                    error: function(xhr) {
                        showToast('Gagal memuat profil: ' + (xhr.responseJSON?.error || 'Silakan login ulang.'), 'error');
                        setTimeout(() => window.location.href = '/login', 2000);
                    }
                });
            }

            function confirmLogout() {
                const token = getToken();
                if (!token) {
                    showToast('Sesi tidak ditemukan. Silakan login ulang.', 'error');
                    setTimeout(() => window.location.href = '/login', 2000);
                    return;
                }

                fetch('/api/penitip-logout', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json' 
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message, 'success');
                    localStorage.clear();
                    setTimeout(() => window.location.href = '/login', 2000);
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    showToast('Gagal logout. Mengarahkan ke login...', 'error');
                    localStorage.clear();
                    setTimeout(() => window.location.href = '/login', 2000);
                });
            }
        });
    </script>
</body>
</html>