

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Verifikasi Pengajuan Sidang</h1>
    <p>Anda akan memverifikasi pengajuan sidang ini:</p>

    <div class="card mb-4">
        <div class="card-header">Detail Pengajuan</div>
        <div class="card-body">
            <p><strong>ID Pengajuan:</strong> <?php echo e($pengajuan->id); ?></p>
            <p><strong>Nama Mahasiswa:</strong> <?php echo e($pengajuan->mahasiswa->nama); ?></p>
            <p><strong>NIM:</strong> <?php echo e($pengajuan->mahasiswa->nim); ?></p>
            <p><strong>Judul Tugas Akhir:</strong> <?php echo e($pengajuan->judul_tugas_akhir); ?></p>
            <p><strong>Status Saat Ini:</strong> <?php echo e(Str::replace('_', ' ', Str::title($pengajuan->status))); ?></p>
            
        </div>
    </div>

    <form action="<?php echo e(route('kajur.verifikasi.store', $pengajuan->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <p>Apakah Anda yakin ingin memverifikasi pengajuan sidang ini?</p>
        
        
        <button type="submit" class="btn btn-success">Verifikasi Sekarang</button>
        <a href="<?php echo e(route('kajur.dashboard')); ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kajur/pengajuan/verifikasi.blade.php ENDPATH**/ ?>