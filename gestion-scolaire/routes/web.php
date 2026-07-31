<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\GuardianManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification (publiques)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Routes d'authentification pour les parents (publiques)
Route::get('/guardian/login', [GuardianController::class, 'showLogin'])->name('guardian.login');
Route::post('/guardian/login', [GuardianController::class, 'login'])->name('guardian.login.post');

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes réservées aux gestionnaires
    Route::middleware(['role:gestionnaire'])->group(function () {
        Route::resource('classes', ClassRoomController::class);
        Route::resource('students', StudentController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('guardians', GuardianManagementController::class);
    });

    // Routes accessibles aux gestionnaires et enseignants
    Route::middleware(['role:gestionnaire,enseignant'])->group(function () {
        Route::resource('notifications', NotificationController::class);
        Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('notifications/{id}/mark-as-unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');
        Route::resource('attendances', AttendanceController::class);
    });

    // Routes accessibles aux gestionnaires et enseignants
    Route::resource('grades', GradeController::class);
    Route::get('grades/bulk/create', [GradeController::class, 'createBulk'])->name('grades.create-bulk');
    Route::post('grades/bulk', [GradeController::class, 'storeBulk'])->name('grades.store-bulk');

    Route::get('grades/class/{classId}/trimester/{trimester}', [GradeController::class, 'byClass'])
        ->name('grades.by-class');

    Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])
        ->name('payments.receipt');
});

// Routes protégées pour les parents
Route::middleware(['auth:guardian'])->group(function () {
    Route::get('/guardian/dashboard', [GuardianController::class, 'dashboard'])->name('guardian.dashboard');
    Route::post('/guardian/logout', [GuardianController::class, 'logout'])->name('guardian.logout');
});
