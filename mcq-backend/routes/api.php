<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\InstructorController; 
use App\Http\Controllers\StudentController; 

use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController; 


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
// Route::get('/test', function () {
//     return response()->json([
//         'message' => 'API route is working'
//     ]);
// });

/// Public Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Password reset
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

/// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // email verification notification
    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);

    // for admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    });
    //for instructors
    Route::middleware(['role:instructor'])->prefix('instructor')->group(function () {
        Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');

        Route::get('/exams', [ExamController::class, 'getExamList']);
        Route::get('/question/list', [QuestionController::class, 'index']);
        Route::get('/question/{id}', [QuestionController::class, 'show']);
        Route::post('/question', [QuestionController::class, 'store']);
        Route::put('/question/{id}', [QuestionController::class, 'update']);
        Route::delete('/question/{id}', [QuestionController::class, 'destroy']);
    });
    //for students
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/exams', [ExamController::class, 'getExamList']);
        Route::get('/exams/{id}/questions', [QuestionController::class, 'getQuestionsForStudent']);
        Route::get('/exams/list', [ExamController::class, 'index']);
    });

});




