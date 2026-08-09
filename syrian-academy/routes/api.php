<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\TeacherController;
use App\Http\Controllers\Api\V1\EnrollmentRequestController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\NotificationController;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth - Public
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Public Read
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::get('/courses/latest', [CourseController::class, 'latest']);
    Route::get('/courses/free', [CourseController::class, 'free']);
    Route::get('/courses/paid', [CourseController::class, 'paid']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/teachers/{id}/courses', [TeacherController::class, 'courses']);
    Route::get('/categories/{id}/courses', [CategoryController::class, 'courses']);
    

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::delete('/account', [AuthController::class, 'deleteAccount']);

        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::post('/enrollment-requests', [EnrollmentRequestController::class, 'store']);
        Route::get('/my-enrollments', [EnrollmentRequestController::class, 'myRequests']);
        //لمعلم هاد فقط
        Route::get('/my-courses', [CourseController::class, 'myCourses']);
        Route::post('/activate-course', [EnrollmentRequestController::class, 'activate']);

        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
        Route::put('/favorites/note', [FavoriteController::class, 'updateNote']);
        Route::put('/favorites/position', [FavoriteController::class, 'updatePosition']);

        // Notifications Routes
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread', [NotificationController::class, 'unread']);
        Route::post('/notifications', [NotificationController::class, 'store']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

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

            Route::get('/enrollment-requests', [EnrollmentRequestController::class, 'index']);
            Route::get('/enrollment-requests/{id}', [EnrollmentRequestController::class, 'show']);

            // Enrollment Requests - عمليات الأدمن
            Route::post('/enrollment-requests/{id}/approve', [EnrollmentRequestController::class, 'approve']);
            Route::post('/enrollment-requests/{id}/reject', [EnrollmentRequestController::class, 'reject']);
            Route::post('/enrollment-requests/{id}/regenerate-code', [EnrollmentRequestController::class, 'regenerateCode']);
            Route::post('/enrollment-requests/{id}/cancel-code', [EnrollmentRequestController::class, 'cancelCode']);
        });
    });
});