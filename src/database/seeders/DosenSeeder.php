<?php

namespace Database\Seeders;

use App\Models\Dosen; // Pastikan model Dosen di-import
use App\Models\User; // Pastikan model User di-import
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Jika masih ingin pakai DB::table

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Anda membersihkan tabel jika menjalankan seeder berkali-kali untuk menghindari duplikasi
        // DB::table('dosens')->truncate(); // Opsional, hati-hati jika ada data penting
        // DB::table('users')->where('role', 'dosen')->delete(); // Opsional, hati-hati

        $dosensData = [
            [
                'name' => 'Prof. Dr. Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'password' => 'password123',
                'nidn' => '197001012000011001',
                'jurusan' => 'Teknik Informatika',
                'prodi' => 'Sistem Informasi',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => 'password123',
                'nidn' => '198005102005021002',
                'jurusan' => 'Teknik Informatika',
                'prodi' => 'Teknik Komputer',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Dra. Citra Dewi, M.Kom',
                'email' => 'citra.dewi@example.com',
                'password' => 'password123',
                'nidn' => '197511202002032003',
                'jurusan' => 'Teknik Informatika',
                'prodi' => 'Sistem Informasi',
                'jenis_kelamin' => 'Perempuan',
            ],
            [
                'name' => 'Dra. Rayhan Dwiwata Putra, M.Kom',
                'email' => 'Rayhan.dwiwata@example.com',
                'password' => 'password123',
                'nidn' => '197511202002032004',
                'jurusan' => 'Teknologi Informasi',
                'prodi' => 'Sistem Komputer',
                'jenis_kelamin' => 'Laki-Laki',
            ],
        ];

        foreach ($dosensData as $data) {
            // Buat user terlebih dahulu
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'dosen',
                ]
            );

            // Kemudian buat dosen, hubungkan dengan user_id yang baru dibuat atau yang sudah ada
            Dosen::firstOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['name'],
                    'jurusan' => $data['jurusan'],
                    'prodi' => $data['prodi'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    // Password di tabel dosen bisa diabaikan jika login via tabel users,
                    // atau bisa disimpan jika memang ada kebutuhan terpisah.
                    // Sebaiknya dihapus dari tabel dosen atau set null jika tidak digunakan.
                    'password' => Hash::make($data['password']), // HASH password di sini juga jika disimpan di tabel dosen
                ]
            );
        }

        echo "Berhasil nambahin daftar dosen yang terdaftar di sistem!\n";
    }
}