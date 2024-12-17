<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\QuestionChoiceController;
use App\Http\Controllers\TekaTekiController;
use App\Http\Controllers\LoginAuthController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {    
//     return $request->user();
// });

    Route::post('/login', [LoginAuthController::class, 'login'])->name('login');



    // Route::middleware('IsLogin')->post('/logout', [LoginAuthController::class, 'logout']);

    Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
        Route::apiResource('/payment', PaymentController::class);
    
        Route::post('/question', [QuestionChoiceController::class, 'store']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'show']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'update']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'destroy']);
        Route::post('/question/{id}/check', [QuestionChoiceController::class, 'checkAnswer']);
    
        Route::post('/add-teka-teki', [TekaTekiController::class, 'store']); // Untuk menambah teka-teki baru oleh admin
        Route::get('/teka-teki', [TekaTekiController::class, 'index']); // Untuk mendapatkan teka-teki
        Route::post('/teka-teki/cek', [TekaTekiController::class, 'cekJawaban']);

        Route::post('/absensi', [AbsensiController::class, 'store']);
        Route::get('/kelas/{kelas_id}/siswa', [AbsensiController::class, 'getSiswaByKelas']);

        Route::apiResource('/siswa', SiswaController::class);
        Route::get('/list-siswa', [SiswaController::class, 'listSiswa']);
    
        Route::apiResource('/guru', GuruController::class);
    
        Route::apiResource('/mapel', MapelController::class);

        Route::apiResource('/materi', MateriController::class);
    
        Route::apiResource('/kehadiran/{id}', KehadiranController::class);
        Route::get('/kehadiran/perbulan', [KehadiranController::class, 'kehadiranPerBulan']);
    
        Route::apiResource('/kelas', KelasController::class);
        Route::get('/kelas/listSiswaByKelas', [KelasController::class, 'listSiswaByKelas']);
    });