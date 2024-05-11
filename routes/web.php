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
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });



Route::get('/',[AuthController::class,'index'])->name('login');
Route::post('/login-proses',[AuthController::class,'login_proses'])->name('login-proses');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');
// Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['prefix' => 'admin','middleware' => ['auth'], 'as' => 'admin.'],function(){
  Route::resource('/dashboard', DashboardController::class);
  // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::resource('importDataMaster', ImportDataMasterController::class);

  Route::resource('jurusan', JurusanController::class);
  Route::resource('level', LevelController::class);
  Route::resource('kelas', KelasController::class);
  Route::resource('mapel', MapelController::class);

  Route::resource('siswa', SiswaController::class);
  Route::get('/admin/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');

  Route::resource('informasi', InformasiSekolahController::class);

  Route::resource('kalender', KalenderSekolahController::class);

  Route::resource('absensi', AbsensiController::class);
  Route::get('dataAbsen', [AbsensiController::class, 'dataAbsen']);
  Route::post('/absensi/absen', [AbsensiController::class, 'absen'])->name('absensi.absen');

  Route::resource('jadwal', JadwalMapelController::class);

  Route::resource('rapot', RapotController::class);
  Route::get('/rapot/create/{kelasId}', [RapotController::class, 'create'])->name('rapot.create');

  Route::resource('tagihan', TagihanController::class);

  Route::resource('point', PointController::class);

  Route::resource('pointSiswa', PointSiswaController::class);
  Route::get('/pointSiswa/proses/{id_siswa}/{tanggal}', [PointSiswaController::class, 'proses'])->name('pointSiswa.proses');
  Route::get('/pointSiswa/inputPoint/{id_point}/{id_siswa}/{id_kelas}/{id_jurusan}/{tanggal}', [PointSiswaController::class, 'inputPoint'])->name('pointSiswa.inputPoint');
  Route::get('/pointSiswa/reviewPointSiswa/{id_siswa}', [PointSiswaController::class, 'reviewPointSiswa'])->name('pointSiswa.review_point_siswa');
  Route::delete('/admin/pointSiswa/{id}', [PointSiswaController::class, 'destroy']);

  Route::resource('modul', ModulController::class);

  Route::resource('berita', BeritaController::class);

  Route::resource('guru', GuruController::class);

  Route::resource('setting', SettingController::class);
});
// Route::resource('/dashboard', DashboardController::class);

// Route::resource('importDataMaster', ImportDataMasterController::class);

// Route::resource('jurusan', JurusanController::class);
// Route::resource('level', LevelController::class);
// Route::resource('kelas', KelasController::class);
// Route::resource('mapel', MapelController::class);

// Route::resource('siswa', SiswaController::class);

// Route::resource('informasi', InformasiSekolahController::class);

// Route::resource('kalender', KalenderSekolahController::class);

// Route::resource('absensi', AbsensiController::class);
// Route::post('/absensi/absen', [AbsensiController::class, 'absen'])->name('absensi.absen');

// Route::resource('jadwal', JadwalMapelController::class);

// Route::resource('rapot', RapotController::class);
// Route::get('/rapot/create/{kelasId}', [RapotController::class, 'create'])->name('rapot.create');

// Route::resource('tagihan', TagihanController::class);

// Route::resource('point', PointController::class);
// Route::resource('pointSiswa', PointSiswaController::class);
// Route::get('/pointSiswa/proses/{id_siswa}/{tanggal}', [PointSiswaController::class, 'proses'])->name('pointSiswa.proses');
// Route::get('/pointSiswa/inputPoint/{id_point}/{id_siswa}/{id_kelas}/{id_jurusan}/{tanggal}', [PointSiswaController::class, 'inputPoint'])->name('pointSiswa.inputPoint');
// Route::get('/point-siswa/review-point-siswa', [PointSiswaController::class, 'reviewPointSiswa'])->name('pointSiswa.review_point_siswa');
// Route::delete('/pointSiswa/{id}', [PointSiswaController::class, 'destroy']);

// Route::resource('modul', ModulController::class);

// Route::resource('berita', BeritaController::class);

// Route::resource('guru', GuruController::class);

// Route::resource('setting', SettingController::class);