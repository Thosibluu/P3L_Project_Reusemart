<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand { font-weight: bold; color: #00aa5b !important; }
        .card { background-color: #ffffff; border-radius: 1rem; transition: 0.3s ease; margin-bottom: 15px; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 0 25px rgba(0,0,0,0.05); }
        .btn-outline-primary, .btn-danger { min-width: 120px; }
        .address-container { display: flex; flex-wrap: nowrap; gap: 15px; overflow-x: auto; padding: 10px 0; }
        .address-card { width: 300px; flex: 0 0 auto; }
        .opacity-50 { opacity: 0.5; transition: opacity 0.3s ease; }
        #loadingSpinner { margin-top: 10px; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1055; }
        .search-container { display: flex; align-items: center; gap: 10px; }
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
        <div class="row g-4" id="profile-content">
            <!-- Profile Card will be loaded via AJAX -->
        </div>
    </div>

    <div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="alamatForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="alamatModalLabel">Tambah Alamat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_alamat" id="id_alamat">
                        <div class="mb-3">
                            <label for="nama_alamat" class="form-label">Nama Alamat</label>
                            <input type="text" name="nama_alamat" id="nama_alamat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_alamat" class="form-label">Jenis Alamat</label>
                            <select name="jenis_alamat" id="jenis_alamat" class="form-select" required>
                                <option value="">Pilih Jenis Alamat</option>
                                <option value="Rumah">Rumah</option>
                                <option value="Kantor">Kantor</option>
                                <option value="Apartemen">Apartemen</option>
                                <option value="Toko">Toko</option>
                            </select>
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

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus alamat ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
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
        // Define functions globally so they can be accessed by onclick
        function getToken() {
            const urlParams = new URLSearchParams(window.location.search);
            const tokenFromUrl = urlParams.get('token');
            return tokenFromUrl || localStorage.getItem('access_token') || '';
        }

        // Function to show toast notifications
        function showToast(message, type = 'success') {
            const toastContainer = $('.toast-container');
            const toastId = 'toast-' + new Date().getTime(); // Unique ID for each toast

            // Define toast classes based on type
            const toastClass = type === 'success' ? 'bg-success text-white' : 'bg-danger text-white';

            // Create toast HTML
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

            // Append toast to container
            toastContainer.append(toastHtml);

            // Initialize and show the toast
            const toastElement = $(`#${toastId}`);
            const toast = new bootstrap.Toast(toastElement[0], { delay: 3000 }); // Auto-hide after 3 seconds
            toast.show();

            // Remove toast from DOM after it hides
            toastElement.on('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }

        function bukaModalCreate() {
            console.log('Opening create modal'); // Debugging
            const form = document.getElementById('alamatForm');
            if (!form) console.error('alamatForm not found');
            form.action = '/api/alamats'; // Ensure correct API endpoint
            form.querySelector('[name="_method"]')?.remove();
            document.getElementById('alamatModalLabel').innerText = 'Tambah Alamat';
            document.getElementById('id_alamat').value = '';
            document.getElementById('nama_alamat').value = '';
            document.getElementById('jenis_alamat').value = '';
            const modalElement = document.getElementById('alamatModal');
            if (!modalElement) console.error('alamatModal not found');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function bukaModalEdit(id, nama, jenis) {
            console.log('Opening edit modal for ID:', id); // Debugging
            const form = document.getElementById('alamatForm');
            if (!form) console.error('alamatForm not found');
            form.action = '/api/alamats/' + id; // Ensure correct API endpoint
            form.querySelector('[name="_method"]')?.remove();
            document.getElementById('alamatModalLabel').innerText = 'Edit Alamat';
            document.getElementById('id_alamat').value = id;
            document.getElementById('nama_alamat').value = nama;
            document.getElementById('jenis_alamat').value = jenis;
            const modalElement = document.getElementById('alamatModal');
            if (!modalElement) console.error('alamatModal not found');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function deleteAddress(id) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();

            $('#confirmDelete').off('click').on('click', function() {
                const token = getToken();

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                console.log('Deleting ID:', id, 'Token:', token); // Debugging

                $.ajax({
                    url: '/api/alamats/' + id,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        showToast(response.message, 'success');
                        loadAddresses();
                        modal.hide();
                    },
                    error: function(xhr) {
                        console.log('Delete Error:', xhr); // Debugging
                        if (xhr.status === 401 || xhr.status === 403) {
                            showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                            window.location.href = '/login';
                        } else {
                            showToast('Gagal menghapus alamat. ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                        }
                    }
                });
            });
        }

        function setUtama(id) {
            const token = getToken();

            if (!token) {
                window.location.href = '/login';
                return;
            }

            console.log('Setting Utama ID:', id, 'Token:', token); // Debugging

            $.ajax({
                url: '/api/alamats/' + id + '/set-utama',
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    showToast(response.message, 'success');
                    loadAddresses();
                },
                error: function(xhr) {
                    console.log('Set Utama Error:', xhr); // Debugging
                    if (xhr.status === 401 || xhr.status === 403) {
                        showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                        window.location.href = '/login';
                    } else {
                        showToast('Gagal mengatur alamat utama. ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    }
                }
            });
        }

        function loadAddresses(search = '') {
            const token = getToken();

            if (!token) {
                window.location.href = '/login';
                return;
            }

            $('#loadingSpinner').removeClass('d-none');
            $('#addressContainer').addClass('opacity-50');

            $.ajax({
                url: '/api/alamats?search=' + encodeURIComponent(search),
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('API Response for search:', search, response); // Debugging
                    const addressContainer = $('#addressContainer');
                    addressContainer.empty();
                    if (response.length > 0) {
                        response.forEach((alamat, index) => {
                            addressContainer.append(`
                                <div class="card address-card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">${alamat.id_alamat}</h5>
                                        <p class="card-text"><strong>Nama:</strong> ${alamat.nama_alamat}</p>
                                        <p class="card-text"><strong>Tipe:</strong> ${alamat.jenis_alamat}</p>
                                        <p class="card-text"><strong>Status:</strong>
                                            ${alamat.status_alamat === 'Utama' ? '<span class="badge bg-success">Utama</span>' : '<span class="badge bg-secondary">Cadangan</span>'}
                                        </p>
                                        <div class="mt-3">
                                            <button class="btn btn-sm btn-warning me-2" onclick="bukaModalEdit('${alamat.id_alamat}', '${alamat.nama_alamat}', '${alamat.jenis_alamat}')">Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteAddress('${alamat.id_alamat}')">Hapus</button>
                                            ${alamat.status_alamat !== 'Utama' ? '<button class="btn btn-sm btn-outline-primary mt-2" onclick="setUtama(\'' + alamat.id_alamat + '\')">Jadikan Utama</button>' : ''}
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        addressContainer.append('<p class="text-muted">Tidak ada alamat yang cocok dengan pencarian.</p>');
                    }
                },
                error: function(xhr) {
                    console.log('Load Addresses Error:', xhr); // Debugging
                    if (xhr.status === 401 || xhr.status === 403) {
                        showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                        window.location.href = '/login';
                    } else {
                        showToast('Gagal memuat alamat. ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    }
                },
                complete: function() {
                    $('#loadingSpinner').addClass('d-none');
                    $('#addressContainer').removeClass('opacity-50');
                }
            });
        }

        $(document).ready(function () {
            

            function loadProfile() {
                const token = getToken();

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                $.ajax({
                    url: '/api/profile',
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        let profileHtml = `
                            <div class="col-md-5">
                                <div class="card shadow-lg p-4 rounded-4">
                                    <div class="text-center">
                                        
                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="modal" data-bs-target="#showModal">${response.nama}</a></li>
                                        <p class="text-muted">${response.email}</p>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Nomor Telepon:</strong>
                                        <p>${response.no_hp}</p>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <img src="${response.foto || '{{ asset('img/img1.jpg') }}'}" class="rounded-circle mb-3" width="120" height="120" alt="Foto Profil" style="object-fit: cover;">
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                    <strong>Total Poin:</strong>
                                                    <p>${response.total_poin}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        let addressHtml = `
                            <div class="col-md-7">
                                <div class="card shadow-lg p-4 rounded-4 h-100">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="search-container">
                                            <input type="text" id="searchAddress" class="form-control w-50" placeholder="Cari alamat...">
                                            <button class="btn btn-outline-primary" id="searchButton">Cari</button>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary" onclick="bukaModalCreate()">+ Tambah Alamat</button>
                                        </div>
                                    </div>
                                    <div id="loadingSpinner" class="text-center d-none">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Memuat...</span>
                                        </div>
                                    </div>
                                    <div class="address-container" id="addressContainer">
                        `;

                        if (response.alamats && response.alamats.length) {
                            response.alamats.forEach((alamat, index) => {
                                addressHtml += `
                                    <div class="card address-card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">${alamat.id_alamat}</h5>
                                            <p class="card-text"><strong>Nama:</strong> ${alamat.nama_alamat}</p>
                                            <p class="card-text"><strong>Tipe:</strong> ${alamat.jenis_alamat}</p>
                                            <p class="card-text"><strong>Status:</strong>
                                                ${alamat.status_alamat === 'Utama' ? '<span class="badge bg-success">Utama</span>' : '<span class="badge bg-secondary">Cadangan</span>'}
                                            </p>
                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-warning me-2" onclick="bukaModalEdit('${alamat.id_alamat}', '${alamat.nama_alamat}', '${alamat.jenis_alamat}')">Edit</button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteAddress('${alamat.id_alamat}')">Hapus</button>
                                                ${alamat.status_alamat !== 'Utama' ? '<button class="btn btn-sm btn-outline-primary mt-2" onclick="setUtama(\'' + alamat.id_alamat + '\')">Jadikan Utama</button>' : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            addressHtml += '<p class="text-muted">Tidak ada alamat.</p>';
                        }

                        addressHtml += `</div></div></div>`;
                        profileHtml += addressHtml;

                        $('#profile-content').html(profileHtml);
                        loadAddresses(); // Load all addresses initially
                    },
                    error: function(xhr) {
                        console.log('Profile Error:', xhr); // Debugging
                        if (xhr.status === 401 || xhr.status === 403) {
                            showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                            window.location.href = '/login';
                        } else {
                            showToast('Gagal memuat profil. Silakan coba lagi.', 'error');
                        }
                    }
                });
            }


            // Real-time search (default: enabled)
            $('#searchAddress').on('input', function() {
                if (isRealTimeSearch) {
                    const searchValue = $(this).val();
                    loadAddresses(searchValue);
                }
            });

            // Search on Enter key
            $('#searchAddress').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    const searchValue = $(this).val();
                    loadAddresses(searchValue);
                }
            });

            // Search button click with event delegation
            $(document).on('click', '#searchButton', function() {
                const searchValue = $('#searchAddress').val();
                console.log('Search button clicked with value:', searchValue); // Debugging
                loadAddresses(searchValue);
            });

            $('#alamatForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = $(this).attr('action'); // Use dynamic action set by bukaModalCreate/Edit
                const method = formData.get('id_alamat') ? 'POST' : 'POST'; // Both use POST as per api.php
                const token = getToken();

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                console.log('Submitting to:', url, 'Method:', method, 'Token:', token); // Debugging

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        showToast(response.message, 'success');
                        loadAddresses();
                        $('#alamatModal').modal('hide');
                    },
                    error: function(xhr) {
                        console.log('Form Submit Error:', xhr); // Debugging
                        if (xhr.status === 401 || xhr.status === 403) {
                            showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                            window.location.href = '/login';
                        } else {
                            showToast('Gagal menyimpan alamat. ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                        }
                    }
                });
            });

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

            loadProfile();
        });
    </script>
</body>
</html>