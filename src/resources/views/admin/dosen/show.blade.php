<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dosen</title>
</head>

<body>

    <h2>Detail Dosen</h2>

    <p>NIDN: {{ $dosen->nidn }}</p>
    <p>Nama Lengkap: {{ $dosen->nama_lengkap }}</p>
    <p>Jenis Kelamin: {{ $dosen->jenis_kelamin }}</p>

    <a href="{{ route('admin.dosen.index') }}">Kembali ke Daftar Dosen</a>

</body>

</html>