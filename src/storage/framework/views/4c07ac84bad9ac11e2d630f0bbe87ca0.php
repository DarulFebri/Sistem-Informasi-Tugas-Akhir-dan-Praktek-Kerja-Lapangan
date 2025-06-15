<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Terverifikasi - Kajur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 1280px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .header { background-color: #f2f2f2; padding: 15px; border-bottom: 1px solid #e0e0e0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 1.8rem; color: #343a40; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: 500; }
        .nav-links a:hover { text-decoration: underline; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
        .table th { background-color: #e9ecef; font-weight: bold; color: #495057; }
        .table tbody tr:nth-child(even) { background-color: #f8f9fa; }
        .table tbody tr:hover { background-color: #e2e6ea; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.9rem; transition: background-color 0.3s ease; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-size: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .logout-form button { background-color: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem; transition: background-color 0.3s ease; }
        .logout-form button:hover { background-color: #c82333; }
        .text-muted { color: #6c757d; }
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
            <p class="text-muted">Tidak ada pengajuan sidang yang sudah diverifikasi saat ini.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pengajuan</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pengajuanTerverifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($pengajuan->id); ?></td>
                        <td><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?></td>
                        <td><?php echo e(Str::replace('_', ' ', Str::title($pengajuan->jenis_pengajuan))); ?></td>
                        <td><?php echo e($pengajuan->created_at->format('d M Y H:i')); ?></td>
                        <td><?php echo e(Str::replace('_', ' ', Str::title($pengajuan->status))); ?></td>
                        <td>
                            <?php if($pengajuan->sidang): ?> 
                                <a href="<?php echo e(route('kajur.sidang.show', $pengajuan->sidang->id)); ?>" class="btn btn-primary">Lihat Detail</a>
                            <?php else: ?>
                                <span class="text-muted">Sidang Belum Dijadwalkan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    </body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kajur/pengajuan/sudah_verifikasi.blade.php ENDPATH**/ ?>