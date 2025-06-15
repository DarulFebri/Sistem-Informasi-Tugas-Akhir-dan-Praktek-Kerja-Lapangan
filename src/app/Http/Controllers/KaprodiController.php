<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Dosen;
use App\Models\Sidang; // Pastikan model Sidang di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Untuk validasi unique
use App\Notifications\DosenSidangInvitation; // Pastikan ini di-import
use Illuminate\Support\Facades\DB;

class KaprodiController extends Controller
{
    // Method untuk menampilkan form login Kaprodi
    public function loginForm()
    {
        return view('kaprodi.auth.login'); // Asumsi view login ada di kaprodi/auth/login.blade.php
    }

    // Method untuk memproses login Kaprodi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'kaprodi') {
                $request->session()->regenerate();
                return redirect()->intended(route('kaprodi.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai Kaprodi.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password salah.',
        ]);
    }

    // Method untuk logout Kaprodi
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('kaprodi.login')->with('success', 'Anda telah berhasil logout.');
    }

    // Method untuk dashboard Kaprodi
    public function dashboard()
    {
        // 1. Ambil Jumlah Dosen
        $jumlahDosen = Dosen::count();

        // 2. Ambil Jumlah Pengajuan Baru (misalnya yang statusnya 'diverifikasi_admin')
        // Sesuaikan status ini berdasarkan alur kerja Anda.
        // Asumsi 'diverifikasi_admin' adalah status ketika pengajuan sudah diverifikasi admin dan siap untuk kaprodi.
        $jumlahPengajuan = Pengajuan::where('status', 'diverifikasi_admin')->count();

        // 3. Ambil Pengajuan Terbaru (misalnya 5 pengajuan terbaru dengan status 'diverifikasi_admin')
        // Eager load relasi 'mahasiswa' jika Anda ingin menampilkan nama mahasiswa di view
        $pengajuanBaru = Pengajuan::where('status', 'diverifikasi_admin')
                                ->with('mahasiswa')
                                ->latest() // Mengurutkan berdasarkan created_at secara descending
                                ->take(5) // Mengambil 5 data terbaru
                                ->get();


        // Kirim semua data ini ke view
        return view('kaprodi.dashboard', compact('jumlahDosen', 'jumlahPengajuan', 'pengajuanBaru'));
    }

    // Method untuk menampilkan daftar dosen
    public function daftarDosen()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('kaprodi.dosen.index', compact('dosens'));
    }

    // --- Pengajuan-related methods ---

    // Menampilkan daftar pengajuan yang perlu ditinjau Kaprodi
    public function indexPengajuan()
    {
        // 1. Ambil pengajuan yang sedang menunggu aksi Kaprodi
        $pengajuansKaprodi = Pengajuan::where('status', 'diverifikasi_admin')
                                    ->orWhere('status', 'menunggu_persetujuan_dosen')
                                    ->with('mahasiswa')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10); // Atau gunakan get() jika tidak ada pagination di bagian ini

        // 2. Ambil pengajuan yang telah selesai ditangani oleh Kaprodi
        // Status 'sidang_dijadwalkan_final' berarti sudah difinalisasi Kaprodi.
        // Status 'ditolak_kaprodi' berarti sudah ditolak Kaprodi.
        $pengajuansSelesaiKaprodi = Pengajuan::whereIn('status', ['sidang_dijadwalkan_final', 'ditolak_kaprodi'])
                                            ->with('mahasiswa') // Eager load relasi mahasiswa
                                            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan update terakhir
                                            ->get(); // Atau gunakan paginate(10) jika Anda ingin pagination di bagian ini juga


        // Kirim kedua set data ke view
        return view('kaprodi.pengajuan.index', compact('pengajuansKaprodi', 'pengajuansSelesaiKaprodi'));
    }

    // Menampilkan detail pengajuan
    public function showPengajuan(Pengajuan $pengajuan)
    {
        // Eager load relasi yang diperlukan untuk detail
        // Pastikan semua relasi dosen pada Sidang di-load.
        // 'sidang.dosenPembimbing', 'sidang.dosenPenguji1' juga perlu di-load
        // karena mereka digunakan di view.
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
            'sidang.dosenPembimbing', // Pastikan ini ada
            'sidang.dosenPenguji1'   // Pastikan ini ada (Pembimbing 2/Penguji 1)
        ]);
    
        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get(); // Menggunakan 'nama' sesuai model Dosen
    
        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens'));
    }

    public function showAksiKaprodi(Pengajuan $pengajuan)
    {
        // Pastikan relasi sidang sudah ada atau buat jika belum
        // Ini memastikan $pengajuan->sidang selalu tersedia
        if (!$pengajuan->sidang) {
            $sidang = new Sidang();
            $sidang->pengajuan_id = $pengajuan->id;
            $sidang->save();
            $pengajuan->load('sidang'); // Reload pengajuan untuk mendapatkan relasi sidang yang baru
        }

        // Eager load relasi yang diperlukan untuk form aksi
        $pengajuan->load([
            'mahasiswa',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1'
        ]);

        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get();

        return view('kaprodi.pengajuan.aksi', compact('pengajuan', 'dosens'));
    }

    // Menampilkan form untuk menjadwalkan/mengedit jadwal sidang
    public function jadwalkanSidangForm(Pengajuan $pengajuan)
    {
        // Kaprodi dapat menjadwalkan jika statusnya 'diverifikasi_admin' (setelah admin memverifikasi dokumen),
        // atau jika statusnya 'siap_dijadwalkan_kaprodi' (setelah kaprodi menyetujui),
        // atau jika statusnya 'dosen_ditunjuk' (untuk edit jadwal yang sudah ada).
        if (!in_array($pengajuan->status, ['diverifikasi_admin', 'siap_dijadwalkan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.index')->with('error', 'Pengajuan ini tidak dapat dijadwalkan pada tahap ini.');
        }

        $dosens = Dosen::orderBy('nama')->get();
        $sidang = $pengajuan->sidang; 

        // View yang cocok adalah 'jadwal_sidang_form.blade.php'
        return view('kaprodi.pengajuan.jadwal_sidang_form', compact('pengajuan', 'dosens', 'sidang'));
    }

    // Menyimpan atau memperbarui jadwal sidang
    public function storeUpdateJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $validatedData = $request->validate([
            'sekretaris_sidang_id' => 'required|exists:dosens,id',
            'anggota_1_sidang_id' => 'required|exists:dosens,id',
            'anggota_2_sidang_id' => 'nullable|exists:dosens,id', // Opsional
            'tanggal_waktu_sidang' => 'required|date|after_or_equal:now',
            'ruangan_sidang' => 'required|string|max:255',
        ]);
    
        // Pastikan tidak ada duplikasi dosen (antara yang dipilih di form dan pembimbing/penguji dari mahasiswa)
        // Ambil ID dosen yang sedang login (Kaprodi)
        //$kaprodiDosenId = $request->user()->dosen->id; // Ambil ID Dosen dari user yang login
    
        // Gabungkan semua ID dosen yang terlibat
        $allInvolvedDosenIds = array_filter([
            //$kaprodiDosenId, // Kaprodi sebagai Ketua Sidang
            $validatedData['sekretaris_sidang_id'],
            $validatedData['anggota_1_sidang_id'],
            $validatedData['anggota_2_sidang_id'],
            $pengajuan->mahasiswa->pembimbing1_id, // Pembimbing 1 mahasiswa
            $pengajuan->mahasiswa->pembimbing2_id, // Pembimbing 2 mahasiswa
        ]);
    
        // Cek duplikasi
        if (count($allInvolvedDosenIds) !== count(array_unique($allInvolvedDosenIds))) {
            // Identifikasi dosen mana yang duplikat untuk pesan yang lebih informatif
            $duplicateDosenNames = [];
            $dosenNames = Dosen::whereIn('id', $allInvolvedDosenIds)->pluck('nama', 'id');
            $counts = array_count_values($allInvolvedDosenIds);
            foreach ($counts as $id => $count) {
                if ($count > 1) {
                    $duplicateDosenNames[] = $dosenNames[$id] ?? 'Dosen ID: ' . $id;
                }
            }
            return back()->withErrors(['dosen_duplikat' => 'Dosen yang ditunjuk tidak boleh sama: ' . implode(', ', $duplicateDosenNames) . '. Pastikan Dosen Ketua, Sekretaris, Anggota, dan Pembimbing/Penguji berbeda satu sama lain.']);
        }
    
        DB::beginTransaction();
        try {
            $sidang = $pengajuan->sidang()->firstOrNew(['pengajuan_id' => $pengajuan->id]);
        
            // Tetapkan ID Dosen dari user yang login sebagai Ketua Sidang
            //$sidang->ketua_sidang_dosen_id = $kaprodiDosenId;
        
            // Tetapkan ID Pembimbing dari Mahasiswa (ini tidak berubah dari pengajuan)
            //$sidang->dosen_pembimbing_id = $pengajuan->mahasiswa->pembimbing1_id;
            //$sidang->dosen_penguji1_id = $pengajuan->mahasiswa->pembimbing2_id; // Ini akan menjadi Pembimbing 2/Penguji 1
        
            // Tetapkan ID Dosen lainnya dari form
            $sidang->sekretaris_sidang_dosen_id = $validatedData['sekretaris_sidang_id'];
            $sidang->anggota1_sidang_dosen_id = $validatedData['anggota_1_sidang_id'];
            $sidang->anggota2_sidang_dosen_id = $validatedData['anggota_2_sidang_id']; // Bisa null jika opsional
        
            // Tetapkan detail jadwal
            $sidang->tanggal_waktu_sidang = $validatedData['tanggal_waktu_sidang'];
            $sidang->ruangan_sidang = $validatedData['ruangan_sidang'];
        
            $sidang->save();
        
            // Perbarui status pengajuan
            $pengajuan->status = 'menunggu_persetujuan_dosen';
            $pengajuan->save();
        
            DB::commit();
        
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('success', 'Jadwal sidang berhasil ' . ($sidang->wasRecentlyCreated ? 'ditentukan' : 'diperbarui') . '. Menunggu persetujuan dosen.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menentukan jadwal sidang: ' . $e->getMessage())->withInput();
        }
    }

    // Method untuk menyetujui pengajuan (setelah admin memverifikasi dokumen)
    public function setujuiPengajuan(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui pada tahap ini.');
        }

        $pengajuan->update(['status' => 'siap_dijadwalkan_kaprodi']); // Status baru: siap dijadwalkan oleh Kaprodi
        // TODO: Kirim notifikasi ke admin atau pihak terkait jika diperlukan
        return redirect()->route('kaprodi.pengajuan.index')->with('success', 'Pengajuan berhasil disetujui untuk dijadwalkan.');
    }

    // Method untuk menolak pengajuan (setelah admin memverifikasi dokumen)
    public function tolakPengajuan(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak pada tahap ini.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'status' => 'ditolak_kaprodi',
            'alasan_penolakan_kaprodi' => $request->alasan_penolakan,
        ]);

        // TODO: Kirim notifikasi ke mahasiswa bahwa pengajuannya ditolak Kaprodi
        return redirect()->route('kaprodi.pengajuan.index')->with('success', 'Pengajuan berhasil ditolak.');
    }

    // Method untuk memfinalkan jadwal sidang setelah semua dosen menyetujui
    public function finalkanJadwal(Pengajuan $pengajuan)
    {
        $sidang = $pengajuan->sidang;

        // Pastikan sidang ada dan statusnya sudah 'dosen_ditunjuk' atau 'dosen_menyetujui'
        if (!$sidang || !in_array($pengajuan->status, ['dosen_ditunjuk', 'dosen_menyetujui', 'menunggu_persetujuan_dosen'])) {
            return back()->with('error', 'Jadwal sidang belum lengkap atau belum semua dosen menyetujui.');
        }

        // Logika untuk memeriksa apakah semua dosen wajib sudah menyetujui
        $allDosenAgreed = true;

        if ($sidang->ketua_sidang_dosen_id && $sidang->persetujuan_ketua_sidang !== 'setuju') {
            $allDosenAgreed = false;
        }
        if ($sidang->sekretaris_sidang_dosen_id && $sidang->persetujuan_sekretaris_sidang !== 'setuju') {
            $allDosenAgreed = false;
        }
        if ($sidang->anggota1_sidang_dosen_id && $sidang->persetujuan_anggota1_sidang !== 'setuju') {
            $allDosenAgreed = false;
        }
        if ($sidang->anggota2_sidang_dosen_id && $sidang->persetujuan_anggota2_sidang !== 'setuju' && $sidang->anggota2_sidang_dosen_id !== null) { // Anggota 2 opsional
            $allDosenAgreed = false;
        }
        if ($sidang->dosen_pembimbing_id && $sidang->persetujuan_dosen_pembimbing !== 'setuju') {
            $allDosenAgreed = false;
        }
        if ($sidang->dosen_penguji1_id && $sidang->persetujuan_dosen_penguji1 !== 'setuju') {
            $allDosenAgreed = false;
        }

        if ($allDosenAgreed) {
            $pengajuan->update(['status' => 'siap_sidang_kajur']); // Status baru: siap diverifikasi Kajur
            // TODO: Kirim notifikasi ke Kajur
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)->with('success', 'Jadwal sidang berhasil difinalisasi. Menunggu verifikasi Kajur.');
        } else {
            return back()->with('error', 'Belum semua dosen yang terlibat menyetujui jadwal sidang.');
        }
    }
}