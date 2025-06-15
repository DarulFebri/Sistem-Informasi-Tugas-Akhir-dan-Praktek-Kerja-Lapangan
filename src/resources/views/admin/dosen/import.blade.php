<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impor Data Dosen</title>
</head>
<body>
    <h1>Impor Data Dosen dari Excel</h1>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Pilih File Excel (.xls, .xlsx, .csv):</label>
            <input type="file" name="file" id="file" required>
            @error('file')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <button type="submit">Impor Data</button>
    </form>

    <p>Pastikan file Excel Anda memiliki kolom dengan header persis seperti di bawah ini (case-insensitive):</p>
    <table>
        <thead>
            <tr>
                <th>NIDN</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Jenis Kelamin</th>
                <th>Email</th> {{-- <--- PENTING: Tambahkan header ini --}}
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>197001012000011001</td>
                <td>Prof. Dr. Andi Wijaya</td>
                <td>Teknik Informatika</td>
                <td>Sistem Informasi</td>
                <td>Laki-laki</td>
                <td>andi.wijaya@example.com</td> {{-- <--- PENTING: Tambahkan contoh email --}}
            </tr>
            <tr>
                <td>198005102005021002</td>
                <td>Dr. Budi Santoso</td>
                <td>Teknik Informatika</td>
                <td>Teknik Komputer</td>
                <td>Laki-laki</td>
                <td>budi.santoso@example.com</td> {{-- <--- PENTING: Tambahkan contoh email --}}
            </tr>
        </tbody>
    </table>
    <p>Password akun dosen akan disetel default `password123`. Harap informasikan kepada dosen untuk mengubahnya setelah login pertama kali.</p>

    <br>
    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard Admin</a>
    <br>
    <a href="{{ route('admin.dosen.index') }}">Lihat Daftar Dosen</a>

</body>
</html>