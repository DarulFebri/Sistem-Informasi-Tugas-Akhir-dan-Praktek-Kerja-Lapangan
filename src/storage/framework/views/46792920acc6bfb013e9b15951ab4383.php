<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
</head>

<body>

    <h2>Daftar Dosen</h2>

    <?php if(session('success')): ?>
    <div>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <a href="<?php echo e(route('admin.dosen.create')); ?>">Tambah Dosen</a>
    <a href="<?php echo e(route('admin.dosen.import')); ?>">Import Dosen</a>
    <a href="<?php echo e(route('admin.dashboard')); ?>">Kembali Ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>NIDN</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($dosen->nidn); ?></td>
                <td><?php echo e($dosen->nama); ?></td>
                <td><?php echo e($dosen->jurusan); ?></td>
                <td><?php echo e($dosen->prodi); ?></td>
                <td><?php echo e($dosen->jenis_kelamin); ?></td>
                <td>
                    <a href="<?php echo e(route('admin.dosen.show', $dosen->id)); ?>">Detail</a>
                    <a href="<?php echo e(route('admin.dosen.edit', $dosen->id)); ?>">Edit</a>
                    <form action="<?php echo e(route('admin.dosen.destroy', $dosen->id)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>

</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/admin/dosen/index.blade.php ENDPATH**/ ?>