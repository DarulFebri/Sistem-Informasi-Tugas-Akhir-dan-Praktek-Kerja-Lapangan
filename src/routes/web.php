<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KajurController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Kaprodi\PengajuanKaprodiController;
use App\Http\Controllers\Admin\PengajuanAdminController;

// Middleware aliases (you can define these in your RouteServiceProvider or directly here)
Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
Route::aliasMiddleware('mahasiswa', \App\Http\Middleware\MahasiswaMiddleware::class);
Route::aliasMiddleware('dosen', \App\Http\Middleware\DosenMiddleware::class);
Route::aliasMiddleware('kaprodi', \App\Http\Middleware\KaprodiMiddleware::class);
Route::aliasMiddleware('kajur', \App\Http\Middleware\KajurMiddleware::class);

// Rute Default
Route::get('/', function () {
    return view('welcome2');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Public routes (no middleware)
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    
    // Protected routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Mahasiswa Management
        Route::get('/mahasiswa', [AdminController::class, 'daftarMahasiswa'])->name('admin.mahasiswa.index');
        Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('admin.mahasiswa.create');
        Route::get('/mahasiswa/{mahasiswa}', [AdminController::class, 'detailMahasiswa'])->name('admin.mahasiswa.show');
        Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
        Route::get('/mahasiswa/{mahasiswa}/edit', [AdminController::class, 'editMahasiswa'])->name('admin.mahasiswa.edit');
        Route::put('/mahasiswa/{mahasiswa}', [AdminController::class, 'updateMahasiswa'])->name('admin.mahasiswa.update');
        Route::delete('/mahasiswa/{mahasiswa}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');
        
        // Mahasiswa Import/Export
        Route::get('/mahasiswa/import', [MahasiswaController::class, 'importForm'])->name('admin.mahasiswa.import.form');
        Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('admin.mahasiswa.import');
        Route::get('/mahasiswas/export', [MahasiswaController::class, 'export'])->name('mahasiswas.export');
        
        // Dosen Management
        Route::get('/dosen', [AdminController::class, 'daftarDosen'])->name('admin.dosen.index');
        Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('admin.dosen.create');
        Route::post('/dosen', [AdminController::class, 'storeDosen'])->name('admin.dosen.store');
        Route::get('/dosen/{dosen}', [AdminController::class, 'detailDosen'])->name('admin.dosen.show');
        Route::get('/dosen/{dosen}/edit', [AdminController::class, 'editDosen'])->name('admin.dosen.edit');
        Route::put('/dosen/{dosen}', [AdminController::class, 'updateDosen'])->name('admin.dosen.update');
        Route::delete('/dosen/{dosen}', [AdminController::class, 'destroyDosen'])->name('admin.dosen.destroy');
        
        // Dosen Import/Export
        Route::get('/dosen/import', [DosenController::class, 'importForm'])->name('admin.dosen.import.form');
        Route::post('/dosen/import', [DosenController::class, 'import'])->name('admin.dosen.import');
        Route::get('/dosen/export', [AdminController::class, 'exportDosen'])->name('admin.dosen.export');
        
        // Pengajuan Management
        Route::get('/pengajuan', [AdminController::class, 'daftarPengajuan'])->name('admin.pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminController::class, 'detailPengajuan'])->name('admin.pengajuan.show');
        Route::put('/pengajuan/{pengajuan}/setujui', [AdminController::class, 'setujuiPengajuan'])->name('admin.pengajuan.setujui');
        Route::put('/pengajuan/{pengajuan}/tolak', [AdminController::class, 'tolakPengajuan'])->name('admin.pengajuan.tolak');
        
        // Sidang Management
        Route::get('/sidang', [AdminController::class, 'daftarSidang'])->name('admin.sidang.index');
        Route::get('/sidang/kalender', [AdminController::class, 'kalenderSidang'])->name('admin.sidang.kalender');
        Route::get('/sidang/{sidang}', [AdminController::class, 'detailSidang'])->name('admin.sidang.show');
        Route::get('/sidang/export', [AdminController::class, 'exportSidang'])->name('admin.sidang.export');
        
        // Activities Log
        Route::get('/activities', [AdminController::class, 'showActivities'])->name('admin.activities.index');
    });
});

// Admin Pengajuan Verification Routes
Route::prefix('admin/pengajuan-verifikasi')->name('admin.pengajuan.verifikasi.')
    ->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [PengajuanAdminController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [PengajuanAdminController::class, 'show'])->name('show');
        // Ubah dari Route::post menjadi Route::put
        Route::put('/{pengajuan}/verify', [PengajuanAdminController::class, 'verify'])->name('verify');
        // Ubah dari Route::post menjadi Route::put
        Route::put('/{pengajuan}/reject', [PengajuanAdminController::class, 'reject'])->name('reject');
    });

