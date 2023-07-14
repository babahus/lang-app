<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\StageController;

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
Route::get('login/{provider}', [AuthController::class ,'getProviderLink']);
Route::get('login/{provider}/callback', [AuthController::class ,'handleProviderCallback']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('/exercise', ExerciseController::class)->except(
            'show','index'
        );
        Route::get('/admin/roles', [AdminController::class, 'getRoles']);
        Route::apiResource('/admin', AdminController::class);
    });
    Route::apiResource('/course', CourseController::class);
    Route::apiResource('/stage', StageController::class)->except('index');
    Route::get('/stages/{course_id}', [StageController::class, 'index']);

    Route::post('/exercise/solve', [ExerciseController::class, 'solving']);
    Route::get('/exercise', [ExerciseController::class, 'index']);
    Route::post('/exercise/attach', [ExerciseController::class, 'attachExerciseToStageCourses']);
    Route::post('/exercise/detach', [ExerciseController::class, 'detachExerciseToStageCourses']);
    Route::get('/exercise/upload/{id}', [ExerciseController::class, 'uploadAudioAndTranscript']);
    Route::get('/exercise/{type}/{id}', [ExerciseController::class, 'show']);
    Route::get('/exercise/{type}', [ExerciseController::class, 'getExercisesByType']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
Route::post('/webhook', [ExerciseController::class, 'webHook']);