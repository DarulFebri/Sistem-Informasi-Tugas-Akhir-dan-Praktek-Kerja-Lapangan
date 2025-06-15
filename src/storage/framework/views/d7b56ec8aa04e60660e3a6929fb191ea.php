<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan'); ?> <?php echo e(strtoupper($jenis)); ?></title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="file"] { border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: calc(100% - 22px); background-color: #f9f9f9; }
        select { border: 1px solid #ccc; padding: 10px; border-radius: 5px; width: 100%; background-color: #f9f9f9; }
        button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px; transition: background-color 0.3s ease; }
        button:hover { background-color: #0056b3; }
        .button-draft { background-color: #6c757d; }
        .button-draft:hover { background-color: #5a6268; }
        .back-link { display: block; text-align: center; margin-top: 30px; color: #007bff; text-decoration: none; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }
        .error-message { color: red; font-size: 0.9em; margin-top: 5px; display: block; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .current-file { font-size: 0.9em; color: #666; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="container">
        <h2><?php echo e(isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan'); ?> <?php echo e(strtoupper($jenis)); ?></h2>

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

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(isset($pengajuan) ? route('mahasiswa.pengajuan.update', $pengajuan->id) : route('mahasiswa.pengajuan.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php if(isset($pengajuan)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>
            <input type="hidden" name="jenis_pengajuan" value="<?php echo e($jenis); ?>">

            <div class="form-group">
                <label for="dosen_pembimbing1_id">Pilih Dosen Pembimbing 1:</label>
                <select name="dosen_pembimbing1_id" id="dosen_pembimbing1_id" required>
                    <option value="">-- Pilih Dosen --</option>
                    <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dosen->id); ?>" <?php echo e((isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_pembimbing_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing1_id') == $dosen->id ? 'selected' : '')); ?>>
                            <?php echo e($dosen->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['dosen_pembimbing1_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="dosen_pembimbing2_id">Pilih Dosen Pembimbing 2:</label>
                <select name="dosen_pembimbing2_id" id="dosen_pembimbing2_id">
                    <option value="">-- Pilih Dosen --</option>
                    <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dosen->id); ?>" <?php echo e((isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_penguji1_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing2_id') == $dosen->id ? 'selected' : '')); ?>>
                            <?php echo e($dosen->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['dosen_pembimbing2_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <h3>Unggah Dokumen Persyaratan:</h3>
            <?php $__currentLoopData = $dokumenSyarat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $namaDokumen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-group">
                    <label for="dokumen_file_<?php echo e($key); ?>"><?php echo e($loop->iteration); ?>. <?php echo e($namaDokumen); ?></label>
                    <input type="file" name="dokumen[<?php echo e($key); ?>]" id="dokumen_file_<?php echo e($key); ?>" accept=".pdf,.jpg,.jpeg,.png">

                    <?php if(isset($dokumenTerupload[$namaDokumen])): ?>
                        <div class="current-file">
                            File saat ini: <a href="<?php echo e(Storage::url($dokumenTerupload[$namaDokumen]->path_file)); ?>" target="_blank"><?php echo e($dokumenTerupload[$namaDokumen]->nama_file); ?></a>
                            <input type="hidden" name="existing_dokumen_file_check[<?php echo e($key); ?>]" value="1">
                        </div>
                    <?php endif; ?>
                    <?php $__errorArgs = ["dokumen.{$key}"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($jenis == 'ta' && $key == 'nilai_toeic'): ?>
                        <small style="color: #666; display: block; margin-top: 5px;">Jika belum mencukupi, fotokopi kartu nilai TOEIC terakhir dan fotokopi bukti pendaftaran tes TOEIC berikutnya.</small>
                    <?php endif; ?>
                     <?php if($jenis == 'pkl' && $key == 'kuisioner_kelulusan'): ?>
                        <small style="color: #666; display: block; margin-top: 5px;">Opsional jika tidak ada.</small>
                    <?php endif; ?>
                     <?php if($jenis == 'ta' && $key == 'ipk_terakhir'): ?>
                        <small style="color: #666; display: block; margin-top: 5px;">(Lampiran Rapor Semester 1 s.d 5 (D3) dan 1 s.d 7 (D4))</small>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <p style="font-size: 0.9em; color: #666; margin-top: 20px;">Catatan: Untuk "Map Plastik", akan diurus secara fisik dan tidak perlu diunggah.</p>

            <button type="submit" name="action" value="submit">Ajukan <?php echo e(strtoupper($jenis)); ?></button>
            <button type="submit" name="action" value="draft" class="button-draft">Simpan sebagai Draft</button>
        </form>

        <a href="<?php echo e(route('mahasiswa.dashboard')); ?>" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/mahasiswa/pengajuan/form.blade.php ENDPATH**/ ?>