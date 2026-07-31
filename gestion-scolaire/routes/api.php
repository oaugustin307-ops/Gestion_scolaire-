<?php

use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\GradeApiController;
use App\Http\Controllers\Api\GuardianApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

/**
 * Routes API pour l'application mobile de suivi scolaire
 * 
 * Ces routes permettent à l'application mobile Android de consommer
 * les données du système de gestion scolaire via API REST.
 */

// Routes publiques pour l'authentification des parents
Route::post('/guardian/login', [GuardianApiController::class, 'login']);

// Routes protégées par authentification (nécessitent une session valide)
Route::middleware('auth:guardian')->group(function () {
    // Routes d'authentification des parents
    Route::post('/guardian/logout', [GuardianApiController::class, 'logout']);
    Route::post('/guardian/change-password', [GuardianApiController::class, 'changePassword']);
    Route::get('/guardian/profile', [GuardianApiController::class, 'profile']);
    
    // Routes pour les enfants d'un parent
    Route::get('/guardian/children', [StudentApiController::class, 'getChildren']);
    Route::get('/guardian/children/{id}', [StudentApiController::class, 'show']);
    Route::get('/guardian/children/{id}/dashboard', [StudentApiController::class, 'getDashboard']);
    
    // Routes pour les notes
    Route::get('/guardian/children/{id}/subjects', [GradeApiController::class, 'getSubjects']);
    Route::get('/guardian/children/{id}/grades', [GradeApiController::class, 'getAllGrades']);
    Route::get('/guardian/children/{id}/grades/{subjectId}', [GradeApiController::class, 'getGradesBySubject']);
    Route::get('/guardian/children/{id}/grades/trimester/{trimester}', [GradeApiController::class, 'getGradesByTrimester']);
    
    // Routes pour les paiements (lecture seule - les parents ne peuvent pas créer de paiements)
    Route::get('/guardian/children/{id}/payments', [PaymentApiController::class, 'getPayments']);
    Route::get('/guardian/children/{id}/payments/summary', [PaymentApiController::class, 'getPaymentSummary']);
    Route::get('/guardian/children/{id}/payments/{paymentId}', [PaymentApiController::class, 'getPaymentDetails']);
    Route::get('/guardian/children/{id}/payments/{paymentId}/receipt', [PaymentApiController::class, 'getReceiptUrl']);
    
    // Routes pour les absences
    Route::get('/guardian/children/{id}/attendances', [AttendanceApiController::class, 'getAttendances']);
    Route::get('/guardian/children/{id}/attendances/summary', [AttendanceApiController::class, 'getAttendanceSummary']);
    
    // Routes pour les notifications
    Route::get('/guardian/notifications', [NotificationApiController::class, 'getNotifications']);
    Route::get('/guardian/notifications/unread', [NotificationApiController::class, 'getUnreadNotifications']);
    Route::post('/guardian/notifications/{id}/read', [NotificationApiController::class, 'markAsRead']);
});

// Routes API existantes pour le système web (compatibilité)
Route::get('/students/by-class/{classId}', [StudentController::class, 'getByClass']);
Route::get('/subjects/by-class/{classId}', [SubjectController::class, 'getByClass']);
