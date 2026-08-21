<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SellerVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Noksha (নকশা)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Demo Resource Details Page
Route::get('/resource/demo', function () {
    return view('resource.show');
})->name('resource.demo');

// Demo Seller Profile Page
Route::get('/seller/demo', function () {
    return view('seller.profile');
})->name('seller.demo');



/*
|--------------------------------------------------------------------------
| Guest Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    // Forgot / Reset Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

    // Google OAuth Placeholder Routes
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Email Verification Routes
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // OTP Architecture Placeholder Routes
    Route::get('/otp/verify', [OtpController::class, 'showForm'])->name('otp.form');
    Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

    // Seller Asset Upload Routes
    Route::get('/resource/upload', [ResourceController::class, 'create'])->name('resource.create');
    Route::post('/resource/upload', [ResourceController::class, 'store'])->name('resource.store');

    // Seller Dashboard Routes
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::delete('/seller/resource/{resource}', [SellerDashboardController::class, 'destroy'])->name('seller.resource.destroy');

    // Super Admin Resource Approval Panel Routes
    Route::get('/admin/resources', [AdminResourceController::class, 'index'])->name('admin.resources.index');
    Route::post('/admin/resources/{resource}/approve', [AdminResourceController::class, 'approve'])->name('admin.resources.approve');
    Route::post('/admin/resources/{resource}/reject', [AdminResourceController::class, 'reject'])->name('admin.resources.reject');

    // Seller Identity Verification Routes
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');
});
