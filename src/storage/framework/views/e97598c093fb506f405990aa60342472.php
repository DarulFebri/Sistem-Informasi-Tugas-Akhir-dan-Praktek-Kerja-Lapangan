<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Undangan Sidang</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #007bff; margin-bottom: 20px; }
        p { margin-bottom: 10px; }
        strong { color: #333; } /* Updated color for strong tags */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        textarea, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .buttons { margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 1em; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; margin-left: 10px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Respon Undangan Sidang</h2>
        <a href="<?php echo e(route('dosen.dashboard')); ?>" class="back-link">&larr; Kembali ke Dashboard</a>

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

        <h3>Detail Sidang</h3>
        <p><strong>Mahasiswa:</strong> <?php echo e($sidang->pengajuan->mahasiswa->nama_lengkap); ?> (NIM: <?php echo e($sidang->pengajuan->mahasiswa->nim); ?>)</p>
        <p><strong>Jenis Sidang:</strong> <?php echo e(strtoupper($sidang->pengajuan->jenis_pengajuan)); ?></p>
        <p><strong>Tanggal & Waktu:</strong> <?php echo e(\Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i')); ?></p>
        <p><strong>Ruangan:</strong> <?php echo e($sidang->ruangan_sidang); ?></p>
        <p><strong>Ketua Sidang:</strong> <?php echo e($sidang->ketuaSidang->nama ?? 'N/A'); ?></p>
        <p><strong>Sekretaris Sidang:</strong> <?php echo e($sidang->sekretarisSidang->nama ?? 'N/A'); ?></p>
        <p><strong>Anggota Sidang 1:</strong> <?php echo e($sidang->anggota1Sidang->nama ?? 'N/A'); ?></p>
        <p><strong>Anggota Sidang 2:</strong> <?php echo e($sidang->anggota2Sidang->nama ?? 'N/A'); ?></p>
        <p><strong>Dosen Pembimbing 1:</strong> <?php echo e($sidang->dosenPembimbing->nama ?? 'N/A'); ?></p>
        <p><strong>Dosen Pembimbing 2 (Penguji 1):</strong> <?php echo e($sidang->dosenPenguji1->nama ?? 'N/A'); ?></p>

        <hr>

        <h3>Respon Anda</h3>
        <p>Anda berperan sebagai:
            <?php
                $dosenLoginId = Auth::user()->dosen->id;
                if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) echo 'Ketua Sidang';
                elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) echo 'Sekretaris Sidang';
                elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) echo 'Anggota Sidang 1 (Penguji)';
                elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) echo 'Anggota Sidang 2 (Penguji)';
                elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) echo 'Dosen Pembimbing 1';
                elseif ($sidang->dosen_penguji1_id == $dosenLoginId) echo 'Dosen Pembimbing 2';
            ?>
        </p>

        <form action="<?php echo e(route('dosen.sidang.respon.submit', $sidang->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="respon">Pilih Respon:</label>
                <select name="respon" id="respon" class="form-control" required>
                    <option value="">-- Pilih Respon --</option>
                    <option value="setuju" <?php echo e(old('respon') == 'setuju' ? 'selected' : ''); ?>>Setuju</option>
                    <option value="tolak" <?php echo e(old('respon') == 'tolak' ? 'selected' : ''); ?>>Tolak</option>
                </select>
                <?php $__errorArgs = ['respon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan (Opsional):</label>
                <textarea name="catatan" id="catatan" rows="4" class="form-control" placeholder="Tulis catatan jika diperlukan"><?php echo e(old('catatan')); ?></textarea>
                <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-primary">Kirim Respon</button>
            </div>
        </form>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/dosen/respon_sidang.blade.php ENDPATH**/ ?>