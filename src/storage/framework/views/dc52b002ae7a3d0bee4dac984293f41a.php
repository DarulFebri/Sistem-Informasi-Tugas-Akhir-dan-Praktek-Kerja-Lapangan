<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Sidang <?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></title> 
</head>
<body>

    <h2>Detail Pengajuan Sidang <?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></h2> 

    
    <?php if(session('success')): ?>
        <div style="color: green; margin-bottom: 10px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="color: red; margin-bottom: 10px;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <p>Jenis Pengajuan: <strong><?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></strong></p>
    <p>Status:
        
        <?php if($pengajuan->status === 'draft'): ?>
            <strong style="color: orange;">DRAFT</strong>
        <?php elseif($pengajuan->status === 'diajukan'): ?>
            <strong style="color: blue;">DIAJUKAN</strong>
        <?php elseif($pengajuan->status === 'diproses'): ?>
            <strong style="color: green;">DIPROSES</strong>
        <?php elseif($pengajuan->status === 'disetujui'): ?>
            <strong style="color: purple;">DISETUJUI</strong>
        <?php elseif($pengajuan->status === 'ditolak'): ?>
            <strong style="color: red;">DITOLAK</strong>
        <?php elseif($pengajuan->status === 'selesai'): ?>
            <strong style="color: green;">SELESAI</strong>
        <?php elseif($pengajuan->status === 'sidang_dijadwalkan_final'): ?> 
            <strong style="color: darkgreen;">SIDANG DIJADWALKAN FINAL</strong>
        <?php else: ?>
            <strong><?php echo e(strtoupper($pengajuan->status)); ?></strong> 
        <?php endif; ?>
    </p>
    <p>Tanggal Dibuat: <?php echo e($pengajuan->created_at->format('d M Y H:i')); ?></p>
    <p>Terakhir Diperbarui: <?php echo e($pengajuan->updated_at->format('d M Y H:i')); ?></p>

    
    <h3>Informasi Jadwal Sidang</h3>
    <?php if($pengajuan->sidang && $pengajuan->sidang->tanggal_waktu_sidang && $pengajuan->sidang->ruangan_sidang): ?>
        <p>Tanggal Sidang: <strong><?php echo e(\Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y')); ?></strong></p>
        <p>Waktu Sidang: <strong><?php echo e(\Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('H:i')); ?> WIB</strong></p>
        <p>Ruangan Sidang: <strong><?php echo e($pengajuan->sidang->ruangan_sidang); ?></strong></p>
    <?php else: ?>
        <p>Jadwal sidang belum ditetapkan.</p>
    <?php endif; ?>
    
    <h3>Dokumen Terupload:</h3>
    
    <?php if($pengajuan->dokumens->count() > 0): ?>
        <ul>
            <?php $__currentLoopData = $pengajuan->dokumens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dokumen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                <li>
                    <?php echo e($dokumen->nama_file); ?>:
                    <a href="<?php echo e(asset('storage/' . $dokumen->path_file)); ?>" target="_blank">Lihat File</a>
                    
                    
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <p>Belum ada dokumen yang diunggah untuk pengajuan ini.</p>
    <?php endif; ?>

    <h3>Informasi Anggota Sidang</h3>
    <?php if($pengajuan->sidang): ?>
        <p><strong>Ketua Sidang:</strong> <?php echo e($pengajuan->sidang->ketuaSidang->nama ?? 'Belum Ditunjuk'); ?></p>
        <p><strong>Sekretaris Sidang:</strong> <?php echo e($pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk'); ?></p>
        <p><strong>Anggota Sidang 1:</strong> <?php echo e($pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk'); ?></p>
        <p><strong>Anggota Sidang 2:</strong> <?php echo e($pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk'); ?></p>
    <?php else: ?>
        <p>Anggota sidang belum ditunjuk.</p>
    <?php endif; ?>

    <hr> 

    
    <a href="<?php echo e(route('mahasiswa.pengajuan.index')); ?>">Kembali ke Daftar Pengajuan</a>

    <?php if($pengajuan->status === 'draft'): ?>
        <a href="<?php echo e(route('mahasiswa.pengajuan.edit', $pengajuan->id)); ?>" style="margin-left: 10px;">Edit Pengajuan Draft</a>
    <?php endif; ?>

    
    <?php if($pengajuan->status !== 'disetujui' && $pengajuan->status !== 'diproses' && $pengajuan->status !== 'selesai' && $pengajuan->status !== 'sidang_dijadwalkan_final'): ?> 
        <form action="<?php echo e(route('mahasiswa.pengajuan.destroy', $pengajuan->id)); ?>" method="POST" style="display:inline; margin-left: 10px;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini dan semua dokumennya? Aksi ini tidak bisa dibatalkan.');" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;">Hapus Pengajuan</button>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="<?php echo e(route('mahasiswa.dashboard')); ?>">Kembali ke Dashboard Utama</a>

</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/mahasiswa/pengajuan/show.blade.php ENDPATH**/ ?>