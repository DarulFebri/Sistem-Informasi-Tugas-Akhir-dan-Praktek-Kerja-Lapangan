<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kajur</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 1280px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .header { background-color: #f2f2f2; padding: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 15px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header { font-weight: bold; margin-bottom: 10px; color: #333; }
        .card-body { font-size: 1.5em; text-align: center; color: #555; }
        .row { display: flex; justify-content: space-around; flex-wrap: wrap; }
        .col-md-4 { flex: 0 0 30%; max-width: 30%; padding: 10px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        hr { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .logout-form button { background-color: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard Kajur</h1>
            <div class="nav-links">
                <a href="<?php echo e(route('kajur.dashboard')); ?>">Dashboard</a>
                <a href="<?php echo e(route('kajur.pengajuan.perlu_verifikasi')); ?>">Pengajuan Perlu Verifikasi</a>
                <a href="<?php echo e(route('kajur.pengajuan.sudah_verifikasi')); ?>">Pengajuan Terverifikasi</a>
                
                <form action="<?php echo e(route('kajur.logout')); ?>" method="POST" class="logout-form" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

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

        <h2>Ringkasan Sidang</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Sidang Hari Ini</div>
                    <div class="card-body"><?php echo e($jumlahSidangSedang); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Sidang Telah Berlangsung</div>
                    <div class="card-body"><?php echo e($jumlahSidangTelah); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Sidang Akan Datang</div>
                    <div class="card-body"><?php echo e($jumlahSidangAkan); ?></div>
                </div>
            </div>
        </div>

        <hr>

        
        <p>Gunakan navigasi di atas untuk melihat daftar pengajuan.</p>
    </div>
</body>
</html><?php /**PATH /home/darulfebri/Documents/percobaan web laravel/gmni_1 sebelum mencoba timbal balik kaprodi dan dosen/gmni_1/resources/views/kajur/dashboard.blade.php ENDPATH**/ ?>