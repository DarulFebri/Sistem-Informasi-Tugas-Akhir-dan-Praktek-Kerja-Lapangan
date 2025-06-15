<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen</title>
</head>

<body>

    <h2>Edit Dosen</h2>

    <?php if($errors->any()): ?>
    <div>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.dosen.update', $dosen->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label for="nidn">NIDN</label>
            <input type="text" name="nidn" id="nidn" value="<?php echo e(old('nidn', $dosen->nidn)); ?>" required>
        </div>

        <div>
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo e(old('nama_lengkap', $dosen->nama_lengkap)); ?>"
                required>
        </div>

        <div>
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $dosen->jenis_kelamin)=='Laki-laki' ? 'selected' : ''); ?>>Laki-laki
                </option>
                <option value="Perempuan" <?php echo e(old('jenis_kelamin', $dosen->jenis_kelamin)=='Perempuan' ? 'selected' : ''); ?>>Perempuan
                </option>
            </select>
        </div>

        <button type="submit">Update</button>
    </form>

</body>

</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/admin/dosen/edit.blade.php ENDPATH**/ ?>