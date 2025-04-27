@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Dashboard Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Card Mahasiswa -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="bi bi-people-fill text-primary"></i> Total Mahasiswa
                                    </h5>
                                    <p class="card-text display-6">{{ $mahasiswaCount }}</p>
                                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-primary btn-sm">
                                        Lihat Data <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Dosen -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="bi bi-person-badge-fill text-success"></i> Total Dosen
                                    </h5>
                                    <p class="card-text display-6">{{ $dosenCount }}</p>
                                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-success btn-sm">
                                        Lihat Data <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Lainnya -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="bi bi-calendar-check-fill text-info"></i> Bimbingan Aktif
                                    </h5>
                                    <p class="card-text display-6">0</p>
                                    <a href="#" class="btn btn-info btn-sm">
                                        Lihat Jadwal <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Aktivitas Terbaru</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle-fill"></i> Sistem dalam pengembangan. Fitur akan terus ditambahkan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection