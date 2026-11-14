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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
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
use App\Http\Controllers\InboxController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SellerVerificationController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\WalletController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes - Noksha
|--------------------------------------------------------------------------
*/

// Authoritative Administrator Provisioning Endpoint for Cloud / Initial Setup
Route::get('/setup-admin-user', function () {
    try {
        Artisan::call('db:seed', ['--force' => true]);

        return response("Authoritative admin provisioned successfully via DatabaseSeeder.", 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Throwable $e) {
        return response("Failed to provision admin user: " . $e->getMessage(), 500)
            ->header('Content-Type', 'text/plain');
    }
})->name('admin.setup');

// Diagnostic Route for Brevo HTTPS REST API Verification (Port 443)
Route::get('/test-brevo-mail', function (\Illuminate\Http\Request $request, \App\Services\BrevoMailService $brevoMailService) {
    $to = $request->input('to', config('mail.from.address', 'noksha.official1@gmail.com'));
    $apiKey = env('BREVO_API_KEY') ?: config('services.brevo.key') ?: env('MAIL_PASSWORD') ?: config('mail.mailers.smtp.password');

    $config = [
        'mode' => 'Brevo HTTPS REST API (Port 443)',
        'endpoint' => 'https://api.brevo.com/v3/smtp/email',
        'api_key_status' => !empty($apiKey) && $apiKey !== 'apnar_brevo_smtp_master_key' ? 'CONFIGURED (' . substr($apiKey, 0, 8) . '...)' : 'MISSING / PLACEHOLDER',
        'from_address' => config('mail.from.address', 'miadkhan.official1@gmail.com'),
        'from_name' => config('mail.from.name', 'Noksha Security'),
        'http_timeout' => '5 seconds',
    ];

    $diagnostics = [];

    // Step 1: Brevo HTTPS REST API Ping over standard Port 443
    $apiPingStart = microtime(true);
    try {
        $pingResponse = \Illuminate\Support\Facades\Http::timeout(5)->get('https://api.brevo.com/v3/smtp/email');
        $apiPingTime = round((microtime(true) - $apiPingStart) * 1000, 2);
        $diagnostics['api_connectivity'] = [
            'status' => 'ONLINE (PORT 443 REACHABLE)',
            'endpoint' => 'https://api.brevo.com/v3/smtp/email',
            'latency_ms' => $apiPingTime,
            'http_code' => $pingResponse->status(),
        ];
    } catch (\Throwable $e) {
        $apiPingTime = round((microtime(true) - $apiPingStart) * 1000, 2);
        $diagnostics['api_connectivity'] = [
            'status' => 'UNREACHABLE',
            'endpoint' => 'https://api.brevo.com/v3/smtp/email',
            'latency_ms' => $apiPingTime,
            'error' => $e->getMessage(),
        ];
    }

    // Step 2: Live OTP Email Dispatch Attempt via Brevo HTTPS REST API
    $dispatchStart = microtime(true);
    $testOtp = (string) random_int(100000, 999999);
    $result = $brevoMailService->sendOtpMail($to, $testOtp, 'Diagnostic Tester');
    $dispatchTime = round((microtime(true) - $dispatchStart) * 1000, 2);

    $diagnostics['dispatch'] = [
        'recipient' => $to,
        'otp_generated' => $testOtp,
        'transport_used' => $result['transport'],
        'success' => $result['success'],
        'duration_ms' => $dispatchTime,
        'error' => $result['error'],
        'message_id' => $result['messageId'] ?? null,
    ];

    if ($request->wantsJson()) {
        return response()->json([
            'status' => $result['success'] ? 'SUCCESS' : 'FAILED',
            'config' => $config,
            'diagnostics' => $diagnostics,
        ], $result['success'] ? 200 : 502);
    }

    return response()->view('test-brevo-mail', [
        'config' => $config,
        'diagnostics' => $diagnostics,
        'recipient' => $to,
        'success' => $result['success'],
    ], $result['success'] ? 200 : 502);
})->name('test.brevo.mail');

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
Route::get('/assets/{slug}', [ResourceController::class, 'show'])->where('slug', '^(?!upload$|demo$).*')->name('assets.show');

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
Route::get('/seller/{username}', [PublicProfileController::class, 'show'])->name('seller.profile.show');



// Marketplace Templates / Catalog Routes
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/resources', [TemplateController::class, 'index'])->name('resources.index');
Route::get('/templates/{id}', [TemplateController::class, 'show'])->name('templates.show');

// Verified Creator Directory & Discovery Routes
Route::get('/creators', [CreatorDirectoryController::class, 'index'])->name('creators.index');

// On-Demand Creator Services Discovery Routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::post('/services/{service}/inquire', [ServiceController::class, 'inquire'])->name('services.inquire')->middleware('auth');

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
    // Registration Flow (Two-Step Signup with 6-Digit Email OTP - Throttled: 5 attempts per min)
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.store');
    Route::get('/register/verify-otp', [RegisterController::class, 'showVerifyOtp'])->name('register.verify.otp');
    Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('register.verify.otp.submit');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->middleware('throttle:4,1')->name('register.resend.otp');

    // Login (Brute-force protection: 5 attempts per min)
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    // Modern 6-Digit Email OTP Password Recovery Flow (Throttled: 4 attempts per min)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->middleware('throttle:4,1')->name('password.email');
    Route::get('/password/verify-otp', [ForgotPasswordController::class, 'showVerifyOtp'])->name('password.otp.view');
    Route::post('/password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->middleware('throttle:4,1')->name('password.otp.verify');
    Route::post('/password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->middleware('throttle:4,1')->name('password.otp.resend');
    Route::get('/password/reset-password', [ResetPasswordController::class, 'create'])->name('password.reset.view');
    Route::post('/password/reset-password', [ResetPasswordController::class, 'store'])->middleware('throttle:4,1')->name('password.update');
    Route::get('/reset-password/{token}', function () {
        return redirect()->route('password.request')->withErrors([
            'email' => 'Password recovery now uses secure 6-digit OTP verification. Please enter your email to receive a code.'
        ]);
    })->name('password.reset');

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

    // Email Verification Routes (6-Digit OTP & Legacy Signed URLs)
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verify-otp', [VerificationController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('verification.verify.otp');
    Route::post('/email/resend-otp', [VerificationController::class, 'resendOtp'])->middleware('throttle:4,1')->name('verification.resend.otp');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // OTP Architecture Placeholder Routes (Rate Limited)
    Route::get('/otp/verify', [OtpController::class, 'showForm'])->name('otp.form');
    Route::post('/otp/send', [OtpController::class, 'send'])->middleware('throttle:4,1')->name('otp.send');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->middleware('throttle:5,1')->name('otp.verify');

    // Mode Switcher (Buyer <-> Contributor/Seller)
    Route::post('/user/mode/switch', [DashboardController::class, 'switchMode'])->name('user.switch-mode');

    // Seller & Creator Management Routes (Strictly Restricted to Approved Contributors)
    Route::middleware('EnsureApprovedContributor')->group(function () {
        Route::get('/resource/upload', [ResourceController::class, 'create'])->name('resource.create');
        Route::post('/resource/upload', [ResourceController::class, 'store'])->name('resource.store');
        Route::post('/creator/assets', [AssetController::class, 'store'])->name('creator.assets.store');
        Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
        Route::post('/seller/payout/request', [DashboardController::class, 'requestPayout'])->name('seller.payout.request');
        Route::delete('/seller/resource/{resource}', [DashboardController::class, 'destroyResource'])->name('seller.resource.destroy');
        Route::post('/seller/payouts/withdraw', [PayoutController::class, 'requestWithdrawal'])->name('seller.payouts.withdraw');
        Route::get('/creator/guidelines/download', [ResourceController::class, 'downloadGuidelines'])->name('guidelines.download-pdf');

        // Creator Service Studio Management
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    });

    // Dedicated Seller Dashboard Route: Switches session mode to 'seller' and cleanly resolves to /dashboard
    Route::get('/seller/dashboard', function (\Illuminate\Http\Request $request) {
        session(['dashboard_mode' => 'seller']);
        if (auth()->check() && auth()->user()->isApprovedContributor()) {
            auth()->user()->update(['active_mode' => 'seller']);
        }
        $query = $request->getQueryString();
        return redirect()->to(route('dashboard') . ($query ? '?' . $query : ''));
    })->name('seller.dashboard');

    // Seller Earnings & Payouts Interface (Accessible to Auth users; non-contributors redirect cleanly to wallet)
    Route::get('/seller/payouts', [PayoutController::class, 'index'])->name('seller.payouts');
    Route::get('/seller/payouts-ledger', [PayoutController::class, 'index'])->name('seller.payouts.index');
    Route::redirect('/contributor/upload', '/dashboard?tab=upload');
    Route::redirect('/seller/upload', '/dashboard?tab=upload');
    Route::redirect('/upload', '/dashboard?tab=upload');
    Route::redirect('/creator/upload', '/dashboard?tab=upload');
    Route::redirect('/creator/studio', '/dashboard?tab=upload');
    Route::redirect('/creator/dashboard', '/seller/dashboard');

    // Unified User & Contributor Dashboard Route (/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/guidelines/seen', [DashboardController::class, 'markGuidelinesSeen'])->name('creator.guidelines.seen');
    Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');

    // User Profile Settings Hub Routes
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('settings.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['post', 'put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::match(['post', 'put', 'patch'], '/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::put('/settings/password', [ProfileSettingsController::class, 'updatePassword'])->name('settings.password.update');

    // ==========================================
    // NOKSHA REAL-TIME ESCROW INBOX & MESSAGING
    // ==========================================
    Route::get('/inbox', [MessageController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{conversation}', [MessageController::class, 'show'])->name('inbox.show');
    Route::delete('/inbox/{conversation}', [MessageController::class, 'destroy'])->name('inbox.destroy');
    Route::delete('/chat/{conversation}', [MessageController::class, 'destroy'])->name('chat.destroy');
    Route::delete('/conversations/{conversation}', [MessageController::class, 'destroy'])->name('conversations.destroy');
    Route::get('/inbox/{conversation}/poll', [InboxController::class, 'pollMessages'])->name('inbox.poll');
    Route::post('/inbox/{conversation}/messages', [MessageController::class, 'store'])->name('inbox.messages.store');
    Route::post('/inbox/{conversation}/send', [MessageController::class, 'store'])->name('chat.send');
    Route::post('/chat/{conversation}/send', [MessageController::class, 'store'])->name('chat.send.direct');
    Route::get('/chat/{identifier}', [MessageController::class, 'show'])->name('chat.show');
    Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');
    Route::post('/inbox/{conversation}/freeze', [InboxController::class, 'freeze'])->name('inbox.freeze');
    Route::post('/inbox/{conversation}/reopen-request', [InboxController::class, 'requestReopen'])->name('inbox.reopen-request');
    Route::post('/inbox/{conversation}/reopen-respond', [InboxController::class, 'respondReopen'])->name('inbox.reopen-respond');
    Route::post('/inbox/{conversation}/service-proposal', [InboxController::class, 'storeServiceProposal'])->name('inbox.service-proposal.store');

    // Escrow Order Pipeline Execution Routes
    Route::post('/orders/{order}/fund', [InboxController::class, 'fundOrder'])->name('orders.fund');
    Route::post('/inbox/orders/{order}/fund', [InboxController::class, 'fundOrder'])->name('inbox.orders.fund');
    Route::post('/inbox/orders/{order}/respond', [InboxController::class, 'respondOrderProposal'])->name('inbox.orders.respond');
    Route::post('/inbox/orders/{order}/deliver', [InboxController::class, 'deliverWork'])->name('inbox.orders.deliver');
    Route::get('/inbox/orders/{order}/download-delivery', [InboxController::class, 'downloadDelivery'])->name('inbox.orders.download-delivery');
    Route::post('/inbox/orders/{order}/release', [InboxController::class, 'releaseEscrow'])->name('inbox.orders.release');
    Route::post('/inbox/orders/{order}/revision', [InboxController::class, 'requestRevision'])->name('inbox.orders.revision');
    Route::post('/inbox/orders/{order}/minor-fix', [InboxController::class, 'requestMinorFix'])->name('inbox.orders.minor-fix');
    Route::post('/inbox/orders/{order}/request-extension', [InboxController::class, 'requestExtension'])->name('inbox.orders.request-extension');
    Route::post('/inbox/orders/{order}/respond-extension', [InboxController::class, 'respondExtension'])->name('inbox.orders.respond-extension');
    Route::post('/inbox/orders/{order}/report-fake', [InboxController::class, 'reportFakeDelivery'])->name('inbox.orders.report-fake');
    Route::post('/inbox/orders/{order}/review', [InboxController::class, 'submitOrderReview'])->name('inbox.orders.review');
    Route::post('/inbox/orders/{order}/cancel', [InboxController::class, 'cancelOrder'])->name('inbox.orders.cancel');

    // Follow / Unfollow User Route (Realtime AJAX)
    Route::post('/u/{user}/follow', [FollowController::class, 'toggle'])->name('user.follow.toggle');
    Route::post('/u/{user}/follow/toggle', [FollowController::class, 'toggle'])->name('user.follow');

    // Contributor Identity Verification Routes (Apply to Become Contributor)
    Route::get('/contributor/apply', [SellerVerificationController::class, 'create'])->name('contributor.apply');
    Route::post('/contributor/apply', [SellerVerificationController::class, 'store'])->name('contributor.store');
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');
    Route::get('/kyc/verify', [KycController::class, 'create'])->name('kyc.verify');
    Route::post('/kyc/verify', [KycController::class, 'store'])->name('kyc.store');
    Route::post('/kyc/upload', [KycController::class, 'store'])->name('kyc.upload');

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
    Route::get('/orders/{order}/download/{asset}', [OrderController::class, 'downloadResource'])->name('orders.download.resource')->middleware('auth');
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

    // AI Template Customizer Workspace Routes
    Route::get('/templates/{id}/ai-edit', [AiEditorController::class, 'edit'])->name('templates.ai-edit');
    Route::post('/templates/{id}/ai-generate', [AiEditorController::class, 'generate'])->name('templates.ai-generate');
    Route::post('/templates/ai-edit/deduct-credit', [AiEditorController::class, 'deductCredit'])->name('templates.ai-edit.deduct');

    // Central Wallet & Financial Hub Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/buy-credits', [WalletController::class, 'buyCredits'])->name('wallet.buy-credits');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

    // Direct Template Purchase with Wallet Balance
    Route::post('/resource/{resource}/buy', [CheckoutController::class, 'buyWithWallet'])->name('resource.buy-wallet');

    // Seller Identity Verification Routes
    Route::get('/seller/verification', [SellerVerificationController::class, 'create'])->name('seller.verification.create');
    Route::post('/seller/verification', [SellerVerificationController::class, 'store'])->name('seller.verification.store');

    // ==========================================
    // SUPER ADMIN PROTECTED ROUTES (auth + admin)
    // Environment-driven Admin Route Prefix Obfuscation
    // ==========================================
    $adminPrefix = env('ADMIN_ROUTE_PREFIX', 'noksha-hq-secure-panel');
    Route::middleware(['admin'])->prefix($adminPrefix)->name('admin.')->group(function () {
        // Direct redirect /admin -> /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // 1. Executive Dashboard & Metrics
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/broadcast', [AdminDashboardController::class, 'broadcastNotification'])->name('broadcast');
        Route::get('/broadcast-history', [\App\Http\Controllers\NotificationController::class, 'broadcastHistory'])->name('broadcastHistory');

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
        Route::get('/kyc-document/{type}/{filename}', [AdminUserController::class, 'viewKycDocument'])
            ->where('type', 'ids|selfies|videos|documents')
            ->where('filename', '[A-Za-z0-9_.-]+')
            ->name('kyc.document');

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
        Route::post('/messages/{id}/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');
        Route::post('/support-messages/{id}/reply', [AdminMessageController::class, 'reply'])->name('support-messages.reply');
        Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        // 9. Creator Services, Smart Escrow & Disputes Hub
        Route::get('/services-hub', [\App\Http\Controllers\AdminServiceEscrowController::class, 'index'])->name('services-hub.index');
        Route::post('/services-hub/orders/{order}/resolve-dispute', [\App\Http\Controllers\AdminServiceEscrowController::class, 'resolveDispute'])->name('services-hub.resolve-dispute');
        Route::post('/services-hub/creators/{creator}/adjust-demerits', [\App\Http\Controllers\AdminServiceEscrowController::class, 'adjustDemerits'])->name('services-hub.adjust-demerits');
        Route::post('/services-hub/services/{service}/toggle-status', [\App\Http\Controllers\AdminServiceEscrowController::class, 'toggleServiceStatus'])->name('services-hub.toggle-service');
        Route::post('/services-hub/reviews/{review}/resolve', [\App\Http\Controllers\AdminServiceEscrowController::class, 'resolveComplaintReview'])->name('services-hub.resolve-review');
    });

    // Scanner Trap / Honeypot: Automated scanners targeting legacy /admin path get instantly deflected to root
    Route::any('/admin/{any?}', function () {
        return redirect('/')->with('error', 'Access denied: Administrative endpoint not found.');
    })->where('any', '.*');
});

Route::get('/run-noksha-seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return response('Seeding completed successfully!<br><pre>' . e(Artisan::output()) . '</pre>');
});


Route::get('/setup-demo-assets', function () {
    return response()->json([
        'status' => 'info',
        'message' => 'Demo asset seeding disabled to maintain a clean authoritative state.',
    ]);
});

// ==========================================
// RESILIENT PUBLIC STORAGE FILE SERVING FALLBACK
// Directly serves files from storage_path('app/public/{path}') or public_path('uploads/{path}')
// when webserver symlinks are missing or unsupported (e.g. Render.com)
// ==========================================
Route::get('/storage/{path}', function ($path) {
    if (str_contains($path, '..')) {
        abort(404);
    }

    $candidates = [
        storage_path('app/public/' . $path),
        public_path('uploads/' . $path),
        public_path('storage/' . $path),
    ];

    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $mimeType = @mime_content_type($filePath) ?: 'application/octet-stream';
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }

    abort(404);
})->where('path', '.*')->name('storage.fallback');

