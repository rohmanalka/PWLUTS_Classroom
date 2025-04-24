<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KelasControler;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TugasmhsController;

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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [WelcomeController::class, 'index'])->middleware('auth:mahasiswa,dosen');

Route::middleware(['auth:dosen'])->prefix('mahasiswa')->group(function () {
    Route::get('/', [MahasiswaController::class, 'index']);
    Route::post('/list', [MahasiswaController::class, 'list']);
    Route::get('/create', [MahasiswaController::class, 'create']);
    Route::post('/ajax', [MahasiswaController::class, 'store']);
    Route::get('/{id}/edit', [MahasiswaController::class, 'edit']);
    Route::put('/{id}/update', [MahasiswaController::class, 'update']);
    Route::get('/{id}/delete', [MahasiswaController::class, 'confirm']);
    Route::delete('/{id}/delete', [MahasiswaController::class, 'delete']);
    Route::get('/{id}/show', [MahasiswaController::class, 'show']);
});

Route::middleware(['auth:dosen'])->prefix('dosen')->group(function () {
    Route::get('/', [DosenController::class, 'index']);
    Route::post('/list', [DosenController::class, 'list']);
    Route::get('/create', [DosenController::class, 'create']);
    Route::post('/ajax', [DosenController::class, 'store']);
    Route::get('/{id}/edit', [DosenController::class, 'edit']);
    Route::put('/{id}/update', [DosenController::class, 'update']);
    Route::get('/{id}/delete', [DosenController::class, 'confirm']);
    Route::delete('/{id}/delete', [DosenController::class, 'delete']);
    Route::get('/{id}/show', [DosenController::class, 'show']);
});

Route::middleware(['auth:dosen'])->prefix('kelas')->group(function () {
    Route::get('/', [KelasControler::class, 'index']);
    Route::post('/list', [KelasControler::class, 'list']);
    Route::get('/create', [KelasControler::class, 'create']);
    Route::post('/ajax', [KelasControler::class, 'store']);
    Route::get('/{id}/edit', [KelasControler::class, 'edit']);
    Route::put('/{id}/update', [KelasControler::class, 'update']);
    Route::get('/{id}/delete', [KelasControler::class, 'confirm']);
    Route::delete('/{id}/delete', [KelasControler::class, 'delete']);
    Route::get('/{id}/show', [KelasControler::class, 'show']);
});

Route::middleware(['auth:dosen'])->prefix('tugas')->group(function () {
    Route::get('/', [TugasController::class, 'index']);
    Route::post('/list', [TugasController::class, 'list']);
    Route::get('/create', [TugasController::class, 'create']);
    Route::post('/ajax', [TugasController::class, 'store']);
    Route::get('/{id}/edit', [TugasController::class, 'edit']);
    Route::put('/{id}/update', [TugasController::class, 'update']);
});

Route::middleware(['auth:mahasiswa'])->prefix('tugasmhs')->group(function () {
    Route::get('/', [TugasmhsController::class, 'index']);
    Route::post('/list', [TugasmhsController::class, 'list']);
    Route::get('/upload/{id}', [TugasmhsController::class, 'uploadForm']);
    Route::post('/upload', [TugasmhsController::class, 'upload']);
});
