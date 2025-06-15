<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen</title>
</head>

<body>

    <h2>Tambah Dosen</h2>

    <?php if($errors->any()): ?>
    <div style="color: red;"> 
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.dosen.store')); ?>">
        <?php echo csrf_field(); ?>

        <div>
            <label for="nidn">NIDN:</label>
            <input type="text" name="nidn" id="nidn" value="<?php echo e(old('nidn')); ?>" required>
        </div>

        <div>
            <label for="nama">Nama Dosen:</label> 
            <input type="text" name="nama" id="nama" value="<?php echo e(old('nama')); ?>" required>
        </div>

        <div>
            <label for="jurusan">Jurusan:</label> 
            <input type="text" name="jurusan" id="jurusan" value="<?php echo e(old('jurusan')); ?>" required>
        </div>

        <div>
            <label for="prodi">Program Studi:</label> 
            <input type="text" name="prodi" id="prodi" value="<?php echo e(old('prodi')); ?>" required>
        </div>

        <div>
            <label for="jenis_kelamin">Jenis Kelamin:</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="Laki-laki" <?php echo e(old('jenis_kelamin')=='Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                <option value="Perempuan" <?php echo e(old('jenis_kelamin')=='Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
            </select>
        </div>

        <div>
            <label for="email">Email (untuk Login):</label> 
            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required>
        </div>

        <div>
            <label for="password">Password (untuk Login):</label> 
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Simpan Dosen</button>
    </form>

</body>

</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/admin/dosen/create.blade.php ENDPATH**/ ?>