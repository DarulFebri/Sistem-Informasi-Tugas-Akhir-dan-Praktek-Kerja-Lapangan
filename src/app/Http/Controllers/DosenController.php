<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Pengajuan; 
use App\Models\Sidang; // Pastikan model Sidang di-import
use App\Models\Dokumen; // Jika Anda perlu berinteraksi dengan dokumen
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login
use Illuminate\Support\Facades\Validator; // Untuk validasi
use Illuminate\Support\Facades\Storage; // Untuk file storage
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini di-import jika Anda menggunakan Excel Import
use App\Imports\DosenImport; // Pastikan ini di-import jika Anda menggunakan DosenImport


// Pastikan Anda mengimpor Mail jika digunakan untuk notifikasi email
// use Illuminate\Support\Facades\Mail;
// use App\Mail\JadwalSidangNotification; // Import mail class Anda

class DosenController extends Controller
{
    public function loginForm()
    {
        return view('dosen.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'dosen'; // Tambahkan role ke credentials

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dosen.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        // Memastikan user yang login memiliki relasi ke model Dosen
        $dosen = $user->dosen;

        // Jika tidak ada objek Dosen terkait, handle error atau redirect
        if (!$dosen) {
            Auth::logout();
            return redirect()->route('dosen.login')->with('error', 'Profil dosen tidak ditemukan.');
        }

        // Ambil notifikasi yang belum dibaca untuk user ini
        $unreadNotifications = $user->unreadNotifications;

        // Ambil sidang di mana dosen ini terlibat dan statusnya masih 'dosen_ditunjuk'
        // (menunggu persetujuan dosen)
        $sidangInvitations = Sidang::where(function($query) use ($dosen) {
                                $query->where('ketua_sidang_dosen_id', $dosen->id)
                                      ->where('persetujuan_ketua_sidang', 'pending');
                            })->orWhere(function($query) use ($dosen) {
                                $query->where('sekretaris_sidang_dosen_id', $dosen->id)
                                      ->where('persetujuan_sekretaris_sidang', 'pending');
                            })->orWhere(function($query) use ($dosen) {
                                $query->where('anggota1_sidang_dosen_id', $dosen->id)
                                      ->where('persetujuan_anggota1_sidang', 'pending');
                            })->orWhere(function($query) use ($dosen) {
                                $query->where('anggota2_sidang_dosen_id', $dosen->id)
                                      ->where('persetujuan_anggota2_sidang', 'pending');
                            })->orWhere(function($query) use ($dosen) {
                                $query->where('dosen_pembimbing_id', $dosen->id)
                                      ->where('persetujuan_dosen_pembimbing', 'pending');
                            })->orWhere(function($query) use ($dosen) {
                                $query->where('dosen_penguji1_id', $dosen->id) // Ini adalah pembimbing 2
                                      ->where('persetujuan_dosen_penguji1', 'pending');
                            })
                            ->with([
                                'pengajuan.mahasiswa',
                                'ketuaSidang', // Load ini
                                'sekretarisSidang', // Load ini
                                'anggota1Sidang', // Load ini
                                'anggota2Sidang', // Load ini
                                'dosenPembimbing', // Load ini
                                'dosenPenguji1' // Load ini (jika digunakan sebagai pembimbing 2)
                            ]) // Load relasi yang diperlukan
                            ->get();


        return view('dosen.dashboard', compact('unreadNotifications', 'sidangInvitations'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama atau halaman lain setelah logout
    }

    // Dibawah ini Pengajuan Sidang Methods
    public function daftarPengajuan()
    {
        // Method ini sepertinya untuk melihat SEMUA pengajuan, bukan yang melibatkan dosen
        // Jika tujuannya untuk semua, pastikan eager loading relasi sidang dan dosen di dalamnya
        $pengajuans = Pengajuan::with([
            'mahasiswa',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang'
        ])->get();

        return view('dosen.pengajuan.index', compact('pengajuans'));
    }

    public function detailPengajuan(Pengajuan $pengajuan)
    {
        // Pastikan dosen yang login memiliki akses ke pengajuan ini (jika perlu)
        // Saya menambahkan eager loading di sini agar semua informasi dosen di sidang
        // tersedia di view detail pengajuan.
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ]);

        return view('dosen.pengajuan.show', compact('pengajuan')); // Dokumen sudah dimuat via relasi pengajuan->dokumens
    }

    // Method untuk menandai notifikasi sudah dibaca
    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        // Pastikan notifikasi ini milik user yang sedang login
        if (Auth::id() !== $notification->notifiable_id) {
            abort(403, 'Unauthorized action.');
        }
        $notification->markAsRead();
        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    // Helper method untuk mengecek status persetujuan semua dosen dan update status pengajuan
    protected function checkAndSetPengajuanStatus(Sidang $sidang)
    {
        $allDosenResponded = true;
        $allDosenAgreed = true;

        $rolesToCheck = [
            'ketua_sidang' => $sidang->ketua_sidang_dosen_id,
            'sekretaris_sidang' => $sidang->sekretaris_sidang_dosen_id,
            'anggota1_sidang' => $sidang->anggota1_sidang_dosen_id,
            'anggota2_sidang' => $sidang->anggota2_sidang_dosen_id, // ini bisa null
            'dosen_pembimbing' => $sidang->dosen_pembimbing_id,
            'dosen_penguji1' => $sidang->dosen_penguji1_id, // pembimbing 2
        ];

        foreach ($rolesToCheck as $role => $dosenId) {
            if ($dosenId !== null) { // Hanya cek jika peran dosen ini diisi
                $persetujuanKolom = 'persetujuan_' . $role;
                if ($sidang->$persetujuanKolom === 'pending') {
                    $allDosenResponded = false;
                    break; // Keluar dari loop jika ada yang belum merespon
                }
                if ($sidang->$persetujuanKolom === 'tolak') {
                    $allDosenAgreed = false;
                    break; // Keluar jika ada yang menolak
                }
            }
        }

        if ($allDosenResponded) {
            if ($allDosenAgreed) {
                // Semua sudah merespon dan semua setuju
                $sidang->pengajuan->update(['status' => 'dosen_menyetujui']);
            } else {
                // Semua sudah merespon tapi ada yang menolak
                $sidang->pengajuan->update(['status' => 'dosen_menolak_jadwal']); // Status baru jika ada yang menolak
                // Mungkin kirim notifikasi ke Kaprodi bahwa ada dosen yang menolak
            }
        }
        // Jika belum semua merespon, status pengajuan tidak berubah (tetap 'dosen_ditunjuk')
    }

    // --- METODE BARU UNTUK DAFTAR PENGAJUAN YANG MELIBATKAN DOSEN INI ---
    public function pengajuanSaya()
    {
        $user = Auth::user();

        // Pastikan user yang login memang terkait dengan model Dosen
        if (!$user || !$user->dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Akses ditolak. Anda tidak terdaftar sebagai dosen.');
        }

        $dosenId = Auth::user()->dosen->id;

        // Mengambil pengajuan di mana dosen ini terlibat
        // Kita akan mencari di tabel 'sidangs' karena semua peran dosen ada di sana
        $pengajuansInvolved = Pengajuan::whereHas('sidang', function ($query) use ($dosenId) {
            $query->where('dosen_pembimbing_id', $dosenId)
                  ->orWhere('dosen_penguji1_id', $dosenId)
                  ->orWhere('dosen_penguji2_id', $dosenId)
                  ->orWhere('ketua_sidang_dosen_id', $dosenId)
                  ->orWhere('sekretaris_sidang_dosen_id', $dosenId)
                  ->orWhere('anggota1_sidang_dosen_id', $dosenId)
                  ->orWhere('anggota2_sidang_dosen_id', $dosenId);
        })
        ->with([
            'mahasiswa',
            'sidang.dosenPembimbing', // Eager load semua relasi dosen yang mungkin terkait
            'sidang.dosenPenguji1',
            'sidang.dosenPenguji2',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang'
        ])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('dosen.pengajuan.pengajuan_saya', compact('pengajuansInvolved'));
    }
    // --- AKHIR METODE BARU ---


    // Diabawah ini Validasi Dokumen Methods
    public function setujuiDokumen(Dokumen $dokumen)
    {
        // Pastikan dosen memiliki akses untuk memvalidasi dokumen ini (jika perlu)
        // Disarankan menambahkan logika otorisasi yang lebih kuat di sini.
        // Misalnya, hanya dosen pembimbing atau penguji yang bisa setujui dokumen TA/PKL tertentu.
        // if ($dokumen->pengajuan->sidang->dosenPembimbing->id !== Auth::user()->dosen->id) {
        //     abort(403, 'Anda tidak berhak menyetujui dokumen ini.');
        // }

        $dokumen->update(['status' => 'disetujui']);
        return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function tolakDokumen(Dokumen $dokumen)
    {
        // Pastikan dosen memiliki akses untuk memvalidasi dokumen ini (jika perlu)
        // Disarankan menambahkan logika otorisasi yang lebih kuat di sini.
        // if ($dokumen->pengajuan->sidang->dosenPembimbing->id !== Auth::user()->dosen->id) {
        //     abort(403, 'Anda tidak berhak menolak dokumen ini.');
        // }

        $dokumen->update(['status' => 'ditolak']);
        return redirect()->back()->with('success', 'Dokumen berhasil ditolak.');
    }

    // DIbawah ini Penjadwalan Sidang Methods
    public function formJadwalSidang(Pengajuan $pengajuan)
    {
        // Pastikan dosen memiliki akses untuk menjadwalkan sidang ini (jika perlu)
        // Misalnya, hanya ketua sidang yang bisa menjadwalkan.
        // if ($pengajuan->sidang && $pengajuan->sidang->ketua_sidang_dosen_id !== Auth::user()->dosen->id) {
        //      abort(403, 'Anda tidak berhak menjadwalkan sidang ini.');
        // }

        return view('dosen.jadwal.create', compact('pengajuan'));
    }

    public function simpanJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'tanggal_waktu_sidang' => 'required|date', // Gunakan satu kolom dateTime
            'ruangan_sidang' => 'required|string|max:255',
        ]);

