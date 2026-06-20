<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

// Public splash
Route::get('/', function () { return view('welcome'); })->name('welcome');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification
Route::get('/email/verify', [VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth','signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware(['auth','throttle:6,1'])->name('verification.resend');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('forms', FormController::class)->except(['show']);
    Route::post('/forms/ai-generate', [FormController::class, 'aiGenerate'])->name('forms.ai-generate');
    Route::get('/forms/qr/{id}', [FormController::class, 'qr'])->name('forms.qr'); // ✅ QR route added
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{id}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/export/{format}', [SubmissionController::class, 'export'])->name('submissions.export');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::delete('/subscription', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/use/{slug}', [TemplateController::class, 'useTemplate'])->name('templates.use');
Route::get('/templates/fields/{slug}', [TemplateController::class, 'getFields'])->name('templates.fields');
    });

// Public Forms
Route::get('/forms/{link}', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/forms/{link}', [PublicFormController::class, 'submit'])->name('public.form.submit');

// Admin (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});