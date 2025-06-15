<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Terverifikasi - Kajur</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 1280px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .header { background-color: #f2f2f2; padding: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .logout-form button { background-color: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengajuan Sudah Diverifikasi</h1>
            <div class="nav-links">
                <a href="<?php echo e(route('kajur.dashboard')); ?>">Dashboard</a>
                <a href="<?php echo e(route('kajur.pengajuan.perlu_verifikasi')); ?>">Pengajuan Perlu Verifikasi</a>
                <a href="<?php echo e(route('kajur.pengajuan.sudah_verifikasi')); ?>">Pengajuan Terverifikasi</a>
                <form action="<?php echo e(route('kajur.logout')); ?>" method="POST" class="logout-form" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($pengajuanTerverifikasi->isEmpty()): ?>
            <p>Tidak ada pengajuan sidang yang sudah diverifikasi saat ini.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pengajuan</th>
                        <th>Nama Mahasiswa</th> <th>Jenis Pengajuan</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pengajuanTerverifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($pengajuan->id); ?></td>
                        <td><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?></td> <td><?php echo e(Str::replace('_', ' ', Str::title($pengajuan->jenis_pengajuan))); ?></td>
                        <td><?php echo e($pengajuan->created_at->format('d M Y H:i')); ?></td>
                        <td><?php echo e(Str::replace('_', ' ', Str::title($pengajuan->status))); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kajur/pengajuan/sudah_verifikasi.blade.php ENDPATH**/ ?>