<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Terverifikasi - Kajur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 1280px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .header { background-color: #f2f2f2; padding: 15px; border-bottom: 1px solid #e0e0e0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 1.8rem; color: #343a40; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: 500; }
        .nav-links a:hover { text-decoration: underline; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
        .table th { background-color: #e9ecef; font-weight: bold; color: #495057; }
        .table tbody tr:nth-child(even) { background-color: #f8f9fa; }
        .table tbody tr:hover { background-color: #e2e6ea; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.9rem; transition: background-color 0.3s ease; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-size: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .logout-form button { background-color: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem; transition: background-color 0.3s ease; }
        .logout-form button:hover { background-color: #c82333; }
        .text-muted { color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengajuan Sudah Diverifikasi</h1>
            <div class="nav-links">
                <a href="{{ route('kajur.dashboard') }}">Dashboard</a>
                <a href="{{ route('kajur.pengajuan.perlu_verifikasi') }}">Pengajuan Perlu Verifikasi</a>
                <a href="{{ route('kajur.pengajuan.sudah_verifikasi') }}">Pengajuan Terverifikasi</a>
                <form action="{{ route('kajur.logout') }}" method="POST" class="logout-form" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($pengajuanTerverifikasi->isEmpty())
            <p class="text-muted">Tidak ada pengajuan sidang yang sudah diverifikasi saat ini.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pengajuan</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                        <th>Aksi</th> {{-- Kolom Aksi baru --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanTerverifikasi as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->id }}</td>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ Str::replace('_', ' ', Str::title($pengajuan->jenis_pengajuan)) }}</td>
                        <td>{{ $pengajuan->created_at->format('d M Y H:i') }}</td>
                        <td>{{ Str::replace('_', ' ', Str::title($pengajuan->status)) }}</td>
                        <td>
                            @if ($pengajuan->sidang) {{-- Pastikan pengajuan memiliki sidang terkait --}}
                                <a href="{{ route('kajur.sidang.show', $pengajuan->sidang->id) }}" class="btn btn-primary">Lihat Detail</a>
                            @else
                                <span class="text-muted">Sidang Belum Dijadwalkan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    </body>
</html>