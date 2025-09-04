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
use App\Http\Controllers\ModulController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalMapelController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\RekapKehadiranGuruController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SiswaPklController;
use App\Exports\AbsensiGuruExport;
use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\RfidController;

// Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login-proses', [AuthController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Grouped Routes for Admin with Auth Middleware
Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
    Route::resource('/dashboard', DashboardController::class);
    Route::get('/guru/profile/{id_guru}', [DashboardController::class, 'edit'])->name('guru.profile');

    Route::resource('importDataMaster', ImportDataMasterController::class);
    Route::post('/import', [ImportDataMasterController::class, 'importData']);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('level', LevelController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MapelController::class);

    Route::resource('siswa', SiswaController::class);
    Route::get('siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::get('siswa/{id_guru}/reset', [SiswaController::class, 'reset'])->name('siswa.reset');
    Route::get('/download-siswa', [SiswaController::class, 'download'])->name('siswa.download');

    Route::post('kelas/{id_kelas}/naik-kelas', [KelasController::class, 'naikKelas']);
    Route::post('kelas/proses-individual', [KelasController::class, 'prosesIndividu']);

    Route::post('kelas/{id_kelas}/pindah-semua-alumni', [SiswaController::class, 'pindahSemuaKeAlumni']);
    Route::post('siswa/{id}/alumni', [SiswaController::class, 'pindahKeAlumni']);

    Route::resource('dataAlumni', DataAlumniController::class);

    Route::resource('informasi', InformasiSekolahController::class);
    Route::resource('kalender', KalenderSekolahController::class);

    // Absensi Routes
    Route::post('absensi/absen', [AbsensiController::class, 'absen'])->name('absensi.absen');
    Route::get('rekapAbsen', [AbsensiController::class, 'rekapAbsen'])->name('absensi.rekap');
    Route::get('/showRekapAbsen', [AbsensiController::class, 'showRekapAbsen']);
    Route::get('/rekapAbsenSiswa', [AbsensiController::class, 'rekapAbsenSiswa'])->name('rekap.siswa');
    Route::get('/siswa-tidak-hadir', [AbsensiController::class, 'siswaTidakHadir'])->name('siswa.tidak.hadir');
    Route::post('/absensi/tambah-kehadiran', [AbsensiController::class, 'tambahKehadiran'])->name('absensi.tambah-kehadiran');
    Route::get('/exportExcelRekapSiswa', [AbsensiController::class, 'exportRekapSiswa']);
    Route::get('/downloadAbsensiHarianSiswa', [AbsensiController::class, 'AbsensiSiswaExport']);
    Route::post('/absensi/simpan', [AbsensiController::class, 'simpan'])->name('absensi.simpan');
    Route::get('/absensi/download', [AbsensiController::class, 'downloadShowRekap']);
    Route::get('/get-siswa-by-kelas/{id_kelas}', [AbsensiController::class, 'getSiswaByKelas']);
    Route::resource('absensi', AbsensiController::class);
    Route::delete('/absensi/{id_absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');

    // Absensi Guru Routes
    Route::resource('absensi_guru', AbsensiGuruController::class);
    Route::get('/rekapAbsenGuru', [AbsensiGuruController::class, 'rekapAbsenGuru'])->name('rekap.guru');
    Route::get('/downloadAbsensiHarian', [AbsensiGuruController::class, 'AbsensiGuruExport']);
    Route::post('/tambah-kehadiran', [AbsensiGuruController::class, 'storeKehadiran']);
    Route::put('/edit-kehadiran/{id_absensi}', [AbsensiController::class, 'update']);
    Route::get('/exportExcel', [AbsensiGuruController::class, 'exportExcel']);

    Route::resource('jadwal', JadwalMapelController::class);
    Route::resource('jurnal', JurnalMengajarController::class)->except(['show']);
    Route::get('/jurnal/download-pdf', [JurnalMengajarController::class, 'downloadPdf'])->name('jurnal.downloadPdf');
    Route::get('/get-jadwal', [JurnalMengajarController::class, 'getJadwal'])->name('jurnal.getJadwal');


    Route::get('rekap-kehadiran-guru', [RekapKehadiranGuruController::class, 'index'])->name('rekapGuru.index');
    Route::get('rekap-kehadiran-guru/export', [RekapKehadiranGuruController::class, 'export'])->name('rekapGuru.export');
    Route::get('rekap-guru/pdf', [RekapKehadiranGuruController::class, 'downloadPdf'])->name('rekapGuru.downloadPdf');

    Route::resource('rapot', RapotController::class);
    Route::get('rapot/create/{kelasId}', [RapotController::class, 'create'])->name('rapot.create');

    Route::resource('dokumen', DokumenController::class);

    Route::resource('tagihan', TagihanController::class);
    Route::resource('point', PointController::class);
    Route::resource('pointSiswa', PointSiswaController::class);
    
    Route::get('pointSiswa/proses/{id_siswa}/{tanggal}', [PointSiswaController::class, 'proses'])->name('pointSiswa.proses');
    Route::get('pointSiswa/inputPoint/{id_point}/{id_siswa}/{id_kelas}/{id_jurusan}/{tanggal}', [PointSiswaController::class, 'inputPoint'])->name('pointSiswa.inputPoint');
    Route::get('pointSiswa/reviewPointSiswa/{id_siswa}', [PointSiswaController::class, 'reviewPointSiswa'])->name('pointSiswa.review_point_siswa');
    Route::delete('pointSiswa/{id_point_siswa}', [PointSiswaController::class, 'destroy'])
        ->name('admin.pointSiswa.destroy');

    Route::resource('modul', ModulController::class);
    Route::resource('berita', BeritaController::class);
    
    Route::resource('perusahaan', PerusahaanController::class);
    Route::resource('siswaPkl', SiswaPklController::class);

    Route::resource('guru', GuruController::class);
    Route::get('guru/{id_guru}/reset', [GuruController::class, 'reset'])->name('guru.reset');
    
    Route::resource('setting', SettingController::class);
    Route::put('/admin/setting-versi/{id_version}', [SettingController::class, 'updateVersiAplikasi'])
    ->name('setting.updateVersiAplikasi');

});
