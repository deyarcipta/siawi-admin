<?php

namespace App\Http\Controllers\Kejuruan;

use App\Exports\InventarisLabExport;
use App\Http\Controllers\Controller;
use App\Models\AsetLab;
use App\Models\Guru;
use App\Models\Lab;
use App\Models\PemakaianBahanLab;
use App\Models\PeminjamanAset;
use App\Models\PerawatanAsetLab;
use App\Models\Setting;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InventarisLabController extends Controller
{
    /**
     * Dashboard utama Inventaris Lab (Katalog Aset & Monitoring Stok).
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Kaprog dan Admin.');
        }

        $tab = $request->input('tab', 'aset'); // 'aset', 'peminjaman', 'bahan', 'perawatan'

        $labs = Lab::forUserJurusan($user)->with('jurusan')->orderBy('nama_lab', 'asc')->get();

        // 1. Tab Aset
        $asetQuery = AsetLab::forUserJurusan($user)
            ->with(['lab.jurusan'])
            ->orderBy('id_aset', 'desc');

        if ($request->filled('lab_id')) {
            $asetQuery->where('id_lab', $request->lab_id);
        }
        if ($request->filled('kategori')) {
            $asetQuery->where('kategori', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $asetQuery->where('kondisi', $request->kondisi);
        }
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $asetQuery->where(function ($q) use ($keyword) {
                $q->where('nama_aset', 'like', "%{$keyword}%")
                    ->orWhere('kode_aset', 'like', "%{$keyword}%")
                    ->orWhere('spesifikasi', 'like', "%{$keyword}%");
            });
        }
        $asets = $asetQuery->get();

        // 2. Tab Peminjaman
        $peminjamanQuery = PeminjamanAset::forUserJurusan($user)
            ->with(['aset.lab', 'peminjamGuru', 'peminjamSiswa', 'petugas'])
            ->orderBy('id_peminjaman', 'desc');
        if ($request->filled('status_pinjam')) {
            $peminjamanQuery->where('status', $request->status_pinjam);
        }
        $peminjamans = $peminjamanQuery->get();

        // 3. Tab Bahan Habis Pakai & Peringatan Stok Menipis
        $bahanMenipis = AsetLab::forUserJurusan($user)
            ->stokMenipis()
            ->with('lab')
            ->get();

        $pemakaians = PemakaianBahanLab::forUserJurusan($user)
            ->with(['aset.lab', 'penggunaGuru', 'petugas'])
            ->orderBy('id_pemakaian', 'desc')
            ->get();

        // 4. Tab Perawatan & Kerusakan
        $perawatans = PerawatanAsetLab::forUserJurusan($user)
            ->with(['aset.lab', 'pelapor'])
            ->orderBy('id_perawatan', 'desc')
            ->get();

        // Statistik Ringkasan
        $totalAlat = AsetLab::forUserJurusan($user)->alat()->count();
        $totalBahan = AsetLab::forUserJurusan($user)->bahan()->count();
        $totalPinjamAktif = PeminjamanAset::forUserJurusan($user)->aktif()->count();
        $totalPerawatanAktif = PerawatanAsetLab::forUserJurusan($user)->aktif()->count();

        // Data untuk Modal Peminjaman & Pemakaian
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();
        $siswas = Siswa::forUserJurusan($user)->with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $asetAlatList = AsetLab::forUserJurusan($user)->alat()->where('stok_tersedia', '>', 0)->orderBy('nama_aset', 'asc')->get();
        $asetBahanList = AsetLab::forUserJurusan($user)->bahan()->where('stok_tersedia', '>', 0)->orderBy('nama_aset', 'asc')->get();
        $asetAllList = AsetLab::forUserJurusan($user)->orderBy('nama_aset', 'asc')->get();

        return view('kejuruan.inventaris_lab.index', compact(
            'layout', 'setting', 'user', 'tab', 'labs', 'asets', 'peminjamans',
            'bahanMenipis', 'pemakaians', 'perawatans', 'totalAlat', 'totalBahan',
            'totalPinjamAktif', 'totalPerawatanAktif', 'gurus', 'siswas',
            'asetAlatList', 'asetBahanList', 'asetAllList'
        ));
    }

    /**
     * Form tambah aset / bahan laboratorium baru.
     */
    public function createAset(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Kaprog dan Admin.');
        }

        $labs = Lab::forUserJurusan($user)->with('jurusan')->orderBy('nama_lab', 'asc')->get();

        return view('kejuruan.inventaris_lab.aset_create', compact(
            'layout', 'setting', 'user', 'labs'
        ));
    }

    /**
     * Simpan aset baru ke database.
     */
    public function storeAset(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas.');
        }

        $request->validate([
            'id_lab' => 'required|exists:lab,id_lab',
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|in:alat,bahan_habis_pakai',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'tahun_perolehan' => 'required|digits:4',
            'stok_minimum' => 'nullable|integer|min:0',
            'sumber_dana' => 'nullable|string|max:100',
            'harga_satuan' => 'nullable|numeric|min:0',
            'spesifikasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'id_lab.required' => 'Laboratorium wajib dipilih.',
            'nama_aset.required' => 'Nama aset wajib diisi.',
            'jumlah.required' => 'Jumlah aset wajib diisi minimal 1.',
            'satuan.required' => 'Satuan aset wajib diisi.',
            'tahun_perolehan.required' => 'Tahun perolehan wajib diisi (4 digit).',
        ]);

        $lab = Lab::with('jurusan')->findOrFail($request->id_lab);

        // Buat kode aset otomatis
        $prefixLab = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $lab->kode_lab ?: ('LAB' . $lab->id_lab)));
        $categoryPrefix = ($request->kategori === 'alat') ? 'ALT' : 'BHN';
        $count = AsetLab::where('id_lab', $lab->id_lab)->count() + 1;
        $kodeAset = $categoryPrefix . '-' . $prefixLab . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        if (AsetLab::where('kode_aset', $kodeAset)->exists()) {
            $kodeAset .= '-' . strtoupper(Str::random(3));
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('aset_lab', 'public');
        }

        $jumlah = intval($request->jumlah);
        $stokMinimum = $request->filled('stok_minimum') ? intval($request->stok_minimum) : 5;

        $aset = AsetLab::create([
            'id_lab' => $request->id_lab,
            'kode_aset' => $kodeAset,
            'nama_aset' => trim($request->nama_aset),
            'kategori' => $request->kategori,
            'spesifikasi' => $request->spesifikasi ? trim($request->spesifikasi) : null,
            'jumlah' => $jumlah,
            'stok_tersedia' => $jumlah,
            'stok_minimum' => $stokMinimum,
            'satuan' => trim($request->satuan),
            'kondisi' => $request->kondisi,
            'tahun_perolehan' => $request->tahun_perolehan,
            'sumber_dana' => $request->sumber_dana ? trim($request->sumber_dana) : null,
            'harga_satuan' => $request->filled('harga_satuan') ? floatval($request->harga_satuan) : 0.00,
            'foto' => $fotoPath,
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
        ]);

        return redirect('/admin/kejuruan/inventaris-lab/aset/' . $aset->id_aset)
            ->with('success', "Aset '{$aset->nama_aset}' ({$aset->kode_aset}) berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail aset, riwayat peminjaman, pemakaian, dan QR Code.
     */
    public function showAset(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $aset = AsetLab::forUserJurusan($user)
            ->with([
                'lab.jurusan',
                'peminjamans.peminjamGuru',
                'peminjamans.peminjamSiswa',
                'pemakaians.penggunaGuru',
                'perawatans.pelapor'
            ])
            ->findOrFail($id);

        return view('kejuruan.inventaris_lab.aset_show', compact(
            'layout', 'setting', 'user', 'aset'
        ));
    }

    /**
     * Form edit aset.
     */
    public function editAset(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $aset = AsetLab::forUserJurusan($user)->findOrFail($id);
        $labs = Lab::forUserJurusan($user)->with('jurusan')->orderBy('nama_lab', 'asc')->get();

        return view('kejuruan.inventaris_lab.aset_edit', compact(
            'layout', 'setting', 'user', 'aset', 'labs'
        ));
    }

    /**
     * Perbarui data aset.
     */
    public function updateAset(Request $request, string $id)
    {
        $user = Auth::user();
        $aset = AsetLab::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'id_lab' => 'required|exists:lab,id_lab',
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|in:alat,bahan_habis_pakai',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'tahun_perolehan' => 'required|digits:4',
            'stok_minimum' => 'nullable|integer|min:0',
            'sumber_dana' => 'nullable|string|max:100',
            'harga_satuan' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $fotoPath = $aset->foto;
        if ($request->hasFile('foto')) {
            if ($aset->foto && Storage::disk('public')->exists($aset->foto)) {
                Storage::disk('public')->delete($aset->foto);
            }
            $fotoPath = $request->file('foto')->store('aset_lab', 'public');
        }

        // Hitung selisih jumlah jika ada perubahan stok total
        $jumlahLama = $aset->jumlah;
        $jumlahBaru = intval($request->jumlah ?: $aset->jumlah);
        $selisih = $jumlahBaru - $jumlahLama;
        $stokTersediaBaru = max(0, $aset->stok_tersedia + $selisih);

        $aset->update([
            'id_lab' => $request->id_lab,
            'nama_aset' => trim($request->nama_aset),
            'kategori' => $request->kategori,
            'spesifikasi' => $request->spesifikasi ? trim($request->spesifikasi) : null,
            'jumlah' => $jumlahBaru,
            'stok_tersedia' => $stokTersediaBaru,
            'stok_minimum' => $request->filled('stok_minimum') ? intval($request->stok_minimum) : $aset->stok_minimum,
            'satuan' => trim($request->satuan),
            'kondisi' => $request->kondisi,
            'tahun_perolehan' => $request->tahun_perolehan,
            'sumber_dana' => $request->sumber_dana ? trim($request->sumber_dana) : null,
            'harga_satuan' => $request->filled('harga_satuan') ? floatval($request->harga_satuan) : 0.00,
            'foto' => $fotoPath,
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
        ]);

        return redirect('/admin/kejuruan/inventaris-lab/aset/' . $aset->id_aset)
            ->with('success', "Aset '{$aset->nama_aset}' berhasil diperbarui.");
    }

    /**
     * Hapus aset.
     */
    public function destroyAset(string $id)
    {
        $user = Auth::user();
        $aset = AsetLab::forUserJurusan($user)->findOrFail($id);

        if ($aset->foto && Storage::disk('public')->exists($aset->foto)) {
            Storage::disk('public')->delete($aset->foto);
        }

        $aset->delete();

        return redirect('/admin/kejuruan/inventaris-lab?tab=aset')
            ->with('success', 'Aset laboratorium berhasil dihapus.');
    }

    /**
     * Cetak Label Stiker QR Code untuk Aset (single atau bulk per lab).
     */
    public function cetakQr(Request $request)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        $asetId = $request->input('aset_id');
        $labId = $request->input('lab_id');

        $query = AsetLab::forUserJurusan($user)->with('lab.jurusan');

        if ($asetId) {
            $query->where('id_aset', $asetId);
        } elseif ($labId) {
            $query->where('id_lab', $labId);
        }

        $asets = $query->orderBy('kode_aset', 'asc')->get();

        if ($asets->isEmpty()) {
            return back()->with('error', 'Tidak ada aset yang dipilih untuk dicetak QR Code.');
        }

        return view('kejuruan.inventaris_lab.cetak_qr', compact('setting', 'user', 'asets'));
    }

    /**
     * Form & Proses Peminjaman Alat.
     */
    public function storePeminjaman(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_aset' => 'required|exists:aset_lab,id_aset',
            'tipe_peminjam' => 'required|in:guru,siswa',
            'id_peminjam_guru' => 'nullable|required_if:tipe_peminjam,guru|exists:guru,id_guru',
            'id_peminjam_siswa' => 'nullable|required_if:tipe_peminjam,siswa|exists:siswa,id_siswa',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'nullable|string',
        ], [
            'id_aset.required' => 'Pilih aset yang akan dipinjam.',
            'jumlah.required' => 'Jumlah peminjaman wajib diisi.',
            'rencana_kembali.after_or_equal' => 'Rencana kembali harus sama atau setelah tanggal pinjam.',
        ]);

        $aset = AsetLab::forUserJurusan($user)->findOrFail($request->id_aset);

        // Validasi stok alat mencukupi
        if ($aset->stok_tersedia < intval($request->jumlah)) {
            return back()->withInput()->with('error', "Stok tersedia tidak mencukupi. Sisa stok di lab: {$aset->stok_tersedia} {$aset->satuan}.");
        }

        DB::beginTransaction();
        try {
            // Generate kode peminjaman
            $kodePeminjaman = 'PJM-' . date('Ym') . '-' . strtoupper(Str::random(5));

            $peminjaman = PeminjamanAset::create([
                'kode_peminjaman' => $kodePeminjaman,
                'id_aset' => $aset->id_aset,
                'tipe_peminjam' => $request->tipe_peminjam,
                'id_peminjam_guru' => $request->tipe_peminjam === 'guru' ? $request->id_peminjam_guru : null,
                'id_peminjam_siswa' => $request->tipe_peminjam === 'siswa' ? $request->id_peminjam_siswa : null,
                'jumlah' => intval($request->jumlah),
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'rencana_kembali' => $request->rencana_kembali,
                'status' => 'dipinjam',
                'id_petugas' => $user->id_guru,
                'keperluan' => $request->keperluan ? trim($request->keperluan) : null,
            ]);

            // Kurangi stok tersedia
            $aset->decrement('stok_tersedia', intval($request->jumlah));

            DB::commit();

            return redirect('/admin/kejuruan/inventaris-lab?tab=peminjaman')
                ->with('success', "Peminjaman alat '{$aset->nama_aset}' sebanyak {$request->jumlah} {$aset->satuan} berhasil dicatat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Proses Pengembalian Alat (Stok Kembali Otomatis).
     */
    public function kembalikanPeminjaman(Request $request, string $id)
    {
        $user = Auth::user();
        $peminjaman = PeminjamanAset::forUserJurusan($user)->with('aset')->findOrFail($id);

        if ($peminjaman->status === 'kembali') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        $request->validate([
            'kondisi_sesudah' => 'required|in:baik,rusak_ringan,rusak_berat',
            'tanggal_kembali' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'status' => 'kembali',
                'tanggal_kembali' => $request->tanggal_kembali,
                'kondisi_sesudah' => $request->kondisi_sesudah,
                'catatan' => $request->catatan ? trim($request->catatan) : $peminjaman->catatan,
            ]);

            // Tambahkan kembali stok tersedia
            $peminjaman->aset->increment('stok_tersedia', $peminjaman->jumlah);

            // Jika barang dikembalikan dalam kondisi rusak berat, perbarui kondisi aset induk jika perlu
            if ($request->kondisi_sesudah === 'rusak_berat') {
                $peminjaman->aset->update(['kondisi' => 'rusak_ringan']);
            }

            DB::commit();

            return redirect('/admin/kejuruan/inventaris-lab?tab=peminjaman')
                ->with('success', "Pengembalian alat '{$peminjaman->aset->nama_aset}' berhasil diproses. Stok telah dikembalikan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Pencatatan Pemakaian Bahan Habis Pakai (Stok Berkurang Otomatis).
     */
    public function storePemakaianBahan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_aset' => 'required|exists:aset_lab,id_aset',
            'jumlah_pakai' => 'required|integer|min:1',
            'tanggal_pakai' => 'required|date',
            'keperluan' => 'required|string|max:255',
            'id_pengguna_guru' => 'nullable|exists:guru,id_guru',
            'catatan' => 'nullable|string',
        ], [
            'id_aset.required' => 'Pilih bahan habis pakai.',
            'jumlah_pakai.required' => 'Jumlah pemakaian wajib diisi.',
            'keperluan.required' => 'Keperluan penggunaan bahan wajib diisi.',
        ]);

        $aset = AsetLab::forUserJurusan($user)->findOrFail($request->id_aset);

        if ($aset->kategori !== 'bahan_habis_pakai') {
            return back()->with('error', 'Aset yang dipilih bukan kategori bahan habis pakai.');
        }

        if ($aset->stok_tersedia < intval($request->jumlah_pakai)) {
            return back()->withInput()->with('error', "Stok bahan tidak mencukupi. Sisa stok: {$aset->stok_tersedia} {$aset->satuan}.");
        }

        DB::beginTransaction();
        try {
            $sisaStok = $aset->stok_tersedia - intval($request->jumlah_pakai);

            PemakaianBahanLab::create([
                'id_aset' => $aset->id_aset,
                'jumlah_pakai' => intval($request->jumlah_pakai),
                'sisa_stok_saat_itu' => $sisaStok,
                'tanggal_pakai' => $request->tanggal_pakai,
                'keperluan' => trim($request->keperluan),
                'id_pengguna_guru' => $request->id_pengguna_guru,
                'id_petugas' => $user->id_guru,
                'catatan' => $request->catatan ? trim($request->catatan) : null,
            ]);

            // Potong stok tersedia permanen
            $aset->decrement('stok_tersedia', intval($request->jumlah_pakai));

            DB::commit();

            return redirect('/admin/kejuruan/inventaris-lab?tab=bahan')
                ->with('success', "Pemakaian bahan '{$aset->nama_aset}' sebanyak {$request->jumlah_pakai} {$aset->satuan} berhasil dicatat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat pemakaian bahan: ' . $e->getMessage());
        }
    }

    /**
     * Catat Laporan Kerusakan & Jadwal Perawatan Aset.
     */
    public function storePerawatan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_aset' => 'required|exists:aset_lab,id_aset',
            'jenis' => 'required|in:perawatan_rutin,perbaikan,kalibrasi',
            'tanggal_jadwal' => 'required|date',
            'deskripsi_kendala' => 'required|string',
            'teknisi' => 'nullable|string|max:150',
            'biaya' => 'nullable|numeric|min:0',
            'foto_sebelum' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $fotoSebelum = null;
        if ($request->hasFile('foto_sebelum')) {
            $fotoSebelum = $request->file('foto_sebelum')->store('perawatan_aset', 'public');
        }

        $perawatan = PerawatanAsetLab::create([
            'id_aset' => $request->id_aset,
            'jenis' => $request->jenis,
            'tanggal_jadwal' => $request->tanggal_jadwal,
            'deskripsi_kendala' => trim($request->deskripsi_kendala),
            'teknisi' => $request->teknisi ? trim($request->teknisi) : null,
            'biaya' => $request->filled('biaya') ? floatval($request->biaya) : 0.00,
            'status' => 'terjadwal',
            'id_pelapor' => $user->id_guru,
            'foto_sebelum' => $fotoSebelum,
        ]);

        return redirect('/admin/kejuruan/inventaris-lab?tab=perawatan')
            ->with('success', 'Jadwal perawatan / laporan kerusakan aset berhasil dicatat.');
    }

    /**
     * Selesaikan proses servis / perawatan.
     */
    public function updatePerawatan(Request $request, string $id)
    {
        $user = Auth::user();
        $perawatan = PerawatanAsetLab::forUserJurusan($user)->with('aset')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:dalam_pengerjaan,selesai,tidak_dapat_diperbaiki',
            'tindakan_perbaikan' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'kondisi_akhir' => 'required_if:status,selesai|in:baik,rusak_ringan,rusak_berat',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $updateData = [
            'status' => $request->status,
            'tindakan_perbaikan' => $request->tindakan_perbaikan ? trim($request->tindakan_perbaikan) : $perawatan->tindakan_perbaikan,
            'biaya' => $request->filled('biaya') ? floatval($request->biaya) : $perawatan->biaya,
            'kondisi_akhir' => $request->kondisi_akhir,
            'tanggal_selesai' => ($request->status === 'selesai') ? ($request->tanggal_selesai ?: date('Y-m-d')) : null,
        ];

        DB::beginTransaction();
        try {
            $perawatan->update($updateData);

            if ($request->status === 'selesai' && $request->filled('kondisi_akhir')) {
                $perawatan->aset->update(['kondisi' => $request->kondisi_akhir]);
            } elseif ($request->status === 'tidak_dapat_diperbaiki') {
                $perawatan->aset->update(['kondisi' => 'rusak_berat']);
            }

            DB::commit();

            return redirect('/admin/kejuruan/inventaris-lab?tab=perawatan')
                ->with('success', 'Status perawatan aset berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status perawatan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Rekapitulasi Laporan Inventaris per Lab.
     */
    public function laporan(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Kaprog dan Admin.');
        }

        $labs = Lab::forUserJurusan($user)->with('jurusan')->orderBy('nama_lab', 'asc')->get();
        $selectedLabId = $request->input('lab_id', $labs->first()->id_lab ?? null);
        $selectedLab = $labs->firstWhere('id_lab', $selectedLabId);

        $asets = collect();
        if ($selectedLab) {
            $asets = AsetLab::where('id_lab', $selectedLab->id_lab)
                ->orderBy('kategori', 'asc')
                ->orderBy('nama_aset', 'asc')
                ->get();
        }

        $totalNilaiAset = $asets->sum(function ($item) {
            return $item->jumlah * $item->harga_satuan;
        });

        return view('kejuruan.inventaris_lab.laporan', compact(
            'layout', 'setting', 'user', 'labs', 'selectedLab', 'asets', 'totalNilaiAset'
        ));
    }

    /**
     * Ekspor Laporan Inventaris Lab ke PDF Resmi dengan Kop Surat.
     */
    public function exportPdf(Request $request)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas.');
        }

        $labId = $request->input('lab_id');
        $lab = Lab::forUserJurusan($user)->with('jurusan')->findOrFail($labId);

        $asets = AsetLab::where('id_lab', $lab->id_lab)
            ->orderBy('kategori', 'asc')
            ->orderBy('nama_aset', 'asc')
            ->get();

        $totalNilaiAset = $asets->sum(function ($item) {
            return $item->jumlah * $item->harga_satuan;
        });

        $pdf = Pdf::loadView('kejuruan.inventaris_lab.pdf', [
            'setting' => $setting,
            'user' => $user,
            'lab' => $lab,
            'asets' => $asets,
            'totalNilaiAset' => $totalNilaiAset,
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
        ]);

        $pdf->setPaper('a4', 'landscape');
        $filename = 'Laporan_Inventaris_' . Str::slug($lab->nama_lab) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Ekspor Laporan Inventaris Lab ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas.');
        }

        $labId = $request->input('lab_id');
        $lab = Lab::forUserJurusan($user)->with('jurusan')->findOrFail($labId);

        $asets = AsetLab::where('id_lab', $lab->id_lab)
            ->orderBy('kategori', 'asc')
            ->orderBy('nama_aset', 'asc')
            ->get();

        $filename = 'Inventaris_' . Str::slug($lab->nama_lab) . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new InventarisLabExport($asets, $lab, $setting, $user), $filename);
    }
}
