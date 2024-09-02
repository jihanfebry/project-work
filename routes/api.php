<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EssayAnswerController;
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