<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Sidang {{ strtoupper($pengajuan->jenis_pengajuan) }}</title> {{-- Tambahkan jenis pengajuan di title --}}
</head>
<body>

    <h2>Detail Pengajuan Sidang {{ strtoupper($pengajuan->jenis_pengajuan) }}</h2> {{-- Tambahkan jenis pengajuan di H2 --}}

    {{-- Pesan sukses/error dari session --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <p>Jenis Pengajuan: <strong>{{ strtoupper($pengajuan->jenis_pengajuan) }}</strong></p>
    <p>Status:
        {{-- Tampilan status yang lebih informatif dengan warna --}}
        @if ($pengajuan->status === 'draft')
            <strong style="color: orange;">DRAFT</strong>
        @elseif ($pengajuan->status === 'diajukan')
            <strong style="color: blue;">DIAJUKAN</strong>
        @elseif ($pengajuan->status === 'diproses')
            <strong style="color: green;">DIPROSES</strong>
        @elseif ($pengajuan->status === 'disetujui')
            <strong style="color: purple;">DISETUJUI</strong>
        @elseif ($pengajuan->status === 'ditolak')
            <strong style="color: red;">DITOLAK</strong>
        @elseif ($pengajuan->status === 'selesai')
            <strong style="color: green;">SELESAI</strong>
        @elseif ($pengajuan->status === 'sidang_dijadwalkan_final') {{-- Tambahkan status ini --}}
            <strong style="color: darkgreen;">SIDANG DIJADWALKAN FINAL</strong>
        @else
            <strong>{{ strtoupper($pengajuan->status) }}</strong> {{-- Default jika ada status lain --}}
        @endif
    </p>
    <p>Tanggal Dibuat: {{ $pengajuan->created_at->format('d M Y H:i') }}</p>
    <p>Terakhir Diperbarui: {{ $pengajuan->updated_at->format('d M Y H:i') }}</p>

    {{-- Bagian informasi jadwal sidang --}}
    <h3>Informasi Jadwal Sidang</h3>
    @if ($pengajuan->sidang && $pengajuan->sidang->tanggal_waktu_sidang && $pengajuan->sidang->ruangan_sidang)
        <p>Tanggal Sidang: <strong>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y') }}</strong></p>
        <p>Waktu Sidang: <strong>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('H:i') }} WIB</strong></p>
        <p>Ruangan Sidang: <strong>{{ $pengajuan->sidang->ruangan_sidang }}</strong></p>
    @else
        <p>Jadwal sidang belum ditetapkan.</p>
    @endif
    
    <h3>Dokumen Terupload:</h3>
    {{-- Ganti $dokumenTerupload dengan $pengajuan->dokumens --}}
    @if ($pengajuan->dokumens->count() > 0)
        <ul>
            @foreach ($pengajuan->dokumens as $dokumen) {{-- Ganti $dokumenTerupload dengan $pengajuan->dokumens --}}
                <li>
                    {{ $dokumen->nama_file }}:
                    <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank">Lihat File</a>
                    {{-- Status dokumen individual (jika diperlukan) --}}
                    {{-- (Status: {{ $dokumen->status }}) --}}
                </li>
            @endforeach
        </ul>
    @else
        <p>Belum ada dokumen yang diunggah untuk pengajuan ini.</p>
    @endif

    <h3>Informasi Anggota Sidang</h3>
    @if ($pengajuan->sidang)
        <p><strong>Ketua Sidang:</strong> {{ $pengajuan->sidang->ketuaSidang->nama ?? 'Belum Ditunjuk' }}</p>
        <p><strong>Sekretaris Sidang:</strong> {{ $pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk' }}</p>
        <p><strong>Anggota Sidang 1:</strong> {{ $pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk' }}</p>
        <p><strong>Anggota Sidang 2:</strong> {{ $pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk' }}</p>
    @else
        <p>Anggota sidang belum ditunjuk.</p>
    @endif

    <hr> {{-- Garis pemisah untuk aksi --}}

    {{-- Tombol Aksi --}}
    <a href="{{ route('mahasiswa.pengajuan.index') }}">Kembali ke Daftar Pengajuan</a>

    @if ($pengajuan->status === 'draft')
        <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan->id) }}" style="margin-left: 10px;">Edit Pengajuan Draft</a>
    @endif

    {{-- Tombol Hapus hanya jika statusnya BUKAN 'disetujui', 'diproses', atau 'selesai' --}}
    @if ($pengajuan->status !== 'disetujui' && $pengajuan->status !== 'diproses' && $pengajuan->status !== 'selesai' && $pengajuan->status !== 'sidang_dijadwalkan_final') {{-- Tambahkan status ini --}}
        <form action="{{ route('mahasiswa.pengajuan.destroy', $pengajuan->id) }}" method="POST" style="display:inline; margin-left: 10px;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini dan semua dokumennya? Aksi ini tidak bisa dibatalkan.');" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;">Hapus Pengajuan</button>
        </form>
    @endif

    <br><br>
    <a href="{{ route('mahasiswa.dashboard') }}">Kembali ke Dashboard Utama</a>

</body>
</html>