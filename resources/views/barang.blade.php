<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $barang->nama_produk }} | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1200px; }
        .navbar-brand { color: #00aa5b !important; font-weight: bold; }
        .image-section img { width: 100%; height: auto; max-height: 400px; object-fit: cover; }
        .info-section { padding-left: 20px; }
        .button-section { padding-left: 20px; }
        .rounded-circle { border-radius: 50%; object-fit: cover; }
        .card { border: none; background-color: #fff; }
        .btn-custom { width: 100%; margin-bottom: 10px; }
        .modal-content { border-radius: 10px; }
        .comment-item { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .comment-item img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .comment-text { flex-grow: 1; }
        .no-comments { color: #666; font-style: italic; text-align: center; }
        .navbar-brand i { margin-right: 8px; }
        .penitip-message { display: none; color: #dc3545; text-align: center; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1055; }
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
                        <img src="{{ asset('img/img1.jpg') }}" alt="Profil" width="40" height="40" class="rounded-circle me-2" >
                        <span id="user-name" class="me-3">Pengguna</span>
                    </li>
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

    <div class="container py-4">
        <div class="row">
            <!-- Left: Image Section -->
            <div class="col-md-4 image-section">
                <img src="{{ $barang->gambar ? asset('storage/barang/' . $barang->gambar) : asset('img/img1.jpg') }}" alt="{{ $barang->nama_produk }}" class="img-fluid rounded">
            </div>

            <!-- Center: Info Section -->
            <div class="col-md-4 info-section">
                <h2>{{ $barang->nama_produk }}</h2>
                <p><strong>Harga:</strong> Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                <p><strong>Penitip:</strong> {{ $penitipName }}</p>
                <p><strong>Status Perpanjang:</strong> {{ $barang->status_perpanjang }}</p>
                <p><strong>Deskripsi:</strong> {{ $barang->deskripsi ?: '(Deskripsi belum tersedia)' }}</p>
            </div>

            <!-- Right: Button Section -->
            <div class="col-md-4 button-section">
                <div class="card p-3 h-100">
                    <button class="btn btn-success btn-custom" disabled>Checkout</button>
                    <button class="btn btn-primary btn-custom" disabled>Beli</button>
                    <button class="btn btn-info btn-custom" data-bs-toggle="modal" data-bs-target="#diskusiModal">Pesan Diskusi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Diskusi -->
    <div class="modal fade" id="diskusiModal" tabindex="-1" aria-labelledby="diskusiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diskusiModalLabel">Pesan Diskusi - {{ $barang->nama_produk }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="comment-list" class="mb-3">
                        @if ($komentars->isEmpty())
                            <p class="no-comments">Tidak ada komentar untuk produk ini</p>
                        @else
                            @foreach ($komentars as $komentar)
                                <div class="comment-item">
                                    <img src="{{ asset('img/img3.jpg') }}" alt="Profile" class="rounded-circle">
                                    <div class="comment-text">
                                        <strong>{{ $komentar->pembeli->nama_pembeli }}</strong><br>
                                        {{ $komentar->komentar }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p class="penitip-message">Tidak dapat melakukan komentar.</p>
                    <form id="commentForm" method="POST" action="/api/komentar/{{ $barang->kode_produk }}">
                        @csrf
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Tambah Komentar</label>
                            <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="alamat_email" id="alamat_email" value="">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
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

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center text-muted py-3">
                    © 2025 Reusemart. Semua hak dilindungi.
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define functions globally
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

        function loadUserData() {
            const token = getToken();

            if (!token) {
                window.location.href = '/login';
                return;
            }

            $.ajax({
                url: '/api/profile', // Adjust to '/api/profile-penitip' if needed based on role
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                success: function(response) {
                    const user = response;
                    const alamat_email = user.email;
                    const userName = user.nama;
                    const profileImage = user.foto || '{{ asset('img/img1.jpg') }}';
                    const role = user.role || 'pembeli'; // Fallback role

                    // Debug: Log the fetched user data
                    console.log('Fetched User Data:', user);

                    // Update navbar with user data
                    $('#user-name').text(userName || 'Pengguna');
                    $('#user-image').attr('src', profileImage);

                    // Set the hidden email field
                    $('#alamat_email').val(alamat_email || '');

                    // Handle role: hide form and show message for penitip
                    if (role === 'penitip') {
                        $('#commentForm').hide();
                        $('.penitip-message').show();
                    } else {
                        $('#commentForm').show();
                        $('.penitip-message').hide();
                    }
                },
                error: function(xhr) {
                    console.log('Load User Data Error:', xhr.responseJSON);
                    if (xhr.status === 401 || xhr.status === 403) {
                        showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                        window.location.href = '/login';
                    } else {
                        showToast('Gagal memuat data pengguna. ' + (xhr.responseJSON?.error || 'Coba lagi.'), 'error');
                    }
                }
            });
        }

        $(document).ready(function () {
            // Load user data on page load
            loadUserData();

            $('#commentForm').on('submit', function (e) {
                e.preventDefault();
                const token = getToken();
                const kode_produk = '{{ $barang->kode_produk }}';
                const komentar = $('#komentar').val();
                const alamat_email = $('#alamat_email').val();

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                if (!komentar) {
                    showToast('Komentar tidak boleh kosong.', 'error');
                    return;
                }

                if (!alamat_email) {
                    showToast('Penitip Tidak Dapat Komentar!.', 'error');
                    return;
                }

                $.ajax({
                    url: `/api/komentar/${kode_produk}`,
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                    data: { komentar: komentar, alamat_email: alamat_email, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        $('#comment-list').html(`
                            <div class="comment-item">
                                <img src="${$('#user-image').attr('src') || '{{ asset('img/img3.jpg') }}'}" alt="Profile" class="rounded-circle">
                                <div class="comment-text">
                                    <strong>${$('#user-name').text() || 'Pengguna'}</strong><br>
                                    ${komentar}
                                </div>
                            </div>
                        ` + $('#comment-list').html().replace('<p class="no-comments">Tidak ada komentar untuk produk ini</p>', ''));
                        $('#komentar').val('');
                        showToast('Komentar berhasil ditambahkan!', 'success');
                    },
                    error: function (xhr) {
                        console.log('Comment Submit Error:', xhr.responseJSON);
                        showToast('Gagal menambahkan komentar: ' + (xhr.responseJSON?.message || 'Coba lagi.'), 'error');
                    }
                });
            });

            function confirmLogout() {
                const token = getToken();
                const role = localStorage.getItem('role'); // Use role for logout endpoint
                const endpoint = role === 'pembeli' ? '/api/pembeli-logout' : '/api/penitip-logout';

                if (!token) {
                    window.location.href = '/login';
                    return;
                }

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
        });
    </script>
</body>
</html>