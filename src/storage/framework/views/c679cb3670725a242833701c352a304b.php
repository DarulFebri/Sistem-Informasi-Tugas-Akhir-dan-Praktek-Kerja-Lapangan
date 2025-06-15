
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aksi Kaprodi untuk Pengajuan Sidang</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>"> 
    <style>
        /* Tambahkan CSS dasar di sini atau di file CSS terpisah */
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #007bff; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .buttons { margin-top: 20px; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .error-message { color: red; font-size: 0.9em; margin-top: 5px; display: block; }
        hr { margin: 20px 0; border: 0; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Aksi Kaprodi untuk Pengajuan Sidang</h2>
        <a href="<?php echo e(route('kaprodi.pengajuan.show', $pengajuan->id)); ?>" class="back-link">&larr; Kembali ke Detail Pengajuan</a>

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
        <?php if(session('finalisasi_error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('finalisasi_error')); ?>

            </div>
        <?php endif; ?>

        <p><strong>Mahasiswa:</strong> <?php echo e($pengajuan->mahasiswa->nama_lengkap); ?> (<?php echo e($pengajuan->mahasiswa->nim); ?>)</p>
        <p><strong>Jenis Pengajuan:</strong> <?php echo e($pengajuan->jenis_pengajuan); ?></p>
        <p><strong>Status Pengajuan:</strong> <?php echo e($pengajuan->status); ?></p>
        <p><strong>Tanggal Diajukan:</strong> <?php echo e(\Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i')); ?></p>

        <hr>

        <h3>Informasi Sidang Saat Ini:</h3>
        <p><strong>Pembimbing 1:</strong> <?php echo e($pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Terpilih'); ?></p>
        <p><strong>Pembimbing 2:</strong> <?php echo e($pengajuan->sidang->dosenPenguji1->nama ?? 'Belum Terpilih'); ?></p>
        <p><strong>Tanggal & Waktu Sidang:</strong> <?php echo e($pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan'); ?></p>
        <p><strong>Ruangan Sidang:</strong> <?php echo e($pengajuan->sidang->ruangan_sidang ?? 'Belum Ditentukan'); ?></p>
        <p><strong>Ketua Sidang:</strong> <?php echo e($pengajuan->sidang->ketuaSidang->nama ?? 'Belum Terpilih'); ?></p>
        <p><strong>Sekretaris Sidang:</strong> <?php echo e($pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Terpilih'); ?></p>
        <p><strong>Anggota Sidang 1:</strong> <?php echo e($pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Terpilih'); ?></p>
        <p><strong>Anggota Sidang 2:</strong> <?php echo e($pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Terpilih'); ?></p>

        <hr>

        <?php if(in_array($pengajuan->status, ['diverifikasi_admin', 'menunggu_persetujuan_dosen'])): ?>
            <h4>Form Penjadwalan Sidang</h4>
            <form action="<?php echo e(route('kaprodi.pengajuan.jadwalkan.storeUpdate', $pengajuan->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label for="sekretaris_sidang_id">Sekretaris Sidang:</label>
                    <select name="sekretaris_sidang_id" id="sekretaris_sidang_id" class="form-control" required>
                        <option value="">Pilih Sekretaris</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dosen->id); ?>" <?php echo e(old('sekretaris_sidang_id', $pengajuan->sidang->sekretaris_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : ''); ?>>
                                <?php echo e($dosen->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['sekretaris_sidang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="anggota_1_sidang_id">Anggota 1 Sidang:</label>
                    <select name="anggota_1_sidang_id" id="anggota_1_sidang_id" class="form-control" required>
                        <option value="">Pilih Anggota 1</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dosen->id); ?>" <?php echo e(old('anggota_1_sidang_id', $pengajuan->sidang->anggota1_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : ''); ?>>
                                <?php echo e($dosen->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['anggota_1_sidang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="anggota_2_sidang_id">Anggota 2 Sidang:</label>
                    <select name="anggota_2_sidang_id" id="anggota_2_sidang_id" class="form-control">
                        <option value="">Pilih Anggota 2 (Opsional)</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dosen->id); ?>" <?php echo e(old('anggota_2_sidang_id', $pengajuan->sidang->anggota2_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : ''); ?>>
                                <?php echo e($dosen->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['anggota_2_sidang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="tanggal_waktu_sidang">Tanggal dan Waktu Sidang:</label>
                    <input type="datetime-local" name="tanggal_waktu_sidang" id="tanggal_waktu_sidang" class="form-control"
                                 value="<?php echo e(old('tanggal_waktu_sidang', $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('Y-m-d\TH:i') : '')); ?>" required>
                    <?php $__errorArgs = ['tanggal_waktu_sidang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="ruangan_sidang">Ruangan Sidang:</label>
                    <input type="text" name="ruangan_sidang" id="ruangan_sidang" class="form-control" placeholder="Contoh: Ruang Sidang A"
                                 value="<?php echo e(old('ruangan_sidang', $pengajuan->sidang->ruangan_sidang ?? '')); ?>" required>
                    <?php $__errorArgs = ['ruangan_sidang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">
                        <?php
                            $isScheduled = false;
                            // Memeriksa apakah sidang sudah ada DAN memiliki data jadwal esensial
                            if ($pengajuan->sidang && $pengajuan->sidang->exists &&
                                $pengajuan->sidang->sekretaris_sidang_dosen_id &&
                                $pengajuan->sidang->anggota1_sidang_dosen_id &&
                                $pengajuan->sidang->tanggal_waktu_sidang &&
                                $pengajuan->sidang->ruangan_sidang) {
                                $isScheduled = true;
                            }
                        ?>

                        <?php if($isScheduled): ?>
                            Update Jadwal
                        <?php else: ?>
                            Jadwalkan Sidang
                        <?php endif; ?>
                    </button>
                </div>
            </form>

            
            <?php if($pengajuan->sidang && $pengajuan->status === 'menunggu_persetujuan_dosen'): ?>
                <form action="<?php echo e(route('kaprodi.pengajuan.finalkan.jadwal', $pengajuan->id)); ?>" method="POST" style="display:inline-block; margin-top: 15px;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary">Finalisasi Pengajuan Sidang</button>
                </form>
            <?php endif; ?>

        

        <?php elseif($pengajuan->status === 'sidang_dijadwalkan_final'): ?>
            <div class="alert alert-info">Jadwal sidang ini sudah difinalisasi dan diteruskan ke Kajur.</div>
        <?php elseif($pengajuan->status === 'ditolak_kaprodi'): ?>
            <div class="alert alert-danger">Pengajuan ini ditolak oleh Kaprodi.</div>
        <?php else: ?>
            <div class="alert alert-info">Pengajuan ini tidak memerlukan aksi Kaprodi saat ini atau sudah diproses.</div>
        <?php endif; ?>

    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kaprodi/pengajuan/aksi.blade.php ENDPATH**/ ?>