// Mahasiswa Routes
Route::prefix('mahasiswa')->group(function () {
    // Public routes
    Route::get('/login', [MahasiswaController::class, 'loginForm'])->name('mahasiswa.login');
    Route::post('/login', [MahasiswaController::class, 'login']);
    Route::post('/logout', [MahasiswaController::class, 'logout'])->name('mahasiswa.logout');

    // Protected routes
    Route::middleware(['auth', 'mahasiswa'])->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');

        // Pengajuan routes
        Route::prefix('pengajuan')->name('mahasiswa.pengajuan.')->group(function () {
            Route::get('/pilih', [PengajuanController::class, 'pilihJenis'])->name('pilih');
            Route::get('/buat/{jenis}', [PengajuanController::class, 'create'])->name('create'); // Rute baru untuk membuat TA/PKL
            Route::post('/', [PengajuanController::class, 'store'])->name('store');
            Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('destroy');
            Route::get('/', [PengajuanController::class, 'index'])->name('index');
            Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');
            Route::put('/{pengajuan}/draft', [PengajuanController::class, 'simpanSebagaiDraft'])->name('draft');
            Route::get('/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('edit');
            Route::put('/{pengajuan}', [PengajuanController::class, 'update'])->name('update');
        });

        // Dokumen routes (jika masih diperlukan, ini hanya untuk melihat daftar dokumen)
        Route::prefix('dokumen')->name('mahasiswa.dokumen.')->group(function () {
            Route::get('/pengajuan/{pengajuan}', [DokumenController::class, 'index'])->name('index');
            // Jika Anda memutuskan untuk memungkinkan penghapusan dokumen individual, tambahkan rute ini:
            Route::delete('/{dokumen}', [DokumenController::class, 'destroy'])->name('destroy');
        });
    });
});

// Dosen Routes
Route::post('/dosen/notifications/{notification}/mark-as-read', function (\Illuminate\Notifications\DatabaseNotification $notification) {
    $notification->markAsRead();
    return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
})->name('dosen.notifications.markAsRead')->middleware(['auth', 'dosen']);
Route::prefix('dosen')->group(function () {
    // Public routes
    Route::get('/login', [DosenController::class, 'loginForm'])->name('dosen.login');
    Route::post('/login', [DosenController::class, 'login']);
    Route::post('/logout', [DosenController::class, 'logout'])->name('dosen.logout');
    
    // Protected routes
    Route::middleware(['auth', 'dosen'])->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
        Route::get('/pengajuan-saya', [DosenController::class, 'pengajuanSaya'])->name('dosen.pengajuan.saya');
        
        // Pengajuan routes
        Route::get('/pengajuan', [DosenController::class, 'daftarPengajuan'])->name('dosen.pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [DosenController::class, 'detailPengajuan'])->name('dosen.pengajuan.show');
        
        // Dokumen validation
        Route::put('/dokumen/{dokumen}/setujui', [DosenController::class, 'setujuiDokumen'])->name('dosen.dokumen.setujui');
        Route::put('/dokumen/{dokumen}/tolak', [DosenController::class, 'tolakDokumen'])->name('dosen.dokumen.tolak');
        
        // Jadwal sidang
        Route::get('/pengajuan/{pengajuan}/jadwal', [DosenController::class, 'formJadwalSidang'])->name('dosen.jadwal.create');
        Route::post('/pengajuan/{pengajuan}/jadwal', [DosenController::class, 'simpanJadwalSidang'])->name('dosen.jadwal.store');
        Route::get('/jadwal/{sidang}', [DosenController::class, 'detailJadwalSidang'])->name('dosen.jadwal.show');
        
        // Sidang routes
        Route::get('/sidang/{sidang}/laporan', [DosenController::class, 'unduhLaporan'])->name('dosen.sidang.laporan');
        Route::get('/sidang/{sidang}/nilai', [DosenController::class, 'formNilaiSidang'])->name('dosen.sidang.nilai.edit');
        Route::post('/sidang/{sidang}/nilai', [DosenController::class, 'simpanNilaiSidang'])->name('dosen.sidang.nilai.store');

        // === Tambahkan ini di sini ===
        Route::prefix('sidang')->name('dosen.sidang.')->group(function () {
            Route::get('/{sidang}/respon', [DosenController::class, 'formResponSidang'])->name('respon.form'); // Form respon dosen
            Route::post('/{sidang}/respon', [DosenController::class, 'submitResponSidang'])->name('respon.submit'); // Submit respon dosen
        });
        
        // Import routes
        Route::get('/import/form', [DosenController::class, 'importForm'])->name('dosen.import.form');
        Route::post('/import', [DosenController::class, 'import'])->name('dosen.import');
    });
});

