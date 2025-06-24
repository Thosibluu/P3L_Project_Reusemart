<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $barang->nama_produk }} | Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1200px; }
        .navbar-brand { color: #00aa5b !important; font-weight: bold; }
        .image-section img { width: 100%; height: auto; max-height: 400px; aspect-ratio: 16 / 9; object-fit: cover; }
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
        .floating-cart { position: fixed; bottom: 20px; right: 20px; z-index: 1050; }
        .cart-modal .modal-body { max-height: 400px; overflow-y: auto; }
        .cart-item { display: flex; align-items: center; gap: 15px; padding: 10px 0; border-bottom: 1px solid #eee; }
        .cart-item img { width: 60px; height: 60px; aspect-ratio: 16 / 9; object-fit: cover; border-radius: 5px; }
        .cart-item .item-details { flex-grow: 1; }
        .cart-item .item-details strong { display: block; margin-bottom: 5px; }
        .checkout-modal .modal-dialog { max-width: 80%; }
        .payment-modal .modal-dialog { max-width: 80%; }
        .success-alert { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1060; display: none; }
        .item-card img { width: 50px; height: 50px; aspect-ratio: 16 / 9; object-fit: cover; }
        .checkout-section { padding: 20px; margin-bottom: 20px; border-bottom: 1px solid #eee; }
        .checkout-section:last-child { border-bottom: none; }
        .text-right { text-align: right; }
        .alert-text { color: #dc3545; font-size: 0.9rem; margin-top: 5px; display: none; }
        .bank-details-card { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        img[onerror] { aspect-ratio: 16 / 9; object-fit: cover; }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Success Alert -->
    <div class="alert success-alert alert-success text-center p-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> Pembayaran berhasil! Anda akan dialihkan...
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/home"><i class="bi bi-cart-check"></i>Reusemart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <img src="/img/barang_image2.jpg" alt="Profil" width="40" height="40" class="rounded-circle me-2" id="user-image">
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
                <img src="{{ asset('img/' . $barang->gambar) }}" class="card-img-top" alt="{{ $barang->nama_produk }}" width="720" height="405" onerror="this.src='{{ asset('img/img1.jpg') }}';">
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
                    <button class="btn btn-success btn-custom add-to-cart" data-kode="{{ $barang->kode_produk }}" data-nama="{{ $barang->nama_produk }}" data-harga="{{ $barang->harga }}" data-gambar="{{ $barang->gambar }}">Add to Cart</button>
                    <button class="btn btn-primary btn-custom" onclick="buyNow('{{ $barang->kode_produk }}', '{{ $barang->nama_produk }}', '{{ $barang->harga }}', '{{ $barang->gambar }}')">Beli</button>
                    <button class="btn btn-info btn-custom" data-bs-toggle="modal" data-bs-target="#diskusiModal">Pesan Diskusi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Cart Button -->
    <button class="btn btn-success btn-lg floating-cart d-none" id="cartButton" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="bi bi-cart-fill"></i> (<span id="cartCount">0</span>)
    </button>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Keranjang Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body cart-modal">
                    <div id="cartItems"></div>
                    <button class="btn btn-success w-100 mt-3" onclick="buyFromCart()">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade checkout-modal" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="showCancelConfirmation('checkout')"></button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm" action="/checkout" method="POST">
                        <input type="hidden" name="cart_data" id="checkoutCartData">
                        <input type="hidden" name="source" id="checkoutSource">

                        <!-- Shipping Method and Address Section -->
                        <div class="checkout-section">
                            <h5>Metode Pengiriman</h5>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="shippingMethod" id="courier" value="courier" checked>
                                        <label class="form-check-label" for="courier">Kurir</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="shippingMethod" id="selfPickup" value="selfPickup">
                                        <label class="form-check-label" for="selfPickup">Ambil Sendiri</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col" id="addressList"></div>
                            </div>
                            <div id="checkoutError" class="alert-text" style="display: none;"></div>
                        </div>
                        <!-- Item List Section -->
                        <div class="checkout-section">
                            <h5>Daftar Barang</h5>
                            <div id="checkoutItemList"></div>
                        </div>

                        <!-- Total and Points Section -->
                        <div class="checkout-section">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-start"><h5>Informasi Harga dan Poin</h5></td>
                                        <td class="text-end"><button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#pointsModal">Gunakan Poin</button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start"><strong>Total Harga Barang:</strong></td>
                                        <td class="text-end" id="totalItemsPrice">0</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start"><strong>Harga Ongkir:</strong></td>
                                        <td class="text-end" id="shippingCost">0</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start d-flex align-items-center">
                                            <strong>Potongan Poin:</strong>
                                        </td>
                                        <td class="text-end">
                                            <span id="pointsDiscount">0</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start"><strong>Total Harga Akhir:</strong></td>
                                        <td class="text-end" id="totalPrice">0</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start"><strong>Poin yang Akan Didapat:</strong></td>
                                        <td class="text-end" id="pointsEarned">0</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Hidden input tetap di bawah -->
                            <input type="hidden" name="points_used" id="pointsUsed" value="0">
                            <button type="submit" class="btn btn-success w-100 mt-3" id="proceedToPayment">Lanjut ke Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Points Modal -->
    <div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pointsModalLabel">Gunakan Poin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Total Poin Anda: <span id="userPoints">0</span></p>
                    <div class="mb-3">
                        <label for="pointsInput" class="form-label">Masukkan Jumlah Poin</label>
                        <input type="number" class="form-control" id="pointsInput" min="0" placeholder="Masukkan poin">
                    </div>
                    <div id="pointsError" class="alert-text"></div>
                    <button type="button" class="btn btn-primary" id="applyPoints">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade payment-modal" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="showCancelConfirmation('payment')"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="transactionId">

                    <!-- Countdown Timer -->
                    <div class="card p-4 mb-4 text-center">
                        <h5 class="mb-3">Waktu Tersisa untuk Pembayaran</h5>
                        <p id="countdownTimer" style="font-size: 48px; margin: 0;"><strong>01:00</strong></p>
                    </div>

                    <!-- Bank Account Details -->
                    <div class="card bank-details-card mb-4 p-4">
                        <h5>Detail Rekening Reusemart</h5>
                        <div class="row mb-2">
                            <div class="col text-start"><strong>Bank:</strong></div>
                            <div class="col text-end">Bank BACA</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col text-start"><strong>Nomor Rekening:</strong></div>
                            <div class="col text-end">123-456-7890123</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col text-start"><strong>Atas Nama:</strong></div>
                            <div class="col text-end">Reusemart Yogyakarta</div>
                        </div>
                    </div>

                    <!-- Upload Payment Proof -->
                    <div class="card p-4 mb-4">
                        <h5>Upload Bukti Pembayaran</h5>
                        <form id="paymentForm" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="paymentProof" class="form-label">Pilih Bukti Pembayaran</label>
                                <input type="file" class="form-control" id="paymentProof" name="payment_proof" accept="image/*">
                            </div>
                            <div id="paymentError" class="alert-text"></div>
                            <button type="submit" class="btn btn-primary w-100" id="confirmPayment">Konfirmasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade" id="cancelConfirmationModal" tabindex="-1" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelConfirmationModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="cancelMessage">Apakah Anda yakin ingin membatalkan checkout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-danger" id="confirmCancel">Ya, Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Confirmation Modal -->
    <div class="modal fade" id="successConfirmationModal" tabindex="-1" aria-labelledby="successConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <!-- Section 1: Icon dan Teks Sukses -->
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill" style="font-size: 48px; color: #28a745;"></i>
                        <h4 class="mt-2" id="successMessage">Pembayaran Berhasil!</h4>
                        <p id="paymentDate" class="text-muted"></p>
                    </div>

                    <!-- Section 2: Total Harga dan Poin Didapatkan -->
                    <div class="mb-4">
                        <h6><strong>Total Harga yang Dibayar:</strong></h6>
                        <p id="paidTotalPrice" class="mb-2">Rp0</p>
                        <h6><strong>Poin yang Didapatkan:</strong></h6>
                        <p id="earnedPoints">0</p>
                    </div>

                    <!-- Section 3: Detail Barang yang Dibeli -->
                    <div class="mb-4">
                        <h6><strong>Detail Barang yang Dibeli:</strong></h6>
                        <ul id="purchasedItems" class="list-unstyled"></ul>
                    </div>

                    <!-- Section 4: Total Harga dan Poin (Tanpa Button) -->
                    <div>
                        <h6><strong>Ringkasan Transaksi:</strong></h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-start"><strong>Total Harga Barang:</strong></td>
                                    <td class="text-end" id="summaryTotalItemsPrice">Rp0</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Harga Ongkir:</strong></td>
                                    <td class="text-end" id="summaryShippingCost">Rp0</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Potongan Poin:</strong></td>
                                    <td class="text-end" id="summaryPointsDiscount">Rp0</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Total Harga Akhir:</strong></td>
                                    <td class="text-end" id="summaryTotalPrice">Rp0</td>
                                </tr>
                                <tr>
                                    <td class="text-start"><strong>Poin yang Akan Didapat:</strong></td>
                                    <td class="text-end" id="summaryPointsEarned">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <h6><strong>Silahkan menunggu verifikasi bukti pembayaran dari CS.</strong></h6>
                        <p>Pembayaran akan dikembalikan jika bukti pembayaran tidak valid.</p>
                    </div>
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
                                    <img src="{{ $komentar->pembeli->foto ? asset('storage/profiles/' . $komentar->pembeli->foto) : asset('img/img3.jpg') }}" alt="Profile" class="rounded-circle">
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
        let countdownTimer;
        let userAddresses = []; // Variabel global untuk menyimpan alamat

        function getToken() {
            const urlParams = new URLSearchParams(window.location.search);
            const tokenFromUrl = urlParams.get('token');
            let token = tokenFromUrl || localStorage.getItem('access_token');

            if (!token && tokenFromUrl) {
                localStorage.setItem('access_token', tokenFromUrl);
                token = tokenFromUrl;
            }
            return token || null;
        }

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
                    <div class="toast-body">${message}</div>
                </div>
            `;
            toastContainer.append(toastHtml);
            const toastElement = $(`#${toastId}`);
            const toast = new bootstrap.Toast(toastElement[0], { delay: 3000 });
            toast.show();
            toastElement.on('hidden.bs.toast', function () { toastElement.remove(); });
        }

        function loadUserData() {
            const token = getToken();

            if (!token) {
                window.location.href = '/login';
                return;
            }

            $.ajax({
                url: '/api/profiles',
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` },
                success: function(response) {
                    const user = response;
                    $('#user-name').text(user.nama || 'Pengguna');
                    const imageSrc = user.gambar;
                    $('#user-image').attr('src', imageSrc);
                    $('#alamat_email').val(user.email || '');
                    $('#userPoints').text(user.total_poin || 0);
                    userAddresses = user.alamats || []; // Simpan alamat di variabel global

                    if (user.role === 'penitip') {
                        $('#commentForm').hide();
                        $('.penitip-message').show();
                        $('.add-to-cart, [onclick*="buyNow"]').prop('disabled', true).addClass('disabled');
                    } else {
                        $('#commentForm').show();
                        $('.penitip-message').hide();
                        $('.add-to-cart, [onclick*="buyNow"]').prop('disabled', false).removeClass('disabled');
                    }
                },
                error: function(xhr) {
                    console.log('Load User Data Error:', xhr.responseJSON);
                    if (xhr.status === 401 || xhr.status === 403) {
                        localStorage.removeItem('access_token');
                        showToast('Sesi kadaluarsa. Silakan login ulang.', 'error');
                        window.location.href = '/login';
                    } else {
                        showToast('Gagal memuat data pengguna: ' + (xhr.responseJSON?.error || 'Coba lagi.'), 'error');
                    }
                }
            });
        }

        function refreshToken() {
            return fetch('/api/refresh-token', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${getToken()}`,
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem('access_token', data.token);
                    return data.token;
                }
                throw new Error('Failed to refresh token');
            });
        }

        function buyNow(kode, nama, harga, gambar) {
            const role = localStorage.getItem('role');
            let token = getToken();

            console.log('buyNow - Role:', role, 'Token:', token);

            if (!role) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '/login';
                return;
            }

            if (!token) {
                console.log('No token found. Attempting to refresh token.');
                refreshToken().then(newToken => {
                    if (newToken) {
                        token = newToken;
                        localStorage.setItem('access_token', token);
                        console.log('Token refreshed:', token);
                        proceedToCheckoutModal(token, [{ kode, nama, harga: parseFloat(harga), gambar: gambar || '' }], 'buyNow');
                    } else {
                        console.error('Failed to refresh token.');
                        alert('Gagal mendapatkan token. Silakan login ulang.');
                        window.location.href = '/login';
                    }
                }).catch(err => {
                    console.error('Refresh token error:', err);
                    window.location.href = '/login';
                });
                return;
            }

            proceedToCheckoutModal(token, [{ kode, nama, harga: parseFloat(harga), gambar: gambar || '' }], 'buyNow');
        }

        function buyFromCart() {
            const role = localStorage.getItem('role');
            let token = getToken();
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            console.log('buyFromCart - Role:', role, 'Token:', token, 'Cart:', cart);

            if (!role) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '/login';
                return;
            }

            if (!token) {
                console.log('No token found. Attempting to refresh token.');
                refreshToken().then(newToken => {
                    if (newToken) {
                        token = newToken;
                        localStorage.setItem('access_token', token);
                        console.log('Token refreshed:', token);
                        proceedToCheckoutModal(token, cart, 'cart');
                    } else {
                        console.error('Failed to refresh token.');
                        alert('Gagal mendapatkan token. Silakan login ulang.');
                        window.location.href = '/login';
                    }
                }).catch(err => {
                    console.error('Refresh token error:', err);
                    window.location.href = '/login';
                });
                return;
            }

            proceedToCheckoutModal(token, cart, 'cart');
        }

        function proceedToCheckoutModal(token, cartItems, source) {
            $('#checkoutError').hide().text('');
            $('.is-invalid').removeClass('is-invalid');

            $('#checkoutCartData').val(JSON.stringify(cartItems));
            $('#checkoutSource').val(source);
            loadUserAddresses(token);
            updateCheckoutItemList(cartItems);
            updateCheckoutTotal(cartItems);
            $('#checkoutModal').modal('show');
        }

        function loadUserAddresses(token) {
            const shippingMethod = $('input[name="shippingMethod"]:checked').val();
            const addressList = $('#addressList');
            addressList.empty();

            if (shippingMethod === 'courier') {
                addressList.show();
                if (userAddresses.length > 0) {
                    userAddresses.forEach(address => {
                        addressList.append(`
                            <div class="address-card mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="selectedAddress" value="${address.id_alamat}">
                                    <label class="form-check-label">${address.nama_alamat || address.alamat_lengkap}</label>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    addressList.append('<p class="text-danger">Tidak ada alamat tersedia. Silakan tambahkan alamat di profil Anda.</p>');
                }
            } else {
                addressList.hide();
            }
        }

        function updateCheckoutItemList(cartItems) {
            const itemList = $('#checkoutItemList');
            itemList.empty();
            cartItems.forEach(item => {
                itemList.append(`
                    <div class="d-flex justify-content-between align-items-center mb-2 item-card">
                        <div class="d-flex align-items-center">
                            <img src="/img/${item.gambar ? item.gambar : 'img1.jpg'}" alt="${item.nama}">
                            <div class="ms-3">
                                <strong>${item.nama}</strong><br>
                                Rp${item.harga.toLocaleString('id-ID')}
                            </div>
                        </div>
                        <button class="btn btn-danger btn-sm remove-item" data-kode="${item.kode}">Hapus</button>
                    </div>
                `);
            });
        }

        function updateCheckoutTotal(cartItems) {
            const totalItemsPrice = cartItems.reduce((sum, item) => sum + parseInt(item.harga), 0);
            const shippingCost = $('input[name="shippingMethod"]:checked').val() === 'courier' && totalItemsPrice < 1500000 ? 100000 : 0;
            const pointsDiscount = parseInt($('#pointsUsed').val()) * 100 || 0;
            const totalPrice = totalItemsPrice + shippingCost - pointsDiscount;

            let pointsEarned = Math.floor(totalItemsPrice / 10000);
            if (totalItemsPrice > 500000) {
                pointsEarned += Math.floor(pointsEarned * 0.2);
            }

            $('#totalItemsPrice').text(
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalItemsPrice)
            );
            $('#shippingCost').text(
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(shippingCost)
            );
            $('#pointsDiscount').text(
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pointsDiscount)
            );
            $('#totalPrice').text(
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalPrice)
            );
            $('#pointsEarned').text(pointsEarned);
        }

        function showPaymentConfirmationModal(transactionId, token, waktuBatas) {
            $('#transactionId').val(transactionId);
            $('#paymentForm').attr('action', `/api/konfirmasi/${transactionId}`);
            $('#checkoutModal').modal('hide');
            $('#paymentModal').modal('show');

            let timeLeft = Math.floor((new Date(waktuBatas) - new Date()) / 1000);
            if (timeLeft <= 0) {
                cancelTransaction(transactionId, token);
                return;
            }

            clearInterval(countdownTimer);
            countdownTimer = setInterval(function () {
                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    cancelTransaction(transactionId, token);
                } else {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    $('#countdownTimer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
                    timeLeft--;
                }
            }, 1000);
        }

        function cancelTransaction(transactionId, token) {
            $.ajax({
                url: `/api/konfirmasi/${transactionId}/cancel`,
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` },
                success: function () {
                    showToast('Waktu pembayaran habis. Transaksi dibatalkan.', 'error');
                    $('#paymentModal').modal('hide');
                },
                error: function (xhr) {
                    console.error('Cancel Error:', xhr.responseJSON);
                    showToast('Gagal membatalkan transaksi: ' + (xhr.responseJSON?.error || 'Coba lagi.'), 'error');
                    $('#paymentModal').modal('hide');
                }
            });
        }

        $(document).ready(function () {
            loadUserData();

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const maxCartItems = 5;

            $('.add-to-cart').on('click', function () {
                if (cart.length >= maxCartItems) {
                    showToast('Keranjang penuh! Maksimal 5 barang.', 'error');
                    return;
                }
                const kode = $(this).data('kode');
                if (cart.some(item => item.kode === kode)) {
                    showToast('Barang sudah dicart!', 'error');
                    return;
                }
                const item = {
                    kode: kode,
                    nama: $(this).data('nama'),
                    harga: $(this).data('harga'),
                    gambar: $(this).data('gambar')
                };
                cart.push(item);
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCart();
                showToast('Barang ditambahkan ke keranjang!', 'success');
            });

            function updateCart() {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                const cartCount = cart.length;
                $('#cartCount').text(cartCount);
                $('#cartButton').toggleClass('d-none', cartCount === 0);
                const cartItems = $('#cartItems');
                cartItems.empty();
                cart.forEach(item => {
                    cartItems.append(`
                        <div class="cart-item">
                            <img src="/img/${item.gambar ? item.gambar : 'img1.jpg'}" alt="${item.nama}">
                            <div class="item-details">
                                <strong>${item.nama}</strong>
                                <span>Rp${item.harga.toLocaleString('id-ID')}</span>
                            </div>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-kode="${item.kode}">Hapus</button>
                        </div>
                    `);
                });
            }

            $(document).on('click', '.remove-from-cart', function () {
                const kode = $(this).data('kode');
                cart = cart.filter(item => item.kode !== kode);
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCart();
            });

            updateCart();

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

            $('#checkoutForm').on('submit', function (e) {
                e.preventDefault();
                const shippingMethod = $('input[name="shippingMethod"]:checked').val();
                const selectedAddress = $('input[name="selectedAddress"]:checked').val();
                $('#checkoutError').hide();

                if (shippingMethod === 'courier' && !selectedAddress) {
                    $('#checkoutError').text('Silakan pilih alamat pengiriman.').show();
                    return;
                }

                const token = getToken();
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                const formData = $(this).serialize() + '&source=cart';
                $.ajax({
                    url: '/api/checkout',
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: formData,
                    success: function (response) {
                        showPaymentConfirmationModal(response.id_transaksi_beli, token, response.waktu_batas);
                        if (response.source === 'cart') {
                            localStorage.removeItem('cart');
                            updateCart();
                        }
                    },
                    error: function (xhr) {
                        console.log('Checkout Error Response:', xhr.responseJSON);
                        $('#checkoutError').text('Gagal memproses checkout: ' + (xhr.responseJSON?.error || 'Coba lagi.')).show();
                    }
                });
            });

            $('#applyPoints').on('click', function () {
                const pointsInput = parseInt($('#pointsInput').val()) || 0;
                const userPoints = parseInt($('#userPoints').text()) || 0;
                $('#pointsError').hide();

                if (pointsInput > userPoints) {
                    $('#pointsError').text('Poin yang dimasukkan melebihi poin yang dimiliki.').show();
                    return;
                }

                $('#pointsUsed').val(pointsInput);
                $('#pointsModal').modal('hide');
                updateCheckoutTotal(JSON.parse($('#checkoutCartData').val()));
            });

            $('input[name="shippingMethod"]').on('change', function () {
                const token = getToken();
                loadUserAddresses(token);
                updateCheckoutTotal(JSON.parse($('#checkoutCartData').val()));
            });

            $(document).on('click', '.remove-item', function () {
                const kode = $(this).data('kode');
                let cart = JSON.parse($('#checkoutCartData').val());
                cart = cart.filter(item => item.kode !== kode);
                $('#checkoutCartData').val(JSON.stringify(cart));

                // Update localStorage untuk menyinkronkan keranjang
                localStorage.setItem('cart', JSON.stringify(cart));

                // Perbarui tampilan di modal checkout
                updateCheckoutItemList(cart);
                updateCheckoutTotal(cart);

                // Perbarui tampilan di modal keranjang (floating cart button)
                updateCart();
            });

            $('#paymentForm').on('submit', function (e) {
                e.preventDefault();
                const transactionId = $('#transactionId').val();
                const fileInput = $('#paymentProof')[0].files[0];
                $('#paymentError').hide();

                if (!fileInput) {
                    $('#paymentError').text('Silakan upload bukti pembayaran.').show();
                    return;
                }

                const token = getToken();
                const formData = new FormData(this);

                $.ajax({
                    url: `/api/konfirmasi/${transactionId}`,
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        clearInterval(countdownTimer);
                        $('#paymentModal').modal('hide');

                        // Ambil data dari checkout sebelumnya untuk ditampilkan
                        const cartData = JSON.parse($('#checkoutCartData').val() || '[]');
                        const pointsUsed = parseInt($('#pointsUsed').val()) || 0;
                        const totalItemsPrice = cartData.reduce((sum, item) => sum + parseInt(item.harga), 0);
                        const shippingCost = $('input[name="shippingMethod"]:checked').val() === 'courier' && totalItemsPrice < 1500000 ? 100000 : 0;
                        const pointsDiscount = pointsUsed * 100;
                        const totalPrice = totalItemsPrice + shippingCost - pointsDiscount;
                        let pointsEarned = Math.floor(totalItemsPrice / 10000);
                        if (totalItemsPrice > 500000) {
                            pointsEarned += Math.floor(pointsEarned * 0.2);
                        }

                        // Isi modal sukses
                        const paymentDate = new Date().toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        $('#paymentDate').text(`Tanggal Lunas: ${paymentDate}`);
                        $('#paidTotalPrice').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalPrice));
                        $('#earnedPoints').text(pointsEarned);

                        $('#purchasedItems').empty();
                        cartData.forEach(item => {
                            $('#purchasedItems').append(`
                                <li>${item.nama} - Rp${item.harga.toLocaleString('id-ID')}</li>
                            `);
                        });

                        $('#summaryTotalItemsPrice').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalItemsPrice));
                        $('#summaryShippingCost').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(shippingCost));
                        $('#summaryPointsDiscount').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pointsDiscount));
                        $('#summaryTotalPrice').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalPrice));
                        $('#summaryPointsEarned').text(pointsEarned);

                        // Tampilkan modal sukses
                        $('#successConfirmationModal').modal('show');

                        // Setelah 5 detik, arahkan ke halaman home
                        setTimeout(() => {
                            $('#successConfirmationModal').modal('hide');
                            window.location.href = '/home';
                        }, 10000);
                    },
                    error: function (xhr) {
                        $('#paymentError').text('Gagal mengupload bukti pembayaran: ' + (xhr.responseJSON?.error || 'Coba lagi.')).show();
                    }
                });
            });

            function showCancelConfirmation(type) {
                $('#cancelMessage').text(type === 'checkout' ? 'Apakah Anda yakin ingin membatalkan checkout?' : 'Apakah Anda yakin ingin membatalkan pembayaran?');
                $('#cancelConfirmationModal').data('type', type).modal('show');
            }

            $('#confirmCancel').on('click', function () {
                const type = $('#cancelConfirmationModal').data('type');
                const token = getToken();
                const transactionId = $('#transactionId').val();

                $('#cancelConfirmationModal').modal('hide');
                if (type === 'checkout') {
                    $('#checkoutModal').modal('hide');
                } else {
                    $('#paymentModal').modal('hide');
                    clearInterval(countdownTimer);
                    if (transactionId) {
                        $.ajax({
                            url: `/api/konfirmasi/${transactionId}/cancel`,
                            method: 'POST',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                            success: function () {
                                showToast('Transaksi dibatalkan.', 'error');
                            },
                            error: function (xhr) {
                                showToast('Gagal membatalkan transaksi: ' + (xhr.responseJSON?.error || 'Coba lagi.'), 'error');
                            }
                        });
                    }
                }
            });

            function confirmLogout() {
                const token = getToken();
                const role = localStorage.getItem('role');
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