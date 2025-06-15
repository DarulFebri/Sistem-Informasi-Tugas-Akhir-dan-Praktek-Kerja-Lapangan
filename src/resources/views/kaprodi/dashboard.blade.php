<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi</title>
</head>

<body>

    <h2>Dashboard Kaprodi</h2>

    <p>Jumlah Dosen: {{ $jumlahDosen }}</p>
    <p>Jumlah Pengajuan Baru: {{ $jumlahPengajuan }}</p>

    <h3>Pengajuan Terbaru</h3>
    @if ($pengajuanBaru->count() > 0)
        <ul>
            @foreach ($pengajuanBaru as $pengajuan)
                <li>{{ $pengajuan->mahasiswa->nama_lengkap }} - {{ $pengajuan->jenis_pengajuan }}</li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada pengajuan baru.</p>
    @endif

    <li><a href="{{ route('kaprodi.pengajuan.index') }}">Ke Menu Menajemen Pengajuan Sidang</a></li>

    <br>

    <form action="{{ route('kaprodi.logout') }}" method="POST">
        @csrf <button type="submit">Logout</button>
    </form>

</body>

</html>