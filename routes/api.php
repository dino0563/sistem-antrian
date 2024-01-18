<?php

use App\Http\Controllers\Api\AntrianController;
use App\Http\Controllers\Api\LoketController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
// Users
Route::get('/users', [UserController::class, 'index'])->middleware('checkHost');
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('checkHost');
Route::post('/users', [UserController::class, 'store'])->middleware('checkHost');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('checkHost');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('checkHost');
Route::get('/users/{name}', [UserController::class, 'search'])->middleware('checkHost');

// Lokets
Route::get('/lokets', [LoketController::class, 'index'])->middleware('checkHost');
Route::get('/lokets/{id}', [LoketController::class, 'show'])->middleware('checkHost');
Route::post('/lokets', [LoketController::class, 'store'])->middleware('checkHost');
Route::put('/lokets/{id}', [LoketController::class, 'update'])->middleware('checkHost');
Route::delete('/lokets/{id}', [LoketController::class, 'destroy'])->middleware('checkHost');

// Antrian
Route::get('/antrians', [AntrianController::class, 'index'])->middleware('checkHost');
Route::post('/antrians', [AntrianController::class, 'store'])->middleware('checkHost')->name('ambil-antrian');
Route::put('/antrians/{id}', [AntrianController::class, 'update'])->middleware('checkHost');
Route::get('/antrians/{id}', [AntrianController::class, 'show'])->middleware('checkHost');

//Page
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{id}', [PageController::class, 'show']);
Route::post('/pages', [PageController::class, 'store']);
Route::put('/pages/{id}', [PageController::class, 'update']);
Route::delete('/pages/{id}', [PageController::class, 'destroy']);
