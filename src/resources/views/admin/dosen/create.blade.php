@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Data Dosen</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.dosen.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nomor_induk" class="form-label">Nomor Induk Dosen</label>
                        <input type="text" class="form-control @error('nomor_induk') is-invalid @enderror" 
                               id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk') }}" required>
                        @error('nomor_induk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="kompetensi" class="form-label">Kompetensi</label>
                        <input type="text" class="form-control @error('kompetensi') is-invalid @enderror" 
                               id="kompetensi" name="kompetensi" value="{{ old('kompetensi') }}" required>
                        @error('kompetensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="ketersediaan" class="form-label">Ketersediaan</label>
                        <select class="form-select @error('ketersediaan') is-invalid @enderror" 
                                id="ketersediaan" name="ketersediaan" required>
                            <option value="1" {{ old('ketersediaan', 1) == 1 ? 'selected' : '' }}>Tersedia</option>
                            <option value="0" {{ old('ketersediaan') === '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('ketersediaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection