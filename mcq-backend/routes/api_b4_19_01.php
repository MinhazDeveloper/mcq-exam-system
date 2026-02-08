<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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

    // for admins
    Route::middleware('isAdmin')->group(function () {
        Route::get('/admin/exams', [ExamController::class, 'getExamList']);
        Route::get('/admin/question/list', [QuestionController::class, 'index']);
        Route::get('/admin/question/{id}', [QuestionController::class, 'show']);

        Route::post('/admin/question', [QuestionController::class, 'store']);
        Route::put('/admin/question/{id}', [QuestionController::class, 'update']);
        Route::delete('/admin/question/{id}', [QuestionController::class, 'destroy']);
        
        Route::get('/admin/stats', [QuestionController::class, 'dashboardStats']);
    });
    // for student and admin
    Route::get('/student/exams', [ExamController::class, 'getExamList']);
    Route::get('/student/exams/{id}/questions', [QuestionController::class, 'getQuestionsForStudent']);
    
    Route::get('/exams', [ExamController::class, 'index']);


    //start
    // এডমিন রুটস (System Management)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index']);
        Route::get('/users', [AdminController::class, 'manageUsers']);
    });

    // ইন্সট্রাক্টর রুটস (Exam Creation)
    Route::middleware(['role:instructor'])->prefix('instructor')->group(function () {
        Route::post('/exams/create', [ExamController::class, 'store']);
        Route::get('/my-students', [ExamController::class, 'studentList']);
    });

    // স্টুডেন্ট রুটস (Taking Exams)
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/exams', [StudentController::class, 'availableExams']);
        Route::post('/exams/submit', [StudentController::class, 'submitExam']);
    });

});




