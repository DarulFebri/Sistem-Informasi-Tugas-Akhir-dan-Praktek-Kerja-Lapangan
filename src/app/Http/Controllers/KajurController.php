<?php

namespace App\Http\Controllers;


use App\Models\Sidang;
use App\Models\Pengajuan; // Pastikan model Pengajuan sudah di-import
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini jika Anda menggunakan transaksi DB
use Illuminate\Support\Str; // Tambahkan ini untuk fungsi Str::title
use Illuminate\Support\Facades\Auth; // Add this line


class KajurController extends Controller
{
    public function loginForm()
    {
        return view('kajur.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'kajur'; // Tambahkan role ke credentials

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('kajur.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        return view('kajur.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama atau halaman lain setelah logout
    }

    // Dibawah ini method
    public function index()
    {
        // Logika untuk menampilkan ringkasan data di dashboard Kajur
        $jumlahSidangSedang = Sidang::whereDate('tanggal_waktu_sidang', Carbon::today())->count();
        $jumlahSidangTelah = Sidang::whereDate('tanggal_waktu_sidang', '<', Carbon::today())->count();
        $jumlahSidangAkan = Sidang::whereDate('tanggal_waktu_sidang', '>', Carbon::today())->count();

        // Ambil daftar pengajuan dengan status 'siap_sidang_kajur'
        $pengajuanSiapSidang = Pengajuan::where('status', 'siap_sidang_kajur')->get();

        return view('kajur.dashboard', compact(
            'jumlahSidangSedang',
            'jumlahSidangTelah',
            'jumlahSidangAkan',
            'pengajuanSiapSidang' // Kirim data ini ke view
        ));
    }

    public function daftarPengajuanVerifikasi()
    {
        $pengajuanSiapSidang = Pengajuan::where('status', 'siap_sidang_kajur')->get();
        return view('kajur.pengajuan.perlu_verifikasi', compact('pengajuanSiapSidang'));
    }

    public function daftarPengajuanTerverifikasi()
    {
        // Hanya muat relasi 'mahasiswa' karena 'jenis_pengajuan' adalah kolom langsung
        $pengajuanTerverifikasi = Pengajuan::with('mahasiswa')
                                          ->where('status', 'diverifikasi_kajur')
                                          ->get();

        return view('kajur.pengajuan.sudah_verifikasi', compact('pengajuanTerverifikasi'));
    }

    public function daftarSidangSedang()
    {
        $sidangs = Sidang::whereDate('tanggal_waktu_sidang', Carbon::today())->get();
        return view('kajur.sidang.sedang', compact('sidangs'));
    }

    public function daftarSidangTelah()
    {
        $sidangs = Sidang::where('tanggal_sidang', '<', Carbon::today())->get();
        return view('kajur.sidang.telah', compact('sidangs'));
    }

    public function daftarSidangAkan()
    {
        $sidangs = Sidang::whereDate('tanggal_waktu_sidang', '>', Carbon::today())->get();
        return view('kajur.sidang.akan', compact('sidangs'));
    }

    public function detailSidang(Sidang $sidang)
    {
        // Eager load necessary relationships
        $sidang->load([
            'pengajuan.mahasiswa', // Load pengajuan and its mahasiswa
            'dosenPembimbing',
            'dosenPenguji1',
            'dosenPenguji2',
            // Add other relationships if you want to display ketuaSidang, sekretarisSidang, etc.
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
        ]);

        return view('kajur.sidang.show', compact('sidang'));
    }

    public function showVerifikasiForm(Pengajuan $pengajuan)
    {
        // Pastikan hanya pengajuan dengan status 'siap_sidang_kajur' yang bisa diverifikasi
        if ($pengajuan->status !== 'siap_sidang_kajur') {
            return redirect()->route('kajur.dashboard')->with('error', 'Pengajuan ini tidak dalam status siap verifikasi oleh Kajur.');
        }

        return view('kajur.pengajuan.verifikasi', compact('pengajuan'));
    }

    public function verifikasiPengajuan(Request $request, Pengajuan $pengajuan)
    {
        // Validasi opsional jika ada input tambahan dari form
        // $request->validate([
        //     // 'catatan_verifikasi' => 'nullable|string',
        // ]);

        // Pastikan hanya pengajuan dengan status 'siap_sidang_kajur' yang bisa diverifikasi
        if ($pengajuan->status !== 'siap_sidang_kajur') {
            return redirect()->route('kajur.dashboard')->with('error', 'Pengajuan ini tidak dalam status siap verifikasi oleh Kajur.');
        }

        // Proses verifikasi
        try {
            DB::beginTransaction(); // Mulai transaksi database

            $pengajuan->status = 'diverifikasi_kajur'; // Ubah status pengajuan
            // Anda bisa menambahkan kolom lain jika ada, misalnya id kajur yang memverifikasi
            // $pengajuan->verified_by_kajur_id = auth()->user()->id;
            // $pengajuan->verified_at = now();
            $pengajuan->save();

            DB::commit(); // Komit transaksi

            return redirect()->route('kajur.dashboard')->with('success', 'Pengajuan berhasil diverifikasi oleh Kajur.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi pengajuan: ' . $e->getMessage());
        }
    }

    public function showPengajuanDetail(Pengajuan $pengajuan)
    {
        // Eager load necessary relationships for the detail view
        // For example, if you want to show student details and supervisor details
        $pengajuan->load(['mahasiswa', 'dosenPembimbing', 'dosenPenguji1', 'dosenPenguji2']);

        return view('kajur.pengajuan.detail', compact('pengajuan'));
    }
}