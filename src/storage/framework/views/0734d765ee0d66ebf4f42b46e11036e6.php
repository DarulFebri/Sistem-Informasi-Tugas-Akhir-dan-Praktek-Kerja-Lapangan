<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard</title>
    <!-- Tambahkan CSRF Token meta tag untuk keamanan -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>

    <h2>Mahasiswa Dashboard</h2>

    <p>Selamat datang, Mahasiswa!</p>

    <!-- Tombol Logout dengan Form POST -->
    <form action="<?php echo e(route('mahasiswa.logout')); ?>" method="POST">
        <?php echo csrf_field(); ?> <!-- CSRF Protection -->
        <a href="<?php echo e(route('mahasiswa.pengajuan.index')); ?>">Lihat Daftar Pengajuan Saya</a>
        <br>
        <button type="submit">Logout</button>
    </form>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/mahasiswa/dashboard.blade.php ENDPATH**/ ?>