<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\JurnalMengajar;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\JadwalMapel;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalMengajarController extends Controller
{
    public function index(Request $request)
{
    $kelas   = Kelas::orderBy('nama_kelas', 'asc')->get();
    $guru    = Guru::orderBy('nama_guru', 'asc')->get();
    $layout  = 'layout.app';
    $setting = Setting::find(1);
    $user    = Auth::user();

    $query = JurnalMengajar::with(['guru','kelas']);

    // Filter tanggal jika dipilih
    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
    }

    // Role admin bisa lihat semua data
    if ($user->role !== 'admin') {
        $query->where('id_guru', $user->id_guru);
    }

    // 👉 Jangan paginate, cukup ambil semua data
    $jurnals = $query->latest()->get();

    $jadwal = JadwalMapel::with(['mapel','guru','kelas'])
            ->orderBy('hari', 'asc')
            ->get();

    return view('jurnal.index', compact('jurnals','kelas','guru','setting','layout','user','jadwal'));
}


    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $jadwal = JadwalMapel::with(['mapel','guru','kelas'])->get();

        if (auth()->user()->role === 'admin') {
            $guru = Guru::orderBy('nama_guru', 'asc')->get();
            return view('jurnal.create', compact('kelas','guru','jadwal'));
        }

        return view('jurnal.create', compact('kelas','jadwal'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if (auth()->user()->role === 'admin') {
            $request->validate([
                'id_jadwal'   => 'required|exists:jadwal_mapel,id_jadwal',
                'jam_awal'    => 'required|string',
                'jam_akhir'   => 'required|string',
                'materi'      => 'required|string|max:255',
                'tanggal'     => 'required|date',
                'foto_kelas'  => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ], [
                'foto_kelas.required' => 'Silakan upload foto kelas terlebih dahulu!',
                'foto_kelas.image'    => 'File harus berupa gambar.',
                'foto_kelas.mimes'    => 'Format gambar harus jpg/jpeg/png.',
                'foto_kelas.max'      => 'Ukuran gambar maksimal 3MB.',
            ]);
            
            $id_guru = $request->id_guru;
            $tanggal = $request->tanggal;
        } else {
            $request->validate([
                'id_jadwal'   => 'required|exists:jadwal_mapel,id_jadwal',
                'jam_awal'    => 'required|string',
                'jam_akhir'   => 'required|string',
                'materi'      => 'required|string|max:255',
                'foto_kelas'  => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ], [
                'foto_kelas.required' => 'Silakan upload foto kelas terlebih dahulu!',
                'foto_kelas.image'    => 'File harus berupa gambar.',
                'foto_kelas.mimes'    => 'Format gambar harus jpg/jpeg/png.',
                'foto_kelas.max'      => 'Ukuran gambar maksimal 3MB.',
            ]);

            $id_guru = auth()->user()->id_guru; // ambil id guru dari user login
            $tanggal = now()->toDateString();   // otomatis hari ini
        }

        // 🔍 Cek apakah sudah ada jurnal dengan guru, jadwal, dan tanggal yang sama
        $cek = JurnalMengajar::where('id_guru', $id_guru)
                ->where('id_jadwal', $request->id_jadwal)
                ->where('tanggal', $tanggal)
                ->first();

        if ($cek) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jurnal untuk jadwal tersebut pada tanggal ini sudah ada!');
        }

         // 🔑 ambil id_kelas dari tabel jadwal_mapel
        $jadwal = JadwalMapel::findOrFail($request->id_jadwal);
        $id_kelas = $jadwal->id_kelas;

        $fotoPath = null;
        if ($request->hasFile('foto_kelas')) {
            $fotoPath = $request->file('foto_kelas')->store('foto_kelas', 'public');
        }
        
        JurnalMengajar::create([
            'id_guru'        => $id_guru,
            'id_kelas'       => $id_kelas,
            'id_jadwal'      => $request->id_jadwal,
            'jam_awal'       => $request->jam_awal,
            'jam_akhir'      => $request->jam_akhir,
            'materi'         => $request->materi,
            'tanggal'        => $tanggal,
            'foto_kelas'     => $fotoPath,
        ]);

        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Ambil data jurnal yang akan diupdate
        $jurnal = JurnalMengajar::findOrFail($id);
        $jadwal = JadwalMapel::with(['mapel','guru','kelas'])->get();

        // Validasi input
        if (auth()->user()->role === 'admin') {
            $request->validate([
                'id_guru' => 'required|exists:guru,id_guru',
                'id_jadwal' => 'required|exists:jadwal_mapel,id_jadwal',
                'jam_awal' => 'required|string',
                'jam_akhir' => 'required|string',
                'materi' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'foto_kelas' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            ]);
            

            $id_guru = $request->id_guru;
            $tanggal = $request->tanggal;
        } else {
            $request->validate([
                'id_jadwal' => 'required|exists:jadwal_mapel,id_jadwal',
                'jam_awal' => 'required|string',
                'jam_akhir' => 'required|string',
                'materi' => 'required|string|max:255',
                'foto_kelas' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            ]);

            $id_guru = auth()->user()->id_guru; // ambil id guru dari user login
            $tanggal = $jurnal->tanggal;        // tanggal tidak diubah untuk guru
        }

        // Handle upload foto baru
        if ($request->hasFile('foto_kelas')) {
            // Hapus foto lama jika ada
            if ($jurnal->foto_kelas && file_exists(storage_path('app/public/' . $jurnal->foto_kelas))) {
                unlink(storage_path('app/public/' . $jurnal->foto_kelas));
            }
            // Simpan foto baru
            $fotoPath = $request->file('foto_kelas')->store('foto_kelas', 'public');
            $jurnal->foto_kelas = $fotoPath;
        }

        // 🔑 ambil id_kelas dari tabel jadwal_mapel
        $jadwal = JadwalMapel::findOrFail($request->id_jadwal);
        $id_kelas = $jadwal->id_kelas;

        // Update data jurnal
        $jurnal->id_guru   = $id_guru;
        $jurnal->id_jadwal  = $request->id_jadwal;
        $jurnal->id_kelas  = $id_kelas;
        $jurnal->jam_awal  = $request->jam_awal;
        $jurnal->jam_akhir = $request->jam_akhir;
        $jurnal->materi    = $request->materi;
        $jurnal->tanggal   = $tanggal;

        $jurnal->save();

        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil diperbarui!');
    }

    public function downloadPdf(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $jurnal = JurnalMengajar::when($tanggalAwal && $tanggalAkhir, function($q) use ($tanggalAwal, $tanggalAkhir) {
                        $q->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
                    })
                    ->get();

        $pdf = Pdf::loadView('jurnal.pdf', compact('jurnal', 'tanggalAwal', 'tanggalAkhir'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_jurnal.pdf');
    }

    public function destroy($id)
    {
        $jurnal = JurnalMengajar::findOrFail($id);

        // Hapus file foto jika ada
        if ($jurnal->foto_kelas && \Storage::exists('public/' . $jurnal->foto_kelas)) {
            \Storage::delete('public/' . $jurnal->foto_kelas);
        }

        $jurnal->delete();

        return redirect()->route('admin.jurnal.index')->with('success', 'Jurnal berhasil dihapus!');
    }

public function getJadwal(Request $request)
{
    $tanggal = $request->tanggal;
    $id_guru = $request->id_guru;

    if (!$tanggal || !$id_guru) {
        return response()->json(['error' => 'Tanggal atau guru tidak diisi']);
    }

    // Ambil nama hari dari tanggal
    $hari = \Carbon\Carbon::parse($tanggal)->translatedFormat('l'); // ex: Monday
    $mapHari = [
        'Monday' => 'senin',
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu',
        'Sunday' => 'minggu'
    ];
    $hariDb = $mapHari[$hari] ?? strtolower($hari);

    // Ambil jadwal
    $jadwal = JadwalMapel::with(['mapel','kelas','guru'])
                ->whereRaw('LOWER(hari) = ?', [$hariDb])
                ->where('id_guru', $id_guru)
                ->orderBy('waktu_awal', 'asc')
                ->get();

    // DEBUG: tampilkan info
    return response()->json([
        'tanggal' => $tanggal,
        'hari_didapat' => $hari,
        'hari_db' => $hariDb,
        'id_guru' => $id_guru,
        'count' => $jadwal->count(),
        'data' => $jadwal
    ]);
}


}
