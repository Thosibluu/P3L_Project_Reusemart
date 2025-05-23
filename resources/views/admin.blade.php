<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
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
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
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
                <h2>Manajemen Pengguna</h2>
                <p>Halaman untuk mengelola pengguna akan ditambahkan di sini.</p>
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
                    <!--<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#organisasiModal" id="tambahOrganisasiBtn">Tambah Organisasi</button>-->
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
                    <button type="button" class="btn btn-primary" onclick="window.location.href='/admin-login'">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

        $(document).ready(function () {
            // Sidebar toggle for mobile
            $('#sidebarToggle').on('click', function () {
                $('.sidebar').toggleClass('d-none d-md-block');
                $('.content').toggleClass('ms-0 ms-md-250');
            });

            // Load organizations when tab is shown
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr('href').substring(1);
                if (target === 'organisasi') {
                    loadOrganisasi();
                }
            });

            // Real-time search
            $('#searchOrganisasi').on('input', function() {
                const searchValue = $(this).val();
                loadOrganisasi(searchValue);
            });

            // Search on button click
            $('#searchButton').on('click', function() {
                const searchValue = $('#searchOrganisasi').val();
                loadOrganisasi(searchValue);
            });

            // Search on Enter key
            $('#searchOrganisasi').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const searchValue = $(this).val();
                    loadOrganisasi(searchValue);
                }
            });

            // Event delegation for dynamically added buttons
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

            // Handle "Tambah Organisasi" button click
            $('#tambahOrganisasiBtn').on('click', function() {
                bukaModalTambah();
            });

            // Handle form submission for create/edit
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

            // Check authentication status client-side
            const token = localStorage.getItem('access_token');
            if (!token) {
                $('#userName').text('Tidak autentikasi');
                console.warn('No token found, consider redirecting to /admin-login');
            } else {
                $.ajax({
                    url: '/api/user',
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + token },
                    success: function(response) {
                        $('#userName').text(response.nama_pegawai || 'Admin');
                    },
                    error: function() {
                        $('#userName').text('Tidak autentikasi');
                        localStorage.removeItem('access_token');
                        console.warn('Invalid token, consider redirecting to /admin-login');
                    }
                });
            }
        });

        $(document).ajaxSend(function(event, jqxhr, settings) {
            const token = localStorage.getItem('access_token');
            if (token && !settings.url.includes('/api/')) {
                jqxhr.setRequestHeader('Authorization', 'Bearer ' + token);
            }
        });
    </script>
</body>
</html>