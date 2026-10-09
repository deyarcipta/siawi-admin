<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $tagihan = Tagihan::find('1');
        $kelasId = $request->input('kelas', '');

        $isWaliKelasOnly = $user && $user->isWaliKelasKeuanganStrict();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        if ($isWaliKelasOnly) {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->orderBy('nama_kelas', 'asc')->get();
            if ($kelasWali->isNotEmpty()) {
                $kelas = $kelasWali;
                if (empty($kelasId) || !$kelas->contains('id_kelas', $kelasId)) {
                    $kelasId = $kelas->first()->id_kelas;
                }
            } else {
                abort(403, 'Anda belum ditugaskan sebagai wali kelas.');
            }
        }

        $query = Siswa::with('kelas');
        if ($isWaliKelasOnly) {
            $kelasWaliIds = $kelas->pluck('id_kelas');
            if (!empty($kelasId) && $kelasId !== 'all') {
                $query->where('id_kelas', $kelasId);
            } else {
                $query->whereIn('id_kelas', $kelasWaliIds);
            }
        } elseif (!empty($kelasId) && $kelasId !== 'all') {
            $query->where('id_kelas', $kelasId);
        }

        $siswa = $query->orderBy('nama_siswa', 'asc')->get();
        $dataKelas = (!empty($kelasId) && $kelasId !== 'all') ? Kelas::find($kelasId) : null;

        return view('tagihan.data_tagihan', compact('kelas', 'siswa', 'kelasId', 'layout', 'setting', 'dataKelas', 'tagihan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        if ($user && !in_array($user->role, ['admin', 'keuangan'])) {
            return redirect('/admin/tagihan')->with('failed', 'Hanya Admin dan Staf Keuangan yang dapat mengubah template link tagihan.');
        }

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $edit = Tagihan::find('1');
        return view('tagihan.edit_tagihan', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_tagihan)
    {
        $user = Auth::user();
        if ($user && !in_array($user->role, ['admin', 'keuangan'])) {
            return redirect('/admin/tagihan')->with('failed', 'Hanya Admin dan Staf Keuangan yang dapat mengubah template link tagihan.');
        }

        $request->validate([
            'link' => 'required',
        ]);

        Tagihan::where('id_tagihan', $id_tagihan)->update([
            'link' => $request->link,
        ]);

        return redirect('/admin/tagihan')->with('success', 'Template link tagihan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_tagihan)
    {
        //
    }
}
