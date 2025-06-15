<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen</title>
</head>

<body>

    <h2>Edit Dosen</h2>

    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.dosen.update', $dosen->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="nidn">NIDN</label>
            <input type="text" name="nidn" id="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
        </div>

        <div>
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                required>
        </div>

        <div>
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>
        </div>

        <button type="submit">Update</button>
    </form>

</body>

</html>