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
        'nama_lengkap',
        'jurusan',
        'prodi',
        'jenis_kelamin',
        'kelas',
        'email',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'otp_expires_at' => 'datetime', // WAJIB: Pastikan ini dicasting sebagai datetime
        ];
    }

    // Relasi ke User
    public function user()
    {
        // Asumsi: Mahasiswa memiliki satu User yang terkait.
        // Default foreign key adalah user_id, jadi tidak perlu eksplisit kecuali berbeda.
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Pengajuan (jika perlu)
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
}
