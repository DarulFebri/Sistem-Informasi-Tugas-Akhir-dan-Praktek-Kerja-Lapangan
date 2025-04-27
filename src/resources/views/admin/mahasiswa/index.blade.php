@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Data Mahasiswa</h5>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Mahasiswa
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th>JK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswas as $mhs)
                        <tr>
                            <td>{{ $mhs->nomor_induk }}</td>
                            <td>{{ $mhs->name }}</td>
                            <td>{{ $mhs->kelas }}</td>
                            <td>{{ $mhs->jurusan }}</td>
                            <td>{{ $mhs->program_studi }}</td>
                            <td>{{ $mhs->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Detail</a>
                                <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>
@endsection