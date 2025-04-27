@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Data Dosen</h5>
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Dosen
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.dosen.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/NID..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="kompetensi" class="form-select">
                            <option value="">Semua Kompetensi</option>
                            <option value="Pemrograman" {{ request('kompetensi') == 'Pemrograman' ? 'selected' : '' }}>Pemrograman</option>
                            <option value="Jaringan" {{ request('kompetensi') == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                            <option value="Basis Data" {{ request('kompetensi') == 'Basis Data' ? 'selected' : '' }}>Basis Data</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="ketersediaan" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('ketersediaan') === '1' ? 'selected' : '' }}>Tersedia</option>
                            <option value="0" {{ request('ketersediaan') === '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>NID</th>
                            <th>Nama</th>
                            <th>Kompetensi</th>
                            <th>Status</th>
                            <th>JK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosens as $dosen)
                        <tr>
                            <td>{{ $dosen->nomor_induk }}</td>
                            <td>{{ $dosen->name }}</td>
                            <td>{{ $dosen->kompetensi }}</td>
                            <td>
                                <span class="badge bg-{{ $dosen->ketersediaan ? 'success' : 'danger' }}">
                                    {{ $dosen->ketersediaan ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                            <td>{{ $dosen->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>
                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin menghapus dosen ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data dosen</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $dosens->links() }}
        </div>
    </div>
</div>
@endsection