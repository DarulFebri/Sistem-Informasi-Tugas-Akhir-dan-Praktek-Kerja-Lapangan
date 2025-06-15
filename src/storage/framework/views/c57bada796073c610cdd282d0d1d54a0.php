<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi</title>
</head>

<body>

    <h2>Dashboard Kaprodi</h2>

    <p>Jumlah Dosen: <?php echo e($jumlahDosen); ?></p>
    <p>Jumlah Pengajuan Baru: <?php echo e($jumlahPengajuan); ?></p>

    <h3>Pengajuan Terbaru</h3>
    <?php if($pengajuanBaru->count() > 0): ?>
        <ul>
            <?php $__currentLoopData = $pengajuanBaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?> - <?php echo e($pengajuan->jenis_pengajuan); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada pengajuan baru.</p>
    <?php endif; ?>

    <li><a href="<?php echo e(route('kaprodi.pengajuan.index')); ?>">Ke Menu Menajemen Pengajuan Sidang</a></li>

    <br>

    <form action="<?php echo e(route('kaprodi.logout')); ?>" method="POST">
        <?php echo csrf_field(); ?> <button type="submit">Logout</button>
    </form>

</body>

</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kaprodi/dashboard.blade.php ENDPATH**/ ?>