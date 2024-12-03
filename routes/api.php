
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PaymentReceiptsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\QuestionChoiceController;
use App\Http\Controllers\TekaTekiController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\QuestionEssayController;



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




    Route::post('/login', [LoginAuthController::class, 'login'])->name('login');

    // Route::middleware('IsLogin')->post('/logout', [LoginAuthController::class, 'logout']);

    Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::post('users/check-existing', [UserController::class, 'checkExistingUsers']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    

        Route::post('/payments/notify', [PaymentController::class, 'notifyUsers']);
        Route::post('/payments/upload', [PaymentController::class, 'uploadReceipt']);
        Route::put('/payments/validate/{id}', [PaymentController::class, 'validatePayment']);
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payments-image', [PaymentController::class, 'getUserWithImage']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);


        Route::get('/essay-questions', [QuestionEssayController::class, 'index']); // Menampilkan semua soal essay
        Route::post('/essay-questions', [QuestionEssayController::class, 'store']);
        Route::get('/essay-questions/{id}', [QuestionEssayController::class, 'show']);
        Route::put('/essay-questions/{id}', [QuestionEssayController::class, 'update']);
        Route::delete('/essay-questions/{id}', [QuestionEssayController::class, 'destroy']);
    
        Route::get('/question-choice', [QuestionChoiceController::class, 'index']);
        Route::post('/question-choice', [QuestionChoiceController::class, 'store']);
        Route::get('/question-choice/{id}', [QuestionChoiceController::class, 'show']);
        Route::put('question-choice/{questionChoice}/questions/{question}', [QuestionChoiceController::class, 'update']);
        Route::delete('question-choice/{questionChoice}/questions/{question}', [QuestionChoiceController::class, 'destroy']);
        Route::delete('question-choice/{qestionChoiceTitleID}', [QuestionChoiceController::class, 'deleteAll']);
    
        Route::post('/teka-teki', [TekaTekiController::class, 'store']); // Untuk menambah teka-teki baru oleh admin
        Route::get('/teka-teki', [TekaTekiController::class, 'index']); // Untuk mendapatkan teka-teki
    
        Route::apiResource('/siswa', SiswaController::class);
        Route::get('/list-siswa', [SiswaController::class, 'listSiswa']);
    
        Route::apiResource('/guru', GuruController::class);
    
        Route::apiResource('/mapel', MapelController::class);
    
        Route::apiResource('/absensi', KehadiranController::class);
    
        Route::apiResource('/kelas', KelasController::class);
        Route::get('/listSiswaByKelas', [KelasController::class, 'listSiswaByKelas']);
    });
