<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Praktek Kerja Lapangan</title>
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
        <h2>Form Pengajuan Praktek Kerja Lapangan</h2>

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
            <input type="hidden" name="jenis_pengajuan" value="pkl">

            <div class="form-group">
                <label for="laporan_pkl">1. Laporan PKL (2 rangkap):</label>
                <input type="file" name="dokumen[laporan_pkl]" id="laporan_pkl" accept=".pdf" required>
                @error('dokumen.laporan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="buku_pkl">2. Buku PKL:</label>
                <input type="file" name="dokumen[buku_pkl]" id="buku_pkl" accept=".pdf" required>
                @error('dokumen.buku_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="kuisioner_survey_pkl">3. Kuisioner Survey PKL (telah diisi, ditandatangani, dan distempel perusahaan):</label>
                <input type="file" name="dokumen[kuisioner_survey_pkl]" id="kuisioner_survey_pkl" accept=".pdf" required>
                @error('dokumen.kuisioner_survey_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="kuisioner_kelulusan">4. Kuisioner Kelulusan (jika ada):</label>
                <input type="file" name="dokumen[kuisioner_kelulusan]" id="kuisioner_kelulusan" accept=".pdf">
                <small>Opsional jika tidak ada</small>
                @error('dokumen.kuisioner_kelulusan')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="kuisioner_balikan_pkl">5. Kuisioner Balikan PKL:</label>
                <input type="file" name="dokumen[kuisioner_balikan_pkl]" id="kuisioner_balikan_pkl" accept=".pdf" required>
                @error('dokumen.kuisioner_balikan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="lembaran_rekomendasi_penguji">6. Lembaran Rekomendasi Penguji:</label>
                <input type="file" name="dokumen[lembaran_rekomendasi_penguji]" id="lembaran_rekomendasi_penguji" accept=".pdf" required>
                @error('dokumen.lembaran_rekomendasi_penguji')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="surat_permohonan_sidang_pkl">7. Surat Permohonan Sidang PKL:</label>
                <input type="file" name="dokumen[surat_permohonan_sidang_pkl]" id="surat_permohonan_sidang_pkl" accept=".pdf" required>
                @error('dokumen.surat_permohonan_sidang_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="lembar_penilaian_sidang_pkl">8. Lembar Penilaian Sidang PKL (Penguji):</label>
                <input type="file" name="dokumen[lembar_penilaian_sidang_pkl]" id="lembar_penilaian_sidang_pkl" accept=".pdf" required>
                @error('dokumen.lembar_penilaian_sidang_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="surat_keterangan_pelaksanaan_pkl">9. Surat Keterangan Pelaksanaan PKL (Asli, distempel dan ditandatangani pihak perusahaan):</label>
                <input type="file" name="dokumen[surat_keterangan_pelaksanaan_pkl]" id="surat_keterangan_pelaksanaan_pkl" accept=".pdf" required>
                @error('dokumen.surat_keterangan_pelaksanaan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotocopy_cover_laporan_pkl">10. Fotokopi Cover Laporan PKL (ada tanda tangan persetujuan sidang dari dosen pembimbing PKL):</label>
                <input type="file" name="dokumen[fotocopy_cover_laporan_pkl]" id="fotocopy_cover_laporan_pkl" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotocopy_cover_laporan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotocopy_lembar_penilaian_industri">11. Fotokopi Lembar Penilaian dari Pembimbing di Industri (ditandatangani pembimbing industri):</label>
                <input type="file" name="dokumen[fotocopy_lembar_penilaian_industri]" id="fotocopy_lembar_penilaian_industri" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotocopy_lembar_penilaian_industri')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotocopy_lembar_penilaian_dosen_pembimbing_pkl">12. Fotokopi Lembar Penilaian dari Dosen Pembimbing PKL (ditandatangani pembimbing kampus):</label>
                <input type="file" name="dokumen[fotocopy_lembar_penilaian_dosen_pembimbing_pkl]" id="fotocopy_lembar_penilaian_dosen_pembimbing_pkl" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotocopy_lembar_penilaian_dosen_pembimbing_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="fotocopy_lembar_konsultasi_bimbingan_pkl">13. Fotokopi Lembar Konsultasi Bimbingan PKL (diisi dan ditandatangani pembimbing kampus):</label>
                <input type="file" name="dokumen[fotocopy_lembar_konsultasi_bimbingan_pkl]" id="fotocopy_lembar_konsultasi_bimbingan_pkl" accept=".pdf,.jpg,.jpeg,.png" required>
                @error('dokumen.fotocopy_lembar_konsultasi_bimbingan_pkl')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Ajukan PKL</button>
        </form>

        <a href="{{ route('mahasiswa.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html>