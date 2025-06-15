<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Tugas Akhir</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="file"] { border: 1px solid #ccc; padding: 8px; border-radius: 4px; width: calc(100% - 18px); }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .error-message { color: red; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Pengajuan Tugas Akhir</h2>

        @if (session('success'))
            <div style="color: green; text-align: center; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="color: red; text-align: center; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_pengajuan" value="ta">

            <div class="form-group">
                <label for="surat_permohonan_sidang">1. Surat Permohonan Sidang:</label>
                <input type="file" name="dokumen[surat_permohonan_sidang]" id="surat_permohonan_sidang" accept=".pdf" required>
                @error('dokumen.surat_permohonan_sidang')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="bebas_kompensasi">2. Surat Keterangan Bebas Kompensasi Semester Ganjil & Genap:</label>
                <input type="file" name="dokumen[surat_keterangan_bebas_kompensasi]" id="bebas_kompensasi" accept=".pdf" required>
                @error('dokumen.surat_keterangan_bebas_kompensasi')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="ipk_terakhir">3. IPK Terakhir (Lampiran Rapor Semester 1 s.d 5 (D3) dan 1 s.d 7 (D4)):</label>
                <input type="file" name="dokumen[ipk_terakhir]" id="ipk_terakhir" accept=".pdf" required>
                @error('dokumen.ipk_terakhir')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="bukti_laporan_pkl">4. Bukti Menyerahkan Laporan PKL:</label>
                <input type="file" name="dokumen[bukti_menyerahkan_laporan_pkl]" id="bukti_laporan_pkl" accept=".pdf" required>
                @error('dokumen.bukti_menyerahkan_laporan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="nilai_toeic">5. Nilai TOEIC (minimal 450 (D3) / 550 (D4) - Lampirkan kartu TOEIC. Jika belum mencukupi, fotokopi kartu nilai TOEIC terakhir dan bukti pendaftaran tes TOEIC berikutnya):</label>
                <input type="file" name="dokumen[nilai_toeic]" id="nilai_toeic" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.nilai_toeic')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="tugas_akhir_rangkap">6. Tugas Akhir Rangkap 4 yang Disetujui Pembimbing:</label>
                <input type="file" name="dokumen[tugas_akhir_rangkap]" id="tugas_akhir_rangkap" accept=".pdf" required>
                @error('dokumen.tugas_akhir_rangkap')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="kartu_bimbingan">7. Kartu Bimbingan/Konsultasi Tugas Akhir 9x:</label>
                <input type="file" name="dokumen[kartu_bimbingan_ta]" id="kartu_bimbingan" accept=".pdf" required>
                @error('dokumen.kartu_bimbingan_ta')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotokopi_ijazah_sma">8. Fotokopi Ijazah SMA/MA/SMK:</label>
                <input type="file" name="dokumen[fotokopi_ijazah_sma]" id="fotokopi_ijazah_sma" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotokopi_ijazah_sma')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotokopi_sertifikat_diksarlin">10. Fotokopi Sertifikat Diksarlin:</label>
                <input type="file" name="dokumen[fotokopi_sertifikat_diksarlin]" id="fotokopi_sertifikat_diksarlin" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotokopi_sertifikat_diksarlin')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="sertifikat_responsi">11. Sertifikat Responsi:</label>
                <input type="file" name="dokumen[sertifikat_responsi]" id="sertifikat_responsi" accept=".pdf" required>
                @error('dokumen.sertifikat_responsi')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="nilai_ske">12. Nilai Satuan Kredit Ekstrakurikuler (SKE) (Lampirkan kartu SKE):</label>
                <input type="file" name="dokumen[nilai_ske]" id="nilai_ske" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.nilai_ske')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <p style="font-size: 0.9em; color: #666;">Catatan: Untuk 'Map Plastik', akan diurus secara fisik.</p>

            <button type="submit">Ajukan Tugas Akhir</button>
        </form>

        <a href="{{ route('mahasiswa.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html>