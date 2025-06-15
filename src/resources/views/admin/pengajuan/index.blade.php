<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Mahasiswa (Admin)</title>
    </head>
<body>
    <div class="container">
        <h2>Daftar Pengajuan Mahasiswa</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jenis Pengajuan</th>
                    <th>Status</th>
                    <th>Tanggal Diajukan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuans as $pengajuan)
                <tr>
                    <td>{{ $pengajuan->mahasiswa->nim }}</td>
                    <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                    <td>{{ strtoupper($pengajuan->jenis_pengajuan) }}</td>
                    <td>
                        @if ($pengajuan->status == 'diajukan_mahasiswa')
                            <span class="status-pending">Menunggu Verifikasi Admin</span>
                        @elseif ($pengajuan->status == 'diverifikasi_admin')
                            <span class="status-success">Diverifikasi Admin</span>
                        @elseif ($pengajuan->status == 'ditolak_admin')
                            <span class="status-danger">Ditolak Admin</span>
                        @else
                            {{ $pengajuan->status }}
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</td>
                    <td>
                        <a href="{{ route('admin.pengajuan.verifikasi.show', $pengajuan->id) }}">Lihat Detail & Verifikasi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Tidak ada pengajuan yang perlu diverifikasi saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pengajuans->links() }}
    </div>
    <div>
        <li>
            <a href="{{ route('admin.dashboard') }}">kembali Ke Dashboard</a>
        </li>
    </div>
</body>
</html>