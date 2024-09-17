<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\QuestionChoiceController;
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

    Route::post('/login', [LoginAuthController::class, 'loginAuth']);

    // Route::middleware('IsLogin')->post('/logout', [LoginAuthController::class, 'logout']);

    Route::group(['prefix' => 'v1'], function () {
    
        Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sactum']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::apiResource('/payment', PaymentController::class);

        Route::post('/questions', [QuestionChoiceController::class, 'store']);
        Route::post('/questions/{id}/check', [QuestionChoiceController::class, 'checkAnswer']);

        // Route::apiResource('/siswas', SiswaController::class);

        Route::apiResource('/siswa', SiswaController::class);

        Route::apiResource('/guru', GuruController::class);

        Route::apiResource('/mapel', MapelController::class);

        Route::apiResource('/absensi', KehadiranController::class);

        Route::apiResource('/kelas', KelasController::class);
        Route::get('/listSiswaByKelas', [KelasController::class, 'listSiswaByKelas']);
        
    });