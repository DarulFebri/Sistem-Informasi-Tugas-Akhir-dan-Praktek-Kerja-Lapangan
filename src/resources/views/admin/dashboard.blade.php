<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Admin Dashboard</h2>

    <p>Selamat datang, Admin!</p>

    <hr> {{-- Garis pemisah untuk menu navigasi --}}

    <h3>Menu Navigasi</h3>
    <ul>
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.mahasiswa.index') }}">Manajemen Mahasiswa</a></li>
        <li><a href="{{ route('admin.dosen.index') }}">Manajemen Dosen</a></li>
        {{-- Link ke daftar pengajuan yang perlu diverifikasi admin --}}
        <li><a href="{{ route('admin.pengajuan.verifikasi.index') }}">Verifikasi Pengajuan Sidang</a></li>
        {{-- Anda bisa menambahkan link ke daftar pengajuan lama jika masih digunakan --}}
        {{-- <li><a href="{{ route('admin.pengajuan.index') }}">Daftar Semua Pengajuan (Lama)</a></li> --}}        {{-- Tambahkan link lain sesuai kebutuhan --}}
        <li><a href="{{ route('admin.sidang.index') }}">Manajemen Sidang</a></li>
    </ul>

    <hr>

    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf <button type="submit">Logout</button>
    </form>

</body>
</html>