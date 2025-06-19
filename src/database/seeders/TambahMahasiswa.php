<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User; // Jika ada model Mahasiswa terpisah
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TambahMahasiswa extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mahasiswa
        $mahasiswaUser = User::create([
            'name' => 'arlan', // Tambahkan nama
            'email' => 'arlan@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
        ]);

        // Jika ada tabel Mahasiswa terpisah, buat record di sana
        Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'nim' => '2311082011', // Contoh NIM
            'nama_lengkap' => 'Arlan Diana',
            'jurusan' => 'Teknologi Informasi',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'jenis_kelamin' => 'P',
            'kelas' => 'TI-1',
        ]);

        $mahasiswaUser = User::create([
            'name' => 'ayung', // Tambahkan nama
            'email' => 'darulfer097@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
        ]);

        // Jika ada tabel Mahasiswa terpisah, buat record di sana
        Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'nim' => '2311082096', // Contoh NIM
            'nama_lengkap' => 'ayung',
            'jurusan' => 'Teknologi Informasi',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'jenis_kelamin' => 'L',
            'kelas' => 'TI-1',
        ]);
        echo "Berhasil nambahin 2 user mahasiswa\n";
    }
}
