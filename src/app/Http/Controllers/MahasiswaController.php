<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan; // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Models\Dosen;     // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Models\Mahasiswa; // Ini boleh di sini jika dipakai di dashboard Mahasiswa
use App\Imports\MahasiswaImport;
use App\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Log; // Tambahkan ini

class MahasiswaController extends Controller
{
    public function loginForm()
    {
        return view('mahasiswa.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'mahasiswa';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswaId = Auth::id();

        $pengajuanTerbaru = Pengajuan::where('mahasiswa_id', $mahasiswaId)
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get();
        $pengajuanTerbaru->load('mahasiswa', 'sidang');

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        return view('mahasiswa.dashboard', compact('pengajuanTerbaru', 'jumlahPengajuan'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Method untuk menampilkan form impor mahasiswa
    public function importForm()
    {
        return view('admin.mahasiswa.import'); // Buat view ini nanti
    }

    // Method untuk memproses file Excel mahasiswa
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:2048', // Validasi file Excel
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file')); // Proses impor
            return redirect()->back()->with('success', 'Data mahasiswa berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                // Ambil header kolom yang menyebabkan kegagalan
                $attribute = $failure->attribute();
                $errorMessage = implode(', ', $failure->errors());
                $errors[] = 'Baris ' . $failure->row() . ' (Kolom: ' . $attribute . '): ' . $errorMessage;
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data mahasiswa. Ada kesalahan validasi: <ul><li>' . implode('</li><li>', $errors) . '</li></ul>');
        } catch (\Exception $e) {
            Log::error('Kesalahan impor mahasiswa umum: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data mahasiswa: ' . $e->getMessage());
        }
    }

    // Method Untuk Eksport Data Mahasiswa
    public function export()
    {
        // Nama file yang akan diunduh
        $fileName = 'data_mahasiswa_' . date('Ymd_His') . '.xlsx';

        // Unduh file Excel menggunakan kelas export yang sudah dibuat
        return Excel::download(new MahasiswaExport, $fileName);
    }
}