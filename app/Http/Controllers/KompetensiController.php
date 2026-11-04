<?php

namespace App\Http\Controllers;

use App\Models\CapaianPembelajaran;
use App\Models\Jurusan;
use App\Models\Kompetensi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KompetensiController extends Controller
{
    /**
     * Tampilkan daftar capaian pembelajaran (CP) dan alur tujuan pembelajaran (ATP/TP).
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $jurusans = Jurusan::forUserJurusan($user)
            ->where('aktif', true)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        // Daftar Elemen Capaian Pembelajaran (CP) induk beserta relasi TP anak
        $capaianPembelajarans = CapaianPembelajaran::forUserJurusan($user)
            ->with(['jurusan', 'tujuanPembelajarans.jurusan'])
            ->orderBy('fase', 'asc')
            ->orderBy('kode_elemen', 'asc')
            ->get();

        // Daftar Semua TP / Kompetensi Kejuruan
        $kompetensis = Kompetensi::forUserJurusan($user)
            ->with(['jurusan', 'capaianPembelajaran'])
            ->orderBy('id_jurusan', 'asc')
            ->orderBy('tingkat', 'asc')
            ->orderBy('urutan_atp', 'asc')
            ->orderBy('kode_kompetensi', 'asc')
            ->get();

        return view('kejuruan.kompetensi.index', compact(
            'layout',
            'setting',
            'user',
            'kompetensis',
            'capaianPembelajarans',
            'jurusans'
        ));
    }

    /**
     * Form tambah Tujuan Pembelajaran (TP) / kompetensi baru.
     */
    public function create(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $jurusans = Jurusan::forUserJurusan($user)
            ->where('aktif', true)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        $capaianList = CapaianPembelajaran::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('fase', 'asc')
            ->orderBy('kode_elemen', 'asc')
            ->get();

        $selectedCpId = $request->query('cp_id');

        return view('kejuruan.kompetensi.create', compact('layout', 'setting', 'user', 'jurusans', 'capaianList', 'selectedCpId'));
    }

    /**
     * Simpan data Tujuan Pembelajaran (TP).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'id_cp' => 'nullable|exists:capaian_pembelajaran,id_cp',
            'urutan_atp' => 'nullable|integer|min:1',
            'alokasi_jp' => 'nullable|integer|min:1',
            'kode_kompetensi' => 'required|string|max:50',
            'nama_kompetensi' => 'required|string|max:255',
            'materi_pokok' => 'nullable|string',
            'tingkat' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'kkm' => 'nullable|numeric|min:0|max:100',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_kompetensi.required' => 'Kode kompetensi/TP wajib diisi.',
            'nama_kompetensi.required' => 'Rumusan Tujuan Pembelajaran (TP) wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
            'semester.required' => 'Semester wajib dipilih.',
            'kkm.numeric' => 'Nilai KKTP harus berupa angka.',
        ]);

        Kompetensi::create([
            'id_jurusan' => $request->id_jurusan,
            'id_cp' => $request->filled('id_cp') ? $request->id_cp : null,
            'urutan_atp' => $request->filled('urutan_atp') ? (int) $request->urutan_atp : null,
            'alokasi_jp' => $request->filled('alokasi_jp') ? (int) $request->alokasi_jp : null,
            'materi_pokok' => $request->filled('materi_pokok') ? trim($request->materi_pokok) : null,
            'kode_kompetensi' => strtoupper(trim($request->kode_kompetensi)),
            'nama_kompetensi' => trim($request->nama_kompetensi),
            'tingkat' => trim($request->tingkat),
            'semester' => trim($request->semester),
            'kkm' => $request->filled('kkm') ? $request->kkm : 75.00,
        ]);

        return redirect('/admin/kompetensi')->with('success', 'Tujuan Pembelajaran (TP) berhasil ditambahkan.');
    }

    /**
     * Form edit Tujuan Pembelajaran (TP).
     */
    public function edit(string $id_kompetensi)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);

        $jurusans = Jurusan::forUserJurusan($user)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        $capaianList = CapaianPembelajaran::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('fase', 'asc')
            ->orderBy('kode_elemen', 'asc')
            ->get();

        return view('kejuruan.kompetensi.edit', compact('layout', 'setting', 'user', 'kompetensi', 'jurusans', 'capaianList'));
    }

    /**
     * Perbarui data Tujuan Pembelajaran (TP).
     */
    public function update(Request $request, string $id_kompetensi)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);
        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'id_cp' => 'nullable|exists:capaian_pembelajaran,id_cp',
            'urutan_atp' => 'nullable|integer|min:1',
            'alokasi_jp' => 'nullable|integer|min:1',
            'kode_kompetensi' => 'required|string|max:50',
            'nama_kompetensi' => 'required|string|max:255',
            'materi_pokok' => 'nullable|string',
            'tingkat' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'kkm' => 'nullable|numeric|min:0|max:100',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_kompetensi.required' => 'Kode kompetensi/TP wajib diisi.',
            'nama_kompetensi.required' => 'Rumusan Tujuan Pembelajaran (TP) wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
            'semester.required' => 'Semester wajib dipilih.',
            'kkm.numeric' => 'Nilai KKTP harus berupa angka.',
        ]);

        $kompetensi->update([
            'id_jurusan' => $request->id_jurusan,
            'id_cp' => $request->filled('id_cp') ? $request->id_cp : null,
            'urutan_atp' => $request->filled('urutan_atp') ? (int) $request->urutan_atp : null,
            'alokasi_jp' => $request->filled('alokasi_jp') ? (int) $request->alokasi_jp : null,
            'materi_pokok' => $request->filled('materi_pokok') ? trim($request->materi_pokok) : null,
            'kode_kompetensi' => strtoupper(trim($request->kode_kompetensi)),
            'nama_kompetensi' => trim($request->nama_kompetensi),
            'tingkat' => trim($request->tingkat),
            'semester' => trim($request->semester),
            'kkm' => $request->filled('kkm') ? $request->kkm : 75.00,
        ]);

        return redirect('/admin/kompetensi')->with('success', 'Tujuan Pembelajaran (TP) berhasil diperbarui.');
    }

    /**
     * Hapus data Tujuan Pembelajaran (TP).
     */
    public function destroy(string $id_kompetensi)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);
        $kompetensi->delete();

        return redirect('/admin/kompetensi')->with('success', 'Tujuan Pembelajaran (TP) berhasil dihapus.');
    }

    // =========================================================================
    // CRUD INDUK ELEMEN CAPAIAN PEMBELAJARAN (CP)
    // =========================================================================

    /**
     * Simpan Elemen Capaian Pembelajaran (CP) baru.
     */
    public function storeCp(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kode_elemen' => 'required|string|max:50',
            'nama_elemen' => 'required|string|max:255',
            'fase' => 'required|in:E,F',
            'deskripsi_cp' => 'nullable|string',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_elemen.required' => 'Kode elemen CP wajib diisi.',
            'nama_elemen.required' => 'Nama elemen CP wajib diisi.',
            'fase.required' => 'Fase wajib dipilih (Fase E atau Fase F).',
        ]);

        CapaianPembelajaran::create([
            'id_jurusan' => $request->id_jurusan,
            'kode_elemen' => strtoupper(trim($request->kode_elemen)),
            'nama_elemen' => trim($request->nama_elemen),
            'fase' => $request->fase,
            'deskripsi_cp' => $request->filled('deskripsi_cp') ? trim($request->deskripsi_cp) : null,
        ]);

        return redirect('/admin/kompetensi?tab=cp')->with('success', 'Elemen Capaian Pembelajaran (CP) berhasil ditambahkan.');
    }

    /**
     * Update Elemen Capaian Pembelajaran (CP).
     */
    public function updateCp(Request $request, string $id_cp)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $cp = CapaianPembelajaran::forUserJurusan($user)->findOrFail($id_cp);
        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kode_elemen' => 'required|string|max:50',
            'nama_elemen' => 'required|string|max:255',
            'fase' => 'required|in:E,F',
            'deskripsi_cp' => 'nullable|string',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_elemen.required' => 'Kode elemen CP wajib diisi.',
            'nama_elemen.required' => 'Nama elemen CP wajib diisi.',
            'fase.required' => 'Fase wajib dipilih (Fase E atau Fase F).',
        ]);

        $cp->update([
            'id_jurusan' => $request->id_jurusan,
            'kode_elemen' => strtoupper(trim($request->kode_elemen)),
            'nama_elemen' => trim($request->nama_elemen),
            'fase' => $request->fase,
            'deskripsi_cp' => $request->filled('deskripsi_cp') ? trim($request->deskripsi_cp) : null,
        ]);

        return redirect('/admin/kompetensi?tab=cp')->with('success', 'Elemen Capaian Pembelajaran (CP) berhasil diperbarui.');
    }

    /**
     * Hapus Elemen Capaian Pembelajaran (CP).
     */
    public function destroyCp(string $id_cp)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $cp = CapaianPembelajaran::forUserJurusan($user)->findOrFail($id_cp);

        // Lepas referensi TP anak agar TP tetap aman
        Kompetensi::where('id_cp', $cp->id_cp)->update(['id_cp' => null]);

        $cp->delete();

        return redirect('/admin/kompetensi?tab=cp')->with('success', 'Elemen Capaian Pembelajaran (CP) berhasil dihapus. TP yang terkait telah dialihkan menjadi mandiri.');
    }
}
