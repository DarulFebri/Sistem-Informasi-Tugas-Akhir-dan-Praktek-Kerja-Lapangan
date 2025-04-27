<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nomor_induk' => 'admin001',
            'name' => 'Admin Utama',
            'email' => 'admin1@sita.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'jenis_kelamin' => 'L'
        ]);

        User::create([
            'nomor_induk' => 'admin002',
            'name' => 'Admin Backup',
            'email' => 'admin2@sita.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'jenis_kelamin' => 'P'
        ]);

        $this->command->info('Berhasil membuat 2 akun admin!');
        $this->command->info('Email: admin1@sita.test / admin2@sita.test');
        $this->command->info('Password: password123');
    }
}