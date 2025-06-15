<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
</head>
<body>

    <h2>Tambah Mahasiswa</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
        @csrf

        <div>
            <label for="nim">NIM</label>
            <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required>
        </div>

        <div>
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
        </div>

        <div>
            <label for="jurusan">Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan') }}" required>
        </div>

        <div>
            <label for="prodi">Prodi</label>
            <input type="text" name="prodi" id="prodi" value="{{ old('prodi') }}" required>
        </div>

        <div>
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div>
            <label for="kelas">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}" required>
        </div>

        {{-- Tambahkan field email dan password ini --}}
        <div>
            <label for="email">Email (untuk Login)</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password (untuk Login)</label>
            <input type="password" name="password" id="password" required>
        </div>
        {{-- Akhir tambahan --}}

        <button type="submit">Simpan</button>
    </form>

</body>
</html>