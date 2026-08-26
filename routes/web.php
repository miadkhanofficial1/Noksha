<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminVerificationController;
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

// Smart Marketplace Search Route
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Resource Details Routes
Route::get('/resource/demo', [ResourceController::class, 'showDemo'])->name('resource.demo');
Route::get('/resource/{slug}', [ResourceController::class, 'show'])->name('resource.show');

// Demo Seller Profile Page
Route::get('/seller/demo', function () {
    return view('seller.profile');
})->name('seller.demo');

// Public Design Contests & Leaderboard Routes
Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
Route::get('/contests/{contest:slug}', [ContestController::class, 'show'])->name('contests.show');



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

    // Buyer Dashboard Route
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{resource}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{resource}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{resource}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{resource}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order History & Resource Download Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/download/{resource}', [OrderController::class, 'download'])->name('resource.download');

    // Reviews & Ratings Routes
    Route::get('/resource/{resource}/reviews', [ReviewController::class, 'index'])->name('resource.reviews.index');
    Route::post('/resource/{resource}/review', [ReviewController::class, 'store'])->name('resource.review.store');

    // Seller Contest Submission Route
    Route::post('/contests/{contest}/submit', [ContestSubmissionController::class, 'store'])->name('contests.submit');

    // Super Admin Design Contests Panel Routes
    Route::get('/admin/contests', [ContestController::class, 'adminIndex'])->name('admin.contests.index');
    Route::post('/admin/contests', [ContestController::class, 'adminStore'])->name('admin.contests.store');
    Route::post('/admin/contests/{contest}/winner', [ContestController::class, 'selectWinner'])->name('admin.contests.winner');

    // Notification Center Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Super Admin Executive Dashboard Routes
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{user}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
    Route::post('/admin/broadcast', [AdminDashboardController::class, 'broadcastNotification'])->name('admin.broadcast');

    // Super Admin Resource Approval Panel Routes
    Route::get('/admin/resources', [AdminResourceController::class, 'index'])->name('admin.resources.index');
    Route::post('/admin/resources/{resource}/approve', [AdminResourceController::class, 'approve'])->name('admin.resources.approve');
    Route::post('/admin/resources/{resource}/reject', [AdminResourceController::class, 'reject'])->name('admin.resources.reject');

    // Seller Identity Verification Routes
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');

    // Super Admin Seller Verification Review Routes
    Route::get('/admin/verifications', [AdminVerificationController::class, 'index'])->name('admin.verifications.index');
    Route::get('/admin/verifications/{verification}', [AdminVerificationController::class, 'show'])->name('admin.verifications.show');
    Route::post('/admin/verifications/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('admin.verifications.approve');
    Route::post('/admin/verifications/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('admin.verifications.reject');
});
