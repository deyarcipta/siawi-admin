<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumni;
use App\Models\Setting;
use DB;

class DataAlumniController extends Controller
{
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $alumni = Alumni::orderBy('nama', 'asc')->get();
        // return view('dataSiswa.data_siswa', compact('layout','siswa','setting','user'));
        return view('dataAlumni.index', compact('layout','setting','user','alumni'));
    }
}
