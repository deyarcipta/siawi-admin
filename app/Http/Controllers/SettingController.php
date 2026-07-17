<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\AppVersion;
use DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $appVersi = AppVersion::where('id_versi', 1)->first();
        $user = Auth::user();
        session(['old_logo' => $setting->logo]);
        return view('settingApp.setting', compact('layout','setting','appVersi','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $user = Auth::user();
        return view('settingApp.setting', compact('layout','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_app' => 'nullable|string|max:255',
            'nama_sekolah' => 'nullable|string|max:255',
            'nama_kepsek' => 'nullable|string|max:255',
            'nip_kepsek' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'kel' => 'nullable|string|max:255',
            'kec' => 'nullable|string|max:255',
            'prov' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:255',
            'secondary_color' => 'nullable|string|max:255',
            // 'logo' => 'required',
        ]);

        $setting = Setting::create([
            'nama_app' => $request->nama_app,
            'nama_sekolah' => $request->nama_sekolah,
            'nama_kepsek' => $request->nama_kepsek,
            'nip_kepsek' => $request->nip_kepsek,
            'alamat' => $request->alamat,
            'kel' => $request->kel,
            'kec' => $request->kec,
            'prov' => $request->prov,
            'kota' => $request->kota,
            'logo' => $setting->logo,
            'primary_color' => $setting->primary_color,
            'secondary_color' => $setting->secondary_color,
        ]);

        return redirect('/admin/setting');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $edit = Setting::find($id);
        $user = Auth::user();
        // session(['old_foto' => $edit->foto]);
        return view('settingApp.setting', compact('layout','edit','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mengambil data setting berdasarkan ID
        $setting = Setting::find($id);

        // Cek apakah request mengandung data yang perlu diproses dari settingDasar
        if ($request->has('nama_sekolah')) {
            $request->validate([
                'nama_app' => 'required',
                'nama_sekolah' => 'required',
                'nama_kepsek' => 'required',
                'nip_kepsek' => 'required',
                'alamat' => 'required',
                'kel' => 'required',
                'kec' => 'required',
                'prov' => 'required',
                'kota' => 'required',
                // 'logo' => 'required',
            ]);
            // Cek apakah ada file logo yang diunggah
            if ($request->hasFile('logo')) {
                
                // Hapus logo lama jika ada
                if ($setting->logo) {
                    Storage::delete('public/gambar/' . $setting->logo);
                }

                // Simpan logo baru
                $file = $request->file('logo');
                $nama_file = time() . '_' . $file->getClientOriginalName(); // Tambahkan timestamp agar unik
                $file->storeAs('public/gambar/', $nama_file);

                // Simpan nama file baru
                $setting->logo = $nama_file;
            } else {
                // Jika tidak ada file logo yang diunggah, biarkan logo lama tetap digunakan
                $nama_file = $setting->logo; 
            }
            // Update settingDasar
            $setting->update([
                'nama_app' => $request->nama_app,
                'nama_sekolah' => $request->nama_sekolah,
                'nama_kepsek' => $request->nama_kepsek,
                'nip_kepsek' => $request->nip_kepsek,
                'alamat' => $request->alamat,
                'kel' => $request->kel,
                'kec' => $request->kec,
                'prov' => $request->prov,
                'kota' => $request->kota,
                'logo' => $nama_file
            ]);
        }

        // Cek apakah request mengandung data yang perlu diproses dari settingAplikasi
        if ($request->has('primary_color') || $request->has('secondary_color')) {
            $request->validate([
                'nama_app' => 'required',
                'primary_color' => 'required',
                'secondary_color' => 'required',
                'third_color' => 'required',
                'four_color' => 'required',
                'five_color' => 'required',
                'six_color' => 'required',
                'text_color' => 'required',
            ]);

            // Konversi warna HEX ke format Flutter (0xFF + HEX tanpa #)
            $primaryColorFlutter = '0xFF' . strtoupper(ltrim($request->primary_color, '#'));
            $secondaryColorFlutter = '0xFF' . strtoupper(ltrim($request->secondary_color, '#'));
            $thirdColorFlutter = '0xFF' . strtoupper(ltrim($request->third_color, '#'));
            $fourColorFlutter = '0xFF' . strtoupper(ltrim($request->four_color, '#'));
            $fiveColorFlutter = '0xFF' . strtoupper(ltrim($request->five_color, '#'));
            $sixColorFlutter = '0xFF' . strtoupper(ltrim($request->six_color, '#'));
            $textColorFlutter = '0xFF' . strtoupper(ltrim($request->text_color, '#'));
            // Update settingAplikasi
            $setting->update([
                'primary_color' => $primaryColorFlutter,
                'secondary_color' => $secondaryColorFlutter,
                'third_color' => $thirdColorFlutter,
                'four_color' => $fourColorFlutter,
                'five_color' => $fiveColorFlutter,
                'six_color' => $sixColorFlutter,
                'text_color' => $textColorFlutter,
            ]);
        }

        // Cek apakah request mengandung 'versi_app' (berarti update versi aplikasi)
        if ($request->has('versi_app')) {
            $request->validate([
                'versi_app' => 'required',
                'link_app' => 'required',
            ]);

            DB::table('versi_siawi')->where('id_versi', $id)->update([
                'versi' => $request->versi_app,
                'download_url' => $request->link_app,
                // 'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Versi aplikasi berhasil diperbarui.');
        }

        // Cek apakah request mengandung 'jam_pelajaran' (berarti update jam pelajaran)
        if ($request->has('jam_pelajaran')) {
            $request->validate([
                'jam_pelajaran' => 'required|array',
            ]);

            $setting->update([
                'jam_pelajaran' => json_encode($request->jam_pelajaran),
            ]);

            return redirect('/admin/setting')->with('success', 'Pengaturan jam pelajaran berhasil diperbarui.');
        }

        // Cek apakah request mengandung 'sp_settings'
        if ($request->has('sp_settings')) {
            $request->validate([
                'sp_settings' => 'required|array',
            ]);

            $setting->update([
                'sp_settings' => json_encode($request->sp_settings),
            ]);

            return redirect('/admin/setting')->with('success', 'Pengaturan Surat Peringatan (SP) berhasil diperbarui.');
        }

        // Cek apakah request mengandung 'wa_settings'
        if ($request->has('wa_settings')) {
            $request->validate([
                'wa_status' => 'required|boolean',
                'wa_api_url' => 'nullable|url',
                'wa_api_key' => 'nullable|string',
                'wa_session_id' => 'nullable|string',
                'wa_rate_limit' => 'nullable|integer|min:1',
            ]);

            $setting->update([
                'wa_status' => $request->wa_status,
                'wa_api_url' => $request->wa_api_url,
                'wa_api_key' => $request->wa_api_key,
                'wa_session_id' => $request->wa_session_id,
                'wa_rate_limit' => $request->wa_rate_limit ?? 10,
            ]);

            return redirect('/admin/setting')->with('success', 'Pengaturan WhatsApp berhasil diperbarui.');
        }

        return redirect('/admin/setting')->with('success', 'Pengaturan berhasil diperbarui.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Mengecek status sesi WhatsApp di OpenWA Server.
     */
    public function checkWhatsAppStatus()
    {
        $setting = Setting::first();
        $baseUrl = ($setting && $setting->wa_api_url) ? $setting->wa_api_url : env('OPEN_WA_API_URL', 'http://localhost:2785/api');
        $apiKey = ($setting && $setting->wa_api_key) ? $setting->wa_api_key : env('OPEN_WA_API_KEY');
        $sessionId = ($setting && $setting->wa_session_id) ? $setting->wa_session_id : env('OPEN_WA_SESSION_ID', 'default');

        $headers = [
            'Content-Type' => 'application/json',
        ];
        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->timeout(5)
                ->get("{$baseUrl}/sessions/{$sessionId}");

            $qrCode = null;
            $status = 'UNKNOWN';
            $message = '';
            $connected = false;

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'] ?? 'CONNECTED';
                $connected = ($status === 'CONNECTED' || $status === 'WORKING');
                $message = $connected ? 'WhatsApp Terhubung!' : 'Sesi aktif tetapi belum terhubung (Status: ' . $status . ')';

                if (!$connected) {
                    // Coba ambil QR Code
                    $qrResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->timeout(5)
                        ->get("{$baseUrl}/sessions/{$sessionId}/qr");
                    if ($qrResponse->successful()) {
                        $qrData = $qrResponse->json();
                        $qrCode = $qrData['qr'] ?? $qrResponse->body();
                    }
                }
            } else {
                $statusCode = $response->status();
                if ($statusCode === 404) {
                    $status = 'NOT_STARTED';
                    $message = 'Sesi belum terdaftar/dimulai di server OpenWA.';
                } else if ($statusCode === 409) {
                    $status = 'NOT_READY';
                    $message = 'WhatsApp belum terhubung (Conflict 409).';
                    
                    // Coba ambil QR Code
                    $qrResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->timeout(5)
                        ->get("{$baseUrl}/sessions/{$sessionId}/qr");
                    if ($qrResponse->successful()) {
                        $qrData = $qrResponse->json();
                        $qrCode = $qrData['qr'] ?? $qrResponse->body();
                    }
                } else {
                    $status = 'ERROR';
                    $message = 'Server OpenWA merespon dengan status: ' . $statusCode;
                }
            }

            return response()->json([
                'success' => true,
                'connected' => $connected,
                'status' => $status,
                'message' => $message,
                'qrCode' => $qrCode,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi OpenWA Server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memulai/menginisialisasi ulang sesi WhatsApp di OpenWA Server.
     */
    public function startWhatsAppSession()
    {
        $setting = Setting::first();
        $baseUrl = ($setting && $setting->wa_api_url) ? $setting->wa_api_url : env('OPEN_WA_API_URL', 'http://localhost:2785/api');
        $apiKey = ($setting && $setting->wa_api_key) ? $setting->wa_api_key : env('OPEN_WA_API_KEY');
        $sessionId = ($setting && $setting->wa_session_id) ? $setting->wa_session_id : env('OPEN_WA_SESSION_ID', 'default');

        $headers = [
            'Content-Type' => 'application/json',
        ];
        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->timeout(10)
                ->post("{$baseUrl}/sessions/{$sessionId}/start");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sesi berhasil dimulai. Silakan tunggu beberapa saat dan klik "Cek Koneksi".'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memulai sesi: ' . $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi OpenWA Server: ' . $e->getMessage()
            ], 500);
        }
    }
}
