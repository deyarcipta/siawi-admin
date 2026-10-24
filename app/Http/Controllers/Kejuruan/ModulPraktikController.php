<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\Kompetensi;
use App\Models\ModulPraktik;
use App\Models\ModulPraktikVersi;
use App\Models\RubrikPenilaian;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModulPraktikController extends Controller
{
    /**
     * Tampilkan daftar Bank Modul Praktik Kejuruan.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = ModulPraktik::forUserJurusan($user)
            ->with(['kompetensi.jurusan', 'guru', 'rubriks'])
            ->orderBy('id_modul_praktik', 'desc');

        if ($request->filled('kompetensi_id')) {
            $query->where('id_kompetensi', $request->kompetensi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('kode_modul', 'like', "%{$keyword}%")
                    ->orWhere('tujuan', 'like', "%{$keyword}%");
            });
        }

        $moduls = $query->paginate(12)->withQueryString();

        $kompetensis = Kompetensi::forUserJurusan($user)
            ->orderBy('kode_kompetensi', 'asc')
            ->get();

        return view('kejuruan.modul_praktik.index', compact(
            'layout', 'setting', 'user', 'moduls', 'kompetensis'
        ));
    }

    /**
     * Form tambah modul praktik baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan', 'guru'])) {
            abort(403, 'Akses terbatas untuk pembuatan modul praktik.');
        }

        $kompetensis = Kompetensi::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('kode_kompetensi', 'asc')
            ->get();

        return view('kejuruan.modul_praktik.create', compact(
            'layout', 'setting', 'user', 'kompetensis'
        ));
    }

    /**
     * Simpan modul praktik baru beserta rubrik indikator penilaian awal.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_kompetensi' => 'required|exists:kompetensi,id_kompetensi',
            'judul' => 'required|string|max:255',
            'tujuan' => 'required|string',
            'alat_bahan' => 'required|string',
            'langkah_kerja' => 'required|string',
            'status' => 'required|in:draft,terbit',
            'file_lembar_kerja' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'rubrik_indikator' => 'nullable|array',
            'rubrik_bobot' => 'nullable|array',
        ], [
            'id_kompetensi.required' => 'Kompetensi kejuruan wajib dipilih.',
            'judul.required' => 'Judul modul praktik wajib diisi.',
            'tujuan.required' => 'Tujuan praktik wajib diisi.',
            'alat_bahan.required' => 'Daftar alat dan bahan wajib diisi.',
            'langkah_kerja.required' => 'Langkah kerja wajib diisi.',
            'file_lembar_kerja.mimes' => 'Lampiran lembar kerja harus berupa PDF atau gambar (JPG/PNG).',
            'file_lembar_kerja.max' => 'Ukuran berkas lembar kerja maksimal 10MB.',
        ]);

        DB::beginTransaction();
        try {
            // Upload file lembar kerja jika ada
            $fileName = null;
            if ($request->hasFile('file_lembar_kerja')) {
                $file = $request->file('file_lembar_kerja');
                $ext = $file->getClientOriginalExtension();
                $fileName = 'jobsheet_' . time() . '_' . Str::random(8) . '.' . $ext;
                $file->storeAs('modul_praktik', $fileName, 'public');
            }

            // Generate kode modul unik
            $kompetensi = Kompetensi::with('jurusan')->findOrFail($request->id_kompetensi);
            $kodeJurusan = $kompetensi->jurusan ? strtoupper($kompetensi->jurusan->kode_jurusan) : 'KJ';
            $countExisting = ModulPraktik::where('id_kompetensi', $request->id_kompetensi)->count() + 1;
            $kodeModul = 'MOD-' . $kodeJurusan . '-' . str_pad($countExisting, 3, '0', STR_PAD_LEFT);

            // Jika kode sudah terpakai, tambahkan suffix unik
            if (ModulPraktik::where('kode_modul', $kodeModul)->exists()) {
                $kodeModul .= '-' . strtoupper(Str::random(3));
            }

            $modul = ModulPraktik::create([
                'id_kompetensi' => $request->id_kompetensi,
                'id_guru' => $user->id_guru,
                'kode_modul' => $kodeModul,
                'judul' => $request->judul,
                'tujuan' => $request->tujuan,
                'alat_bahan' => $request->alat_bahan,
                'langkah_kerja' => $request->langkah_kerja,
                'file_lembar_kerja' => $fileName,
                'versi' => '1.0',
                'status' => $request->status,
            ]);

            // Catat ke riwayat versi perdana (1.0)
            ModulPraktikVersi::create([
                'id_modul_praktik' => $modul->id_modul_praktik,
                'versi' => '1.0',
                'judul' => $modul->judul,
                'tujuan' => $modul->tujuan,
                'alat_bahan' => $modul->alat_bahan,
                'langkah_kerja' => $modul->langkah_kerja,
                'file_lembar_kerja' => $modul->file_lembar_kerja,
                'catatan_revisi' => 'Pembuatan modul praktik versi perdana (1.0)',
                'diubah_oleh' => $user->id_guru,
            ]);

            // Simpan rubrik indikator penilaian jika diisi
            if ($request->has('rubrik_indikator') && is_array($request->rubrik_indikator)) {
                foreach ($request->rubrik_indikator as $idx => $namaIndikator) {
                    $nama = trim($namaIndikator);
                    if ($nama !== '') {
                        $bobot = isset($request->rubrik_bobot[$idx]) ? floatval($request->rubrik_bobot[$idx]) : 100;
                        RubrikPenilaian::create([
                            'id_modul_praktik' => $modul->id_modul_praktik,
                            'id_kompetensi' => $modul->id_kompetensi,
                            'nama_indikator' => $nama,
                            'deskripsi' => $request->rubrik_deskripsi[$idx] ?? null,
                            'bobot' => $bobot,
                            'urutan' => $idx + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect('/admin/kejuruan/modul-praktik/' . $modul->id_modul_praktik)
                ->with('success', "Modul praktik '{$modul->judul}' ({$modul->kode_modul}) berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan modul: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail modul praktik, lembar kerja, dan riwayat versinya.
     */
    public function show(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $modul = ModulPraktik::forUserJurusan($user)
            ->with(['kompetensi.jurusan', 'guru', 'rubriks', 'versis.pengubah'])
            ->findOrFail($id);

        return view('kejuruan.modul_praktik.show', compact(
            'layout', 'setting', 'user', 'modul'
        ));
    }

    /**
     * Form edit modul praktik.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $modul = ModulPraktik::forUserJurusan($user)
            ->with(['rubriks'])
            ->findOrFail($id);

        // Hanya pembuat, kaprog jurusannya, atau admin yang boleh mengedit
        if (!$user->hasRole('admin') && !$user->hasRole('kaprog') && $modul->id_guru != $user->id_guru) {
            abort(403, 'Anda tidak memiliki hak akses mengubah modul praktik ini.');
        }

        $kompetensis = Kompetensi::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('kode_kompetensi', 'asc')
            ->get();

        return view('kejuruan.modul_praktik.edit', compact(
            'layout', 'setting', 'user', 'modul', 'kompetensis'
        ));
    }

    /**
     * Perbarui modul praktik dengan opsi bump versi riwayat revisi.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $modul = ModulPraktik::forUserJurusan($user)->findOrFail($id);

        if (!$user->hasRole('admin') && !$user->hasRole('kaprog') && $modul->id_guru != $user->id_guru) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'id_kompetensi' => 'required|exists:kompetensi,id_kompetensi',
            'judul' => 'required|string|max:255',
            'tujuan' => 'required|string',
            'alat_bahan' => 'required|string',
            'langkah_kerja' => 'required|string',
            'status' => 'required|in:draft,terbit,arsip',
            'is_new_version' => 'nullable|boolean',
            'catatan_revisi' => 'nullable|string',
            'file_lembar_kerja' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $newFileName = $modul->file_lembar_kerja;
            if ($request->hasFile('file_lembar_kerja')) {
                // Hapus berkas lama jika ada
                if ($modul->file_lembar_kerja && Storage::disk('public')->exists('modul_praktik/' . $modul->file_lembar_kerja)) {
                    Storage::disk('public')->delete('modul_praktik/' . $modul->file_lembar_kerja);
                }

                $file = $request->file('file_lembar_kerja');
                $ext = $file->getClientOriginalExtension();
                $newFileName = 'jobsheet_' . time() . '_' . Str::random(8) . '.' . $ext;
                $file->storeAs('modul_praktik', $newFileName, 'public');
            }

            // Tentukan nomor versi baru jika opsi revisi dicentang
            $versiBaru = $modul->versi;
            if ($request->boolean('is_new_version')) {
                // Kenaikan versi otomatis minor (misal 1.0 -> 1.1)
                $currentFloat = floatval($modul->versi ?: '1.0');
                $versiBaru = number_format($currentFloat + 0.1, 1, '.', '');
            }

            $modul->update([
                'id_kompetensi' => $request->id_kompetensi,
                'judul' => $request->judul,
                'tujuan' => $request->tujuan,
                'alat_bahan' => $request->alat_bahan,
                'langkah_kerja' => $request->langkah_kerja,
                'file_lembar_kerja' => $newFileName,
                'versi' => $versiBaru,
                'status' => $request->status,
            ]);

            // Jika versi baru dibuat, simpan ke riwayat versi
            if ($request->boolean('is_new_version') || $request->filled('catatan_revisi')) {
                ModulPraktikVersi::create([
                    'id_modul_praktik' => $modul->id_modul_praktik,
                    'versi' => $versiBaru,
                    'judul' => $modul->judul,
                    'tujuan' => $modul->tujuan,
                    'alat_bahan' => $modul->alat_bahan,
                    'langkah_kerja' => $modul->langkah_kerja,
                    'file_lembar_kerja' => $modul->file_lembar_kerja,
                    'catatan_revisi' => $request->catatan_revisi ?: 'Pembaruan materi modul versi ' . $versiBaru,
                    'diubah_oleh' => $user->id_guru,
                ]);
            }

            // Perbarui rubrik indikator
            if ($request->has('rubrik_indikator') && is_array($request->rubrik_indikator)) {
                // Hapus rubrik lama lalu masukkan yang baru
                RubrikPenilaian::where('id_modul_praktik', $modul->id_modul_praktik)->delete();

                foreach ($request->rubrik_indikator as $idx => $namaIndikator) {
                    $nama = trim($namaIndikator);
                    if ($nama !== '') {
                        $bobot = isset($request->rubrik_bobot[$idx]) ? floatval($request->rubrik_bobot[$idx]) : 100;
                        RubrikPenilaian::create([
                            'id_modul_praktik' => $modul->id_modul_praktik,
                            'id_kompetensi' => $modul->id_kompetensi,
                            'nama_indikator' => $nama,
                            'deskripsi' => $request->rubrik_deskripsi[$idx] ?? null,
                            'bobot' => $bobot,
                            'urutan' => $idx + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect('/admin/kejuruan/modul-praktik/' . $modul->id_modul_praktik)
                ->with('success', "Modul praktik berhasil diperbarui ke versi {$versiBaru}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui modul: ' . $e->getMessage());
        }
    }

    /**
     * Hapus modul praktik beserta riwayat dan berkas lembar kerjanya.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $modul = ModulPraktik::forUserJurusan($user)->findOrFail($id);

        if (!$user->hasRole('admin') && !$user->hasRole('kaprog') && $modul->id_guru != $user->id_guru) {
            abort(403, 'Akses ditolak.');
        }

        if ($modul->file_lembar_kerja && Storage::disk('public')->exists('modul_praktik/' . $modul->file_lembar_kerja)) {
            Storage::disk('public')->delete('modul_praktik/' . $modul->file_lembar_kerja);
        }

        $modul->delete();

        return redirect('/admin/kejuruan/modul-praktik')->with('success', 'Modul praktik berhasil dihapus.');
    }
}
