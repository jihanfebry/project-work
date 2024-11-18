<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EssayAnswerController;
use App\Http\Controllers\QuestionEssayController;
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

Route::prefix('question-essays')->group(function () {
    Route::get('/', [QuestionEssayController::class, 'index']);
    Route::post('/', [QuestionEssayController::class, 'store']);
    Route::get('/{questionEssay}', [QuestionEssayController::class, 'show']);
    Route::put('/{questionEssay}', [QuestionEssayController::class, 'update']);
    Route::delete('/{questionEssay}', [QuestionEssayController::class, 'destroy']);
});

Route::post('/essay-answers', [EssayAnswerController::class, 'store']); 
Route::put('/essay-answers/{id}', [EssayAnswerController::class, 'update']);
Route::get('/essay-answers/question/{question_id}', [EssayAnswerController::class, 'showByQuestion']);
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


        Route::post('/questions', [QuestionChoiceController::class, 'store']);
        Route::post('/questions/{id}/check', [QuestionChoiceController::class, 'checkAnswer']);




    Route::post('/login', [LoginAuthController::class, 'login'])->name('login');

    // Route::middleware('IsLogin')->post('/logout', [LoginAuthController::class, 'logout']);

    Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::post('users/check-existing', [UserController::class, 'checkExistingUsers']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
        // Route::post('/payment-receipts', [PaymentReceiptsController::class, 'notifyUsers']);
        // Route::get('/payment-receipts', [PaymentReceiptsController::class, 'index']);
        // Route::get('/payment-receipts/{id}', [PaymentReceiptsController::class, 'show']);
        // Route::put('/payment-receipts/status/{id}', [PaymentReceiptsController::class, 'updateStatus']);
        // Route::post('/payment-receipts-upload', [PaymentController::class, 'uploadReceipt']);

        Route::post('/payments/notify', [PaymentController::class, 'notifyUsers']);
        Route::post('/payments/upload', [PaymentController::class, 'uploadReceipt']);
        Route::put('/payments/validate/{id}', [PaymentController::class, 'validatePayment']);
        Route::get('/payments', [PaymentController::class, 'index']);

        // Route::apiResource('/siswas', Controller::class);

    // Alternatively, you can use Route::apiResource if all routes are required to be authenticated
    // // Route::apiResource('users', UserController::class)->middleware('auth:sanctum');

    // // Routes for Payment management
    // Route::apiResource('payment', PaymentController::class)->middleware('auth:sanctum');

    // // Routes for Mapel management
    // Route::apiResource('mapel', MapelController::class)->middleware('auth:sanctum');
});
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
