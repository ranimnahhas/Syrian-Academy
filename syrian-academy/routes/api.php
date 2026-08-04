<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth - Public
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public Read
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/teachers/{id}/courses', [TeacherController::class, 'courses']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Admin Only
        Route::middleware('admin')->group(function () {
            // Categories
            Route::post('/categories', [CategoryController::class, 'store']);
            Route::put('/categories/{id}', [CategoryController::class, 'update']);
            Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

            // Teachers
               Route::post('/teachers', [TeacherController::class, 'store']);
               Route::put('/teachers/{id}', [TeacherController::class, 'update']);
               Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

            // Courses
               Route::post('/courses', [CourseController::class, 'store']);
               Route::put('/courses/{id}', [CourseController::class, 'update']);
               Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
        });
    });
});