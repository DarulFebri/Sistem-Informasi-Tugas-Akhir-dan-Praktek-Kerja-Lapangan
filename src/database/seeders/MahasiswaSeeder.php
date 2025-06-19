<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder; // Pastikan Carbon diimpor

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswasData = [
            [
                'name' => 'darul',
                'email' => 'darul@example.com',
                'nim' => '2311082010',
                'nama_lengkap' => 'Darul Febri',
                'jurusan' => 'Teknologi Informasi',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
                'kelas' => 'TI-1',
            ],
            [
                'name' => 'arlan',
                'email' => 'arlan@example.com',
                'nim' => '2311082011',
                'nama_lengkap' => 'Arlan Diana',
                'jurusan' => 'Teknologi Informasi',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Perempuan',
                'kelas' => 'TI-1',
            ],
            [
                'name' => 'ayung',
                'email' => 'darulfer097@gmail.com',
                'nim' => '2311082096',
                'nama_lengkap' => 'ayung',
                'jurusan' => 'Teknologi Informasi',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
                'kelas' => 'TI-1',
            ],
        ];

        foreach ($mahasiswasData as $data) {
            // Cari user yang sudah ada (dibuat di UserSeeder)
            $user = User::where('email', $data['email'])->first();

            if ($user) { // Pastikan user ditemukan sebelum membuat detail mahasiswa
                Mahasiswa::firstOrCreate(
                    ['nim' => $data['nim']],
                    [
                        'user_id' => $user->id,
                        'nama_lengkap' => $data['nama_lengkap'],
                        'jurusan' => $data['jurusan'],
                        'prodi' => $data['prodi'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'kelas' => $data['kelas'],
                        'email' => $data['email'],
                    ]
                );
            } else {
                echo "Peringatan: User untuk mahasiswa '{$data['email']}' tidak ditemukan. Detail mahasiswa tidak dibuat.\n";
            }
        }

        echo "Detail mahasiswa berhasil ditambahkan dan dihubungkan!\n";
    }
}
