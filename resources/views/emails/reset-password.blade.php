<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Reusemart</title>
</head>
<body>
    <h2>Halo!</h2>
    <p>Kami menerima permintaan untuk mereset kata sandi Anda. Klik tautan di bawah ini untuk mengatur ulang kata sandi Anda:</p>
    <a href="{{ url('password/reset', $token) }}">Reset Kata Sandi</a>
    <p>Tautan ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak meminta ini, abaikan email ini.</p>
    <p>Terima kasih,<br>Tim Reusemart</p>
</body>
</html>