        // Pastikan record sidang sudah ada atau dibuat.
        // Dosen yang menjadwalkan mungkin hanya mengisi tanggal/ruangan.
        // Dosen pembimbing/penguji/anggota biasanya sudah ditetapkan Admin/Kaprodi.
        $sidang = $pengajuan->sidang ?? new Sidang(); // Ambil sidang jika sudah ada, atau buat baru
        $sidang->pengajuan_id = $pengajuan->id; // Pastikan terhubung ke pengajuan
        $sidang->tanggal_waktu_sidang = $request->tanggal_waktu_sidang;
        $sidang->ruangan_sidang = $request->ruangan_sidang;
        $sidang->save();

        // Implementasi pengiriman email notifikasi di sini jika diperlukan
        // Contoh:
        // if (class_exists('App\Mail\JadwalSidangNotification')) {
        //     $dosenPenguji1 = $sidang->dosenPenguji1;
        //     if ($dosenPenguji1) {
        //         Mail::to($dosenPenguji1->email)->send(new JadwalSidangNotification($sidang));
        //     }
        // }


        // Log aktivitas (Anda perlu mengimplementasikan fungsi logActivity() ini)
        // if (function_exists('logActivity')) {
        //     logActivity('Membuat jadwal sidang untuk: ' . $pengajuan->mahasiswa->nama_lengkap, 'Penjadwalan');
        // }


