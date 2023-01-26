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
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('/exercise', ExerciseController::class)->except(
            'show','index'
        );
    });
    Route::get('/exercise', [ExerciseController::class, 'index']);
    Route::post('/exercise/attach', [ExerciseController::class, 'attach']);
    Route::get('/exercise/{type}/{id}', [ExerciseController::class, 'show']);
    Route::get('/exercise/{type}', [ExerciseController::class, 'getExercisesByType']);
});