// Kaprodi Routes
Route::prefix('kaprodi')->group(function () {
    // Public routes (Login/Logout Kaprodi)
    Route::get('/login', [KaprodiController::class, 'loginForm'])->name('kaprodi.login');
    Route::post('/login', [KaprodiController::class, 'login']);
    Route::post('/logout', [KaprodiController::class, 'logout'])->name('kaprodi.logout');
    
    // Protected routes for Kaprodi dashboard and general lists
    Route::middleware(['auth', 'kaprodi'])->group(function () {
        Route::get('/dashboard', [KaprodiController::class, 'dashboard'])->name('kaprodi.dashboard');
        Route::get('/dosen', [KaprodiController::class, 'daftarDosen'])->name('kaprodi.dosen.index');

         // Pengajuan-related routes under KaprodiController
         Route::prefix('pengajuan')->name('kaprodi.pengajuan.')->group(function () {
            Route::get('/', [KaprodiController::class, 'indexPengajuan'])->name('index'); // Daftar pengajuan untuk Kaprodi
            Route::get('/{pengajuan}', [KaprodiController::class, 'showPengajuan'])->name('show'); // Detail pengajuan
            Route::get('/{pengajuan}/aksi', [KaprodiController::class, 'showAksiKaprodi'])->name('aksi');

            // Rute untuk menjadwalkan/mengedit jadwal sidang
            // GET untuk menampilkan form penjadwalan (ini tidak diperlukan jika form langsung di show)
            // Namun, jika ada halaman terpisah untuk jadwal, ini diperlukan.
            // Jika form langsung di show, maka showPengajuan akan menyediakan data yang sama.
            // Route::get('/{pengajuan}/jadwalkan', [KaprodiController::class, 'jadwalkanSidangForm'])->name('jadwalkan.form'); // MUNGKIN TIDAK PERLU lagi

            Route::put('/{pengajuan}/jadwalkan', [KaprodiController::class, 'storeUpdateJadwalSidang'])->name('jadwalkan.storeUpdate'); // Menyimpan/memperbarui jadwal

            // Rute untuk penolakan pengajuan oleh Kaprodi
            Route::post('/{pengajuan}/tolak-pengajuan', [KaprodiController::class, 'tolakPengajuan'])->name('tolak.pengajuan');

            // Rute untuk finalisasi jadwal sidang setelah dosen setuju
            Route::post('/{pengajuan}/finalkan-jadwal', [KaprodiController::class, 'finalkanJadwal'])->name('finalkan.jadwal');
        });

        // Dosen Persetujuan Sidang Routes (ini untuk dosen merespon undangan sidang)
        //Route::prefix('persetujuan-sidang')->name('kaprodi.persetujuan-sidang.')->group(function () {
            // Ini seharusnya ada di route khusus dosen atau punya controller tersendiri untuk dosen
            // Untuk sementara, kita letakkan di sini sebagai placeholder, nanti bisa dipindahkan.
            //Route::get('/{sidang}/respon', [DosenController::class, 'formResponSidang'])->name('respon.form'); // Form respon dosen
            //Route::post('/{sidang}/respon', [DosenController::class, 'submitResponSidang'])->name('respon.submit'); // Submit respon dosen
        //});
    });
});


// Kajur Routes
Route::prefix('kajur')->group(function () {
    // Public routes
    Route::get('/login', [KajurController::class, 'loginForm'])->name('kajur.login');
    Route::post('/login', [KajurController::class, 'login']);
    Route::post('/logout', [KajurController::class, 'logout'])->name('kajur.logout');

    // Protected routes
    Route::middleware(['auth', 'kajur'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [KajurController::class, 'index'])->name('kajur.dashboard');

        // Rute untuk daftar pengajuan
        Route::get('/pengajuan/perlu-verifikasi', [KajurController::class, 'daftarPengajuanVerifikasi'])->name('kajur.pengajuan.perlu_verifikasi');
        Route::get('/pengajuan/sudah-verifikasi', [KajurController::class, 'daftarPengajuanTerverifikasi'])->name('kajur.pengajuan.sudah_verifikasi');

        // Rute untuk Verifikasi Pengajuan
        // Gunakan satu set rute saja untuk verifikasi, yang dengan '/form' lebih jelas
        Route::get('/pengajuan/{pengajuan}/verifikasi/form', [KajurController::class, 'showVerifikasiForm'])->name('kajur.verifikasi.form');
        Route::post('/pengajuan/{pengajuan}/verifikasi', [KajurController::class, 'verifikasiPengajuan'])->name('kajur.verifikasi.store');

        // Rute untuk daftar sidang
        Route::get('/sidang/sedang', [KajurController::class, 'daftarSidangSedang'])->name('kajur.sidang.sedang');
        Route::get('/sidang/telah', [KajurController::class, 'daftarSidangTelah'])->name('kajur.sidang.telah');
        Route::get('/sidang/akan', [KajurController::class, 'daftarSidangAkan'])->name('kajur.sidang.akan');
        Route::get('/sidang/{sidang}', [KajurController::class, 'detailSidang'])->name('kajur.sidang.show');
    });
});