<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
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
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
   

        <h2 class="mb-4">Diskusi Produk</h2>
        <div class="form-container">
            <div class="mb-3">
                <label for="diskusi" class="form-label">Tambahkan Pesan Diskusi</label>
                <textarea class="form-control" id="diskusi" rows="3"></textarea>
            </div>
            <button class="btn btn-success">Kirim</button>

            <hr>

            <div class="mt-3">
                <h6>Diskusi Sebelumnya:</h6>
                <div class="border p-2 rounded mb-2">Apakah produk ini masih tersedia? - <strong>Ani</strong></div>
                <div class="border p-2 rounded mb-2">Berapa ongkos kirim ke Bandung? - <strong>Budi</strong></div>
            </div>
        </div>
 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>