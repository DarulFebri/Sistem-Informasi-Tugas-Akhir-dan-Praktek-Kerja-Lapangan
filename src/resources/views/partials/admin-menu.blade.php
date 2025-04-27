<li class="nav-item">
    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" 
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard Admin
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}" 
       href="{{ route('admin.mahasiswa.index') }}">
        <i class="bi bi-people-fill me-2"></i>Data Mahasiswa
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->is('admin/dosen*') ? 'active' : '' }}" 
       href="{{ route('admin.dosen.index') }}">
        <i class="bi bi-person-badge me-2"></i>Data Dosen
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-calendar-check me-2"></i>Jadwal Bimbingan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-file-earmark-text me-2"></i>Laporan
    </a>
</li>