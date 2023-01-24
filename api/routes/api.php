<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class, 'register' ]);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/exercises/for-user', [ExerciseController::class, 'getAllExercises']);
    Route::get('/exercises/{type}', [ExerciseController::class, 'getExercisesByType']);
    Route::post('/exercise/{type}/{id}', [ExerciseController::class, 'getExerciseByIdAndType']);
});
