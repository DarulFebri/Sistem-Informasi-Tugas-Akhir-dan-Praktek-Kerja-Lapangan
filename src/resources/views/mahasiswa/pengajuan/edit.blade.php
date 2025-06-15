<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengajuan Sidang {{ strtoupper($jenis) }}</title>
</head>
<body>

    <h2>Edit Pengajuan Sidang {{ strtoupper($jenis) }}</h2>

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

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form action mengarah ke route update --}}
    <form method="POST" action="{{ route('mahasiswa.pengajuan.update', $pengajuan->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting untuk metode PUT/PATCH --}}
    
        <input type="hidden" name="jenis_pengajuan" value="{{ $jenis }}">
    
        <h3>Dokumen Persyaratan:</h3>
    
        <div>
            <label for="ketua_sidang_dosen_id">Pilih Ketua Sidang:</label>
            <select name="ketua_sidang_dosen_id" id="ketua_sidang_dosen_id" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach ($dosens as $dosen)
                    {{-- Gunakan ?? null atau ?? '' untuk memastikan tidak error jika $pengajuan->sidang belum ada --}}
                    <option value="{{ $dosen->id }}" {{ old('ketua_sidang_dosen_id', $pengajuan->sidang->ketua_sidang_dosen_id ?? null) == $dosen->id ? 'selected' : '' }}>
                        {{ $dosen->nama }}
                    </option>
                @endforeach
            </select>
            @error('ketua_sidang_dosen_id') {{-- Tampilkan error validasi jika ada --}}
                <div style="color: red; font-size: 0.8em; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    
        @foreach ($dokumenSyarat as $key => $namaDokumen)
            @php
                $uploadedDoc = $pengajuan->dokumens->where('nama_file', $namaDokumen)->first();
            @endphp
            <div>
                <label for="dokumen_file_{{ $key }}">{{ $namaDokumen }}:</label>
                @if ($uploadedDoc)
                    <p>File saat ini: <a href="{{ asset('storage/' . $uploadedDoc->path_file) }}" target="_blank">Lihat File</a></p>
                @else
                    <p>Belum ada file terupload.</p>
                @endif
                <input type="file" name="dokumen_file_{{ $key }}" id="dokumen_file_{{ $key }}" accept="application/pdf">
                <small>(Upload file baru untuk mengganti yang lama atau mengisi yang kosong. Format: PDF, Maksimal: 2MB)</small>
            </div>
            @if (!$loop->last) {{-- Tambahkan HR di antara setiap item kecuali yang terakhir --}}
                <br><br> {{-- Tambahkan dua tag <br> untuk menciptakan gap --}}
            @endif
        @endforeach
    
        <br>
        <button type="submit" name="action" value="draft">Simpan Perubahan Draft</button>
        <button type="submit" name="action" value="submit" onclick="return confirm('Apakah Anda yakin ingin memfinalisasi pengajuan ini? Setelah difinalisasi, Anda tidak dapat mengubahnya lagi.');">Finalisasi dan Ajukan</button>
    </form>

    <a href="{{ route('mahasiswa.dashboard') }}">Kembali ke Dashboard</a>
    <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan->id) }}">Kembali ke Detail Pengajuan</a>

</body>
</html>