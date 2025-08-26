<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">404</h1>
        <h3 class="mb-3">Halaman Tidak Ditemukan</h3>
        <p class="text-muted">Link yang kamu akses tidak ada di sistem.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Kembali ke Login</a>
    </div>
</body>
</html>
