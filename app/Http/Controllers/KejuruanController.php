<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Lab;
use App\Models\Kompetensi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KejuruanController extends Controller
{
    /**
     * Halaman Modul Praktik Kejuruan.
     */
    public function praktik()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas untuk Guru Jurusan, Kaprog, dan Admin.');
        }

        $jurusans = Jurusan::forUserJurusan($user)
            ->with(['labs', 'kompetensis'])
            ->get();

        return view('kejuruan.praktik.index', compact('layout', 'setting', 'user', 'jurusans'));
    }

    /**
     * Halaman Modul Uji Kompetensi Keahlian (UKK).
     */
    public function ujiKompetensi()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Kaprog dan Admin.');
        }

        $jurusans = Jurusan::forUserJurusan($user)
            ->with(['kompetensis'])
            ->get();

        return view('kejuruan.uji_kompetensi.index', compact('layout', 'setting', 'user', 'jurusans'));
    }

    /**
     * Halaman Modul Inventaris & Pemakaian Lab Kejuruan.
     */
    public function inventarisLab()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Kaprog dan Admin.');
        }

        $labs = Lab::forUserJurusan($user)
            ->with('jurusan')
            ->get();

        return view('kejuruan.inventaris_lab.index', compact('layout', 'setting', 'user', 'labs'));
    }
}
