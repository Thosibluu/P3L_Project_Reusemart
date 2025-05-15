<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ccf5d3; 
        }
        .navbar-brand {
            color: #00aa5b !important;
            font-weight: bold;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.03);
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body style="height: 90vh;">
<a href="/" class="d-flex justify-content-center align-items-center fw-bold fs-3" style="color: #00aa5b; height: 10vh; text-decoration: none;">Reusemart</a>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="form-container">
            <h2 class="mb-4 text-center">Register Reusemart</h2>
            <form id="registerForm">
                @if (request()->is('register/pembeli'))
                    <input type="hidden" id="role" value="pembeli">
                <div class="mb-3">
                    <label for="name" class="form-label">Username</label>
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
                    <div class="invalid-feedback" id="error-nama_pembeli"></div>
                </div>
                <div class="mb-3">
                    <label for="emailReg" class="form-label">Email</label>
                    <input type="email" class="form-control" id="alamat_email" name="alamat_email" required>
                    <div class="invalid-feedback" id="error-alamat_email"></div>
                </div>
                <div class="mb-3">
                    <label for="notelpReg" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="nomor_telepon_pembeli" name="nomor_telepon_pembeli" required>
                    <div class="invalid-feedback" id="error-nomor_telepon_pembeli"></div>
                </div>
                <div class="mb-3">
                    <label for="passwordReg" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback" id="error-password"></div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
                @elseif (request()->is('register/penitip'))
                    <input type="hidden" id="role" value="penitip">
                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" class="form-control" id="nama_penitip" name="nama_penitip" required>
                        <div class="invalid-feedback" id="error-nama_penitip"></div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat_rumah" class="form-label">Alamat Rumah</label>
                        <input type="text" class="form-control" id="alamat_penitip" name="alamat_penitip" required>
                        <div class="invalid-feedback" id="error-alamat_penitip"></div>
                    </div>
                    <div class="mb-3">
                        <label for="notelpReg" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="nomor_telepon_penitip" name="nomor_telepon_penitip" required>
                        <div class="invalid-feedback" id="error-nomor_telepon_pembeli"></div>
                    </div>
                    <div class="mb-3">
                        <label for="passwordReg" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback" id="error-password"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                    <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
                @elseif (request()->is('register/organisasi'))
                    <input type="hidden" id="role" value="organisasi">
                        <div class="mb-3">
                            <label class="form-label">Nama Organisasi</label>
                            <input type="text" class="form-control" id="nama_organisasi" name="nama_organisasi" required>
                            <div class="invalid-feedback" id="error-nama_organisasi"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Organisasi</label>
                            <input type="text" class="form-control" id="alamat_organisasi" name="alamat_organisasi" required>
                            <div class="invalid-feedback" id="error-alamat_organisasi"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telpon_organisasi" name="nomor_telpon_organisasi" required>
                            <div class="invalid-feedback" id="error-nomor_telpon_organisasi"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback" id="error-password"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Daftar Organisasi</button>
                        <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
                @endif
            </form>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("registerForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const role = document.getElementById("role").value;
            const password = document.getElementById("password").value;

            let payload = { password };
            let endpoint = "";

            // Reset semua error dulu
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            if (role === "pembeli") {
                const nama = document.getElementById("nama_pembeli").value;
                const email = document.getElementById("alamat_email").value;
                const notelp = document.getElementById("nomor_telepon_pembeli").value;

                payload.nama_pembeli = nama;
                payload.alamat_email = email;
                payload.nomor_telepon_pembeli = notelp;
                endpoint = "/api/pembeli-register";
            } else if (role === "penitip") {
                const nama = document.getElementById("nama_penitip").value;
                const alamat = document.getElementById("alamat_penitip").value;
                const notelp = document.getElementById("nomor_telepon_penitip").value;

                payload.nama_penitip = nama;
                payload.alamat_penitip = alamat;
                payload.nomor_telpon_penitip = notelp;
                endpoint = "/api/penitip-register";
            } else if (role === "organisasi") {
                const nama = document.getElementById("nama_organisasi").value;
                const alamat = document.getElementById("alamat_organisasi").value;
                const notelp = document.getElementById("nomor_telpon_organisasi").value;

                
                payload.nama_organisasi = nama;
                payload.alamat_organisasi = alamat;
                payload.nomor_telpon_organisasi = notelp;
                endpoint = "/api/organisasi-register";
            }else {
                alert("Peran tidak dikenali.");
                return;
            }

            fetch(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(payload)
            })
            .then(async response => {
                const data = await response.json();

                if (response.status === 201) {
                    const toastElement = document.getElementById("successToast");
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();

                    setTimeout(() => {
                        window.location.href = "/login";
                    }, 2500);
                } else if (response.status === 422) {
                    Object.keys(data).forEach(field => {
                        const input = document.getElementById(field);
                        const errorDiv = document.getElementById('error-' + field);
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = data[field][0];
                        }
                    });
                } else {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Gagal mengirim data. Silakan periksa koneksi Anda.");
            });
        });
    </script>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
            <div class="toast-body">
                Registrasi berhasil! Mengarahkan ke halaman login...
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>
</html>