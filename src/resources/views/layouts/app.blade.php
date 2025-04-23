<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SIPRAKTA</title>
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="logo-area">
            <img src="{{ asset('images/logo-anda.png') }}" alt="Logo Perusahaan" class="logo">
        </div>
        <h5><i class="bi bi-columns-gap"></i> Dashboard</h5>
        <ul class="nav flex-column">
            <li>
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#kelolaBimbingan" aria-expanded="false" aria-controls="kelolaBimbingan">
                    <i class="bi bi-people"></i> Kelola Bimbingan <i class="bi bi-caret-down-fill ms-auto dropdown-icon"></i>
                </a>
                <div class="collapse submenu" id="kelolaBimbingan">
                    <a class="nav-link" href="#">Dosen Pembimbing</a>
                    <a class="nav-link" href="#">Mahasiswa Bimbingan</a>
                </div>
            </li>
            <li>
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pengajuanJudul" aria-expanded="false" aria-controls="pengajuanJudul">
                    <i class="bi bi-journal-text"></i> Pengajuan Judul <i class="bi bi-caret-down-fill ms-auto dropdown-icon"></i>
                </a>
                <div class="collapse submenu" id="pengajuanJudul">
                    <a class="nav-link" href="#">Judul Masuk</a>
                    <a class="nav-link" href="#">Judul Diterima</a>
                </div>
            </li>
            <li>
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pengajuanTA" aria-expanded="false" aria-controls="pengajuanTA">
                    <i class="bi bi-journals"></i> Pengajuan TA <i class="bi bi-caret-down-fill ms-auto dropdown-icon"></i>
                </a>
                <div class="collapse submenu" id="pengajuanTA">
                    <a class="nav-link" href="#">TA Pengerjaan</a>
                    <a class="nav-link" href="#">TA Terjadwal</a>
                    <a class="nav-link" href="#">TA Selesai</a>
                </div>
            </li>
            <li>
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#dosen" aria-expanded="false" aria-controls="dosen">
                    <i class="bi bi-person"></i> Dosen <i class="bi bi-caret-down-fill ms-auto dropdown-icon"></i>
                </a>
                <div class="collapse submenu" id="dosen">
                    <a class="nav-link" href="#">Data Dosen</a>
                </div>
            </li>
            <li>
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#mahasiswa" aria-expanded="false" aria-controls="mahasiswa">
                    <i class="bi bi-mortarboard"></i> Mahasiswa <i class="bi bi-caret-down-fill ms-auto dropdown-icon"></i>
                </a>
                <div class="collapse submenu" id="mahasiswa">
                    <a class="nav-link" href="#">Data Mahasiswa</a>
                </div>
            </li>
            <li><a class="nav-link" href="#"><i class="bi bi-book"></i> Program Studi</a></li>
            <li><a class="nav-link" href="#"><i class="bi bi-calendar-event"></i> Jadwal</a></li>
        </ul>
    </div>

    <div class="d-flex flex-column w-100">
        <div class="topbar">
            <div><i class="bi bi-list" id="sidebarToggle"></i></div>
            <div>
                <span>Administrator</span>
                <i class="bi bi-person-circle ms-2"></i>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinksWithSubmenu = document.querySelectorAll('.sidebar .nav-link[data-bs-toggle="collapse"]');
        const submenuLinks = document.querySelectorAll('.sidebar .submenu .nav-link');

        navLinksWithSubmenu.forEach(link => {
            link.addEventListener('click', function() {
                const targetId = this.getAttribute('data-bs-target');
                const currentlyOpen = document.querySelector('.submenu.show');

                if (currentlyOpen && '#' + currentlyOpen.id !== targetId) {
                    const bsCollapse = new bootstrap.Collapse(currentlyOpen);
                    bsCollapse.hide();
                    const currentlyOpenLink = document.querySelector(`.nav-link[data-bs-target="#${currentlyOpen.id}"]`);
                    if (currentlyOpenLink) {
                        currentlyOpenLink.setAttribute('aria-expanded', 'false');
                        const dropdownIcon = currentlyOpenLink.querySelector('.dropdown-icon');
                        if (dropdownIcon) {
                            dropdownIcon.classList.remove('rotated');
                        }
                    }
                }

                const isExpanded = this.getAttribute('aria-expanded') === 'true' || false;
                const dropdownIcon = this.querySelector('.dropdown-icon');
                if (dropdownIcon) {
                    dropdownIcon.classList.toggle('rotated', !isExpanded);
                }
            });
        });

        const allSubmenus = document.querySelectorAll('.submenu');
        allSubmenus.forEach(submenu => {
            const bsCollapse = new bootstrap.Collapse(submenu, { toggle: false });
        });

        submenuLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                submenuLinks.forEach(otherLink => {
                    otherLink.classList.remove('active-submenu');
                });
                this.classList.add('active-submenu');
            });
        });
    });
</script>
</body>
</html>