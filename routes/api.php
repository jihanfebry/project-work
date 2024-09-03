<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\EssayAnswerController;
use App\Http\Controllers\QuestionEssayController;

=======
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\QuestionChoiceController;
>>>>>>> 1d0e17f0e16fe5ecbb3716a55adc9a69907c7a8b

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

<<<<<<< HEAD
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
=======
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




        Route::apiResource('/siswas', SiswaController::class);


        // Route::apiResource('/siswas', Controller::class);

    // Alternatively, you can use Route::apiResource if all routes are required to be authenticated
    // // Route::apiResource('users', UserController::class)->middleware('auth:sanctum');

    // // Routes for Payment management
    // Route::apiResource('payment', PaymentController::class)->middleware('auth:sanctum');

    // // Routes for Mapel management
    // Route::apiResource('mapel', MapelController::class)->middleware('auth:sanctum');
});
>>>>>>> 1d0e17f0e16fe5ecbb3716a55adc9a69907c7a8b
