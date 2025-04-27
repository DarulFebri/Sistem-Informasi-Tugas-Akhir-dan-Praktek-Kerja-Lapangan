<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'mahasiswaCount' => User::where('role', 'mahasiswa')->count(),
            'dosenCount' => User::where('role', 'dosen')->count(),
        ]);
    }

    public function mahasiswa()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
                        ->filter(request(['search', 'kelas', 'jenis_kelamin']))
                        ->paginate(10);

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function createMahasiswa()
    {
        return view('admin.mahasiswa.create');
    }
    
    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'nomor_induk' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
            'jurusan' => 'required',
            'program_studi' => 'required',
            'status' => 'required'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'mahasiswa';

        User::create($validated);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan');
    }
}