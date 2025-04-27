<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nomor_induk',
        'jurusan', 'program_studi', 'kelas', 'jenis_kelamin',
        'kompetensi', 'ketersediaan'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    // Relasi untuk bimbingan
    public function bimbinganSebagaiMahasiswa()
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    public function bimbinganSebagaiDosen()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where('name', 'like', '%'.$search.'%')
                  ->orWhere('nomor_induk', 'like', '%'.$search.'%')
        );

        $query->when($filters['kelas'] ?? false, fn($query, $kelas) =>
            $query->where('kelas', $kelas)
        );

        $query->when($filters['jenis_kelamin'] ?? false, fn($query, $jenis_kelamin) =>
            $query->where('jenis_kelamin', $jenis_kelamin)
        );

        // Filter khusus untuk dosen
        $query->when($filters['kompetensi'] ?? false, fn($query, $kompetensi) =>
            $query->where('kompetensi', $kompetensi)
        );

        $query->when(isset($filters['ketersediaan']), fn($query) =>
            $query->where('ketersediaan', $filters['ketersediaan'])
        );
    }

    public function sendEmailVerificationNotification()
    {
        $this->verification_token = Str::random(60);
        $this->save();

        $this->notify(new VerifyEmailNotification($this->verification_token));
    }
}