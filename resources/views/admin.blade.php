<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #343a40; color: #fff; padding-top: 20px; }
        .sidebar .nav-link { color: #fff; padding: 10px 20px; }
        .sidebar .nav-link:hover { background-color: #495057; }
        .sidebar .nav-link.active { background-color: #007bff; }
        .content { margin-left: 250px; padding: 20px; min-height: 100vh; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar-brand { color: #00aa5b !important; font-weight: bold; display: flex; align-items: center; }
        .navbar-brand i { color: #00aa5b; margin-right: 8px; font-size: 1.5rem; }
        .user-image { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
        .search-container { display: flex; align-items: center; gap: 10px; }
        .table-container { max-height: 400px; overflow-y: auto; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1055; }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="text-center mb-4">Admin Panel</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#users" data-bs-toggle="tab">Manajemen Pengguna</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#products" data-bs-toggle="tab">Manajemen Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#organisasi" data-bs-toggle="tab">Manajemen Organisasi</a>
            </li>
            <li class="nav-item" id="manajemen-transaksi-tab" style="display: none;">
                <a class="nav-link" href="#transactions" data-bs-toggle="tab">Manajemen Transaksi</a>
            </li>
            <li class="nav-item" id="request-donasi-tab" style="display: none;">
                <a class="nav-link" href="#request-donasi" data-bs-toggle="tab">Laporan Request Donasi</a>
            </li>
            <li class="nav-item" id="laporan-donasi-tab" style="display: none;">
                <a class="nav-link" href="#donasi-laporan" data-bs-toggle="tab">Laporan Donasi Barang</a>
            </li>
            <li class="nav-item" id="laporan-penitipan-tab" style="display: none;">
                <a class="nav-link" href="#laporan-penitipan" data-bs-toggle="tab">Laporan Transaksi Penitipan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
            </li>
            <li class="nav-item" id="laporan-donasi-tab2" style="display: none;">
                <a class="nav-link" href="#donasi-laporan2" data-bs-toggle="tab">Laporan Donasi Barang 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#activity-logs" data-bs-toggle="tab">
                    <i class="bi bi-journal-text"></i> Aktivitas Log
                </a>
            </li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-cart-check"></i> Reusemart
            </a>
            <button class="btn btn-dark d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link" id="userName">Admin</span>
                    </li>
                    <li class="nav-item">
                        <img src="{{ asset('img/img1.jpg') }}" alt="Profil" class="user-image me-2">
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <div class="tab-content">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard">
                <h2>Dashboard</h2>
                <p>Selamat datang di dashboard admin!</p>
            </div>

            <!-- Users Tab -->
            <div class="tab-pane fade" id="users">
                <h2 class="mb-4">Manajemen Pengguna Pembeli</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>Nomor Telepon</th>
                                <th>Status Akun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pembeliTableBody">
                            <!-- Data diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Tab -->
            <div class="tab-pane fade" id="products">
                <h2>Manajemen Produk</h2>
                <p>Halaman untuk mengelola produk akan ditambahkan di sini.</p>
            </div>

            <!-- Organisasi Tab -->
            <div class="tab-pane fade" id="organisasi">
                <h2>Manajemen Organisasi</h2>
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-container">
                        <input type="text" id="searchOrganisasi" class="form-control w-50" placeholder="Cari organisasi...">
                        <button class="btn btn-outline-primary" id="searchButton">Cari</button>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="organisasi-table">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Request Donasi Tab -->
            <div class="tab-pane fade" id="request-donasi">
                <h2>Laporan Request Donasi</h2>
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-container">
                        <input type="text" id="searchRequestDonasi" class="form-control w-50" placeholder="Cari request donasi...">
                        <button class="btn btn-outline-primary" id="searchRequestDonasiButton">Cari</button>
                    </div>
                    <button class="btn btn-primary" id="printRequestDonasiPdf">Cetak PDF</button>
                </div>
                <div class="table-container">
                    <table class="table table-striped" id="request-donasi-table">
                        <thead>
                            <tr>
                                <th>ID Organisasi</th>
                                <th>Nama Organisasi</th>
                                <th>Alamat</th>
                                <th>Kode Produk</th>
                                <th>Detail Request</th>
                            </tr>
                        </thead>
                        <tbody id="request-donasi-table-body">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Donasi Laporan Tab -->
            <div class="tab-pane fade" id="donasi-laporan">
                <h2>Laporan Donasi Barang</h2>
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-container">
                        <input type="text" id="searchDonasi" class="form-control w-50" placeholder="Cari donasi...">
                        <button class="btn btn-outline-primary" id="searchDonasiButton">Cari</button>
                    </div>
                    <button class="btn btn-primary" id="printDonasiPdf">Cetak PDF</button>
                </div>
                <div class="table-container">
                    <table class="table table-striped" id="donasi-table">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>ID Penitip</th>
                                <th>Nama Penitip</th>
                                <th>Tanggal Donasi</th>
                                <th>Organisasi</th>
                                <th>Nama Penerima</th>
                                <th>Nama Hunter</th>
                            </tr>
                        </thead>
                        <tbody id="donasi-table-body">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="donasi-laporan2">
                <h2>Laporan Donasi Barang 2</h2>
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-container">
                        <input type="text" id="searchDonasi" class="form-control w-50" placeholder="Cari donasi...">
                        <button class="btn btn-outline-primary" id="searchDonasiButton">Cari</button>
                    </div>
                    <button class="btn btn-primary" id="printDonasiPdf">Cetak PDF</button>
                </div>
                <div class="table-container">
                    <table class="table table-striped" id="donasi-table2">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>ID Penitip</th>
                                <th>Nama Penitip</th>
                                <th>Tanggal Donasi</th>
                                <th>Organisasi</th>
                                <th>Nama Penerima</th>
                                <th>Nama Hunter</th>
                            </tr>
                        </thead>
                        <tbody id="donasi-table-body2">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transactions Tab -->
            <div class="tab-pane fade" id="transactions">
                <h2>Manajemen Transaksi</h2>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Pembeli</th>
                                <th>Total Harga</th>
                                <th>Status Pembelian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transaksi-table">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="laporan-penitipan">
                <h2>Laporan Transaksi Penitipan</h2>
                <div class="d-flex justify-content-between mb-3">
                    <div class="filter-container d-flex align-items-center">
                        <select id="penitipFilter" class="form-select me-3" style="width: 200px;" required>
                            <option value="">Pilih Penitip</option>
                        </select>
                        <select id="monthFilter" class="form-select me-3" style="width: 120px;" required>
                            <option value="">Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <select id="yearFilter" class="form-select" style="width: 100px;" required>
                            <option value="">Tahun</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="printPenitipanPdf" disabled>Cetak PDF</button>
                </div>
                <div class="table-container">
                    <table class="table table-striped" id="penitipan-table">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Laku</th>
                                <th>Harga Jual Bersih</th>
                                <th>Bonus Terjual Cepat</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody id="penitipan-table-body">
                            <tr><td colspan="7" class="text-muted text-center">Pilih filter untuk melihat data transaksi.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- DI DALAM <div class="tab-content"> -->
            <!-- TAB AKTIVITAS LOG (TAMBAH INI DI BAWAH TAB LAIN) -->
            <div class="tab-pane fade" id="activity-logs">
                <h2>Aktivitas Log Pengguna</h2>
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <div class="d-flex gap-2">
                        <input type="text" id="searchLog" class="form-control" placeholder="Cari nama / email / ID..." style="width: 300px;">
                        <select id="filterRole" class="form-select" style="width: 150px;">
                            <option value="">Semua Role</option>
                            <option value="pembeli">Pembeli</option>
                            <option value="penitip">Penitip</option>
                            <option value="organisasi">Organisasi</option>
                            <option value="pegawai">Pegawai</option>
                        </select>
                        <button class="btn btn-primary" id="refreshLogs">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <button class="btn btn-success" id="exportLogPdf">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </button>
                </div>

                <div class="table-responsive" style="max-height: 70vh;">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                            <!-- Data diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit/Create Organisasi -->
    <div class="modal fade" id="organisasiModal" tabindex="-1" aria-labelledby="organisasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="organisasiForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="organisasiModalLabel">Tambah Organisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_organisasi" id="id_organisasi">
                        <div class="mb-3">
                            <label for="nama_organisasi" class="form-label">Nama Organisasi</label>
                            <input type="text" name="nama_organisasi" id="nama_organisasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_organisasi" class="form-label">Alamat Organisasi</label>
                            <input type="text" name="alamat_organisasi" id="alamat_organisasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telpon_organisasi" class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telpon_organisasi" id="nomor_telpon_organisasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Organisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus organisasi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
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
                    <button type="button" class="btn btn-primary" id="confirmLogout">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script>
        // Ambil token dari localStorage
        const token = localStorage.getItem('access_token');

        // Tambahkan header Authorization untuk semua AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': token ? 'Bearer ' + token : ''
            }
        });

        function showToast(message, type = 'success') {
            const toastContainer = $('.toast-container');
            if (!toastContainer.length) {
                console.error('Toast container not found in DOM');
                return;
            }
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
            if (!toastElement.length) {
                console.error('Failed to create toast element');
                return;
            }
            const toast = new bootstrap.Toast(toastElement[0], { delay: 3000 });
            toast.show();

            toastElement.on('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }

        function bukaModalTambah() {
            const modalElement = document.getElementById('organisasiModal');
            if (!modalElement) {
                console.error('Organisasi modal not found in DOM');
                return;
            }
            const form = document.getElementById('organisasiForm');
            form.action = '/api/organisasi';
            document.getElementById('organisasiModalLabel').innerText = 'Tambah Organisasi';
            document.getElementById('id_organisasi').value = '';
            document.getElementById('nama_organisasi').value = '';
            document.getElementById('alamat_organisasi').value = '';
            document.getElementById('nomor_telpon_organisasi').value = '';
            document.getElementById('password').value = '';
            document.getElementById('formMethod').value = 'POST';
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function bukaModalEdit(id, nama, alamat, nomor_telpon) {
            const modalElement = document.getElementById('organisasiModal');
            if (!modalElement) {
                console.error('Organisasi modal not found in DOM');
                return;
            }
            const form = document.getElementById('organisasiForm');
            form.action = '/api/organisasi/' + id;
            document.getElementById('organisasiModalLabel').innerText = 'Edit Organisasi';
            document.getElementById('id_organisasi').value = id;
            document.getElementById('nama_organisasi').value = nama || '';
            document.getElementById('alamat_organisasi').value = alamat || '';
            document.getElementById('nomor_telpon_organisasi').value = nomor_telpon || '';
            document.getElementById('password').value = '';
            document.getElementById('formMethod').value = 'PUT';
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function deleteOrganisasi(id) {
            const modalElement = document.getElementById('deleteModal');
            if (!modalElement) {
                console.error('Delete modal not found in DOM');
                return;
            }
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            $('#confirmDelete').off('click').on('click', function() {
                $.ajax({
                    url: '/api/organisasi/' + id,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' },
                    success: function(response) {
                        showToast(response.message, 'success');
                        loadOrganisasi();
                        modal.hide();
                    },
                    error: function(xhr) {
                        showToast('Gagal menghapus organisasi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    }
                });
            });
        }

        function loadOrganisasi(search = '') {
            const tbody = $('#organisasi-table');
            if (!tbody.length) {
                console.error('Organisasi table body not found in DOM');
                return;
            }
            $.ajax({
                url: '/api/organisasi?search=' + encodeURIComponent(search),
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(response) {
                    tbody.empty();
                    if (response.length > 0) {
                        response.forEach(org => {
                            tbody.append(`
                                <tr>
                                    <td>${org.id_organisasi}</td>
                                    <td>${org.nama_organisasi}</td>
                                    <td>${org.alamat_organisasi}</td>
                                    <td>${org.nomor_telpon_organisasi}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-2 edit-btn" data-id="${org.id_organisasi}" data-nama="${org.nama_organisasi}" data-alamat="${org.alamat_organisasi}" data-nomor="${org.nomor_telpon_organisasi}">Edit</button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${org.id_organisasi}">Hapus</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-muted text-center">Tidak ada organisasi yang ditemukan.</td></tr>');
                    }
                },
                error: function(xhr) {
                    showToast('Gagal memuat organisasi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                }
            });
        }

        function loadTransaksi() {
            const tbody = $('#transaksi-table');
            if (!tbody.length) {
                console.error('Transaksi table body not found in DOM');
                return;
            }
            $.ajax({
                url: '/api/transaksi',
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(response) {
                    tbody.empty();
                    if (response.length > 0) {
                        response.forEach(trans => {
                            let actionContent = '';
                            if (trans.status_pembelian === 'Sedang disiapkan' || trans.status_pembelian === 'Siap Diambilkan') {
                                actionContent = '<span class="text-success">Valid</span>';
                            } else if (trans.status_pembelian === 'Ditolak') {
                                actionContent = '<span class="text-danger">Tidak Valid</span>';
                            } else {
                                actionContent = `
                                    <button class="btn btn-sm btn-success me-2 valid-btn" data-id="${trans.id_transaksi_beli}" data-pembeli="${trans.alamat_email}">Valid</button>
                                    <button class="btn btn-sm btn-danger invalid-btn" data-id="${trans.id_transaksi_beli}" data-pembeli="${trans.alamat_email}">Tidak Valid</button>
                                `;
                            }

                            tbody.append(`
                                <tr>
                                    <td>${trans.id_transaksi_beli}</td>
                                    <td>${trans.pembeli}</td>
                                    <td>Rp ${trans.total_harga.toLocaleString('id-ID')}</td>
                                    <td>${trans.status_pembelian}</td>
                                    <td>${actionContent}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-muted text-center">Tidak ada transaksi yang ditemukan.</td></tr>');
                    }
                },
                error: function(xhr) {
                    showToast('Gagal memuat transaksi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                }
            });
        }

        function loadPembeli() {
    $.ajax({
        url: '/api/pembeli',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function(data) {
            const tbody = $('#pembeliTableBody');
            tbody.empty();

            data.forEach(u => {
                const isLocked = Boolean(Number(u.locked));
                const status = isLocked? '<span class="badge bg-danger">Terkunci</span>'
                    : '<span class="badge bg-success">Aktif</span>';

                const button = isLocked? `<button class="btn btn-success btn-sm unlock-btn">
                         <i class="bi bi-unlock-fill"></i> Unlock
                       </button>`
                    : `<button class="btn btn-danger btn-sm lock-btn">
                         <i class="bi bi-lock-fill"></i> Lock
                       </button>`;

                tbody.append(`
                    <tr data-email="${u.alamat_email}">
                        <td>${u.alamat_email}</td>
                        <td>${u.nama_pembeli || '-'}</td>
                        <td>${u.nomor_telepon_pembeli || '-'}</td>
                        <td>${status}</td>
                        <td>${button}</td>
                    </tr>
                `);
            });
        },
        error: function() {
            alert('Gagal memuat data pembeli. Pastikan token admin valid.');
        }
    });
}

        function validateTransaksi(id, isValid, pembeliEmail) {
            const data = { is_valid: isValid, alamat_email: pembeliEmail };
            $.ajax({
                url: '/api/transaksi/validate/' + id,
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                data: JSON.stringify(data),
                success: function(response) {
                    showToast(response.message, 'success');
                    loadTransaksi();
                },
                error: function(xhr) {
                    showToast('Gagal memvalidasi transaksi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                }
            });
        }

        function loadDonasi(search = '') {
            const tbody = $('#donasi-table-body');
            if (!tbody.length) {
                console.error('Donasi table body not found in DOM');
                return;
            }
            $.ajax({
                url: '/api/donasi-laporan' + (search ? '?search=' + encodeURIComponent(search) : ''),
                method: 'GET',
                headers: { 'Accept': 'application/json'},
                success: function(response) {
                    tbody.empty();
                    if (response.length > 0) {
                        response.forEach(donasi => {
                            let hunterCell = '';
                            if (donasi.nama_hunter === '-') {
                                hunterCell = ''; // Jangan tampilkan kolom hunter
                            } else {
                                hunterCell = `<td>${donasi.nama_hunter || '-'}</td>`; // Tampilkan
                                tbody.append(`
                                <tr>
                                    <td>${donasi.kode_produk || '-'}</td>
                                    <td>${donasi.nama_produk || '-'}</td>
                                    <td>${donasi.id_penitip || '-'}</td>
                                    <td>${donasi.nama_penitip || '-'}</td>
                                    <td>${donasi.tanggal_donasi || '-'}</td>
                                    <td>${donasi.nama_organisasi || '-'}</td>
                                    <td>${donasi.nama_penerima || '-'}</td>
                                     ${hunterCell}
                                </tr>
                            `);
                            }

                            
                        });
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-muted text-center">Tidak ada data donasi yang ditemukan.</td></tr>');
                    }
                },
                error: function(xhr) {
                    showToast('Gagal memuat laporan donasi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    console.error('Error details:', xhr.responseText);
                }
            });
        }

        function loadRequestDonasi(search = '') {
            const tbody = $('#request-donasi-table-body');
            if (!tbody.length) {
                console.error('Request Donasi table body not found in DOM');
                return;
            }
            $.ajax({
                url: '/api/request-donasi' + (search ? '?search=' + encodeURIComponent(search) : ''),
                method: 'GET',
                headers: { 'Accept': 'application/json'},
                success: function(response) {
                    tbody.empty();
                    if (response.length > 0) {
                        response.forEach(request => {
                            tbody.append(`
                                <tr>
                                    <td>${request.id_organisasi || '-'}</td>
                                    <td>${request.nama_organisasi || '-'}</td>
                                    <td>${request.alamat_organisasi || '-'}</td>
                                    <td>${request.kode_produk || '-'}</td>
                                    <td>${request.detail_request || '-'}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-muted text-center">Tidak ada request donasi yang ditemukan.</td></tr>');
                    }
                },
                error: function(xhr) {
                    showToast('Gagal memuat laporan request donasi: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    console.error('Error details:', xhr.responseText);
                }
            });
        }

        function printDonasiPdf() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const currentDate = new Date().toLocaleString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            // Desain judul mirip logo navbar
            doc.setFontSize(18);
            doc.setTextColor(0, 170, 91); // Warna hijau #00aa5b
            doc.text('ReUse Mart', 105, 20, null, null, 'center'); // * sebagai pengganti ikon keranjang
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0); // Kembali ke warna hitam
            doc.text('Jl. Green Eco Park No. 456 Yogyakarta', 105, 28, null, null, 'center');
            doc.text('Laporan Donasi Barang', 105, 36, null, null, 'center');
            doc.text(`Tanggal Cetak: ${currentDate}`, 105, 44, null, null, 'center');

            const tableData = [];
            $('#donasi-table-body tr').each(function() {
                const row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                if (row.length > 0) tableData.push(row);
            });

            doc.autoTable({
                head: [['Kode Produk', 'Nama Produk', 'ID Penitip', 'Nama Penitip', 'Tanggal Donasi', 'Organisasi', 'Nama Penerima', 'Nama Hunter']],
                body: tableData,
                startY: 50,
                theme: 'striped',
                styles: { fontSize: 10 },
                margin: { top: 20 }
            });

            doc.save(`Laporan_Donasi_Barang_${currentDate.replace(/[:/]/g, '-')}.pdf`);
            showToast('PDF berhasil diunduh!', 'success');
        }

        function printRequestDonasiPdf() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const currentDate = new Date().toLocaleString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            // Desain judul mirip logo navbar
            doc.setFontSize(18);
            doc.setTextColor(0, 170, 91); // Warna hijau #00aa5b
            doc.text('ReUse Mart', 105, 20, null, null, 'center'); // * sebagai pengganti ikon keranjang
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0); // Kembali ke warna hitam
            doc.text('Jl. Green Eco Park No. 456 Yogyakarta', 105, 28, null, null, 'center');
            doc.text('Laporan Request Donasi', 105, 36, null, null, 'center');
            doc.text(`Tanggal Cetak: ${currentDate}`, 105, 44, null, null, 'center');

            const tableData = [];
            $('#request-donasi-table-body tr').each(function() {
                const row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                if (row.length > 0) tableData.push(row);
            });

            doc.autoTable({
                head: [['ID Organisasi', 'Nama Organisasi', 'Alamat', 'Kode Produk', 'Detail Request']],
                body: tableData,
                startY: 50,
                theme: 'striped',
                styles: { fontSize: 10 },
                margin: { top: 20 }
            });

            doc.save(`Laporan_Request_Donasi_${currentDate.replace(/[:/]/g, '-')}.pdf`);
            showToast('PDF berhasil diunduh!', 'success');
        }

        function loadPenitipList() {
            $.ajax({
                url: '/api/penitip',
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(response) {
                    const select = $('#penitipFilter');
                    select.empty();
                    select.append('<option value="">Pilih Penitip</option>');
                    response.forEach(penitip => {
                        select.append(`<option value="${penitip.id_penitip}">${penitip.nama_penitip}</option>`);
                    });
                },
                error: function(xhr) {
                    showToast('Gagal memuat daftar penitip: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                }
            });
        }

        function loadPenitipanTransactions(penitipId, month, year) {
            const tbody = $('#penitipan-table-body');
            if (!tbody.length) {
                console.error('Penitipan table body not found in DOM');
                return;
            }
            $.ajax({
                url: '/api/penitipan-transactions',
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                data: {
                    penitip_id: penitipId,
                    month: month,
                    year: year
                },
                success: function(response) {
                    tbody.empty();
                    if (response.length > 0) {
                        let totalHargaJualBersih = 0;
                        let totalBonus = 0;
                        let totalPendapatan = 0;

                        response.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td>${item.kode_produk || '-'}</td>
                                    <td>${item.nama_produk || '-'}</td>
                                    <td>${item.tanggal_masuk || '-'}</td>
                                    <td>${item.tanggal_laku || '-'}</td>
                                    <td>Rp ${item.harga_jual_bersih.toLocaleString('id-ID') || '0'}</td>
                                    <td>Rp ${item.bonus_terjual_cepat.toLocaleString('id-ID') || '0'}</td>
                                    <td>Rp ${item.pendapatan.toLocaleString('id-ID') || '0'}</td>
                                </tr>
                            `);
                            totalHargaJualBersih += item.harga_jual_bersih || 0;
                            totalBonus += item.bonus_terjual_cepat || 0;
                            totalPendapatan += item.pendapatan || 0;
                        });

                        tbody.append(`
                            <tr>
                                <td colspan="4">TOTAL</td>
                                <td>Rp ${totalHargaJualBersih.toLocaleString('id-ID')}</td>
                                <td>Rp ${totalBonus.toLocaleString('id-ID')}</td>
                                <td>Rp ${totalPendapatan.toLocaleString('id-ID')}</td>
                            </tr>
                        `);
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-muted text-center">Tidak ada transaksi penitipan yang ditemukan.</td></tr>');
                    }
                },
                error: function(xhr) {
                    showToast('Gagal memuat transaksi penitipan: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                }
            });
        }

        function loadActivityLogs(search = '', role = '') {
    $.ajax({
        url: '/api/activity-logs',
        method: 'GET',
        data: { search, role },
        success: function(logs) {
            const tbody = $('#logTableBody');
            tbody.empty();

            if (logs.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted py-5">Tidak ada aktivitas ditemukan.</td></tr>');
                return;
            }

            logs.forEach(log => {
                const time = new Date(log.logged_at).toLocaleString('id-ID', {
                    day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                const badgeColor = {
                    'Login': 'bg-success',
                    'Register': 'bg-primary',
                    'Login Failed': 'bg-danger',
                    'Logout': 'bg-secondary'
                }[log.action] || 'bg-info';

                // Sekarang kolom "nama" sudah berformat: "Budi Santoso (budi@gmail.com)" atau "Unknown User (RM001)"
                let userDisplay = log.nama;

                // Kalau masih format lama (hanya nama tanpa ID), kita pisah manual
                if (!userDisplay.includes('(')) {
                    userDisplay = log.nama 
                        ? `<strong>${log.nama}</strong><br><small class="text-muted">${log.user_id}</small>`
                        : `<strong>Unknown User</strong><br><small class="text-muted">${log.user_id}</small>`;
                } else {
                    // Format baru dari LogHelper → "Nama (email/ID)"
                    const namePart = userDisplay.split(' (')[0];
                    const idPart = userDisplay.match(/\((.*?)\)/)?.[1] || log.user_id;
                    userDisplay = `<strong>${namePart}</strong><br><small class="text-muted">${idPart}</small>`;
                }

                tbody.append(`
                    <tr>
                        <td><small>${time}</small></td>
                        <td>${userDisplay}</td>
                        <td><span class="badge bg-info text-dark">${log.user_type}</span></td>
                        <td><span class="badge ${badgeColor}">${log.action}</span></td>
                        <td><small>${log.description || '-'}</small></td>
                        <td><code>${log.ip_address}</code></td>
                    </tr>
                `);
            });
        },
        error: function() {
            showToast('Gagal memuat log aktivitas', 'error');
        }
    });
}

// === EXPORT PDF LOG ===
function exportLogPdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // landscape

    doc.setFontSize(16);
    doc.setTextColor(0, 170, 91);
    doc.text('ReUse Mart - Laporan Aktivitas Pengguna', 15, 20);

    doc.setFontSize(10);
    doc.setTextColor(100);
    doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 15, 30);

    const tableData = [];
    $('#logTableBody tr').each(function() {
        const row = [];
        $(this).find('td').each(function() {
            row.push($(this).text().replace(/^\s+|\s+$/g, ''));
        });
        if (row.length > 0) tableData.push(row);
    });

    doc.autoTable({
        head: [['Waktu', 'User', 'Tipe', 'Aksi', 'Deskripsi', 'IP']],
        body: tableData,
        startY: 40,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 3 },
        headStyles: { fillColor: [0, 170, 91] }
    });

    doc.save(`Log_Aktivitas_${new Date().toISOString().slice(0,10)}.pdf`);
    showToast('PDF Log berhasil diunduh!', 'success');
}        

        // Fungsi untuk memeriksa status filter
        function checkFilterStatus() {
            const penitipId = $('#penitipFilter').val();
            const month = $('#monthFilter').val();
            const year = $('#yearFilter').val();
            const isValid = penitipId && month && year;
            $('#printPenitipanPdf').prop('disabled', !isValid);
        }

        // Panggil saat dropdown berubah
        $('#penitipFilter, #monthFilter, #yearFilter').on('change', function() {
            checkFilterStatus();
        });

        

        // Load saat tab dibuka
        $('a[href="#users"]').on('shown.bs.tab', function() {
            loadPembeli();
        });

        $(document).ready(function () {

            checkFilterStatus();

            $('a[href="#activity-logs"]').on('shown.bs.tab', function() {
                loadActivityLogs();
            });

            // Search real-time
            let searchTimeout;
            $('#searchLog').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadActivityLogs($(this).val(), $('#filterRole').val());
                }, 500);
            });

            $('#filterRole').on('change', function() {
                loadActivityLogs($('#searchLog').val(), $(this).val());
            });

            $('#refreshLogs').on('click', function() {
                loadActivityLogs($('#searchLog').val(), $('#filterRole').val());
                showToast('Log diperbarui!', 'success');
            });

            $('#exportLogPdf').on('click', exportLogPdf);

            // Sidebar toggle for mobile
            $('#sidebarToggle').on('click', function () {
                $('.sidebar').toggleClass('d-none d-md-block');
                $('.content').toggleClass('ms-0 ms-md-250');
            });

            // Real-time search
            $('#searchOrganisasi').on('input', function() {
                const searchValue = $(this).val();
                loadOrganisasi(searchValue);
            });

            $('#searchButton').on('click', function() {
                const searchValue = $('#searchOrganisasi').val();
                loadOrganisasi(searchValue);
            });

            $('#searchOrganisasi').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const searchValue = $(this).val();
                    loadOrganisasi(searchValue);
                }
            });

            $(document).on('click', '.unlock-btn', function() {
    const email = $(this).closest('tr').data('email');
    if (!confirm(`Yakin ingin MEMBUKA akun:\n${email} ?`)) return;

    $.ajax({
        url: '/api/pembeli/unlock',
        method: 'POST',
        data: JSON.stringify({ alamat_email: email }),
        contentType: 'application/json',
        success: function() {
            showToast('Akun berhasil dibuka & attempt direset!', 'success');
            loadPembeli(); // Pastikan reload data
        },
        error: function() {
            showToast('Gagal unlock akun', 'error');
        }
    });
});

$(document).on('click', '.lock-btn', function() {
    const email = $(this).closest('tr').data('email');
    if (!confirm(`Yakin ingin MENGUNCI akun:\n${email} ?`)) return;

    $.ajax({
        url: '/api/pembeli/lock',
        method: 'POST',
        data: JSON.stringify({ alamat_email: email }),
        contentType: 'application/json',
        success: function() {
            showToast('Akun berhasil dikunci!', 'success');
            loadPembeli();
        },
        error: function() {
            showToast('Gagal lock akun', 'error');
        }
    });
});

            // AUTO LOAD KETIKA TAB DIBUKA
            $('a[data-bs-toggle="tab"][href="#users"]').on('shown.bs.tab', function () {
                loadPembeli();
            });

            $('#organisasi-table').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const alamat = $(this).data('alamat');
                const nomor = $(this).data('nomor');
                bukaModalEdit(id, nama, alamat, nomor);
            });

            $('#organisasi-table').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                deleteOrganisasi(id);
            });

            $('#tambahOrganisasiBtn').on('click', function() {
                bukaModalTambah();
            });

            $('#organisasiForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = $(this).attr('action');
                const method = document.getElementById('formMethod').value;

                const nama = document.getElementById('nama_organisasi').value;
                const alamat = document.getElementById('alamat_organisasi').value;
                const nomor = document.getElementById('nomor_telpon_organisasi').value;
                if (!nama || !alamat || !nomor) {
                    showToast('Semua field wajib diisi.', 'error');
                    return;
                }

                $.ajax({
                    url: url,
                    method: method === 'PUT' ? 'POST' : method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'Accept': 'application/json' },
                    success: function(response) {
                        showToast(response.message, 'success');
                        loadOrganisasi();
                        $('#organisasiModal').modal('hide');
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal menyimpan organisasi: ';
                        if (xhr.responseJSON?.message) {
                            errorMsg += xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON?.errors) {
                            errorMsg += ' ' + Object.values(xhr.responseJSON.errors).flat().join(' ');
                        }
                        showToast(errorMsg, 'error');
                    }
                });
            });

            $('#transaksi-table').on('click', '.valid-btn', function() {
                const id = $(this).data('id');
                const pembeliEmail = $(this).data('pembeli');
                validateTransaksi(id, true, pembeliEmail);
            });

            $('#transaksi-table').on('click', '.invalid-btn', function() {
                const id = $(this).data('id');
                const pembeliEmail = $(this).data('pembeli');
                validateTransaksi(id, false, pembeliEmail);
            });

            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr('href').substring(1);
                if (target === 'organisasi') {
                    loadOrganisasi();
                } else if (target === 'transactions') {
                    loadTransaksi();
                } else if (target === 'donasi-laporan') {
                    loadDonasi();
                } else if (target === 'request-donasi') {
                    loadRequestDonasi();
                }
            });

            $('#searchDonasi').on('input', function() {
                const searchValue = $(this).val();
                loadDonasi(searchValue);
            });

            $('#searchDonasiButton').on('click', function() {
                const searchValue = $('#searchDonasi').val();
                loadDonasi(searchValue);
            });

            $('#searchDonasi').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const searchValue = $(this).val();
                    loadDonasi(searchValue);
                }
            });

            $('#printDonasiPdf').on('click', function() {
                printDonasiPdf();
            });

            $('#searchRequestDonasi').on('input', function() {
                const searchValue = $(this).val();
                loadRequestDonasi(searchValue);
            });

            $('#searchRequestDonasiButton').on('click', function() {
                const searchValue = $('#searchRequestDonasi').val();
                loadRequestDonasi(searchValue);
            });

            $('#searchRequestDonasi').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const searchValue = $(this).val();
                    loadRequestDonasi(searchValue);
                }
            });

            $('#printRequestDonasiPdf').on('click', function() {
                printRequestDonasiPdf();
            });

            function printPenitipanPdf() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                const currentDate = new Date().toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                const penitipId = $('#penitipFilter').val();
                const month = $('#monthFilter').val();
                const year = $('#yearFilter').val();
                const monthName = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ][month - 1] || '';

                let startX = 20; // Posisi x untuk align left
                let startY = 20;  // Posisi Y mulai
                let lineSpacing = 8; // Jarak antar baris

                // Desain judul mirip logo navbar (align left)
                doc.setFontSize(18);
                doc.setTextColor(0, 170, 91); // Warna hijau #00aa5b
                doc.text('ReUse Mart', startX, startY, null, null, 'left');

                doc.setFontSize(12);
                doc.setTextColor(0, 0, 0); // Kembali ke warna hitam
                doc.text('Jl. Green Eco Park No. 456 Yogyakarta', startX, startY + lineSpacing, null, null, 'left');

                // Header utama dan informasi
                doc.setFontSize(14);
                doc.text('LAPORAN TRANSAKSI PENITIP', startX, startY + lineSpacing * 3, null, null, 'left');

                doc.setFontSize(10);
                doc.text(`ID Penitip: ${penitipId || '-'}` , startX, startY + lineSpacing * 4, null, null, 'left');
                doc.text(`Nama Penitip: ${$('#penitipFilter option:selected').text() || '-'}` , startX, startY + lineSpacing * 5, null, null, 'left');
                doc.text(`Bulan: ${monthName}`, startX, startY + lineSpacing * 6, null, null, 'left');
                doc.text(`Tahun: ${year || '-'}` , startX, startY + lineSpacing * 7, null, null, 'left');
                doc.text(`Tanggal Cetak: ${currentDate}`, startX, startY + lineSpacing * 8, null, null, 'left');

                // Garis pembatas seperti surat resmi
                doc.setDrawColor(0, 0, 0); // Warna garis hitam
                doc.line(startX, startY + lineSpacing * 9, 190, startY + lineSpacing * 9); // Garis horizontal (190 adalah lebar maksimum halaman A4 dalam mm)

                // Hitung posisi mulai tabel setelah garis pembatas
                let tableStartY = startY + lineSpacing * 10; // Tambahan jarak setelah garis

                // Ambil data tabel
                const tableData = [];
                $('#penitipan-table-body tr').each(function() {
                    const row = [];
                    $(this).find('td').each(function() {
                        row.push($(this).text().replace('Rp ', '').replace(/,/g, ''));
                    });
                    if (row.length > 0 && row[0] !== 'TOTAL') tableData.push(row);
                });

                // Generate tabel
                doc.autoTable({
                    head: [[
                        'Kode Produk', 'Nama Produk', 'Tanggal Masuk',
                        'Tanggal Laku', 'Harga Jual Bersih',
                        'Bonus Terjual Cepat', 'Pendapatan'
                    ]],
                    body: tableData,
                    startY: tableStartY,
                    theme: 'striped',
                    styles: { fontSize: 10 },
                    margin: { top: 20 }
                });

                // Cek kalau ada data, tampilkan baris TOTAL di bawah tabel
                if (tableData.length > 0) {
                    const totalRow = [
                        '', '', '', 'TOTAL',
                        $('#penitipan-table-body tr:last-child td:nth-child(5)').text().replace('Rp ', '').replace(/,/g, ''),
                        $('#penitipan-table-body tr:last-child td:nth-child(6)').text().replace('Rp ', '').replace(/,/g, ''),
                        $('#penitipan-table-body tr:last-child td:nth-child(7)').text().replace('Rp ', '').replace(/,/g, '')
                    ];
                    doc.autoTable({
                        body: [totalRow],
                        startY: doc.lastAutoTable.finalY + 10, // mulai setelah tabel sebelumnya
                        theme: 'striped',
                        styles: { fontSize: 10, fontStyle: 'bold' } // teks TOTAL bold
                    });
                }

                // Save file
                doc.save(`Laporan_Transaksi_Penitipan_${currentDate.replace(/[:/]/g, '-')}.pdf`);
            }

            // Load daftar penitip saat halaman dimuat
            loadPenitipList();

            // Filter transaksi saat dropdown berubah
            $('#penitipFilter, #monthFilter, #yearFilter').on('change', function() {
                const penitipId = $('#penitipFilter').val();
                const month = $('#monthFilter').val();
                const year = $('#yearFilter').val();
                loadPenitipanTransactions(penitipId, month, year);
            });

            // Panggil fungsi cetak PDF
            $('#printPenitipanPdf').on('click', function() {
                printPenitipanPdf();
            });

            $('#confirmLogout').on('click', function() {
                localStorage.removeItem('access_token');
                window.location.href = '/admin-login';
            });

            if (!token) {
                showToast('Sesi tidak valid, silakan login kembali', 'error');
                setTimeout(() => {
                    window.location.href = '/admin-login';
                }, 2000);
                return;
            }

            $.ajax({
                url: '/api/user',
                method: 'GET',
                success: function(response) {
                    console.log('Response dari /api/user:', response);
                    $('#userName').text(response.nama_pegawai || 'Admin');
                    if (response.id_role === 'R44') {
                        console.log('id_role adalah R44, menampilkan tab Manajemen Transaksi');
                        $('#manajemen-transaksi-tab').show();
                    } else {
                        $('#manajemen-transaksi-tab').hide();
                    }
                    // Tampilkan tab Request Donasi hanya untuk id_role R11 atau owner (misal R01)
                    if (response.id_role === 'R11') {
                        console.log('id_role adalah R11 atau R01, menampilkan tab Request Donasi');
                        $('#request-donasi-tab').show();
                    } else {
                        $('#request-donasi-tab').hide();
                    }

                    if (response.id_role === 'R11') {
                        console.log('id_role adalah R11 atau R01, menampilkan tab Request Donasi');
                        $('#laporan-donasi-tab').show();
                    } else {
                        $('#laporan-donasi-tab').hide();
                    }

                    

                    if (response.id_role === 'R11') {
                        $('#laporan-penitipan-tab').show();
                    } else {
                        $('#laporan-penitipan-tab').hide();
                    }
                },
                error: function(xhr) {
                    console.log('Error dari /api/user:', xhr.responseJSON);
                    $('#userName').text('Tidak autentikasi');
                    localStorage.removeItem('access_token');
                    showToast('Sesi tidak valid, silakan login kembali', 'error');
                    setTimeout(() => {
                        window.location.href = '/admin-login';
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html>