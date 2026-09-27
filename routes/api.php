<?php

use App\Http\Controllers\Api\V1\Admin\AdminReviewController;
use App\Http\Controllers\Api\V1\Admin\AdminAuditLogController;
use App\Http\Controllers\Api\V1\Admin\AdminBatchController;
use App\Http\Controllers\Api\V1\Admin\AdminBlogController;
use App\Http\Controllers\Api\V1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminCourseController;
use App\Http\Controllers\Api\V1\Admin\AdminCurriculumController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminEbookController;
use App\Http\Controllers\Api\V1\Admin\AdminEmployeeController;
use App\Http\Controllers\Api\V1\Admin\AdminEnrollmentController;
use App\Http\Controllers\Api\V1\Admin\AdminInstructorController;
use App\Http\Controllers\Api\V1\Admin\AdminLeadController;
use App\Http\Controllers\Api\V1\Admin\AdminNoticeController;
use App\Http\Controllers\Api\V1\Admin\AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\AdminUploadController;
use App\Http\Controllers\Api\V1\Admin\AdminWebinarController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Public\BlogController;
use App\Http\Controllers\Api\V1\Public\ContactController;
use App\Http\Controllers\Api\V1\Public\CourseController;
use App\Http\Controllers\Api\V1\Public\EbookController;
use App\Http\Controllers\Api\V1\Public\HomeController;
use App\Http\Controllers\Api\V1\Public\WebinarController;
use App\Http\Controllers\Api\V1\Student\CheckoutController;
use App\Http\Controllers\Api\V1\Student\PaymentController;
use App\Http\Controllers\Api\V1\Student\StudentCourseController;
use App\Http\Controllers\Api\V1\Student\StudentDashboardController;
use App\Http\Controllers\Api\V1\Student\StudentNoteController;
use App\Http\Controllers\Api\V1\Student\StudentNoticeController;
use App\Http\Controllers\Api\V1\Student\StudentNotificationController;
use App\Http\Controllers\Api\V1\Student\StudentOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // 1. Public Content APIs
    Route::prefix('public')->group(function () {
        Route::get('/home', [HomeController::class, 'index']);
        
        // Courses
        Route::get('/courses', [CourseController::class, 'index']);
        Route::get('/courses/{slug}', [CourseController::class, 'show']);

        // Webinars
        Route::get('/webinars', [WebinarController::class, 'index']);
        Route::get('/webinars/{slug}', [WebinarController::class, 'show']);
        Route::post('/webinars/{slug}/register', [WebinarController::class, 'register'])->middleware('throttle:15,1');

        // Ebooks
        Route::get('/ebooks', [EbookController::class, 'index']);
        Route::get('/ebooks/{slug}', [EbookController::class, 'show']);
        Route::post('/ebooks/{slug}/download', [EbookController::class, 'trackDownload']);

        // Blog
        Route::get('/blog', [BlogController::class, 'index']);
        Route::get('/blog/{slug}', [BlogController::class, 'show']);
        Route::post('/blog/{slug}/comments', [BlogController::class, 'storeComment'])->middleware('throttle:10,1');

        // Mentors & Instructors
        Route::get('/instructors', [\App\Http\Controllers\Api\V1\Public\InstructorController::class, 'index']);

        // Contact & Lead Capture
        Route::post('/contact', [ContactController::class, 'submitContact'])->middleware('throttle:5,1');
        Route::post('/leads', [ContactController::class, 'captureLead'])->middleware('throttle:5,1');

        // Direct Manual Checkout & Coupon Validation for Public
        Route::post('/checkout/validate-coupon', [CheckoutController::class, 'validateCoupon'])->middleware('throttle:20,1');
        Route::post('/checkout/direct-manual', [CheckoutController::class, 'directManualCheckout'])->middleware('throttle:15,1');
    });

    // 2. Authentication Public Routes (Rate limited)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:10,1');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:10,1');
    });

    // 3. Payment Webhook Callback (Public IPN endpoint)
    Route::post('/payments/webhook/{gateway}', [PaymentController::class, 'handleWebhook']);

    // 4. Authenticated Student & User APIs
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Profile & Security
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::put('/password', [AuthController::class, 'updatePassword']);
            Route::get('/sessions', [AuthController::class, 'getSessions']);
            Route::delete('/sessions/{id}', [AuthController::class, 'revokeSession']);
            Route::post('/sessions/revoke-others', [AuthController::class, 'revokeOtherSessions']);
        });

        // Checkout & Payment
        Route::prefix('checkout')->group(function () {
            Route::post('/validate-coupon', [CheckoutController::class, 'validateCoupon']);
            Route::post('/process', [CheckoutController::class, 'process']);
            Route::post('/direct-manual', [CheckoutController::class, 'directManualCheckout']);
        });

        Route::prefix('payments')->group(function () {
            Route::post('/manual-submit', [PaymentController::class, 'submitManual']);
        });

        // Student Portal APIs
        Route::prefix('student')->group(function () {
            Route::get('/dashboard', [StudentDashboardController::class, 'index']);
            Route::get('/courses', [StudentCourseController::class, 'myCourses']);
            Route::get('/courses/{courseId}/learn', [StudentCourseController::class, 'classroom']);
            Route::post('/courses/{courseId}/lessons/{lessonId}/complete', [StudentCourseController::class, 'markLessonComplete']);
            Route::post('/courses/{courseId}/lessons/{lessonId}/uncomplete', [StudentCourseController::class, 'unmarkLessonComplete']);
            Route::post('/courses/{courseId}/lessons/{lessonId}/progress', [StudentCourseController::class, 'savePlaybackPosition']);
            Route::post('/courses/{courseId}/lessons/{lessonId}/quiz', [StudentCourseController::class, 'submitQuiz'])->middleware('throttle:30,1');

            // Personal Lesson Notes (Google Docs–style editor)
            Route::get('/courses/{courseId}/lessons/{lessonId}/note', [StudentNoteController::class, 'showForLesson']);
            Route::put('/courses/{courseId}/lessons/{lessonId}/note', [StudentNoteController::class, 'upsertForLesson'])->middleware('throttle:120,1');
            Route::get('/notes', [StudentNoteController::class, 'index']);
            Route::post('/notes', [StudentNoteController::class, 'store']);
            Route::get('/notes/{id}', [StudentNoteController::class, 'show']);
            Route::put('/notes/{id}', [StudentNoteController::class, 'update'])->middleware('throttle:120,1');
            Route::delete('/notes/{id}', [StudentNoteController::class, 'destroy']);

            // In-app Notifications (new notices, modules, lessons)
            Route::get('/notifications', [StudentNotificationController::class, 'index']);
            Route::get('/notifications/unread-count', [StudentNotificationController::class, 'unreadCount']);
            Route::post('/notifications/read-all', [StudentNotificationController::class, 'markAllAsRead']);
            Route::post('/notifications/{id}/read', [StudentNotificationController::class, 'markAsRead']);

            // Notice Board for Student
            Route::get('/notices', [StudentNoticeController::class, 'index']);
            Route::get('/notices/{id}', [StudentNoticeController::class, 'show']);
            Route::post('/notices/{id}/read', [StudentNoticeController::class, 'markAsRead']);
            Route::post('/notices/read-all', [StudentNoticeController::class, 'markAllAsRead']);
            Route::post('/notices/mark-all-read', [StudentNoticeController::class, 'markAllAsRead']);

            // Orders & Invoices
            Route::get('/orders', [StudentOrderController::class, 'index']);
            Route::get('/orders/{id}', [StudentOrderController::class, 'show']);
        });

        // 5. Admin & Manager Management APIs (Role Protected)
        Route::middleware(['role:SuperAdmin|Admin|Manager|Counselor|Worker|Moderator'])->prefix('admin')->group(function () {
            // Dashboard & Auditing
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);
            Route::get('/audit-logs', [AdminAuditLogController::class, 'index']);

            // Leads & CRM Pipeline
            Route::get('/leads', [AdminLeadController::class, 'index']);
            Route::post('/leads', [AdminLeadController::class, 'store']);
            Route::get('/leads/stats/content-wise', [AdminLeadController::class, 'statsContentWise']);
            Route::get('/leads/{id}', [AdminLeadController::class, 'show']);
            Route::put('/leads/{id}', [AdminLeadController::class, 'update']);
            Route::delete('/leads/{id}', [AdminLeadController::class, 'destroy']);
            Route::post('/leads/{id}/notes', [AdminLeadController::class, 'addNote']);
            Route::post('/leads/{id}/activities', [AdminLeadController::class, 'logActivity']);
            Route::post('/leads/{id}/convert-to-enrollment', [AdminLeadController::class, 'convertToEnrollment']);
            Route::post('/leads/{id}/accept', [AdminLeadController::class, 'accept']);

            // Employee name list — readable by all staff (for accepting leads)
            Route::get('/employees/options', [AdminEmployeeController::class, 'options']);

            // Employee directory management — Admin only (create / edit / remove)
            Route::middleware(['role:SuperAdmin|Admin'])->group(function () {
                Route::get('/employees', [AdminEmployeeController::class, 'index']);
                Route::post('/employees', [AdminEmployeeController::class, 'store']);
                Route::put('/employees/{id}', [AdminEmployeeController::class, 'update']);
                Route::delete('/employees/{id}', [AdminEmployeeController::class, 'destroy']);
            });

            // Student Enrollments Management (Direct & Manual Enrollment Hub)
            Route::get('/enrollments', [AdminEnrollmentController::class, 'index']);
            Route::post('/enrollments', [AdminEnrollmentController::class, 'store']);
            Route::get('/enrollments/search-students', [AdminEnrollmentController::class, 'searchStudents']);
            Route::get('/enrollments/{id}', [AdminEnrollmentController::class, 'show']);
            Route::put('/enrollments/{id}', [AdminEnrollmentController::class, 'update']);
            Route::delete('/enrollments/{id}', [AdminEnrollmentController::class, 'destroy']);

            // Courses
            Route::get('/courses', [AdminCourseController::class, 'index']);
            Route::post('/courses', [AdminCourseController::class, 'store']);
            Route::get('/courses/{id}', [AdminCourseController::class, 'show']);
            Route::put('/courses/{id}', [AdminCourseController::class, 'update']);
            Route::delete('/courses/{id}', [AdminCourseController::class, 'destroy']);

            // Batches
            Route::get('/courses/{courseId}/batches', [AdminBatchController::class, 'index']);
            Route::post('/courses/{courseId}/batches', [AdminBatchController::class, 'store']);
            Route::put('/batches/{id}', [AdminBatchController::class, 'update']);
            Route::delete('/batches/{id}', [AdminBatchController::class, 'destroy']);

            // Curriculum & Lessons
            Route::get('/courses/{courseId}/curriculum', [AdminCurriculumController::class, 'getCurriculum']);
            Route::post('/courses/{courseId}/modules', [AdminCurriculumController::class, 'storeModule']);
            Route::put('/modules/{moduleId}', [AdminCurriculumController::class, 'updateModule']);
            Route::put('/modules/{moduleId}/toggle-publish', [AdminCurriculumController::class, 'toggleModulePublish']);
            Route::post('/courses/{courseId}/modules/reorder', [AdminCurriculumController::class, 'reorderModules']);
            Route::delete('/modules/{moduleId}', [AdminCurriculumController::class, 'deleteModule']);

            Route::post('/modules/{moduleId}/lessons', [AdminCurriculumController::class, 'storeLesson']);
            Route::get('/lessons/{lessonId}', [AdminCurriculumController::class, 'getLesson']);
            Route::put('/lessons/{lessonId}', [AdminCurriculumController::class, 'updateLesson']);
            Route::put('/lessons/{lessonId}/toggle-publish', [AdminCurriculumController::class, 'toggleLessonPublish']);
            Route::put('/lessons/{lessonId}/toggle-lock', [AdminCurriculumController::class, 'toggleLessonLock']);
            Route::post('/modules/{moduleId}/lessons/reorder', [AdminCurriculumController::class, 'reorderLessons']);
            Route::delete('/lessons/{lessonId}', [AdminCurriculumController::class, 'deleteLesson']);

            // Lesson Resources
            Route::post('/lessons/upload-media', [AdminCurriculumController::class, 'uploadLessonMedia']);
            Route::post('/uploads/image', [AdminUploadController::class, 'image'])->middleware('throttle:60,1');
            Route::post('/lessons/{lessonId}/resources', [AdminCurriculumController::class, 'storeResource']);
            Route::put('/resources/{resourceId}', [AdminCurriculumController::class, 'updateResource']);
            Route::delete('/resources/{resourceId}', [AdminCurriculumController::class, 'deleteResource']);

            // Notices & Announcements Management
            Route::get('/notices', [AdminNoticeController::class, 'index']);
            Route::post('/notices', [AdminNoticeController::class, 'store']);
            Route::get('/notices/{id}', [AdminNoticeController::class, 'show']);
            Route::put('/notices/{id}', [AdminNoticeController::class, 'update']);
            Route::put('/notices/{id}/toggle-publish', [AdminNoticeController::class, 'togglePublish']);
            Route::delete('/notices/{id}', [AdminNoticeController::class, 'destroy']);

            // Categories & Instructors
            Route::get('/categories', [AdminCategoryController::class, 'index']);
            Route::post('/categories', [AdminCategoryController::class, 'store']);
            Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
            Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

            Route::get('/instructors', [AdminInstructorController::class, 'index']);
            Route::post('/instructors', [AdminInstructorController::class, 'store']);
            Route::get('/instructors/{id}', [AdminInstructorController::class, 'show']);
            Route::put('/instructors/{id}', [AdminInstructorController::class, 'update']);
            Route::delete('/instructors/{id}', [AdminInstructorController::class, 'destroy']);

            // Course Reviews & Testimonials
            Route::get('/reviews', [AdminReviewController::class, 'index']);
            Route::post('/reviews', [AdminReviewController::class, 'store']);
            Route::put('/reviews/{id}', [AdminReviewController::class, 'update']);
            Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);

            // Webinars & Masterclasses
            Route::get('/webinars', [AdminWebinarController::class, 'index']);
            Route::post('/webinars', [AdminWebinarController::class, 'store']);
            Route::get('/webinars/{id}', [AdminWebinarController::class, 'show']);
            Route::put('/webinars/{id}', [AdminWebinarController::class, 'update']);
            Route::delete('/webinars/{id}', [AdminWebinarController::class, 'destroy']);

            // Webinar Speakers
            Route::get('/webinars/{webinarId}/speakers', [AdminWebinarController::class, 'speakers']);
            Route::post('/webinars/{webinarId}/speakers', [AdminWebinarController::class, 'storeSpeaker']);
            Route::put('/webinars/speakers/{speakerId}', [AdminWebinarController::class, 'updateSpeaker']);
            Route::delete('/webinars/speakers/{speakerId}', [AdminWebinarController::class, 'destroySpeaker']);

            // Webinar Registrations & Attendees
            Route::get('/webinars/{webinarId}/registrations', [AdminWebinarController::class, 'registrations']);
            Route::put('/webinars/registrations/{registrationId}', [AdminWebinarController::class, 'updateRegistration']);
            Route::delete('/webinars/registrations/{registrationId}', [AdminWebinarController::class, 'destroyRegistration']);

            // Blog & Knowledge Hub Management
            Route::get('/blogs', [AdminBlogController::class, 'index']);
            Route::post('/blogs', [AdminBlogController::class, 'store']);
            Route::get('/blogs/categories', [AdminBlogController::class, 'categories']);
            Route::post('/blogs/categories', [AdminBlogController::class, 'storeCategory']);
            Route::put('/blogs/categories/{id}', [AdminBlogController::class, 'updateCategory']);
            Route::delete('/blogs/categories/{id}', [AdminBlogController::class, 'destroyCategory']);
            Route::get('/blogs/{id}', [AdminBlogController::class, 'show']);
            Route::put('/blogs/{id}', [AdminBlogController::class, 'update']);
            Route::delete('/blogs/{id}', [AdminBlogController::class, 'destroy']);
            Route::get('/blogs/{postId}/comments', [AdminBlogController::class, 'comments']);
            Route::put('/blogs/comments/{commentId}/toggle', [AdminBlogController::class, 'toggleCommentApproval']);
            Route::delete('/blogs/comments/{commentId}', [AdminBlogController::class, 'destroyComment']);

            // Ebooks & Study Materials Management
            Route::get('/ebooks', [AdminEbookController::class, 'index']);
            Route::post('/ebooks', [AdminEbookController::class, 'store']);
            Route::get('/ebooks/categories', [AdminEbookController::class, 'categories']);
            Route::post('/ebooks/categories', [AdminEbookController::class, 'storeCategory']);
            Route::put('/ebooks/categories/{id}', [AdminEbookController::class, 'updateCategory']);
            Route::delete('/ebooks/categories/{id}', [AdminEbookController::class, 'destroyCategory']);
            Route::get('/ebooks/{id}', [AdminEbookController::class, 'show']);
            Route::put('/ebooks/{id}', [AdminEbookController::class, 'update']);
            Route::delete('/ebooks/{id}', [AdminEbookController::class, 'destroy']);
            Route::get('/ebooks/{id}/downloads', [AdminEbookController::class, 'downloads']);

            // Orders & Financial Reconciliations
            Route::get('/orders', [AdminOrderController::class, 'index']);
            Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
            Route::put('/orders/{id}/verify-payment', [AdminOrderController::class, 'verifyPayment']);
            Route::put('/orders/{id}/reject', [AdminOrderController::class, 'reject']);
            Route::put('/orders/{id}/refund', [AdminOrderController::class, 'refund']);
        });
    });
});
