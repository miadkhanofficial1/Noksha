<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AiEditorController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestEntryController;
use App\Http\Controllers\ContestSubmissionController;
use App\Http\Controllers\ContestHandoverController;
use App\Http\Controllers\CreatorDirectoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NotificationController;
use App\Models\Resource;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminContestController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\AdminFinanceController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminSystemController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SellerVerificationController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Noksha
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Language Switcher Routes
Route::get('/locale/{locale}', [LanguageController::class, 'switch'])->name('locale.switch');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Smart Marketplace Search Route
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Resource Details Routes
Route::get('/resource/demo', [ResourceController::class, 'showDemo'])->name('resource.demo');
Route::get('/resource/{slug}', [ResourceController::class, 'show'])->where('slug', '^(?!upload$|demo$).*')->name('resource.show');

// Demo Seller Profile Page
Route::get('/seller/demo', function () {
    return view('seller.profile');
})->name('seller.demo');

// Public Design Contests & Leaderboard Routes
Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
Route::post('/contests/entries/{entry}/like', [ContestSubmissionController::class, 'toggleLike'])->name('contests.entries.like');
Route::get('/contests/{contest:slug}', [ContestController::class, 'show'])->where('contest', '^(?!create$).*')->name('contests.show');

// Public User / Designer Portfolio & Profile Route (/u/{username})
Route::get('/u/{username}', [PublicProfileController::class, 'show'])->name('user.profile');
Route::get('/creator/{username?}', [PublicProfileController::class, 'show'])->name('creator.profile');
Route::get('/author/{username?}', [PublicProfileController::class, 'show'])->name('author.show');

// Marketplace Templates / Catalog Routes
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/resources', [TemplateController::class, 'index'])->name('resources.index');
Route::get('/templates/{id}', [TemplateController::class, 'show'])->name('templates.show');

// Verified Creator Directory & Discovery Routes
Route::get('/creators', [CreatorDirectoryController::class, 'index'])->name('creators.index');

// Public Support & Contact Us Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// FAQ & Knowledge Help Center Routes
Route::get('/help', [HelpCenterController::class, 'index'])->name('help.index');
Route::get('/faq', [HelpCenterController::class, 'index'])->name('faq.index');

