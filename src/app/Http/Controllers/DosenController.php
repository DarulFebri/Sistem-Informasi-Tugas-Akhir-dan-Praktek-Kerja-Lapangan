<?php

namespace App\Http\Controllers;

use App\Imports\DosenImport;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\Pengajuan;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel; // Import this!

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
        $credentials['role'] = 'dosen';

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
        $dosen = $user->dosen;

        if (! $dosen) {
            Auth::logout();

            return redirect()->route('dosen.login')->with('error', 'Profil dosen tidak ditemukan.');
        }

        $dosenLoginId = $dosen->id;

        $unreadNotifications = $user->unreadNotifications;

        // Ambil sidang di mana dosen ini terlibat dan statusnya masih 'pending'
        $sidangInvitations = Sidang::where(function ($query) use ($dosenLoginId) {
            $query->where('ketua_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_ketua_sidang', 'pending');
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('sekretaris_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_sekretaris_sidang', 'pending');
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('anggota1_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_anggota1_sidang', 'pending');
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('anggota2_sidang_dosen_id', $dosenLoginId)
                ->where('persetujuan_anggota2_sidang', 'pending');
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('dosen_pembimbing_id', $dosenLoginId)
                ->where('persetujuan_dosen_pembimbing', 'pending');
        })->orWhere(function ($query) use ($dosenLoginId) {
            $query->where('dosen_penguji1_id', $dosenLoginId)
                ->where('persetujuan_dosen_penguji1', 'pending');
        })
            ->with([
                'pengajuan.mahasiswa',
                'ketuaSidang',
                'sekretarisSidang',
                'anggota1Sidang',
                'anggota2Sidang',
                'dosenPembimbing',
                'dosenPenguji1',
            ])
            ->get();

        // --- CORRECTED QUERIES FOR APPROVED AND REJECTED SIDANGS ---
        // Helper function to check if a specific role for the logged-in dosen is 'setuju' or 'tolak'
        $getSidangsByResponse = function ($responseType) use ($dosenLoginId) {
            return Sidang::where(function ($query) use ($dosenLoginId, $responseType) {
                // Check each role specifically for the logged-in dosen and the desired responseType
                $query->where(function ($q) use ($dosenLoginId, $responseType) {
                    $q->where('ketua_sidang_dosen_id', $dosenLoginId)
                        ->where('persetujuan_ketua_sidang', $responseType);
                })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('sekretaris_sidang_dosen_id', $dosenLoginId)
                            ->where('persetujuan_sekretaris_sidang', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('anggota1_sidang_dosen_id', $dosenLoginId)
                            ->where('persetujuan_anggota1_sidang', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('anggota2_sidang_dosen_id', $dosenLoginId)
                            ->where('persetujuan_anggota2_sidang', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('dosen_pembimbing_id', $dosenLoginId)
                            ->where('persetujuan_dosen_pembimbing', $responseType);
                    })
                    ->orWhere(function ($q) use ($dosenLoginId, $responseType) {
                        $q->where('dosen_penguji1_id', $dosenLoginId)
                            ->where('persetujuan_dosen_penguji1', $responseType);
                    });
            })
                ->with([
                    'pengajuan.mahasiswa',
                    'ketuaSidang',
                    'sekretarisSidang',
                    'anggota1Sidang',
                    'anggota2Sidang',
                    'dosenPembimbing',
                    'dosenPenguji1',
                ])
                ->get();
        };

        $approvedSidangs = $getSidangsByResponse('setuju');
        $rejectedSidangs = $getSidangsByResponse('tolak');
        // --- END CORRECTED QUERIES ---

        return view('dosen.dashboard', compact('unreadNotifications', 'sidangInvitations', 'approvedSidangs', 'rejectedSidangs'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function daftarPengajuan()
    {
        $pengajuans = Pengajuan::with([
            'mahasiswa',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ])->get();

        return view('dosen.pengajuan.index', compact('pengajuans'));
    }

    public function detailPengajuan(Pengajuan $pengajuan)
    {
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ]);

        return view('dosen.pengajuan.show', compact('pengajuan'));
    }

    // Method untuk menandai notifikasi sudah dibaca
    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        if (Auth::id() !== $notification->notifiable_id) {
            abort(403, 'Unauthorized action.');
        }
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

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
                $persetujuanKolom = 'persetujuan_'.$role;
                if ($sidang->$persetujuanKolom === 'pending') {
                    $allDosenResponded = false;
                    break;
                }
                if ($sidang->$persetujuanKolom === 'tolak') {
                    $allDosenAgreed = false;
                    break;
                }
            }
        }

        if ($allDosenResponded) {
            if ($allDosenAgreed) {
                $sidang->pengajuan->update(['status' => 'dosen_menyetujui']);
            } else {
                $sidang->pengajuan->update(['status' => 'dosen_menolak_jadwal']);
            }
        }
    }

    public function pengajuanSaya()
    {
        $user = Auth::user();

        if (! $user || ! $user->dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Akses ditolak. Anda tidak terdaftar sebagai dosen.');
        }

        $dosenId = Auth::user()->dosen->id;

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
                'sidang.dosenPembimbing',
                'sidang.dosenPenguji1',
                'sidang.dosenPenguji2',
                'sidang.ketuaSidang',
                'sidang.sekretarisSidang',
                'sidang.anggota1Sidang',
                'sidang.anggota2Sidang',
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dosen.pengajuan.pengajuan_saya', compact('pengajuansInvolved'));
    }

    public function setujuiDokumen(Dokumen $dokumen)
    {
        $dokumen->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function tolakDokumen(Dokumen $dokumen)
    {
        $dokumen->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'Dokumen berhasil ditolak.');
    }

    public function formJadwalSidang(Pengajuan $pengajuan)
    {
        return view('dosen.jadwal.create', compact('pengajuan'));
    }

    public function simpanJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'tanggal_waktu_sidang' => 'required|date',
            'ruangan_sidang' => 'required|string|max:255',
        ]);

        $sidang = $pengajuan->sidang ?? new Sidang;
        $sidang->pengajuan_id = $pengajuan->id;
        $sidang->tanggal_waktu_sidang = $request->tanggal_waktu_sidang;
        $sidang->ruangan_sidang = $request->ruangan_sidang;
        $sidang->save();

        return redirect()->route('dosen.pengajuan.show', $pengajuan->id)->with('success', 'Jadwal sidang berhasil dibuat.');
    }

    public function detailJadwalSidang(Sidang $sidang)
    {
        $sidang->load([
            'pengajuan.mahasiswa',
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
            'dosenPembimbing',
            'dosenPenguji1',
            'dosenPenguji2',
        ]);

        return view('dosen.jadwal.show', compact('sidang'));
    }

    public function unduhLaporan(Sidang $sidang)
    {
        $laporan = Dokumen::where('pengajuan_id', $sidang->pengajuan_id)
            ->where('jenis_dokumen', 'Laporan TA')
            ->first();

        if (! $laporan || ! Storage::exists($laporan->path_file)) {
            abort(404, 'Laporan Tugas Akhir tidak ditemukan atau file tidak ada.');
        }

        return Storage::download($laporan->path_file, $laporan->nama_file);
    }

    public function formNilaiSidang(Sidang $sidang)
    {
        $dosenId = Auth::user()->dosen->id;
        if (! in_array($dosenId, [
            $sidang->dosen_pembimbing_id,
            $sidang->dosen_penguji1_id,
            $sidang->dosen_penguji2_id,
        ])) {
            abort(403, 'Anda tidak berhak memberikan nilai pada sidang ini.');
        }

        return view('dosen.sidang.nilai.edit', compact('sidang'));
    }

    public function simpanNilaiSidang(Request $request, Sidang $sidang)
    {
        $request->validate([
            'nilai_sidang' => 'required|numeric|min:0|max:100',
            'catatan_sidang' => 'nullable|string',
        ]);

        $sidang->update([
            'nilai_sidang' => $request->nilai_sidang,
            'catatan_sidang' => $request->catatan_sidang,
            'hasil_sidang' => ($request->nilai_sidang >= 60) ? 'Lulus' : 'Tidak Lulus',
        ]);

        return redirect()->route('dosen.sidang.nilai.edit', $sidang->id)->with('success', 'Nilai sidang berhasil disimpan.');
    }

    public function importForm()
    {
        return view('admin.dosen.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:2048',
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data dosen berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris '.$failure->row().': '.implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', 'Gagal mengimpor data dosen. Ada kesalahan validasi: '.implode('; ', $errors));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data dosen: '.$e->getMessage());
        }
    }

    public function formResponSidang(Sidang $sidang)
    {
        $dosen = Auth::user()->dosen;
        $dosenLoginId = $dosen->id;

        // Determine if the logged-in dosen is involved and still has a pending response
        $isPending = false;
        if ($sidang->ketua_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_ketua_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->sekretaris_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_sekretaris_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->anggota1_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_anggota1_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->anggota2_sidang_dosen_id === $dosenLoginId && $sidang->persetujuan_anggota2_sidang === 'pending') {
            $isPending = true;
        }
        if ($sidang->dosen_pembimbing_id === $dosenLoginId && $sidang->persetujuan_dosen_pembimbing === 'pending') {
            $isPending = true;
        }
        if ($sidang->dosen_penguji1_id === $dosenLoginId && $sidang->persetujuan_dosen_penguji1 === 'pending') {
            $isPending = true;
        }

        if ($isPending) {
            $sidang->load('pengajuan.mahasiswa', 'ketuaSidang', 'sekretarisSidang', 'anggota1Sidang', 'anggota2Sidang', 'dosenPembimbing', 'dosenPenguji1');

            return view('dosen.respon_sidang', compact('sidang', 'dosen'));
        }

        // If dosen is not involved with a pending response, they are redirected.
        // We can optionally check if they were involved at all to give a more specific message.
        $wasInvolved = false;
        if (
            $sidang->ketua_sidang_dosen_id === $dosenLoginId ||
            $sidang->sekretaris_sidang_dosen_id === $dosenLoginId ||
            $sidang->anggota1_sidang_dosen_id === $dosenLoginId ||
            $sidang->anggota2_sidang_dosen_id === $dosenLoginId ||
            $sidang->dosen_pembimbing_id === $dosenLoginId ||
            $sidang->dosen_penguji1_id === $dosenLoginId
        ) {
            $wasInvolved = true;
        }

        if ($wasInvolved) {
            return redirect()->route('dosen.dashboard')->with('info', 'Anda sudah merespon undangan sidang ini.');
        } else {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses ke undangan sidang ini.');
        }
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
            $peranDosen = 'Dosen Pembimbing 2';
        } else {
            return back()->with('error', 'Anda tidak dapat merespon undangan ini lagi atau tidak terkait.');
        }

        $sidang->save();

        return redirect()->route('dosen.dashboard')->with('success', "Respon Anda sebagai {$peranDosen} ($respon) berhasil disimpan.");
    }
}
