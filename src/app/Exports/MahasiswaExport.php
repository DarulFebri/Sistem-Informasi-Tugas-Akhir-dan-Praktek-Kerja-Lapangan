<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambahkan ini
use Maatwebsite\Excel\Concerns\WithHeadings; // Opsional: Tambahkan ini untuk auto-size kolom

class MahasiswaExport implements FromCollection, ShouldAutoSize, WithHeadings // Tambahkan interfaces
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data mahasiswa
        // Gunakan select() untuk memilih kolom yang ingin Anda ekspor
        // Pastikan nama kolom sesuai dengan nama kolom di tabel mahasiswas
        return Mahasiswa::select(
            'nim',
            'nama_lengkap', // Perhatikan, ini adalah nama kolom di DB Anda
            'jurusan',
            'prodi',
            'jenis_kelamin',
            'kelas',
            'email',
            // Anda bisa menambahkan kolom lain jika diperlukan, seperti 'created_at', 'updated_at'
        )->get();
    }

    public function headings(): array
    {
        // Tentukan heading untuk kolom-kolom di Excel
        // Urutan harus sama dengan urutan di method collection() di atas
        return [
            'NIM',
            'Nama Lengkap',
            'Jurusan',
            'Prodi',
            'Jenis Kelamin',
            'Kelas',
            'Email',
        ];
    }
}
