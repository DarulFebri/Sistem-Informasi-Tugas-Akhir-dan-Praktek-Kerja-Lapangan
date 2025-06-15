<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impor Data Mahasiswa</title>
</head>
<body>
    <h1>Impor Data Mahasiswa dari Excel</h1>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red;">
            {!! session('error') !!} {{-- Gunakan {!! !!} untuk render HTML dari pesan error --}}
        </div>
    @endif

    <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
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
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2021001001</td>
                <td>Budi Cahyono</td>
                <td>Teknologi Informasi</td>
                <td>Teknik Komputer</td>
                <td>Laki-laki</td>
                <td>TI-2</td>
                <td>budi.cahyono@example.com</td>
            </tr>
            <tr>
                <td>2022002002</td>
                <td>Siti Aminah</td>
                <td>Teknologi Informasi</td>
                <td>Sistem Informasi</td>
                <td>Perempuan</td>
                <td>TI-2</td>
                <td>siti.aminah@example.com</td>
            </tr>
        </tbody>
    </table>
    <p>Password akun mahasiswa akan disetel default `password123`. Harap informasikan kepada mahasiswa untuk mengubahnya setelah login pertama kali.</p>

    <br>
    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard Admin</a>
    <br>
    <a href="{{ route('admin.mahasiswa.index') }}">Lihat Daftar Mahasiswa</a>

</body>
</html>