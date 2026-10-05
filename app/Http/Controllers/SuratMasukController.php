<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SuratMasuk;
use App\Models\Setting;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of Surat Masuk.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $selectedTahun = $request->input('tahun', Carbon::now()->format('Y'));
        $search = $request->input('search', '');

        $query = SuratMasuk::with('creator')->where('tahun', $selectedTahun);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('nomor_surat_asal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        $suratMasuk = $query->orderBy('no_urut', 'desc')->get();

        $totalSuratTahunIni = SuratMasuk::where('tahun', $selectedTahun)->count();
        $totalSuratBulanIni = SuratMasuk::where('tahun', $selectedTahun)
            ->whereMonth('tanggal_diterima', Carbon::now()->month)
            ->count();

        $nextAgendaPreview = SuratMasuk::generateNextAgenda(Carbon::now()->toDateString());

        $tahunList = SuratMasuk::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        if (!in_array((int) Carbon::now()->format('Y'), $tahunList)) {
            array_unshift($tahunList, (int) Carbon::now()->format('Y'));
        }

        return view('surat.surat_masuk.index', compact(
            'layout', 'setting', 'user', 'suratMasuk', 'selectedTahun', 'search',
            'totalSuratTahunIni', 'totalSuratBulanIni', 'nextAgendaPreview', 'tahunList'
        ));
    }

    /**
     * Show the form for creating a new Surat Masuk.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $today = Carbon::now()->toDateString();
        $nextAgendaPreview = SuratMasuk::generateNextAgenda($today);

        return view('surat.surat_masuk.tambah', compact(
            'layout', 'setting', 'user', 'today', 'nextAgendaPreview'
        ));
    }

    /**
     * Store a newly created Surat Masuk in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat_asal' => 'required|string|max:150',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'disposisi_kepada' => 'nullable|string|max:200',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $fileLampiranName = null;
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileLampiranName = 'surat_masuk_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('lampiran_surat_masuk', $fileLampiranName, 'public');
        }

        $surat = DB::transaction(function () use ($request, $fileLampiranName, $user) {
            $date = Carbon::parse($request->tanggal_diterima);
            $tahun = (int) $date->format('Y');

            $maxUrut = SuratMasuk::where('tahun', $tahun)->lockForUpdate()->max('no_urut') ?? 0;
            $nextUrut = $maxUrut + 1;
            $formattedUrut = str_pad((string) $nextUrut, 3, '0', STR_PAD_LEFT);
            $nomorAgenda = "AGM/{$formattedUrut}/{$tahun}";

            return SuratMasuk::create([
                'nomor_agenda' => $nomorAgenda,
                'no_urut' => $nextUrut,
                'tahun' => $tahun,
                'nomor_surat_asal' => $request->nomor_surat_asal,
                'asal_surat' => $request->asal_surat,
                'perihal' => $request->perihal,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_diterima' => $request->tanggal_diterima,
                'disposisi_kepada' => $request->disposisi_kepada,
                'isi_disposisi' => $request->isi_disposisi,
                'file_lampiran' => $fileLampiranName,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id_guru ?? null,
            ]);
        });

        return redirect()->route('admin.surat-masuk.index')
            ->with('success', "Surat masuk dari [{$surat->asal_surat}] berhasil dicatat dengan No. Agenda {$surat->nomor_agenda}.");
    }

    /**
     * Show the form for editing the specified Surat Masuk.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $surat = SuratMasuk::findOrFail($id);

        return view('surat.surat_masuk.edit', compact('layout', 'setting', 'user', 'surat'));
    }

    /**
     * Update the specified Surat Masuk in storage.
     */
    public function update(Request $request, string $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $request->validate([
            'nomor_surat_asal' => 'required|string|max:150',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'disposisi_kepada' => 'nullable|string|max:200',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $fileLampiranName = $surat->file_lampiran;
        if ($request->hasFile('file_lampiran')) {
            if ($surat->file_lampiran && Storage::disk('public')->exists('lampiran_surat_masuk/' . $surat->file_lampiran)) {
                Storage::disk('public')->delete('lampiran_surat_masuk/' . $surat->file_lampiran);
            }
            $file = $request->file('file_lampiran');
            $fileLampiranName = 'surat_masuk_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('lampiran_surat_masuk', $fileLampiranName, 'public');
        }

        $surat->update([
            'nomor_surat_asal' => $request->nomor_surat_asal,
            'asal_surat' => $request->asal_surat,
            'perihal' => $request->perihal,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_diterima' => $request->tanggal_diterima,
            'disposisi_kepada' => $request->disposisi_kepada,
            'isi_disposisi' => $request->isi_disposisi,
            'file_lampiran' => $fileLampiranName,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.surat-masuk.index')
            ->with('success', "Data agenda surat masuk [{$surat->nomor_agenda}] berhasil diperbarui.");
    }

    /**
     * Remove the specified Surat Masuk from storage.
     */
    public function destroy(string $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $nomorAgenda = $surat->nomor_agenda;

        if ($surat->file_lampiran && Storage::disk('public')->exists('lampiran_surat_masuk/' . $surat->file_lampiran)) {
            Storage::disk('public')->delete('lampiran_surat_masuk/' . $surat->file_lampiran);
        }

        $surat->delete();

        return redirect()->route('admin.surat-masuk.index')
            ->with('success', "Data agenda [{$nomorAgenda}] berhasil dihapus.");
    }
}
