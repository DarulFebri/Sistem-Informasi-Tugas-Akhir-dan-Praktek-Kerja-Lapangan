<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nomor_induk' => 'required',
            'password' => 'required',
            'role' => 'required|in:admin,dosen,mahasiswa'
        ]);

        // Cek role user yang login sesuai dengan yang dipilih
        $user = User::where('nomor_induk', $request->nomor_induk)
                  ->where('role', $request->role)
                  ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirect berdasarkan role
            return match($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'dosen' => redirect()->intended(route('dosen.dashboard')),
                'mahasiswa' => redirect()->intended(route('mahasiswa.dashboard')),
                default => redirect('/')
            };
        }

        return back()->withErrors([
            'nomor_induk' => 'Kredensial tidak valid atau role tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mahasiswa,dosen'
        ]);

        $user = User::create([
            'nomor_induk' => $request->nomor_induk,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'verification_token' => Str::random(60),
            // Set default values untuk field lainnya
            'jenis_kelamin' => 'L',
            'ketersediaan' => true
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
    }
}