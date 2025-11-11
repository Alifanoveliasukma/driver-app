<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proses Berhasil</title>
    <style>
        .container { text-align: center; margin-top: 100px; font-family: sans-serif; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 20px; border: 1px solid #c3e6cb; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        a { color: #007bff; text-decoration: none; margin-top: 20px; display: block; font-size: 1.1em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Operasi Berhasil!</h1>
        @if(session('success'))
            <div class="alert-success">
                <p>**Detail Proses:**</p>
                <p>{{ session('success') }}</p>
                <p>User berhasil dibuat dan Role Driver (ID 1000049) telah ditambahkan.</p>
            </div>
        @else
            <div class="alert-success">
                <p>Proses pembuatan User, penambahan Role Driver, dan pembuatan Driver berhasil.</p>
            </div>
        @endif
        <a href="{{ route('driver.create') }}">← Kembali ke Formulir Pendaftaran</a>
    </div>
</body>
</html>