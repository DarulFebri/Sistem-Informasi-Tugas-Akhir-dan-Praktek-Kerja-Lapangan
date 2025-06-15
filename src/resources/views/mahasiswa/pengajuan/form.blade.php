<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan' }} {{ strtoupper($jenis) }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="file"] { border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: calc(100% - 22px); background-color: #f9f9f9; }
        select { border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: 100%; background-color: #f9f9f9; }
        button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px; transition: background-color 0.3s ease; }
        button:hover { background-color: #0056b3; }
        .button-draft { background-color: #6c757d; }
        .button-draft:hover { background-color: #5a6268; }
        .back-link { display: block; text-align: center; margin-top: 30px; color: #007bff; text-decoration: none; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }
        .error-message { color: red; font-size: 0.9em; margin-top: 5px; display: block; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .current-file { font-size: 0.9em; color: #666; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>{{ isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan' }} {{ strtoupper($jenis) }}</h2>

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($pengajuan) ? route('mahasiswa.pengajuan.update', $pengajuan->id) : route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($pengajuan))
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_pengajuan" value="{{ $jenis }}">

            <div class="form-group">
                <label for="dosen_pembimbing1_id">Pilih Dosen Pembimbing 1:</label>
                <select name="dosen_pembimbing1_id" id="dosen_pembimbing1_id" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ (isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_pembimbing_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing1_id') == $dosen->id ? 'selected' : '') }}>
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_pembimbing1_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="dosen_pembimbing2_id">Pilih Dosen Pembimbing 2:</label>
                <select name="dosen_pembimbing2_id" id="dosen_pembimbing2_id">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ (isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_penguji1_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing2_id') == $dosen->id ? 'selected' : '') }}>
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_pembimbing2_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <h3>Unggah Dokumen Persyaratan:</h3>
            @foreach ($dokumenSyarat as $key => $namaDokumen)
                <div class="form-group">
                    <label for="dokumen_file_{{ $key }}">{{ $loop->iteration }}. {{ $namaDokumen }}</label>
                    <input type="file" name="dokumen[{{ $key }}]" id="dokumen_file_{{ $key }}" accept=".pdf,.jpg,.jpeg,.png">

                    @if (isset($dokumenTerupload[$namaDokumen]))
                        <div class="current-file">
                            File saat ini: <a href="{{ Storage::url($dokumenTerupload[$namaDokumen]->path_file) }}" target="_blank">{{ $dokumenTerupload[$namaDokumen]->nama_file }}</a>
                            <input type="hidden" name="existing_dokumen_file_check[{{ $key }}]" value="1">
                        </div>
                    @endif
                    @error("dokumen.{$key}")
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    @if ($jenis == 'ta' && $key == 'nilai_toeic')
                        <small style="color: #666; display: block; margin-top: 5px;">Jika belum mencukupi, fotokopi kartu nilai TOEIC terakhir dan fotokopi bukti pendaftaran tes TOEIC berikutnya.</small>
                    @endif
                     @if ($jenis == 'pkl' && $key == 'kuisioner_kelulusan')
                        <small style="color: #666; display: block; margin-top: 5px;">Opsional jika tidak ada.</small>
                    @endif
                     @if ($jenis == 'ta' && $key == 'ipk_terakhir')
                        <small style="color: #666; display: block; margin-top: 5px;">(Lampiran Rapor Semester 1 s.d 5 (D3) dan 1 s.d 7 (D4))</small>
                    @endif
                </div>
            @endforeach

            <p style="font-size: 0.9em; color: #666; margin-top: 20px;">Catatan: Untuk "Map Plastik", akan diurus secara fisik dan tidak perlu diunggah.</p>

            <button type="submit" name="action" value="submit">Ajukan {{ strtoupper($jenis) }}</button>
            <button type="submit" name="action" value="draft" class="button-draft">Simpan sebagai Draft</button>
        </form>

        <a href="{{ route('mahasiswa.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html>