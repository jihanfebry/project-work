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


Route::group(['prefix' => 'v1'], function () {
    // Routes for User management
   
        Route::get('/users', [UserController::class, 'index']);        // List all users
        Route::post('/users', [UserController::class, 'store']);       // Create a new user
        Route::get('/users/{id}', [UserController::class, 'show']);    // Show specific user
        Route::put('/users/{id}', [UserController::class, 'update']);  // Update a user
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete a user


        Route::apiResource('/payment', PaymentController::class);

    

        Route::post('/question', [QuestionChoiceController::class, 'store']);
        Route::get('/question/{id}', [QuestionChoiceController::class, 'show']);
        Route::post('/question/{id}/check', [QuestionChoiceController::class, 'checkAnswer']);



        Route::apiResource('/siswas', SiswaController::class);

        Route::apiResource('/siswa', SiswaController::class);

        Route::apiResource('/guru', GuruController::class);

        Route::apiResource('/mapel', MapelController::class);

        Route::apiResource('/absensi', KehadiranController::class);

        Route::apiResource('/kelas', KelasController::class);
        Route::get('/listSiswaByKelas', [KelasController::class, 'listSiswaByKelas']);


    // Alternatively, you can use Route::apiResource if all routes are required to be authenticated
    // // Route::apiResource('users', UserController::class)->middleware('auth:sanctum');

    // // Routes for Payment management
    // Route::apiResource('payment', PaymentController::class)->middleware('auth:sanctum');

    // // Routes for Mapel management
    // Route::apiResource('mapel', MapelController::class)->middleware('auth:sanctum');
});