// Legal, Trust & Licensing Policy Routes
Route::get('/licenses', [LegalController::class, 'licenses'])->name('legal.licenses');
Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/refund-policy', [LegalController::class, 'refunds'])->name('legal.refunds');



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

    // Mode Switcher (Buyer <-> Contributor/Seller)
    Route::post('/user/mode/switch', [DashboardController::class, 'switchMode'])->name('user.switch-mode');

    // Seller & Creator Management Routes (Strictly Restricted to Approved Contributors)
    Route::middleware('EnsureApprovedContributor')->group(function () {
        Route::get('/resource/upload', [ResourceController::class, 'create'])->name('resource.create');
        Route::post('/resource/upload', [ResourceController::class, 'store'])->name('resource.store');
        Route::get('/seller/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');
        Route::post('/seller/payout/request', [DashboardController::class, 'requestPayout'])->name('seller.payout.request');
        Route::delete('/seller/resource/{resource}', [DashboardController::class, 'destroyResource'])->name('seller.resource.destroy');
        Route::get('/seller/payouts', [PayoutController::class, 'index'])->name('seller.payouts.index');
        Route::post('/seller/payouts/withdraw', [PayoutController::class, 'requestWithdrawal'])->name('seller.payouts.withdraw');
    });
    Route::redirect('/contributor/upload', '/dashboard?tab=upload');
    Route::redirect('/seller/upload', '/dashboard?tab=upload');
    Route::redirect('/upload', '/dashboard?tab=upload');
    Route::redirect('/creator/upload', '/dashboard?tab=upload');
    Route::redirect('/creator/studio', '/dashboard?tab=upload');
    Route::redirect('/creator/dashboard', '/seller/dashboard');

    // Unified User & Contributor Dashboard Route (/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');

    // User Profile Settings Hub Routes
    Route::get('/settings/profile', [ProfileSettingsController::class, 'edit'])->name('settings.profile');
    Route::put('/settings/profile', [ProfileSettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [ProfileSettingsController::class, 'updatePassword'])->name('settings.password.update');

    // Follow / Unfollow User Route (Realtime AJAX)
    Route::post('/u/{user}/follow', [FollowController::class, 'toggle'])->name('user.follow.toggle');
    Route::post('/u/{user}/follow/toggle', [FollowController::class, 'toggle'])->name('user.follow');

    // Contributor Identity Verification Routes (Apply to Become Contributor)
    Route::get('/contributor/apply', [SellerVerificationController::class, 'create'])->name('contributor.apply');
    Route::post('/contributor/apply', [SellerVerificationController::class, 'store'])->name('contributor.store');
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');

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

    // Freelancer-Style Contest Hub Routes
    Route::get('/contests/create', [ContestController::class, 'create'])->name('contests.create');
    Route::post('/contests', [ContestController::class, 'store'])->name('contests.store');

    // Contest Entry Submission Routes
    Route::get('/contests/{contest}/submit', [ContestEntryController::class, 'create'])->name('contests.entries.create');
    Route::post('/contests/{contest}/submit', [ContestEntryController::class, 'store'])->name('contests.entries.store');
    Route::post('/contests/{contest}/entries', [ContestEntryController::class, 'store'])->name('contests.submit');

    // Contest Winner Selection, Handover & Escrow Release Routes
    Route::post('/contests/{contest}/entries/{entry}/rate', [ContestController::class, 'rateEntry'])->name('contests.entries.rate');
    Route::post('/contests/{contest}/award/{entry}', [ContestHandoverController::class, 'awardWinner'])->name('contests.award');
    Route::post('/contests/{contest}/entries/{entry}/award', [ContestHandoverController::class, 'awardWinner'])->name('contests.entries.award');
    Route::get('/contests/{contest}/handover', [ContestHandoverController::class, 'show'])->name('contests.handover.show');
    Route::post('/contests/{contest}/handover/upload', [ContestHandoverController::class, 'uploadFiles'])->name('contests.handover.upload');
    Route::get('/contests/{contest}/handover/download', [ContestHandoverController::class, 'downloadHandoverFiles'])->name('contests.handover.download');
    Route::post('/contests/{contest}/handover/release', [ContestHandoverController::class, 'releaseEscrow'])->name('contests.handover.release');
    Route::post('/contests/{contest}/handover/revision', [ContestHandoverController::class, 'requestRevision'])->name('contests.handover.revision');

    // Notification Center Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read/{notification}', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    // AJAX: Mark all dropdown-visible notifications as seen (clears bell badge) without full page reload
    Route::post('/notifications/badge-clear', [NotificationController::class, 'badgeClear'])->name('notifications.badgeClear');
    // AJAX / Dedicated route: Mark a single notification as read
    Route::match(['post', 'patch'], '/notifications/{notification}/mark-as-read', [NotificationController::class, 'markSingleAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/ajax-read/{notification}', [NotificationController::class, 'markSingleAsRead'])->name('notifications.ajaxRead');
    // Admin: Broadcast history page
    Route::get('/admin/broadcast-history', [NotificationController::class, 'broadcastHistory'])->name('admin.broadcastHistory')->middleware('admin');

    // AI Template Customizer Workspace Routes
    Route::get('/templates/{id}/ai-edit', [AiEditorController::class, 'edit'])->name('templates.ai-edit');
    Route::post('/templates/{id}/ai-generate', [AiEditorController::class, 'generate'])->name('templates.ai-generate');
    Route::post('/templates/ai-edit/deduct-credit', [AiEditorController::class, 'deductCredit'])->name('templates.ai-edit.deduct');

    // Central Wallet & AI Credit Management Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/buy-credits', [WalletController::class, 'buyCredits'])->name('wallet.buy-credits');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');

    // Direct Template Purchase with Wallet Balance
    Route::post('/resource/{resource}/buy', [CheckoutController::class, 'buyWithWallet'])->name('resource.buy-wallet');

    // Seller Identity Verification Routes
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');

    // ==========================================
    // SUPER ADMIN PROTECTED ROUTES (auth + admin)
    // ==========================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Direct redirect /admin -> /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // 1. Executive Dashboard & Metrics
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/broadcast', [AdminDashboardController::class, 'broadcastNotification'])->name('broadcast');

        // 2. Resource Moderation Center
        Route::get('/resources', [AdminResourceController::class, 'index'])->name('resources.index');
        Route::get('/resources/{resource}/download', [AdminResourceController::class, 'download'])->name('resources.download');
        Route::post('/resources/{resource}/approve', [AdminResourceController::class, 'approve'])->name('resources.approve');
        Route::post('/resources/{resource}/reject', [AdminResourceController::class, 'reject'])->name('resources.reject');
        Route::delete('/resources/{resource}', [AdminResourceController::class, 'destroy'])->name('resources.destroy');

        // 3. User & KYC Verification Hub
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::post('/users/{user}/kyc/approve', [AdminUserController::class, 'approveKyc'])->name('users.kyc.approve');
        Route::post('/users/{user}/kyc/reject', [AdminUserController::class, 'rejectKyc'])->name('users.kyc.reject');

        // Existing verification routes fallback
        Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{verification}', [AdminVerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

        // 4. Contest Hub Control
        Route::get('/contests', [AdminContestController::class, 'index'])->name('contests.index');
        Route::post('/contests', [AdminContestController::class, 'store'])->name('contests.store');
        Route::post('/contests/{contest}/approve', [AdminContestController::class, 'approve'])->name('contests.approve');
        Route::post('/contests/{contest}/reject', [AdminContestController::class, 'reject'])->name('contests.reject');
        Route::put('/contests/{contest}', [AdminContestController::class, 'update'])->name('contests.update');
        Route::post('/contests/{contest}/cancel', [AdminContestController::class, 'cancel'])->name('contests.cancel');
        Route::delete('/contests/{contest}', [AdminContestController::class, 'destroy'])->name('contests.destroy');
        Route::get('/contests/{contest}/submissions', [AdminContestController::class, 'submissions'])->name('contests.submissions');
        Route::post('/contests/{contest}/winner', [AdminContestController::class, 'selectWinner'])->name('contests.winner');

        // 5. Financials, Analytics & Payouts Engine
        Route::get('/finance', [AdminFinanceController::class, 'index'])->name('finance.index');
        Route::post('/finance/withdrawals/{id}/approve', [AdminFinanceController::class, 'approveWithdrawal'])->name('finance.approveWithdrawal');
        Route::post('/finance/withdrawals/{id}/reject', [AdminFinanceController::class, 'rejectWithdrawal'])->name('finance.rejectWithdrawal');
        Route::get('/payouts', [AdminFinanceController::class, 'index'])->name('payouts.index');

        // 6. Live System Logs
        Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
        Route::post('/logs/clear', [AdminLogController::class, 'clear'])->name('logs.clear');

        // 7. System Maintenance & Factory Reset (Super Admin Only)
        Route::post('/system/purge', [AdminSystemController::class, 'purgeAllData'])->name('system.purge');

        // 8. Customer Support Inquiries & Messages
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::patch('/messages/{id}/status', [AdminMessageController::class, 'updateStatus'])->name('messages.updateStatus');
        Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
    });
});
