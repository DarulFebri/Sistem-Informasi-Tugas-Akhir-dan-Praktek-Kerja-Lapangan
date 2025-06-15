<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
</head>

<body>

    <h2>Daftar Dosen</h2>

    @if (session('success'))
    <div>
        {{ session('success') }}
    </div>
    @endif

    <a href="{{ route('admin.dosen.create') }}">Tambah Dosen</a>
    <a href="{{ route('admin.dosen.import') }}">Import Dosen</a>
    <a href="{{ route('admin.dashboard') }}">Kembali Ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>NIDN</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dosens as $dosen)
            <tr>
                <td>{{ $dosen->nidn }}</td>
                <td>{{ $dosen->nama }}</td>
                <td>{{ $dosen->jurusan }}</td>
                <td>{{ $dosen->prodi }}</td>
                <td>{{ $dosen->jenis_kelamin }}</td>
                <td>
                    <a href="{{ route('admin.dosen.show', $dosen->id) }}">Detail</a>
                    <a href="{{ route('admin.dosen.edit', $dosen->id) }}">Edit</a>
                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>