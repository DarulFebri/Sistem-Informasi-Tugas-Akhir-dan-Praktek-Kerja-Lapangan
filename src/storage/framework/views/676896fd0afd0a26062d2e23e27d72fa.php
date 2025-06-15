<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menajemen Pengajuan Sidang </title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container {
            max-width: 1400px; /* Lebarkan container untuk menampung lebih banyak kolom */
            width: 95%; /* Pastikan responsifitas untuk layar lebih kecil */
            margin: 30px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            background-color: #fff;
        }
        h2, h3 { text-align: center; margin-bottom: 25px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e9e9e9; padding: 12px 15px; text-align: left; font-size: 0.95em; }
        th { background-color: #f8f8f8; color: #555; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #fcfcfc; }
        tr:hover { background-color: #f1f1f1; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; color: white; display: inline-block; font-size: 0.9em; margin: 2px 0; transition: background-color 0.3s ease; }
        .btn-info { background-color: #17a2b8; }
        .btn-info:hover { background-color: #138496; }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
        .btn-warning { background-color: #ffc107; color: #333; }
        .btn-warning:hover { background-color: #e0a800; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-primary { background-color: #007bff; }
        .btn-primary:hover { background-color: #0069d9; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 5px; font-size: 0.95em; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .no-data { text-align: center; color: #777; padding: 30px; font-size: 1.1em; }
        .action-buttons form { display: inline-block; margin-right: 5px; } /* Untuk merapikan tombol */
    </style>
</head>
<body>
    <div class="container">
        <h2>Menajemen Pengajuan Sidang</h2>
        <p>Selamat datang, Kaprodi!</p>

        
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

        <h3>Pengajuan Menunggu Penjadwalan/Pembaruan</h3>
        <?php if($pengajuansKaprodi->isEmpty()): ?>
            <p class="no-data">Tidak ada pengajuan yang menunggu aksi Kaprodi saat ini.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Jenis</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Pembimbing 1 </th>
                        <th>Pembimbing 2 </th>

                        <th>Ketua Sidang</th>
                        <th>Sekretaris</th>
                        <th>Anggota 1</th>
                        <th>Anggota 2</th>
                        <th>Tgl/Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pengajuansKaprodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($pengajuan->id); ?></td>
                            <td><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?> (<?php echo e($pengajuan->mahasiswa->nim); ?>)</td>
                            <td><?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></td>
                            <td><?php echo e($pengajuan->judul_pengajuan ?? 'Belum Ada Judul'); ?></td> 
                            <td><?php echo e(str_replace('_', ' ', $pengajuan->status)); ?></td>
                            <td><?php echo e($pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->dosenPenguji1->nama ?? 'Belum Terpilih'); ?></td> 

                            <td><?php echo e($pengajuan->sidang->ketuaSidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan'); ?></td>
                            <td><?php echo e($pengajuan->sidang->ruangan_sidang ?? 'Belum Ditentukan'); ?></td>
                            <td class="action-buttons">
                                <a href="<?php echo e(route('kaprodi.pengajuan.show', $pengajuan->id)); ?>" class="btn btn-info">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr style="margin: 40px 0;">

        <h3>Pengajuan Yang Telah Difinalkan/Ditolak Kaprodi</h3>
        <?php if($pengajuansSelesaiKaprodi->isEmpty()): ?>
            <p class="no-data">Tidak ada pengajuan yang sudah selesai Anda tangani.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Jenis</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2 </th>

                        <th>Ketua Sidang</th>
                        <th>Sekretaris</th>
                        <th>Anggota 1</th>
                        <th>Anggota 2</th>
                        <th>Tgl/Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pengajuansSelesaiKaprodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($pengajuan->id); ?></td>
                            <td><?php echo e($pengajuan->mahasiswa->nama_lengkap); ?> (<?php echo e($pengajuan->mahasiswa->nim); ?>)</td>
                            <td><?php echo e(strtoupper($pengajuan->jenis_pengajuan)); ?></td>
                            <td><?php echo e($pengajuan->judul_pengajuan ?? 'Belum Ada Judul'); ?></td> 
                            <td><?php echo e(str_replace('_', ' ', $pengajuan->status)); ?></td>
                            <td><?php echo e($pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->dosenPenguji1->nama ?? 'Belum Terpilih'); ?></td> 

                            <td><?php echo e($pengajuan->sidang->ketuaSidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Terpilih'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y H:i') : 'Belum Dijadwalkan'); ?></td> 
                            <td><?php echo e($pengajuan->sidang->ruangan_sidang ?? 'Belum Ditentukan'); ?></td> 
                            <td>
                                <a href="<?php echo e(route('kaprodi.pengajuan.show', $pengajuan->id)); ?>" class="btn btn-info">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr style="margin: 40px 0;">

        <a href="<?php echo e(route('kaprodi.dashboard')); ?>" class="btn btn-info">Kembali ke Dashboard</a>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kaprodi/pengajuan/index.blade.php ENDPATH**/ ?>