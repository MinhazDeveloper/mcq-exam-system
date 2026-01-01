<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;


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

/// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // for admins
    Route::middleware('isAdmin')->group(function () {
        Route::post('/questions', [AdminController::class, 'storeQuestion']);
        Route::get('/admin/stats', [AdminController::class, 'dashboardStats']);
    });
    // for student and admin
    Route::get('/exams', [ExamController::class, 'index']);
});


