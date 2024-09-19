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
    
<<<<<<< HEAD

=======
>>>>>>> 75a6fad97a3d37fd9777dbf609efe34269761bb9
        Route::get('/question', [QuestionChoiceController::class, 'index']);
        Route::post('/question', [QuestionChoiceController::class, 'store']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'show']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'update']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'destroy']); 
    
        Route::post('/add-teka-teki', [TekaTekiController::class, 'store']); // Untuk menambah teka-teki baru oleh admin
        Route::get('/teka-teki', [TekaTekiController::class, 'index']); // Untuk mendapatkan teka-teki
        Route::post('/teka-teki/cek', [TekaTekiController::class, 'cekJawaban']); // Untuk mengecek jawaban
    
        Route::apiResource('/siswas', SiswaController::class);
    
    
        Route::apiResource('/siswa', SiswaController::class);
    
        Route::apiResource('/guru', GuruController::class);
    
        Route::apiResource('/mapel', MapelController::class);
    
        Route::apiResource('/absensi', KehadiranController::class);
    
        Route::apiResource('/kelas', KelasController::class);
        Route::get('/listSiswaByKelas', [KelasController::class, 'listSiswaByKelas']);
    });
    

        
  

