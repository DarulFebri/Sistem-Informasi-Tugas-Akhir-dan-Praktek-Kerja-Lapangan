<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan & Verifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #0056b3;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .info-group {
            margin-bottom: 20px;
            padding-left: 15px;
            border-left: 5px solid #007bff;
            background-color: #f8fafd;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .info-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .info-group p {
            margin: 0;
            color: #333;
            line-height: 1.5;
        }
        .document-list {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }
        .document-item {
            margin-bottom: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .document-item span {
            font-weight: bold;
            color: #333;
        }
        .document-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.2s ease-in-out;
        }
        .document-item a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    
        hr {
            border: 0;
            height: 1px;
            background: #eee;
            margin: 30px 0;
        }
    
        /* Styles for the verification form */
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column; /* Menumpuk elemen di dalamnya secara vertikal */
        }
    
        /* Ini adalah label utama "Pilih Status Verifikasi:" */
        .form-group > label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }
    
        /* Penataan untuk radio button dan labelnya */
        .form-group .radio-option {
            display: flex; /* Gunakan flexbox untuk menyelaraskan radio dan label di sampingnya */
            align-items: center; /* Pusatkan secara vertikal */
            margin-bottom: 5px; /* Jarak antar pilihan radio */
        }
    
        .form-group input[type="radio"] {
            margin-right: 8px; /* Jarak antara radio button dan label */
            margin-left: 0; /* Pastikan tidak ada margin kiri yang tidak diinginkan */
            flex-shrink: 0; /* Pastikan radio button tidak menyusut */
            order: 1; /* Radio button akan muncul pertama (paling kiri) */
        }
    
        .form-group .radio-option label {
            font-weight: normal; /* Atur ulang bold dari label utama jika perlu */
            margin-bottom: 0; /* Hapus margin bawah default untuk label ini */
            cursor: pointer; /* Memberikan indikasi bahwa label bisa diklik */
            order: 2; /* Label akan muncul kedua (di sebelah kanan radio) */
        }
    
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures padding doesn't affect total width */
            min-height: 80px;
            resize: vertical;
            font-size: 1em;
        }
        .buttons {
            text-align: right;
            margin-top: 25px;
        }
        .buttons button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s ease-in-out;
        }
        .buttons button:hover {
            background-color: #218838;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    
        /* Alerts (from previous suggestions) */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Pengajuan Mahasiswa</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
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

        <div class="info-group">
            <label>Mahasiswa:</label>
            <p>{{ $pengajuan->mahasiswa->nama_lengkap }} (NIM: {{ $pengajuan->mahasiswa->nim }})</p>
        </div>
        <div class="info-group">
            <label>Jenis Pengajuan:</label>
            <p>{{ strtoupper($pengajuan->jenis_pengajuan) }}</p>
        </div>
        <div class="info-group">
            <label>Judul Pengajuan:</label>
            <p>{{ $pengajuan->judul }}</p>
        </div>
        <div class="info-group">
            <label>Tanggal Diajukan:</label>
            <p>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y H:i') }}</p>
        </div>
        <div class="info-group">
            <label>Status Saat Ini:</label>
            <p>{{ $pengajuan->status }} @if($pengajuan->alasan_penolakan_admin) (Alasan: {{ $pengajuan->alasan_penolakan_admin }}) @endif</p>
        </div>

        <h3>Dokumen Terlampir:</h3>
        <ul class="document-list">
            @forelse ($pengajuan->dokumens as $dokumen)
            <li class="document-item">
                {{-- Tampilkan nama dokumen dari kolom 'nama_file' --}}
                <span>{{ $dokumen->nama_file }}</span>
                {{-- Gunakan path_file dari database --}}
                <a href="{{ Storage::url($dokumen->path_file) }}" target="_blank">Lihat Dokumen</a>
            </li>
            @empty
            <li class="document-item">Tidak ada dokumen terlampir.</li>
            @endforelse
        </ul>

        <hr>

        @if ($pengajuan->status == 'diajukan_mahasiswa' || $pengajuan->status == 'ditolak_admin')
            <h3>Verifikasi Dokumen:</h3>
            <form action="{{ route('admin.pengajuan.verifikasi.verify', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Pilih Status Verifikasi:</label>
                
                    <div class="radio-option">
                        <input type="radio" name="verifikasi_status" id="status_setuju" value="setuju" {{ old('verifikasi_status') == 'setuju' ? 'checked' : '' }} required>
                        <label for="status_setuju">Setuju (Dokumen Lengkap & Valid)</label>
                    </div>
                
                    <div class="radio-option">
                        <input type="radio" name="verifikasi_status" id="status_tolak" value="tolak" {{ old('verifikasi_status') == 'tolak' ? 'checked' : '' }} required>
                        <label for="status_tolak">Tolak (Dokumen Tidak Lengkap / Tidak Valid)</label>
                    </div>
                </div>

                <div class="form-group" id="alasan_tolak_admin_group" style="display: {{ old('verifikasi_status') == 'tolak' ? 'block' : 'none' }};">
                    <label for="alasan_penolakan_admin">Alasan Penolakan:</label>
                    <textarea name="alasan_penolakan_admin" id="alasan_penolakan_admin" placeholder="Sebutkan alasan penolakan, contoh: 'Dokumen transkrip nilai belum terlampir.'">{{ old('alasan_penolakan_admin') }}</textarea>
                    @error('alasan_penolakan_admin')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit">Kirim Verifikasi</button>
                </div>
            </form>
        @else
            <div class="alert alert-info">Pengajuan ini sudah diverifikasi dan tidak dapat diubah lagi oleh Admin.</div>
        @endif

        <a href="{{ route('admin.pengajuan.index') }}" class="back-link">Kembali ke Daftar Pengajuan</a>
    </div>

    <script>
        const statusSetuju = document.getElementById('status_setuju');
        const statusTolak = document.getElementById('status_tolak');
        const alasanTolakAdminGroup = document.getElementById('alasan_tolak_admin_group');
        const alasanAdminTextarea = document.getElementById('alasan_penolakan_admin');

        function toggleAlasanAdminField() {
            if (statusTolak.checked) {
                alasanTolakAdminGroup.style.display = 'block';
                alasanAdminTextarea.setAttribute('required', 'required');
            } else {
                alasanTolakAdminGroup.style.display = 'none';
                alasanAdminTextarea.removeAttribute('required');
                alasanAdminTextarea.value = '';
            }
        }

        statusSetuju.addEventListener('change', toggleAlasanAdminField);
        statusTolak.addEventListener('change', toggleAlasanAdminField);

        toggleAlasanAdminField(); // Call on page load
    </script>
</body>
</html>