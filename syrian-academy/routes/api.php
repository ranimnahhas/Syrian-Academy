<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\TeacherController;
use App\Http\Controllers\Api\V1\EnrollmentRequestController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\LessonController;
use App\Http\Controllers\Api\V1\LessonQuestionController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ============ PUBLIC AUTH ============
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/contacts', [ContactController::class, 'store']);

    // ============ PUBLIC READ (ثابتة) ============
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/courses/latest', [CourseController::class, 'latest']);
    Route::get('/courses/free', [CourseController::class, 'free']);
    Route::get('/courses/paid', [CourseController::class, 'paid']);
    Route::get('/courses/most-enrolled', [CourseController::class, 'mostEnrolled']);
    Route::get('/courses/search', [CourseController::class, 'search']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);

    // ============ PUBLIC READ (ديناميكية) ============
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/courses', [CategoryController::class, 'courses']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::get('/teachers/{id}/courses', [TeacherController::class, 'courses']);
    Route::get('/courses/{courseId}/reviews', [ReviewController::class, 'courseReviews']);
    Route::get('/courses/{courseId}/lessons', [LessonController::class, 'courseLessons']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    // ============ PROTECTED ============
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::delete('/account', [AuthController::class, 'deleteAccount']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        // Enrollment
        Route::post('/enrollment-requests', [EnrollmentRequestController::class, 'store']);
        Route::get('/my-enrollments', [EnrollmentRequestController::class, 'myRequests']);
        Route::post('/activate-course', [EnrollmentRequestController::class, 'activate']);

        // Teacher
        Route::get('/my-courses', [CourseController::class, 'myCourses']);

        // Favorites
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
        Route::put('/favorites/note', [FavoriteController::class, 'updateNote']);
        Route::put('/favorites/position', [FavoriteController::class, 'updatePosition']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread', [NotificationController::class, 'unread']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications', [NotificationController::class, 'store']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

        // Lessons
        Route::get('/lessons/{id}', [LessonController::class, 'show']);

        // Questions - ثابتة
        Route::get('/my-questions', [LessonQuestionController::class, 'myQuestions']);
        Route::post('/questions', [LessonQuestionController::class, 'store']);
        Route::get('/lessons/{lessonId}/questions', [LessonQuestionController::class, 'lessonQuestions']);
        Route::post('/questions/{id}/answer', [LessonQuestionController::class, 'answer']);
        Route::get('/questions-pending', [LessonQuestionController::class, 'pending']);
        // Questions - ديناميكية
        Route::get('/questions/{id}', [LessonQuestionController::class, 'show']);

        // ============ ADMIN ONLY ============
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

            // Enrollment Requests
            Route::get('/enrollment-requests/filter', [EnrollmentRequestController::class, 'filter']);
            Route::get('/enrollment-requests', [EnrollmentRequestController::class, 'index']);
            Route::get('/enrollment-requests/{id}', [EnrollmentRequestController::class, 'show']);
            Route::post('/enrollment-requests/{id}/approve', [EnrollmentRequestController::class, 'approve']);
            Route::post('/enrollment-requests/{id}/reject', [EnrollmentRequestController::class, 'reject']);
            Route::post('/enrollment-requests/{id}/regenerate-code', [EnrollmentRequestController::class, 'regenerateCode']);
            Route::post('/enrollment-requests/{id}/cancel-code', [EnrollmentRequestController::class, 'cancelCode']);

            // Reviews
            Route::get('/reviews/pending', [ReviewController::class, 'pending']);
            Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve']);

            // Settings
            Route::put('/settings/{key}', [SettingController::class, 'update']);
            Route::delete('/settings/{key}', [SettingController::class, 'destroy']);

            // Reports
            Route::get('/reports/students/list', [AuthController::class, 'studentsList']);
            Route::get('/reports/students', [AuthController::class, 'studentsReport']);

            // Lessons
            Route::post('/lessons', [LessonController::class, 'store']);
            Route::put('/lessons/{id}', [LessonController::class, 'update']);
            Route::delete('/lessons/{id}', [LessonController::class, 'destroy']);

            // Questions - عمليات
            
            Route::post('/questions/{id}/close', [LessonQuestionController::class, 'close']);
            Route::delete('/questions/{id}', [LessonQuestionController::class, 'destroy']);

            // Contacts
            Route::get('/contacts/unread', [ContactController::class, 'unread']);
            Route::get('/contacts', [ContactController::class, 'index']);
            Route::get('/contacts/{id}', [ContactController::class, 'show']);
            Route::put('/contacts/{id}/read', [ContactController::class, 'markAsRead']);
            Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

            // Dashboard
            Route::get('/dashboard/stats', [DashboardController::class, 'index']);
            Route::get('/dashboard/recent-requests', [DashboardController::class, 'recentRequests']);
            Route::get('/dashboard/recent-contacts', [DashboardController::class, 'recentContacts']);

            Route::get('/dashboard/revenue', [DashboardController::class, 'revenueStats']);
        });
    });
});