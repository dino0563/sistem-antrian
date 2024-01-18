<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AmbilAntrianController;
use App\Http\Controllers\antrianAjaxController;
use App\Http\Controllers\Api\AntrianController;
use App\Http\Controllers\LoketsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PanggilController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/user', function () {
    return view('user.home');
});

Route::get('/cetak', function () {
    return view('laporan.cetak');
});

Route::get('/superadmin/display', function () {
    return view('superadmin.display-interface');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SesiController::class, 'index'])->name('login');
    Route::post('/login', [SesiController::class, 'login']);
});

Route::get('/', [PagesController::class, 'index'])->name('getUpdateData');
Route::post('/', [AmbilAntrianController::class, 'store']);
Route::get('/display', [PagesController::class, 'indexdisplay']);

Route::middleware(['auth'])->group(function () {
    //route admin
    Route::get('/admin', [AdminController::class, 'index'])->middleware('userAkses:admin');
    Route::get('/admin/pegawai', [PegawaiController::class, 'index'])->middleware('userAkses:admin');
    Route::post('/admin/pegawai', [PegawaiController::class, 'store'])->middleware('userAkses:admin');
    Route::get('/admin/pegawai/{id}', [PegawaiController::class, 'edit'])->middleware('userAkses:admin');
    Route::put('/admin/pegawai/{id}', [PegawaiController::class, 'update'])->middleware('userAkses:admin');
    Route::delete('/admin/pegawai/{id}', [PegawaiController::class, 'destroy'])->middleware('userAkses:admin');
    Route::get('/admin/loket', [LoketsController::class, 'index'])->middleware('userAkses:admin');
    Route::post('/admin/loket', [LoketsController::class, 'store'])->middleware('userAkses:admin');
    Route::put('/admin/loket/{id}', [LoketsController::class, 'update'])->middleware('userAkses:admin');
    Route::delete('/admin/loket/{id}', [LoketsController::class, 'destroy'])->middleware('userAkses:admin');
    Route::get('/admin/loket/{id}', [LoketsController::class, 'edit'])->middleware('userAkses:admin');
    Route::get('/admin/laporan', [AmbilAntrianController::class, 'show'])->middleware('userAkses:admin')->name('laporan.show');

    //route super admin
    Route::get('/superadmin/home', [AdminController::class, 'superadmin'])->middleware('userAkses:superadmin');
    Route::get('/superadmin/user-interface/{id}', [PagesController::class, 'edit'])->middleware('userAkses:superadmin');
    Route::put('/superadmin/user-interface/{id}', [PagesController::class, 'update'])->middleware('userAkses:superadmin');

    //route pegawai
    Route::get('/pegawai/home', [AdminController::class, 'pegawai'])->middleware('userAkses:pegawai');
    Route::get('/pegawai/panggil', [PanggilController::class, 'show'])->middleware('userAkses:pegawai');
    Route::get('/pegawai/panggil/{id}', [PanggilController::class, 'show'])->middleware('userAkses:pegawai');

    Route::put('/antrian/{id}', [AntrianController::class, 'update']);

    Route::put('/update/{id}', [PanggilController::class, 'update'])->middleware('userAkses:pegawai');

    Route::put('/pegawai/panggil/{id}', [PanggilController::class, 'update'])->middleware('userAkses:pegawai');

    Route::put('/update/{id}', [PanggilController::class, 'update'])->middleware('userAkses:pegawai');

    Route::resource('antrianAjax', antrianAjaxController::class);

    Route::get('/laporan/pdf', [AmbilAntrianController::class, 'cetakPDF'])->name('laporan.pdf');

    //route logout
    Route::get('/logout', [SesiController::class, 'logout']);
});
