<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Mahasiswa (Admin)</title>
    </head>
<body>
    <div class="container">
        <h2>Daftar Pengajuan Mahasiswa</h2>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jenis Pengajuan</th>
                    <th>Status</th>
                    <th>Tanggal Diajukan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($pengajuan->mahasiswa->nim); ?></td>
                    <td><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?></td>
                    <td><?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></td>
                    <td>
                        <?php if($pengajuan->status == 'diajukan_mahasiswa'): ?>
                            <span class="status-pending">Menunggu Verifikasi Admin</span>
                        <?php elseif($pengajuan->status == 'diverifikasi_admin'): ?>
                            <span class="status-success">Diverifikasi Admin</span>
                        <?php elseif($pengajuan->status == 'ditolak_admin'): ?>
                            <span class="status-danger">Ditolak Admin</span>
                        <?php else: ?>
                            <?php echo e($pengajuan->status); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y')); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.pengajuan.verifikasi.show', $pengajuan->id)); ?>">Lihat Detail & Verifikasi</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">Tidak ada pengajuan yang perlu diverifikasi saat ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php echo e($pengajuans->links()); ?>

    </div>
    <div>
        <li>
            <a href="<?php echo e(route('admin.dashboard')); ?>">kembali Ke Dashboard</a>
        </li>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/admin/pengajuan/index.blade.php ENDPATH**/ ?>