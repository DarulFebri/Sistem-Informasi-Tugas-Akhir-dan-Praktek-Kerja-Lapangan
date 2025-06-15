<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengajuan Sidang <?php echo e(strtoupper($jenis)); ?></title>
</head>
<body>

    <h2>Edit Pengajuan Sidang <?php echo e(strtoupper($jenis)); ?></h2>

    <?php if(session('success')): ?>
        <div style="color: green;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="color: red;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="color: red;">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form method="POST" action="<?php echo e(route('mahasiswa.pengajuan.update', $pengajuan->id)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?> 
    
        <input type="hidden" name="jenis_pengajuan" value="<?php echo e($jenis); ?>">
    
        <h3>Dokumen Persyaratan:</h3>
    
        <div>
            <label for="ketua_sidang_dosen_id">Pilih Ketua Sidang:</label>
            <select name="ketua_sidang_dosen_id" id="ketua_sidang_dosen_id" required>
                <option value="">-- Pilih Dosen --</option>
                <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <option value="<?php echo e($dosen->id); ?>" <?php echo e(old('ketua_sidang_dosen_id', $pengajuan->sidang->ketua_sidang_dosen_id ?? null) == $dosen->id ? 'selected' : ''); ?>>
                        <?php echo e($dosen->nama); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['ketua_sidang_dosen_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                <div style="color: red; font-size: 0.8em; margin-top: 5px;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    
        <?php $__currentLoopData = $dokumenSyarat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $namaDokumen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $uploadedDoc = $pengajuan->dokumens->where('nama_file', $namaDokumen)->first();
            ?>
            <div>
                <label for="dokumen_file_<?php echo e($key); ?>"><?php echo e($namaDokumen); ?>:</label>
                <?php if($uploadedDoc): ?>
                    <p>File saat ini: <a href="<?php echo e(asset('storage/' . $uploadedDoc->path_file)); ?>" target="_blank">Lihat File</a></p>
                <?php else: ?>
                    <p>Belum ada file terupload.</p>
                <?php endif; ?>
                <input type="file" name="dokumen_file_<?php echo e($key); ?>" id="dokumen_file_<?php echo e($key); ?>" accept="application/pdf">
                <small>(Upload file baru untuk mengganti yang lama atau mengisi yang kosong. Format: PDF, Maksimal: 2MB)</small>
            </div>
            <?php if(!$loop->last): ?> 
                <br><br> 
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
        <br>
        <button type="submit" name="action" value="draft">Simpan Perubahan Draft</button>
        <button type="submit" name="action" value="submit" onclick="return confirm('Apakah Anda yakin ingin memfinalisasi pengajuan ini? Setelah difinalisasi, Anda tidak dapat mengubahnya lagi.');">Finalisasi dan Ajukan</button>
    </form>

    <a href="<?php echo e(route('mahasiswa.dashboard')); ?>">Kembali ke Dashboard</a>
    <a href="<?php echo e(route('mahasiswa.pengajuan.show', $pengajuan->id)); ?>">Kembali ke Detail Pengajuan</a>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/mahasiswa/pengajuan/edit.blade.php ENDPATH**/ ?>