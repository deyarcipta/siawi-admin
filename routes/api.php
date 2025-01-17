<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController\HomeController;
use App\Http\Controllers\SiswaController\BeritaController;
use App\Http\Controllers\SiswaController\JadwalController;
use App\Http\Controllers\SiswaController\InformasiController;
use App\Http\Controllers\SiswaController\KalenderController;
use App\Http\Controllers\SiswaController\RapotController;
use App\Http\Controllers\SiswaController\PointController;
use App\Http\Controllers\SiswaController\AbsensiController;
use App\Http\Controllers\SiswaController\TagihanController;
use App\Http\Controllers\SiswaController\ModulController;
use App\Http\Controllers\SiswaController\UploadFotoController;
use App\Http\Controllers\SiswaController\UpdateSiswaController;
use App\Http\Controllers\SiswaController\QrCodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home/{idSiswa}',[HomeController::class, 'index']);
Route::post('/ubahPassword',[HomeController::class, 'ubahPassword']);
Route::post('/login',[HomeController::class, 'login']);
Route::get('/berita',[BeritaController::class, 'index']);
Route::get('/jadwal/{idSiswa}/{hari}',[JadwalController::class, 'index']);
Route::get('/jadwalToday/{idSiswa}',[JadwalController::class, 'jadwalToday']);
Route::get('/informasi',[InformasiController::class, 'index']);
Route::get('/kalender',[KalenderController::class, 'index']);
Route::get('/rapot/{idSiswa}',[RapotController::class, 'index']);
Route::get('/point/{idSiswa}',[PointController::class, 'index']);
Route::get('/absensi/{idSiswa}',[AbsensiController::class, 'index']);
Route::get('/tagihan',[TagihanController::class, 'index']);
Route::get('/modul/{idSiswa}',[ModulController::class, 'index']);
Route::post('/uploadFoto/{idSiswa}',[UploadFotoController::class, 'uploadFoto']);
Route::post('/updateSiswa/{idSiswa}',[UpdateSiswaController::class, 'updateSiswa']);
Route::get('/generate-qrcode', [QRCodeController::class, 'generate']);
