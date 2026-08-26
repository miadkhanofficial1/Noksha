# Complete Routes Reference — Noksha (নকশা)

This document provides a comprehensive sitemap and reference of all **65 HTTP Web Routes** in **Noksha (নকশা)**, categorized by user access roles and middleware protection.

---

## 1. Public Routes (Unauthenticated)

| HTTP Method | Route Path / URI | Route Name | Controller & Method | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `home` | `HomeController@index` | Marketplace homepage with featured & trending assets |
| `GET` | `/search` | `search.index` | `SearchController@index` | Smart search page with AI tag filters & category chips |
| `GET` | `/resource/{slug}` | `resource.show` | `ResourceController@show` | Single resource details page & review list |
| `GET` | `/resource/demo` | `resource.demo` | `ResourceController@showDemo` | Interactive asset preview demo page |
| `GET` | `/contests` | `contests.index` | `ContestController@index` | Contests arena showcase & Top Creator Leaderboard |
| `GET` | `/contests/{contest}` | `contests.show` | `ContestController@show` | Single contest details, prize card, and submissions |
| `GET` | `/seller/demo` | `seller.demo` | Inline View Closure | Demo seller public portfolio profile |
| `GET` | `/login` | `login` | `Auth\LoginController@create` | Account login page |
| `POST` | `/login` | `login.store` | `Auth\LoginController@store` | Authenticate user credentials |
| `GET` | `/register` | `register` | `Auth\RegisterController@create` | Account registration page |
| `POST` | `/register` | `register.store` | `Auth\RegisterController@store` | Register new user account |
| `GET` | `/auth/google` | `auth.google` | `Auth\SocialAuthController@redirectToGoogle` | Initiate Google OAuth SSO |
| `GET` | `/auth/google/callback` | `auth.google.callback` | `Auth\SocialAuthController@handleGoogleCallback` | Handle Google OAuth callback |
| `GET` | `/forgot-password` | `password.request` | `Auth\ForgotPasswordController@create` | Password reset request form |
| `POST` | `/forgot-password` | `password.email` | `Auth\ForgotPasswordController@store` | Send password reset email |
| `GET` | `/reset-password/{token}` | `password.reset` | `Auth\ResetPasswordController@create` | Reset password form |
| `POST` | `/reset-password` | `password.update` | `Auth\ResetPasswordController@store` | Update password credentials |

---

## 2. Authenticated Buyer Routes (`auth` Middleware)

| HTTP Method | Route Path / URI | Route Name | Controller & Method | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/buyer/dashboard` | `buyer.dashboard` | `BuyerDashboardController@index` | Buyer dashboard with downloads & order history |
| `GET` | `/cart` | `cart.index` | `CartController@index` | View shopping cart items |
| `POST` | `/cart/{resource}` | `cart.store` | `CartController@store` | Add resource to cart |
| `DELETE` | `/cart/{resource}` | `cart.destroy` | `CartController@destroy` | Remove resource from cart |
| `GET` | `/wishlist` | `wishlist.index` | `WishlistController@index` | View saved wishlist assets |
| `POST` | `/wishlist/{resource}` | `wishlist.store` | `WishlistController@store` | Save asset to wishlist |
| `DELETE` | `/wishlist/{resource}` | `wishlist.destroy` | `WishlistController@destroy` | Remove asset from wishlist |
| `GET` | `/checkout` | `checkout.index` | `CheckoutController@index` | Checkout payment form |
| `POST` | `/checkout` | `checkout.store` | `CheckoutController@store` | Process order payment |
| `GET` | `/orders` | `orders.index` | `OrderController@index` | Customer order history list |
| `GET` | `/orders/{order}` | `orders.show` | `OrderController@show` | Single order details & item invoice |
| `GET` | `/orders/{order}/success` | `orders.success` | `OrderController@success` | Order confirmation success page |
| `GET` | `/download/{resource}` | `resource.download` | `OrderController@download` | Stream digital asset file download |
| `POST` | `/resource/{resource}/review` | `resource.review.store` | `ReviewController@store` | Submit purchaser review & rating |
| `GET` | `/notifications` | `notifications.index` | `NotificationController@index` | Notification Center |
| `POST` | `/notifications/read/{notification}` | `notifications.read` | `NotificationController@markAsRead` | Mark alert read & redirect |
| `POST` | `/notifications/read-all` | `notifications.readAll` | `NotificationController@markAllAsRead` | Mark all alerts as read |
| `POST` | `/logout` | `logout` | `Auth\LoginController@destroy` | Destroy user session & logout |

---

## 3. Authenticated Seller Routes (`auth` Middleware)

| HTTP Method | Route Path / URI | Route Name | Controller & Method | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/seller/dashboard` | `seller.dashboard` | `SellerDashboardController@index` | Seller dashboard, sales metrics, & top tags |
| `GET` | `/resource/upload` | `resource.create` | `ResourceController@create` | Asset upload form |
| `POST` | `/resource/upload` | `resource.store` | `ResourceController@store` | Store asset & trigger AI auto-tagging |
| `DELETE` | `/seller/resource/{resource}` | `seller.resource.destroy` | `SellerDashboardController@destroy` | Delete owned resource asset |
| `GET` | `/seller/verification` | `seller.verification.create` | `SellerVerificationController@create` | Identity KYC verification upload form |
| `POST` | `/seller/verification` | `seller.verification.store` | `SellerVerificationController@store` | Store identity verification documents |
| `POST` | `/contests/{contest}/submit` | `contests.submit` | `ContestSubmissionController@store` | Upload design entry to active contest |

---

## 4. Super Admin Routes (`auth` + Role Guard Middleware)

| HTTP Method | Route Path / URI | Route Name | Controller & Method | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/admin/dashboard` | `admin.dashboard` | `AdminDashboardController@index` | Super Admin executive dashboard & analytics |
| `POST` | `/admin/users/{user}/toggle-status` | `admin.users.toggleStatus` | `AdminDashboardController@toggleUserStatus` | Suspend or activate user account |
| `POST` | `/admin/broadcast` | `admin.broadcast` | `AdminDashboardController@broadcastNotification` | Broadcast alert to all registered users |
| `GET` | `/admin/resources` | `admin.resources.index` | `AdminResourceController@index` | Resource approval moderation panel |
| `POST` | `/admin/resources/{resource}/approve` | `admin.resources.approve` | `AdminResourceController@approve` | Approve asset & publish to marketplace |
| `POST` | `/admin/resources/{resource}/reject` | `admin.resources.reject` | `AdminResourceController@reject` | Reject pending resource asset |
| `GET` | `/admin/verifications` | `admin.verifications.index` | `AdminVerificationController@index` | KYC seller verification review queue |
| `GET` | `/admin/verifications/{verification}` | `admin.verifications.show` | `AdminVerificationController@show` | Inspect seller identity documents |
| `POST` | `/admin/verifications/{verification}/approve` | `admin.verifications.approve` | `AdminVerificationController@approve` | Approve seller KYC & award verified badge |
| `POST` | `/admin/verifications/{verification}/reject` | `admin.verifications.reject` | `AdminVerificationController@reject` | Reject seller KYC application |
| `GET` | `/admin/contests` | `admin.contests.index` | `ContestController@adminIndex` | Admin design contest management panel |
| `POST` | `/admin/contests` | `admin.contests.store` | `ContestController@adminStore` | Create new design challenge |
| `POST` | `/admin/contests/{contest}/winner` | `admin.contests.winner` | `ContestController@selectWinner` | Pick contest winner & award badge |
