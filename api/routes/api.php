<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProgressExerciseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\ExerciseGeneratorController;

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

Route::post('/register', [AuthController::class, 'register' ])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('login/{provider}', [AuthController::class ,'getProviderLink']);
Route::post('login/{provider}/callback', [AuthController::class ,'handleProviderCallback']);

//Route::post('login/{provider}', [AuthController::class ,'authenticateViaCode']);

Route::post('/forgot-password', [ProfileController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ProfileController::class, 'resetPassword'])->name('password.reset');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('/exercise', ExerciseController::class)->except(
            'show','index'
        );
        Route::get('/admin/roles', [AdminController::class, 'getRoles']);
        Route::apiResource('/admin', AdminController::class);
        Route::post('/exercise/generate', [ExerciseGeneratorController::class, 'generate']);
    });

    Route::get('users/completed-exercises/{userId}', [ProgressExerciseController::class, 'getUserCompletedExercises']);
    Route::delete('exercises/users/delete-progress', [ProgressExerciseController::class, 'deleteUserProgress']);
    Route::get('users/{user_id}/stages/{stage_id}/progress', [ProgressExerciseController::class, 'getProgressByStage']);

    Route::middleware(['email.confirmed'])->group(function () {
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.change');
        Route::post('/change-email', [ProfileController::class, 'changeEmail'])->name('email.change');
        Route::get('/profile-info', [ProfileController::class, 'getProfileInfo'])->name('profile.info');
    });
    Route::get('/get-cache-info', [ProfileController::class, 'getCachedInfo']);
    Route::post('/email/verification-notification', [ProfileController::class, 'sendVerificationNotification'])
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [ProfileController::class, 'verify'])->name('verification.verify');

    Route::apiResource('/course', CourseController::class);
    Route::get('/courses/user/{user}', [CourseController::class, 'getAttachedCoursesToUser']);
    Route::apiResource('/stage', StageController::class)->except('index');
    Route::get('/stages/{course_id}', [StageController::class, 'index']);

    Route::post('/course/attach', [CourseController::class, 'attach']);
    Route::post('/course/detach', [CourseController::class, 'detach']);
    Route::get('/course/{course}/is-attached', [CourseController::class, 'checkIfUserAttachedCourse']);
    Route::get('/course/{course}/is-creator', [CourseController::class, 'checkIfUserIsCourseCreator']);

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
