<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa; // Jika ada model Mahasiswa terpisah
use App\Models\Dosen;     // PENTING: Tambahkan ini untuk mengimpor model Dosen
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Dosen
        // Buat user untuk dosen pertama
        $dosenUser1 = User::create([
            'name' => 'Ilham Widajaya',
            'email' => 'ilham@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'dosen',
        ]);
        // Hubungkan user ini dengan entri di tabel dosens
        Dosen::create([
            'user_id' => $dosenUser1->id, // PENTING: Gunakan ID user yang baru dibuat
            'nidn' => '1234567890',      // Ganti dengan NIDN unik yang valid
            'nama' => 'Ilham Widajaya',  // Pastikan ini sesuai dengan nama di User
            'jurusan' => 'Teknik Informatika',
            'prodi' => 'Ilmu Komputer',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'ilham@example.com',
            'password' => Hash::make('12345678'), // Bisa diabaikan jika dosen login via tabel users
        ]);

        // Buat user untuk dosen kedua
        $dosenUser2 = User::create([
            'name' => 'Andrew Diantara',
            'email' => 'andrew@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'dosen',
        ]);
        // Hubungkan user ini dengan entri di tabel dosens
        Dosen::create([
            'user_id' => $dosenUser2->id,
            'nidn' => '0987654321',      // Ganti dengan NIDN unik yang valid
            'nama' => 'Andrew Diantara',
            'jurusan' => 'Sistem Informasi',
            'prodi' => 'Sistem Informasi',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'andrew@example.com',
            'password' => Hash::make('12345678'),
        ]);

        // Buat user untuk dosen ketiga
        $dosenUser3 = User::create([
            'name' => 'Dimas Prasetyo',
            'email' => 'dimas@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'dosen',
        ]);
        // Hubungkan user ini dengan entri di tabel dosens
        Dosen::create([
            'user_id' => $dosenUser3->id,
            'nidn' => '1122334455',      // Ganti dengan NIDN unik yang valid
            'nama' => 'Dimas Prasetyo',
            'jurusan' => 'Teknik Komputer',
            'prodi' => 'Jaringan Komputer',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'dimas@example.com',
            'password' => Hash::make('12345678'),
        ]);


        // Mahasiswa (bagian ini sudah benar)
        $mahasiswaUser1 = User::create([
            'name' => 'darul',
            'email' => 'darul@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
        ]);
        Mahasiswa::create([
            'user_id' => $mahasiswaUser1->id,
            'nim' => '2311082010',
            'nama_lengkap' => 'Darul Febri',
            'jurusan' => 'Teknologi Informasi',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'jenis_kelamin' => 'Laki-laki',
            'kelas' => 'TI-1',
            'email' => 'darul@example.com',
        ]);

        $mahasiswaUser2 = User::create([
            'name' => 'arlan',
            'email' => 'arlan@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'mahasiswa',
        ]);
        Mahasiswa::create([
            'user_id' => $mahasiswaUser2->id,
            'nim' => '2311082011',
            'nama_lengkap' => 'Arlan Diana',
            'jurusan' => 'Teknologi Informasi',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'jenis_kelamin' => 'Perempuan',
            'kelas' => 'TI-1',
            'email' => 'arlan@example.com',
        ]);

        // Kaprodi
        User::create([
            'name' => 'Kaprodi',
            'email' => 'kaprodi@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'kaprodi',
        ]);

        // Kajur
        User::create([
            'name' => 'Kajur',
            'email' => 'kajur@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'kajur',
        ]);

        echo "Berhasil nambahin user dan detail role-nya!\n";
    }
}