<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>

    <h2>Admin Dashboard</h2>

    <p>Selamat datang, Admin!</p>

    <hr> 

    <h3>Menu Navigasi</h3>
    <ul>
        <li><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li><a href="<?php echo e(route('admin.mahasiswa.index')); ?>">Manajemen Mahasiswa</a></li>
        <li><a href="<?php echo e(route('admin.dosen.index')); ?>">Manajemen Dosen</a></li>
        
        <li><a href="<?php echo e(route('admin.pengajuan.verifikasi.index')); ?>">Verifikasi Pengajuan Sidang</a></li>
        
                
        <li><a href="<?php echo e(route('admin.sidang.index')); ?>">Manajemen Sidang</a></li>
    </ul>

    <hr>

    <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
        <?php echo csrf_field(); ?> <button type="submit">Logout</button>
    </form>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>