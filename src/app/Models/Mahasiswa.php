<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap', // Sesuaikan jika nama kolom di DB berbeda
        'jurusan',
        'prodi',
        'jenis_kelamin',
        'kelas',
        'email',    // Tambahkan jika ada di DB
        //'password', // Tambahkan jika ada di DB
        'user_id',
    ];

    // Jika ada relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pengajuan (jika perlu)
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
}