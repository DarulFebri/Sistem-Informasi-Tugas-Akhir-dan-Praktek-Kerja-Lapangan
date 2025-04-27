<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = User::where('role', 'dosen')
                    ->filter(request(['search', 'kompetensi', 'ketersediaan']))
                    ->paginate(10);
                    
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_induk' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'jenis_kelamin' => 'required',
            'kompetensi' => 'required',
            'ketersediaan' => 'required|boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'dosen';

        User::create($validated);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan');
    }

    public function show(User $dosen)
    {
        abort_unless($dosen->isDosen(), 404);
        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit(User $dosen)
    {
        abort_unless($dosen->isDosen(), 404);
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, User $dosen)
    {
        abort_unless($dosen->isDosen(), 404);

        $validated = $request->validate([
            'nomor_induk' => ['required', Rule::unique('users')->ignore($dosen->id)],
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($dosen->id)],
            'jenis_kelamin' => 'required',
            'kompetensi' => 'required',
            'ketersediaan' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $dosen->update($validated);

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui');
    }

    public function destroy(User $dosen)
    {
        abort_unless($dosen->isDosen(), 404);
        $dosen->delete();
        return back()->with('success', 'Dosen berhasil dihapus');
    }
}