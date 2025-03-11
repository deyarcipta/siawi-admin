<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\Setting;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard.index');
        }

        // Jika belum login, tampilkan halaman login
        $setting = Setting::find('1');
        return view('auth.login', compact('setting'));
    }

    public function login_proses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = Guru::where('username', $request->username)->first();

        if ($user) {
            // Gunakan Hash::check untuk memeriksa password terenkripsi
            if (Hash::check($request->password, $user->password)) {
                // Autentikasi berhasil, loginkan pengguna
                Auth::login($user);

                // Arahkan pengguna ke dashboard atau lokasi yang sesuai
                return redirect('/admin/dashboard')->with('success', 'Berhasil Login');
            }
        }

        return redirect()->route('login')->with('failed', 'Username dan password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
}

