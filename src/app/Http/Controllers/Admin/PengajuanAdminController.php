<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Dokumen; // Perlu untuk menampilkan dokumen
use Illuminate\Support\Facades\Storage; // Jika perlu akses storage

class PengajuanAdminController extends Controller
{
    // Menampilkan daftar pengajuan yang perlu diverifikasi admin
    // Method untuk menampilkan daftar pengajuan mahasiswa yang login
    public function index()
    {
        // Ambil semua pengajuan yang relevan untuk admin
        // Anda bisa menggabungkan kriteria status atau mengurutkan sesuai kebutuhan
        $pengajuans = Pengajuan::whereIn('status', [
                                'diajukan_mahasiswa', // Pastikan status ini sudah diubah sesuai pengajuan controller
                                'diverifikasi_admin',
                                'ditolak_admin',
                                'dosen_ditunjuk',
                                'ditolak_kaprodi',
                                'dosen_menyetujui', // Jika Anda ingin admin melihat status ini
                                'siap_sidang_kajur', // Jika Anda ingin admin melihat status ini
                                'dijadwalkan', // Jika Anda ingin admin melihat status ini
                                'selesai' // Jika Anda ingin admin melihat status ini
                            ])
                            ->with('mahasiswa') // Eager load relasi mahasiswa
                            ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
                            ->paginate(10); // Gunakan paginate untuk paginasi

        // Kirimkan hanya variabel $pengajuans ke view
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

// Menampilkan detail pengajuan untuk verifikasi
    public function show(Pengajuan $pengajuan)
    {
        // Pastikan hanya admin yang bisa melihat pengajuan yang relevan untuk dia
        // Jika statusnya sudah melewati admin (misal 'diverifikasi_admin' atau 'dosen_ditunjuk'), admin tetap bisa lihat tapi tidak bisa aksi
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            // Admin masih bisa melihat, tapi mungkin perlu pesan/tampilan berbeda
            // Atau Anda bisa arahkan kembali jika pengajuan sudah diproses kaprodi
            // return redirect()->route('admin.pengajuan.index')->with('info', 'Pengajuan ini sudah diproses.');
        }

        // Muat relasi dokumen dan sidang agar bisa ditampilkan
        $pengajuan->load(['mahasiswa', 'dokumens', 'sidang.ketuaSidang']);

        // Kita bisa langsung menggunakan $pengajuan->dokumens di view,
        // tidak perlu membuat variabel $dokumens terpisah jika sudah di-load.
        // Jika Anda ingin tetap menggunakan $dokumens terpisah (sesuai view Anda),
        // maka definisikan:
        $dokumens = $pengajuan->dokumens;

        return view('admin.pengajuan.show', compact('pengajuan', 'dokumens')); // <-- Tambahkan 'dokumens' di sini
    }

    // Aksi: Memverifikasi dokumen pengajuan
    public function verify(Pengajuan $pengajuan)
    {
        // Pastikan hanya pengajuan berstatus 'diajukan' atau 'ditolak_admin' yang bisa diverifikasi
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            return redirect()->route('admin.pengajuan.verifikasi.show', $pengajuan->id)
                             ->with('error', 'Pengajuan tidak dapat diverifikasi pada status saat ini.');
        }

        // Ubah status pengajuan menjadi 'diverifikasi_admin'
        $pengajuan->update(['status' => 'diverifikasi_admin']);

        // Redirect kembali ke halaman daftar pengajuan verifikasi admin
        return redirect()->route('admin.pengajuan.verifikasi.index') // <--- PASTIkan ini
                         ->with('success', 'Pengajuan berhasil diverifikasi dan menunggu aksi Kaprodi.');
    }

    // Aksi: Menolak pengajuan
    public function reject(Request $request, Pengajuan $pengajuan)
    {
        // Izinkan penolakan jika status 'diajukan_mahasiswa' atau 'ditolak_admin'
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            return redirect()->route('admin.pengajuan.verifikasi.show', $pengajuan->id)
                             ->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }
    
        $request->validate([
            'alasan_penolakan_admin' => 'required|string|max:500', // Sesuaikan dengan nama input di form
        ]);
    
        $pengajuan->update([
            'status' => 'ditolak_admin',
            'alasan_penolakan_admin' => $request->alasan_penolakan_admin, // Gunakan nama kolom yang benar
        ]);
    
        return redirect()->route('admin.pengajuan.verifikasi.index')
                         ->with('success', 'Pengajuan berhasil ditolak.');
    }
}