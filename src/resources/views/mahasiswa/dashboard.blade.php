<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard</title>
    <!-- Tambahkan CSRF Token meta tag untuk keamanan -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Mahasiswa Dashboard</h2>

    <p>Selamat datang, Mahasiswa!</p>

    <!-- Tombol Logout dengan Form POST -->
    <form action="{{ route('mahasiswa.logout') }}" method="POST">
        @csrf <!-- CSRF Protection -->
        <a href="{{ route('mahasiswa.pengajuan.index') }}">Lihat Daftar Pengajuan Saya</a>
        <br>
        <button type="submit">Logout</button>
    </form>

</body>
</html>