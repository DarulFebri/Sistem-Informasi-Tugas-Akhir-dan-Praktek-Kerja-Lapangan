<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sidang - Kajur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-size: 1.25rem;
            font-weight: bold;
            padding: 15px 20px;
        }
        .card-body p {
            margin-bottom: 8px;
        }
        .status-badge {
            padding: .4em .6em;
            border-radius: .25rem;
            font-weight: bold;
            display: inline-block;
            margin-left: 5px;
            font-size: 0.85em; /* Lebih kecil dari teks biasa */
        }
        .status-badge.primary { background-color: #007bff; color: white; }
        .status-badge.success { background-color: #28a745; color: white; }
        .status-badge.warning { background-color: #ffc107; color: #343a40; }
        .status-badge.danger { background-color: #dc3545; color: white; }
        .status-badge.info { background-color: #17a2b8; color: white; }
        .approval-status {
            font-weight: bold;
        }
        .approval-status.text-success { color: #28a745; }
        .approval-status.text-danger { color: #dc3545; }
        .approval-status.text-warning { color: #ffc107; }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?php echo e(route('kajur.dashboard')); ?>" class="back-link">&larr; Kembali ke Dashboard</a>

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

        <div class="card">
            <div class="card-header">
                Detail Sidang #<?php echo e($sidang->id); ?>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if($sidang->pengajuan && $sidang->pengajuan->mahasiswa): ?>
                            <p><strong>Mahasiswa:</strong> <?php echo e($sidang->pengajuan->mahasiswa->nama_lengkap); ?> (<?php echo e($sidang->pengajuan->mahasiswa->nim); ?>)</p>
                            <p><strong>Jenis Sidang:</strong> <?php echo e($sidang->pengajuan->jenis_pengajuan); ?></p>
                        <?php else: ?>
                            <p class="text-muted">Informasi pengajuan atau mahasiswa tidak tersedia.</p>
                        <?php endif; ?>
                        <p><strong>Tanggal Waktu Sidang:</strong> <?php echo e($sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan'); ?></p>
                        <p><strong>Ruangan Sidang:</strong> <?php echo e($sidang->ruangan_sidang ?? 'Belum Ditentukan'); ?></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><strong>Status Pengajuan:</strong>
                            <?php
                                $statusClass = '';
                                switch ($sidang->pengajuan->status) {
                                    case 'diverifikasi_kajur': $statusClass = 'success'; break;
                                    case 'siap_sidang_kajur': $statusClass = 'warning'; break;
                                    case 'menunggu_persetujuan_dosen': $statusClass = 'info'; break;
                                    case 'diverifikasi_admin': $statusClass = 'primary'; break;
                                    // Tambahkan status lain sesuai kebutuhan
                                    default: $statusClass = 'secondary'; break;
                                }
                            ?>
                            <span class="status-badge status-badge-<?php echo e($statusClass); ?>"><?php echo e(Str::replace('_', ' ', Str::title($sidang->pengajuan->status))); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Susunan Tim Sidang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Dosen Penguji 1:</strong> <?php echo e($sidang->dosenPembimbing->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->dosenPembimbing): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_dosen_pembimbing == 'setuju' ? 'text-success' : ($sidang->persetujuan_dosen_pembimbing == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_dosen_pembimbing))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Dosen Penguji 2:</strong> <?php echo e($sidang->dosenPenguji1->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->dosenPenguji1): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_dosen_penguji1 == 'setuju' ? 'text-success' : ($sidang->persetujuan_dosen_penguji1 == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_dosen_penguji1))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ketua Sidang:</strong> <?php echo e($sidang->ketuaSidang->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->ketuaSidang): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_ketua_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_ketua_sidang == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_ketua_sidang))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Sekretaris Sidang:</strong> <?php echo e($sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->sekretarisSidang): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_sekretaris_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_sekretaris_sidang == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_sekretaris_sidang))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Anggota Sidang 1:</strong> <?php echo e($sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->anggota1Sidang): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_anggota1_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_anggota1_sidang == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_anggota1_sidang))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Anggota Sidang 2:</strong> <?php echo e($sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk'); ?>

                            <?php if($sidang->anggota2Sidang): ?>
                                <span class="approval-status <?php echo e($sidang->persetujuan_anggota2_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_anggota2_sidang == 'tolak' ? 'text-danger' : 'text-warning')); ?>">
                                    (<?php echo e(ucfirst(str_replace('_', ' ', $sidang->persetujuan_anggota2_sidang))); ?>)
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php if($sidang->tanggal_selesai): ?>
            <div class="card">
                <div class="card-header">
                    Hasil Sidang
                </div>
                <div class="card-body">
                    <p><strong>Tanggal Selesai:</strong> <?php echo e(\Carbon\Carbon::parse($sidang->tanggal_selesai)->format('d M Y')); ?></p>
                    <p><strong>Nilai Akhir:</strong> <?php echo e($sidang->nilai_akhir ?? '-'); ?></p>
                    <p><strong>Status Lulus:</strong> <?php echo e(Str::replace('_', ' ', Str::title($sidang->status_lulus ?? '-'))); ?></p>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($sidang->pengajuan->status === 'siap_sidang_kajur'): ?>
            <div class="card">
                <div class="card-header bg-success">
                    Aksi Kajur
                </div>
                <div class="card-body">
                    <p class="mb-3">Pengajuan ini siap untuk diverifikasi oleh Ketua Jurusan.</p>
                    <form action="<?php echo e(route('kajur.verifikasi.store', $sidang->pengajuan->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi sidang ini? Tindakan ini tidak dapat dibatalkan.');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-lg btn-block">Verifikasi Sidang</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 kampus unand/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kajur/sidang/show.blade.php ENDPATH**/ ?>