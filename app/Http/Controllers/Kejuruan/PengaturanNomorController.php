<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\JenisTkk;
use App\Models\Jurusan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanNomorController extends Controller
{
    /**
     * Halaman Pengaturan Format Nomor Piagam TKK & Sertifikat Kejuruan.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses ditolak. Pengaturan ini hanya dapat dikelola oleh Admin atau Kepala Program Keahlian (Kaprog).');
        }

        $canAccessTkk = Jurusan::userCanAccessModul($user, 'tkk');
        $canAccessSertifikat = $user->hasRole('admin') || Jurusan::userCanAccessModul($user, 'ukk') || Jurusan::userCanAccessModul($user, 'portofolio');

        if (!$canAccessTkk && !$canAccessSertifikat) {
            abort(403, 'Akses ditolak. Jurusan Anda tidak memiliki modul TKK maupun Sertifikat/Portofolio yang aktif.');
        }

        $layout = 'layout.app';
        $setting = Setting::find(1);
        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        $sampleJurusan = 'TJKT';
        $sampleKlaster = 'KL1';
        $sampleKodeTkk = 'TKK-01';

        $userJurusanIds = Jurusan::getAccessibleJurusanIds($user);
        $userJurusan = Jurusan::whereIn('id_jurusan', $userJurusanIds)->first();
        if ($userJurusan) {
            $sampleJurusan = $userJurusan->kode_jurusan;
        }

        $sampleTkk = null;
        if ($canAccessTkk) {
            $sampleTkk = JenisTkk::with('jurusan')->forUserJurusan($user)->first() ?: JenisTkk::with('jurusan')->first();
            if ($sampleTkk) {
                $sampleKlaster = 'KL' . ($sampleTkk->tingkat ?: 1);
                if ($sampleTkk->kode_tkk) {
                    $sampleKodeTkk = $sampleTkk->kode_tkk;
                }
            }
        }

        $nomorSettings = $setting->kejuruan_nomor_settings;

        return view('kejuruan.pengaturan_nomor', compact(
            'layout', 'setting', 'user', 'jurusans', 'sampleTkk', 'nomorSettings',
            'canAccessTkk', 'canAccessSertifikat', 'sampleJurusan', 'sampleKlaster', 'sampleKodeTkk'
        ));
    }

    /**
     * Simpan Perubahan Pengaturan Format Nomor.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses ditolak.');
        }

        $canAccessTkk = Jurusan::userCanAccessModul($user, 'tkk');
        $canAccessSertifikat = $user->hasRole('admin') || Jurusan::userCanAccessModul($user, 'ukk') || Jurusan::userCanAccessModul($user, 'portofolio');

        if (!$canAccessTkk && !$canAccessSertifikat) {
            abort(403, 'Akses ditolak.');
        }

        $rules = [];
        if ($canAccessTkk) {
            $rules['tkk_format'] = 'required|string|max:150';
            $rules['tkk_padding'] = 'required|integer|min:2|max:8';
            $rules['tkk_reset_periode'] = 'required|in:tahun,tahun_ajaran,tidak_pernah';
            $rules['tkk_counter'] = 'required|integer|min:0';
        }

        if ($canAccessSertifikat) {
            $rules['sertifikat_format'] = 'required|string|max:150';
            $rules['sertifikat_padding'] = 'required|integer|min:2|max:8';
            $rules['sertifikat_reset_periode'] = 'required|in:tahun,tahun_ajaran,tidak_pernah';
            $rules['sertifikat_counter'] = 'required|integer|min:0';
        }

        $request->validate($rules);

        $setting = Setting::find(1);
        $currentSettings = $setting->kejuruan_nomor_settings;

        if ($canAccessTkk) {
            $currentSettings['tkk']['format'] = trim($request->tkk_format);
            $currentSettings['tkk']['padding'] = intval($request->tkk_padding);
            $currentSettings['tkk']['reset_periode'] = $request->tkk_reset_periode;
            $currentSettings['tkk']['counter'] = intval($request->tkk_counter);
        }

        if ($canAccessSertifikat) {
            $currentSettings['sertifikat']['format'] = trim($request->sertifikat_format);
            $currentSettings['sertifikat']['padding'] = intval($request->sertifikat_padding);
            $currentSettings['sertifikat']['reset_periode'] = $request->sertifikat_reset_periode;
            $currentSettings['sertifikat']['counter'] = intval($request->sertifikat_counter);
        }

        $setting->kejuruan_nomor_settings = json_encode($currentSettings);
        $setting->save();

        $pesan = 'Pengaturan format nomor kejuruan berhasil diperbarui.';
        if ($canAccessTkk && !$canAccessSertifikat) {
            $pesan = 'Pengaturan format nomor Piagam TKK berhasil diperbarui.';
        } elseif (!$canAccessTkk && $canAccessSertifikat) {
            $pesan = 'Pengaturan format nomor Sertifikat Kejuruan berhasil diperbarui.';
        }

        return back()->with('success', $pesan);
    }

    /**
     * Reset nomor urut counter.
     */
    public function resetCounter(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'jenis' => 'required|in:tkk,sertifikat',
            'target_counter' => 'nullable|integer|min:0',
        ]);

        $jenis = $request->jenis;
        if ($jenis === 'tkk' && !Jurusan::userCanAccessModul($user, 'tkk')) {
            abort(403, 'Akses ditolak untuk reset counter TKK.');
        }
        if ($jenis === 'sertifikat' && !($user->hasRole('admin') || Jurusan::userCanAccessModul($user, 'ukk') || Jurusan::userCanAccessModul($user, 'portofolio'))) {
            abort(403, 'Akses ditolak untuk reset counter Sertifikat.');
        }

        $setting = Setting::find(1);
        $currentSettings = $setting->kejuruan_nomor_settings;

        $newCounter = intval($request->input('target_counter', 0));

        $currentSettings[$jenis]['counter'] = $newCounter;
        $currentSettings[$jenis]['last_reset_year'] = date('Y');

        $setting->kejuruan_nomor_settings = json_encode($currentSettings);
        $setting->save();

        $namaTarget = $jenis === 'tkk' ? 'Piagam TKK' : 'Sertifikat UKK';
        return back()->with('success', 'Nomor urut counter ' . $namaTarget . ' berhasil direset menjadi ' . $newCounter . '.');
    }
}
