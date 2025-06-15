<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <style>
        /* Basic styles for overall layout, alerts, etc. (similar to previous views) */
        .notification-section {
            background-color: #e9f5fd;
            border: 1px solid #cce5ff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .notification-section h3 {
            color: #007bff;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .notification-list {
            list-style: none;
            padding: 0;
        }
        .notification-item {
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on small screens */
        }
        .notification-content {
            flex-grow: 1;
        }
        .notification-actions {
            margin-left: 20px;
            white-space: nowrap; /* Keep buttons on one line */
        }
        .notification-item p {
            margin: 0;
            color: #333;
        }
        .notification-item strong {
            color: #0056b3;
        }
        .notification-item .timestamp {
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
            display: block;
        }
        .notification-item a.action-link {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 10px;
        }
        .notification-item button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .sidang-invitations-section {
            background-color: #f7f7f7;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 8px;
        }
        .sidang-invitations-section h3 {
            color: #343a40;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .sidang-item {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .sidang-item strong {
            color: #11acc7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Dosen</h2>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('info')): ?>
            <div class="alert alert-info"><?php echo e(session('info')); ?></div>
        <?php endif; ?>

        <div class="notification-section">
            <h3>Notifikasi Terbaru (Belum Dibaca)</h3>
            <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="notification-item">
                    <div class="notification-content">
                        <?php if($notification->type === 'App\Notifications\DosenSidangInvitation'): ?>
                            <p><strong>Undangan Sidang Baru:</strong> <?php echo e($notification->data['message']); ?></p>
                            <p>Mahasiswa: <?php echo e($notification->data['mahasiswa_nama']); ?></p>
                            <p>Jenis: <?php echo e($notification->data['jenis_pengajuan']); ?></p>
                            <p>Tanggal: <?php echo e($notification->data['tanggal_sidang']); ?></p>
                            <p>Ruangan: <?php echo e($notification->data['ruangan_sidang']); ?></p>
                        <?php else: ?>
                            <p>Notifikasi Umum: <?php echo e($notification->data['message'] ?? 'Tidak ada detail pesan.'); ?></p>
                        <?php endif; ?>
                        <span class="timestamp"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                    </div>
                    <div class="notification-actions">
                        <?php if($notification->type === 'App\Notifications\DosenSidangInvitation' && isset($notification->data['sidang_id'])): ?>
                            <a href="<?php echo e(route('dosen.sidang.respon.form', $notification->data['sidang_id'])); ?>" class="action-link">Respon Undangan</a>
                        <?php endif; ?>
                        <form action="<?php echo e(route('dosen.notifications.markAsRead', $notification->id)); ?>" method="POST" style="display: inline-block;">
                            <?php echo csrf_field(); ?>
                            <button type="submit">Tandai Sudah Dibaca</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>Tidak ada notifikasi baru.</p>
            <?php endif; ?>
            
            
        </div>

        <div class="sidang-invitations-section">
            <h3>Undangan Sidang Menunggu Respon</h3>
            <?php $__empty_1 = true; $__currentLoopData = $sidangInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> 
                <div class="sidang-item">
                    <p><strong>Mahasiswa:</strong> <?php echo e($sidang->pengajuan->mahasiswa->nama_lengkap); ?> (NIM: <?php echo e($sidang->pengajuan->mahasiswa->nim); ?>)</p>
                    <p><strong>Jenis Sidang:</strong> <?php echo e(strtoupper($sidang->pengajuan->jenis_pengajuan)); ?></p>
                    <p><strong>Tanggal & Waktu:</strong> <?php echo e(\Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i')); ?></p>
                    <p><strong>Ruangan:</strong> <?php echo e($sidang->ruangan_sidang); ?></p>
                    <p><strong>Peran Anda:</strong>
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
                    <a href="<?php echo e(route('dosen.sidang.respon.form', $sidang->id)); ?>" class="action-link" style="background-color: #007bff;">Respon Undangan Ini</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>Tidak ada undangan sidang yang menunggu respon Anda.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/dosen/dashboard.blade.php ENDPATH**/ ?>