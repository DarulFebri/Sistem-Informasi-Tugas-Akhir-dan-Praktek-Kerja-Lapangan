{{-- resources/views/kajur/pengajuan/verifikasi.blade.php --}}

@section('content')
<div class="container">
    <h1>Verifikasi Pengajuan Sidang</h1>
    <p>Anda akan memverifikasi pengajuan sidang ini:</p>

    <div class="card mb-4">
        <div class="card-header">Detail Pengajuan</div>
        <div class="card-body">
            <p><strong>ID Pengajuan:</strong> {{ $pengajuan->id }}</p>
            <p><strong>Nama Mahasiswa:</strong> {{ $pengajuan->mahasiswa->nama }}</p>
            <p><strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim }}</p>
            <p><strong>Judul Tugas Akhir:</strong> {{ $pengajuan->judul_tugas_akhir }}</p>
            <p><strong>Status Saat Ini:</strong> {{ Str::replace('_', ' ', Str::title($pengajuan->status)) }}</p>
            {{-- Tambahkan detail lain yang relevan --}}
        </div>
    </div>

    <form action="{{ route('kajur.verifikasi.store', $pengajuan->id) }}" method="POST">
        @csrf
        <p>Apakah Anda yakin ingin memverifikasi pengajuan sidang ini?</p>
        {{-- Anda bisa menambahkan input untuk catatan verifikasi jika diperlukan --}}
        {{--
        <div class="form-group mb-3">
            <label for="catatan">Catatan (Opsional):</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
        </div>
        --}}
        <button type="submit" class="btn btn-success">Verifikasi Sekarang</button>
        <a href="{{ route('kajur.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
