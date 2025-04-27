@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Daftar Akun') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Tambahkan radio button untuk role -->
                        <div class="mb-3">
                            <label class="form-label">Daftar Sebagai</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" 
                                       id="roleMahasiswa" value="mahasiswa" checked>
                                <label class="form-check-label" for="roleMahasiswa">
                                    Mahasiswa
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" 
                                       id="roleDosen" value="dosen">
                                <label class="form-check-label" for="roleDosen">
                                    Dosen
                                </label>
                            </div>
                        </div>

                        <!-- Form fields -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nomor_induk" class="form-label">Nomor Induk</label>
                                <input type="text" class="form-control @error('nomor_induk') is-invalid @enderror" 
                                       name="nomor_induk" required>
                                @error('nomor_induk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Tambahkan field lain sesuai kebutuhan -->
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Daftar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection