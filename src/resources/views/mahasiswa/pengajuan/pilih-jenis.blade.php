<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Pengajuan</title>
    <style>
        body { font-family: sans-serif; margin: 20px; text-align: center; background-color: #f4f4f4;}
        .container { max-width: 600px; margin: auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 25px; }
        .options a { display: inline-block; margin: 15px; padding: 18px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 18px; transition: background-color 0.3s ease; }
        .options a:hover { background-color: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 30px; color: #007bff; text-decoration: none; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pilih Jenis Pengajuan</h2>
        <div class="options">
            <a href="{{ route('mahasiswa.pengajuan.create', 'ta') }}">Pengajuan Tugas Akhir</a>
            <a href="{{ route('mahasiswa.pengajuan.create', 'pkl') }}">Pengajuan Praktek Kerja Lapangan</a>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>
</body>
</html>