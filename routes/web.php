<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportDataMasterController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DataAlumniController;
use App\Http\Controllers\InformasiSekolahController;
use App\Http\Controllers\KalenderSekolahController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RapotController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PointSiswaController;
use App\Http\Controllers\LaporanPelanggaranController;
use App\Http\Controllers\SuratPeringatanController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalMapelController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\RekapKehadiranGuruController;
use App\Http\Controllers\GuruPiketController;
use App\Http\Controllers\PiketPembiasaanPagiController;
use App\Http\Controllers\RekapBelumAbsenController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SiswaPklController;
use App\Http\Controllers\LaporanKedisiplinanController;
use App\Http\Controllers\LaporanAbsensiMingguanController;
use App\Http\Controllers\LaporanAbsensiBulananController;
use App\Http\Controllers\BackupController;

// Auth Routes (Public)
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Grouped Routes with Auth Middleware
Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
    
    // =========================================================================
    // 1. UMUM / SHARED ROUTES (Semua Pengguna Terautentikasi)
    // =========================================================================
    Route::resource('/dashboard', DashboardController::class);
    Route::post('/dashboard/ingatkan-wali-kelas/{id_kelas}', [DashboardController::class, 'ingatkanWaliKelas'])->name('dashboard.ingatkanWaliKelas');
    Route::post('/dashboard/ingatkan-semua-wali-kelas', [DashboardController::class, 'ingatkanSemuaWaliKelas'])->name('dashboard.ingatkanSemuaWaliKelas');
    Route::get('/guru/profile/{id_guru}', [DashboardController::class, 'edit'])->name('guru.profile');

    // Informasi, Kalender & Berita Sekolah
    Route::resource('informasi', InformasiSekolahController::class);
    Route::resource('kalender', KalenderSekolahController::class);
    Route::resource('berita', BeritaController::class);

    // =========================================================================
    // 2. KEUANGAN & TAGIHAN (Admin & Keuangan)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,keuangan']], function () {
        Route::resource('tagihan', TagihanController::class);
    });

    // =========================================================================
    // 3. DOKUMEN & KEARSIPAN (Admin & Tata Usaha)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,tata_usaha']], function () {
        Route::resource('dokumen', DokumenController::class);
        Route::post('kelas/{id_kelas}/naik-kelas', [KelasController::class, 'naikKelas']);
        Route::post('kelas/proses-individual', [KelasController::class, 'prosesIndividu']);
        Route::post('kelas/{id_kelas}/pindah-semua-alumni', [SiswaController::class, 'pindahSemuaKeAlumni']);
        Route::post('siswa/{id}/alumni', [SiswaController::class, 'pindahKeAlumni']);
    });

    // =========================================================================
    // 4. DATA SISWA & ALUMNI (Admin, Tata Usaha, Kesiswaan, Wali Kelas, Kurikulum, Guru, Keuangan)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,tata_usaha,kesiswaan,wali_kelas,kurikulum,guru,keuangan']], function () {
        Route::resource('siswa', SiswaController::class);
        Route::get('siswa/{id_guru}/reset', [SiswaController::class, 'reset'])->name('siswa.reset');
        Route::get('/download-siswa', [SiswaController::class, 'download'])->name('siswa.download');
        Route::resource('dataAlumni', DataAlumniController::class);
        Route::resource('alumni', DataAlumniController::class);
        Route::get('/download-alumni', [DataAlumniController::class, 'download'])->name('alumni.download');
    });

    // =========================================================================
    // 5. ABSENSI & REKAP GURU (Admin, Tata Usaha, Kurikulum)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,tata_usaha,kurikulum']], function () {
        Route::resource('absensi_guru', AbsensiGuruController::class);
        Route::get('/rekapAbsenGuru', [AbsensiGuruController::class, 'rekapAbsenGuru'])->name('rekap.guru');
        Route::get('/downloadAbsensiHarian', [AbsensiGuruController::class, 'AbsensiGuruExport']);
        Route::post('/tambah-kehadiran', [AbsensiGuruController::class, 'storeKehadiran']);
        Route::put('/edit-kehadiran/{id_absensi}', [AbsensiController::class, 'update']);
        Route::get('/exportExcel', [AbsensiGuruController::class, 'exportExcel']);
        Route::get('rekap-kehadiran-guru', [RekapKehadiranGuruController::class, 'index'])->name('rekapGuru.index');
        Route::get('rekap-kehadiran-guru/export', [RekapKehadiranGuruController::class, 'export'])->name('rekapGuru.export');
        Route::get('rekap-guru/pdf', [RekapKehadiranGuruController::class, 'downloadPdf'])->name('rekapGuru.downloadPdf');
    });

    // =========================================================================
    // 6. KURIKULUM & JADWAL (Admin, Kurikulum)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,kurikulum']], function () {
        Route::resource('jadwal', JadwalMapelController::class);
    });

    // =========================================================================
    // 7. PEMBELAJARAN, JURNAL, RAPOT & MODUL (Admin, Kurikulum, Wali Kelas, Guru)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,kurikulum,wali_kelas,guru']], function () {
        Route::resource('jurnal', JurnalMengajarController::class)->except(['show']);
        Route::get('/jurnal/download-pdf', [JurnalMengajarController::class, 'downloadPdf'])->name('jurnal.downloadPdf');
        Route::get('/get-jadwal', [JurnalMengajarController::class, 'getJadwal'])->name('jurnal.getJadwal');
        Route::resource('modul', ModulController::class);
        Route::resource('rapot', RapotController::class)->except(['create']);
        Route::get('rapot/create/{kelasId?}', [RapotController::class, 'create'])->name('rapot.create');
    });

    // =========================================================================
    // 8. PRESENSI HARIAN SISWA (Admin, Kesiswaan, Wali Kelas, Kurikulum, Guru)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas,kurikulum,guru']], function () {
        Route::post('absensi/absen', [AbsensiController::class, 'absen'])->name('absensi.absen');
        Route::post('/absensi/tambah-kehadiran', [AbsensiController::class, 'tambahKehadiran'])->name('absensi.tambah-kehadiran');
        Route::post('/absensi/simpan', [AbsensiController::class, 'simpan'])->name('absensi.simpan');
        Route::get('/downloadAbsensiHarianSiswa', [AbsensiController::class, 'AbsensiSiswaExport']);
        Route::get('/get-siswa-by-kelas/{id_kelas}', [AbsensiController::class, 'getSiswaByKelas']);
        Route::resource('absensi', AbsensiController::class);
    });

    // =========================================================================
    // 9. REKAP ABSENSI SISWA & ARSIP KELAS (Admin, Kesiswaan, Wali Kelas, Tata Usaha, Kurikulum)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas,tata_usaha,kurikulum']], function () {
        Route::get('rekapAbsen', [AbsensiController::class, 'rekapAbsen'])->name('absensi.rekap');
        Route::get('/showRekapAbsen', [AbsensiController::class, 'showRekapAbsen']);
        Route::get('/rekapAbsenSiswa', [AbsensiController::class, 'rekapAbsenSiswa'])->name('rekap.siswa');
        Route::get('/siswa-tidak-hadir', [AbsensiController::class, 'siswaTidakHadir'])->name('siswa.tidak.hadir');
        Route::get('/exportExcelRekapSiswa', [AbsensiController::class, 'exportRekapSiswa']);
        Route::get('/absensi/download', [AbsensiController::class, 'downloadShowRekap']);
    });

    // =========================================================================
    // 10. REKAP KELALAIAN, KEDISIPLINAN MESIN & BROADCAST WA
    // =========================================================================
    // Rekap Belum/Lalai Absen (Admin & Kesiswaan)
    Route::group(['middleware' => ['role:admin,kesiswaan']], function () {
        Route::get('rekap-belum-absen', [RekapBelumAbsenController::class, 'index'])->name('rekapBelumAbsen.index');
        Route::post('rekap-belum-absen', [RekapBelumAbsenController::class, 'store'])->name('rekapBelumAbsen.store');
        Route::get('rekap-belum-absen/export', [RekapBelumAbsenController::class, 'export'])->name('rekapBelumAbsen.export');
    });

    // Laporan Kedisiplinan Mesin RFID (Admin, Kesiswaan, Wali Kelas)
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas']], function () {
        Route::get('laporan-kedisiplinan-siswa', [LaporanKedisiplinanController::class, 'index'])->name('laporanKedisiplinan.index');
        Route::get('laporan-kedisiplinan-siswa/export', [LaporanKedisiplinanController::class, 'exportExcel'])->name('laporanKedisiplinan.export');
    });

    // Laporan Bulanan & Mingguan WA (Admin, Kesiswaan, Wali Kelas, Kurikulum)
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas,kurikulum']], function () {
        Route::get('laporan-bulanan-wa', [LaporanAbsensiBulananController::class, 'index'])->name('laporanBulananWa.index');
        Route::post('laporan-bulanan-wa/kirim-orang-tua', [LaporanAbsensiBulananController::class, 'kirimOrangTua'])->name('laporanBulananWa.kirimOrangTua');
        Route::post('laporan-bulanan-wa/kirim-wali-kelas', [LaporanAbsensiBulananController::class, 'kirimWaliKelas'])->name('laporanBulananWa.kirimWaliKelas');
        Route::get('laporan-bulanan-wa/preview', [LaporanAbsensiBulananController::class, 'preview'])->name('laporanBulananWa.preview');
        Route::get('laporan-mingguan-wa', [LaporanAbsensiMingguanController::class, 'index'])->name('laporanMingguanWa.index');
        Route::post('laporan-mingguan-wa/kirim-orang-tua', [LaporanAbsensiMingguanController::class, 'kirimOrangTua'])->name('laporanMingguanWa.kirimOrangTua');
        Route::post('laporan-mingguan-wa/kirim-wali-kelas', [LaporanAbsensiMingguanController::class, 'kirimWaliKelas'])->name('laporanMingguanWa.kirimWaliKelas');
        Route::get('laporan-mingguan-wa/preview', [LaporanAbsensiMingguanController::class, 'preview'])->name('laporanMingguanWa.preview');
    });

    // =========================================================================
    // 11. KESISWAAN, POIN & PELANGGARAN
    // =========================================================================
    // Master Aturan Poin (Admin & Kesiswaan)
    Route::group(['middleware' => ['role:admin,kesiswaan']], function () {
        Route::resource('point', PointController::class);
    });

    // Input Poin Siswa (Admin, Kesiswaan, Wali Kelas, Kurikulum, Guru)
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas,kurikulum,guru']], function () {
        Route::resource('pointSiswa', PointSiswaController::class);
        Route::get('pointSiswa/proses/{id_siswa}/{tanggal}', [PointSiswaController::class, 'proses'])->name('pointSiswa.proses');
        Route::get('pointSiswa/inputPoint/{id_point}/{id_siswa}/{id_kelas}/{id_jurusan}/{tanggal}', [PointSiswaController::class, 'inputPoint'])->name('pointSiswa.inputPoint');
        Route::get('pointSiswa/reviewPointSiswa/{id_siswa}', [PointSiswaController::class, 'reviewPointSiswa'])->name('pointSiswa.review_point_siswa');
        Route::get('pointSiswa/sp-pdf/{id_siswa}', [PointSiswaController::class, 'downloadSpPdf'])->name('pointSiswa.sp_pdf');
    });

    // Laporan Pelanggaran & SP (Admin, Kesiswaan, Wali Kelas, Kurikulum)
    Route::group(['middleware' => ['role:admin,kesiswaan,wali_kelas,kurikulum']], function () {
        Route::get('laporan-pelanggaran', [LaporanPelanggaranController::class, 'index'])->name('laporanPelanggaran.index');
        Route::get('laporan-pelanggaran/export-excel', [LaporanPelanggaranController::class, 'exportExcel'])->name('laporanPelanggaran.exportExcel');
        Route::get('laporan-pelanggaran/export-pdf', [LaporanPelanggaranController::class, 'exportPdf'])->name('laporanPelanggaran.exportPdf');
        Route::get('laporan-pelanggaran/detail/{id_siswa}', [LaporanPelanggaranController::class, 'detailRiwayat'])->name('laporanPelanggaran.detail');
        Route::get('surat-peringatan', [SuratPeringatanController::class, 'index'])->name('suratPeringatan.index');
        Route::post('surat-peringatan/upload-ttd/{id_sp}', [SuratPeringatanController::class, 'uploadTtd'])->name('suratPeringatan.uploadTtd');
    });

    // =========================================================================
    // 12. GURU PIKET & PEMBIASAAN PAGI (Admin, Guru, Wali Kelas, Kurikulum, Kesiswaan, Tata Usaha)
    // =========================================================================
    Route::group(['middleware' => ['role:admin,guru,wali_kelas,kurikulum,kesiswaan,tata_usaha']], function () {
        Route::get('guruPiket/panel', [GuruPiketController::class, 'panel'])->name('guruPiket.panel');
        Route::post('guruPiket/catat-terlambat', [GuruPiketController::class, 'catatTerlambat'])->name('guruPiket.catatTerlambat');
        Route::delete('guruPiket/hapus-terlambat/{id_absensi}', [GuruPiketController::class, 'hapusTerlambat'])->name('guruPiket.hapusTerlambat');
        Route::resource('guruPiket', GuruPiketController::class);
        Route::resource('piketPembiasaanPagi', PiketPembiasaanPagiController::class);
    });

    // =========================================================================
    // 13. MASTER DATA INTI & SISTEM (Strict Admin-Only)
    // =========================================================================
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('importDataMaster', ImportDataMasterController::class);
        Route::post('/import', [ImportDataMasterController::class, 'importData']);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('level', LevelController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MapelController::class);
        Route::resource('guru', GuruController::class);
        Route::get('guru/{id_guru}/reset', [GuruController::class, 'reset'])->name('guru.reset');
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('siswaPkl', SiswaPklController::class);

        // System Settings & WhatsApp Gateway Config
        Route::get('/setting-whatsapp-status', [SettingController::class, 'checkWhatsAppStatus'])->name('setting.whatsapp-status');
        Route::post('/setting-whatsapp-start', [SettingController::class, 'startWhatsAppSession'])->name('setting.whatsapp-start');
        Route::get('/whatsapp-sessions', [SettingController::class, 'listWhatsAppSessions'])->name('whatsapp-sessions.index');
        Route::post('/whatsapp-sessions', [SettingController::class, 'addWhatsAppSession'])->name('whatsapp-sessions.store');
        Route::put('/whatsapp-sessions/{id}/toggle', [SettingController::class, 'toggleWhatsAppSession'])->name('whatsapp-sessions.toggle');
        Route::delete('/whatsapp-sessions/{id}', [SettingController::class, 'deleteWhatsAppSession'])->name('whatsapp-sessions.destroy');
        Route::get('/whatsapp-sessions/{id}/status', [SettingController::class, 'getWhatsAppSessionStatus'])->name('whatsapp-sessions.status');
        Route::post('/whatsapp-sessions/{id}/start', [SettingController::class, 'startWhatsAppSessionSpec'])->name('whatsapp-sessions.start');
        Route::get('/whatsapp-server/status', [SettingController::class, 'getWhatsAppServerStatus'])->name('whatsapp-server.status');
        Route::get('/scheduler/status', [SettingController::class, 'getSchedulerStatus'])->name('scheduler.status');
        Route::post('/setting-test-rekap-wa', [SettingController::class, 'testRekapWa'])->name('setting.testRekapWa');
        Route::resource('setting', SettingController::class);
        Route::put('/setting-versi/{id_version}', [SettingController::class, 'updateVersiAplikasi'])->name('setting.updateVersiAplikasi');

        // Backup & Restore Database
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
        Route::post('/backup/restore-file/{filename}', [BackupController::class, 'restoreExisting'])->name('backup.restoreExisting');
        Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });

});
