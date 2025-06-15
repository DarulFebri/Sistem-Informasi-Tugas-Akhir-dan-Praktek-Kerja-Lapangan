<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import model Dosen jika sudah ada relasi di sini
use App\Models\Dosen;
use App\Models\Pengajuan;

class Sidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'ketua_sidang_dosen_id',
        'sekretaris_sidang_dosen_id', 
        'anggota1_sidang_dosen_id',
        'anggota2_sidang_dosen_id',
        'tanggal_waktu_sidang', // Pastikan nama kolom ini sesuai di database
        'ruangan_sidang',       // Pastikan nama kolom ini sesuai di database
        'dosen_pembimbing_id',
        'dosen_penguji1_id', // Ini untuk pembimbing 2
        // --- KOLOM BARU DITAMBAHKAN ---
        'persetujuan_ketua_sidang',
        'persetujuan_sekretaris_sidang',
        'persetujuan_anggota1_sidang',
        'persetujuan_anggota2_sidang',
        'persetujuan_dosen_pembimbing',
        'persetujuan_dosen_penguji1',
        // --- AKHIR KOLOM BARU ---
        'status', // Pastikan kolom status sidang juga ada di fillable
    ];

    // Relasi ke Pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function ketuaSidang()
    {
        return $this->belongsTo(Dosen::class, 'ketua_sidang_dosen_id');
    }

    public function sekretarisSidang()
    {
        return $this->belongsTo(Dosen::class, 'sekretaris_sidang_dosen_id');
    }

    public function anggota1Sidang()
    {
        return $this->belongsTo(Dosen::class, 'anggota1_sidang_dosen_id');
    }

    public function anggota2Sidang()
    {
        return $this->belongsTo(Dosen::class, 'anggota2_sidang_dosen_id');
    }

    // --- TAMBAHKAN RELASI INI ---
    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    public function dosenPenguji1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji1_id');
    }

    public function dosenPenguji2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji2_id');
    }
    // --- AKHIR TAMBAHAN RELASI ---
}