<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen</title>
</head>

<body>

    <h2>Tambah Dosen</h2>

    @if ($errors->any())
    <div style="color: red;"> {{-- Menambahkan style agar lebih terlihat --}}
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.dosen.store') }}">
        @csrf

        <div>
            <label for="nidn">NIDN:</label>
            <input type="text" name="nidn" id="nidn" value="{{ old('nidn') }}" required>
        </div>

        <div>
            <label for="nama">Nama Dosen:</label> {{-- Mengganti nama_lengkap menjadi nama --}}
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
        </div>

        <div>
            <label for="jurusan">Jurusan:</label> {{-- Input baru --}}
            <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan') }}" required>
        </div>

        <div>
            <label for="prodi">Program Studi:</label> {{-- Input baru --}}
            <input type="text" name="prodi" id="prodi" value="{{ old('prodi') }}" required>
        </div>

        <div>
            <label for="jenis_kelamin">Jenis Kelamin:</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div>
            <label for="email">Email (untuk Login):</label> {{-- Input baru --}}
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password (untuk Login):</label> {{-- Input baru --}}
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Simpan Dosen</button>
    </form>

</body>

</html>