        return redirect()->route('dosen.pengajuan.show', $pengajuan->id)->with('success', 'Jadwal sidang berhasil dibuat.');
    }

    public function detailJadwalSidang(Sidang $sidang)
    {
        // Pastikan dosen memiliki akses untuk melihat detail jadwal ini (jika perlu)
        $sidang->load([
            'pengajuan.mahasiswa',
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
            'dosenPembimbing',
            'dosenPenguji1',
            'dosenPenguji2'
        ]);

        return view('dosen.jadwal.show', compact('sidang'));
    }

    // Dibawah ini Penilaian Sidang Methods
    public function unduhLaporan(Sidang $sidang)
    {
        // Pastikan dosen memiliki akses untuk mengunduh laporan ini (misalnya, dosen penguji)

        // Asumsi: Path file laporan TA disimpan di tabel 'dokumens' dengan jenis tertentu
        $laporan = Dokumen::where('pengajuan_id', $sidang->pengajuan_id)
                          ->where('jenis_dokumen', 'Laporan TA') // Sesuaikan jenis dokumen Anda
                          ->first();

        if (!$laporan || !Storage::exists($laporan->path_file)) {
            abort(404, 'Laporan Tugas Akhir tidak ditemukan atau file tidak ada.');
        }

        // Pastikan path_file tidak dimulai dengan 'public/' jika Anda menyimpannya demikian.
        // Storage::download() akan otomatis mencari di default disk.
        return Storage::download($laporan->path_file, $laporan->nama_file);
    }

    public function formNilaiSidang(Sidang $sidang)
    {
        // Pastikan dosen memiliki akses untuk menilai sidang ini
        // Misalnya, hanya pembimbing atau penguji yang bisa memberi nilai
        $dosenId = Auth::user()->dosen->id;
        if (!in_array($dosenId, [
            $sidang->dosen_pembimbing_id,
            $sidang->dosen_penguji1_id,
            $sidang->dosen_penguji2_id
        ])) {
            abort(403, 'Anda tidak berhak memberikan nilai pada sidang ini.');
        }

        return view('dosen.sidang.nilai.edit', compact('sidang'));
    }

    public function simpanNilaiSidang(Request $request, Sidang $sidang)
    {
        // Validasi input nilai
        // Sesuaikan kolom nilai yang ada di tabel 'sidangs' Anda
        $request->validate([
            'nilai_sidang' => 'required|numeric|min:0|max:100', // Contoh: nilai total sidang
            // Anda mungkin memiliki kolom nilai_pembimbing, nilai_penguji1, nilai_penguji2, dll.
            // Sesuaikan validasi dengan struktur tabel Anda.
            // 'nilai_pembimbing' => 'required|numeric',
            // 'nilai_penguji_1' => 'required|numeric',
            // 'nilai_penguji_2' => 'required|numeric',
            'catatan_sidang' => 'nullable|string', // Kolom catatan tambahan
        ]);

        // Simpan nilai ke tabel 'sidangs'
        $sidang->update([
            'nilai_sidang' => $request->nilai_sidang,
            // 'nilai_pembimbing' => $request->nilai_pembimbing,
            // 'nilai_penguji_1' => $request->nilai_penguji_1,
            // 'nilai_penguji_2' => $request->nilai_penguji_2,
            'catatan_sidang' => $request->catatan_sidang,
            'hasil_sidang' => ($request->nilai_sidang >= 60) ? 'Lulus' : 'Tidak Lulus', // Contoh logika hasil sidang
        ]);

        // Log aktivitas (Anda perlu mengimplementasikan fungsi logActivity() ini)
        // if (function_exists('logActivity')) {
        //     logActivity('Memasukkan nilai sidang untuk: ' . $sidang->pengajuan->mahasiswa->nama_lengkap, 'Penilaian Sidang');
        // }

        return redirect()->route('dosen.sidang.nilai.edit', $sidang->id)->with('success', 'Nilai sidang berhasil disimpan.');
    }

    // Method untuk menampilkan form impor
    public function importForm()
    {
        return view('admin.dosen.import'); // Buat view ini nanti
    }

    // Method untuk memproses file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:2048', // Validasi file Excel
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file')); // Proses impor
            return redirect()->back()->with('success', 'Data dosen berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data dosen. Ada kesalahan validasi: ' . implode('; ', $errors));
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data dosen: ' . $e->getMessage());
        }
    }

    public function formResponSidang(Sidang $sidang)
    {
        $dosen = Auth::user()->dosen;

        // Pastikan dosen yang login adalah salah satu anggota sidang ini
        // dan status persetujuannya masih 'pending'
        if (
            ($sidang->ketua_sidang_dosen_id === $dosen->id && $sidang->persetujuan_ketua_sidang === 'pending') ||
            ($sidang->sekretaris_sidang_dosen_id === $dosen->id && $sidang->persetujuan_sekretaris_sidang === 'pending') ||
            ($sidang->anggota1_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota1_sidang === 'pending') ||
            ($sidang->anggota2_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota2_sidang === 'pending') ||
            ($sidang->dosen_pembimbing_id === $dosen->id && $sidang->persetujuan_dosen_pembimbing === 'pending') ||
            ($sidang->dosen_penguji1_id === $dosen->id && $sidang->persetujuan_dosen_penguji1 === 'pending')
        ) {
            // Load relasi yang diperlukan untuk tampilan form
            $sidang->load('pengajuan.mahasiswa', 'ketuaSidang', 'sekretarisSidang', 'anggota1Sidang', 'anggota2Sidang', 'dosenPembimbing', 'dosenPenguji1');
            return view('dosen.respon_sidang', compact('sidang', 'dosen'));
        }

        // Jika dosen tidak terkait atau sudah merespon
        return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses ke undangan sidang ini atau sudah merespon.');
    }

    public function submitResponSidang(Request $request, Sidang $sidang)
    {
        $request->validate([
            'respon' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $dosen = Auth::user()->dosen;
        $respon = $request->respon;
        $catatan = $request->catatan;
        $peranDosen = null;

        // Tentukan peran dosen dan update status persetujuan
        if ($sidang->ketua_sidang_dosen_id === $dosen->id && $sidang->persetujuan_ketua_sidang === 'pending') {
            $sidang->persetujuan_ketua_sidang = $respon;
            $peranDosen = 'Ketua Sidang';
        } elseif ($sidang->sekretaris_sidang_dosen_id === $dosen->id && $sidang->persetujuan_sekretaris_sidang === 'pending') {
            $sidang->persetujuan_sekretaris_sidang = $respon;
            $peranDosen = 'Sekretaris Sidang';
        } elseif ($sidang->anggota1_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota1_sidang === 'pending') {
            $sidang->persetujuan_anggota1_sidang = $respon;
            $peranDosen = 'Anggota Sidang 1';
        } elseif ($sidang->anggota2_sidang_dosen_id === $dosen->id && $sidang->persetujuan_anggota2_sidang === 'pending') {
            $sidang->persetujuan_anggota2_sidang = $respon;
            $peranDosen = 'Anggota Sidang 2';
        } elseif ($sidang->dosen_pembimbing_id === $dosen->id && $sidang->persetujuan_dosen_pembimbing === 'pending') {
            $sidang->persetujuan_dosen_pembimbing = $respon;
            $peranDosen = 'Dosen Pembimbing 1';
        } elseif ($sidang->dosen_penguji1_id === $dosen->id && $sidang->persetujuan_dosen_penguji1 === 'pending') {
            $sidang->persetujuan_dosen_penguji1 = $respon;
            $peranDosen = 'Dosen Pembimbing 2'; // Atau Dosen Penguji 1 sesuai konvensi Anda
        } else {
            return back()->with('error', 'Anda tidak dapat merespon undangan ini lagi atau tidak terkait.');
        }

        $sidang->save();

        // Anda bisa menambahkan logika notifikasi ke Kaprodi di sini,
        // misal: Notifikasi::send($kaprodiUser, new DosenRespondedSidang($sidang, $dosen, $respon));

        // Jika semua dosen sudah setuju, ubah status pengajuan menjadi 'menunggu_finalisasi_kaprodi' atau 'dijadwalkan'
        // Ini contoh sederhana, Anda mungkin perlu logika yang lebih kompleks
        // untuk mengecek semua persetujuan:
        // if ($sidang->persetujuan_ketua_sidang === 'setuju' &&
        //     $sidang->persetujuan_sekretaris_sidang === 'setuju' &&
        //     $sidang->persetujuan_anggota1_sidang === 'setuju' &&
        //     // ... dan seterusnya untuk semua dosen yang terlibat ...
        // ) {
        //     $sidang->pengajuan->status = 'dosen_menyetujui_jadwal'; // Status baru di pengajuan
        //     $sidang->pengajuan->save();
        // }


        return redirect()->route('dosen.dashboard')->with('success', "Respon Anda sebagai {$peranDosen} ($respon) berhasil disimpan.");
    }
}