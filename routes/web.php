<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MahasiswaController;

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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'mahasiswa'], function () {
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
