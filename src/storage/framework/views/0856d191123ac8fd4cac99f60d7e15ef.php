<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Saya</title>
</head>
<body>

    <h2>Daftar Pengajuan Sidang Saya</h2>

    <?php if(session('success')): ?>
        <div style="color: green;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="color: red;">
            <?php echo e(session('error' )); ?>

        </div>
    <?php endif; ?>

    <?php if($pengajuans->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Pengajuan</th>
                    <th>Status</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($pengajuan->id); ?></td>
                        <td><?php echo e(ucfirst($pengajuan->jenis_pengajuan)); ?></td>
                        <td><?php echo e(str_replace('_', ' ', strtoupper($pengajuan->status))); ?></td>
                        <td><?php echo e($pengajuan->created_at->format('d M Y H:i')); ?></td>
                        <td>
                            <a href="<?php echo e(route('mahasiswa.pengajuan.show', $pengajuan->id)); ?>">Lihat Detail</a>

                            
                            <?php if($pengajuan->status === 'draft'): ?>
                                <a href="<?php echo e(route('mahasiswa.pengajuan.edit', $pengajuan->id)); ?>">Edit</a>
                                <form action="<?php echo e(route('mahasiswa.pengajuan.destroy', $pengajuan->id)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            <?php elseif($pengajuan->status === 'dosen_ditunjuk'): ?>
                                
                                <span style="color: blue;">(Dosen Ditunjuk)</span>
                                
                                
                            <?php elseif($pengajuan->status === 'ditolak_admin' || $pengajuan->status === 'ditolak_kaprodi'): ?>
                                
                                <span style="color: orange;">(Ditolak)</span>
                                <a href="<?php echo e(route('mahasiswa.pengajuan.edit', $pengajuan->id)); ?>">Ajukan Ulang</a>
                            <?php else: ?>
                                
                                <span style="color: gray;">(Menunggu Proses)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Anda belum memiliki pengajuan. Silakan buat pengajuan baru.</p>
    <?php endif; ?>
    <br>
    <a href="<?php echo e(route('mahasiswa.dashboard')); ?>">Kembali ke Dashboard</a>
    <?php if($pengajuans->count() == 0): ?> 
        <a href="<?php echo e(route('mahasiswa.pengajuan.pilih')); ?>">Buat Pengajuan Baru</a>
    <?php endif; ?>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/mahasiswa/pengajuan/index.blade.php ENDPATH**/ ?>