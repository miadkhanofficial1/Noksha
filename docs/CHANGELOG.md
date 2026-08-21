# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.21.0-alpha] - 2026-08-21

### Added
- Built real **Seller Verification System (KYC)** connected to database & local storage:
  - **Database Migration:** Created [`database/migrations/2026_08_21_000009_create_seller_verifications_table.php`](../database/migrations/2026_08_21_000009_create_seller_verifications_table.php) storing seller identity verification details.
  - **Eloquent Model:** Created [`app/Models/SellerVerification.php`](../app/Models/SellerVerification.php) and added `verification()` HasOne relationship to [`app/Models/User.php`](../app/Models/User.php).
  - **Controller:** Created [`app/Http/Controllers/SellerVerificationController.php`](../app/Http/Controllers/SellerVerificationController.php) handling verification form rendering (`create()`) and document persistence (`store()`).
  - **Glassmorphism View:** Created [`resources/views/seller/verification/create.blade.php`](../resources/views/seller/verification/create.blade.php):
    - **Status Header Card:** Real-time status display (`Not Submitted`, `Pending Review`, `Approved`, `Rejected`).
    - **Personal Identity Form:** Legal full name, date of birth, country of residence dropdown.
    - **Government ID Upload Zone:** ID type selection (NID, Passport, Driving License) with drag & drop document upload (JPG, PNG, PDF max 10MB).
    - **Face Selfie Photo Upload Zone:** Clear face photo drag & drop upload (JPG, PNG max 5MB).
    - **Video Verification Upload Zone:** Short selfie video upload (MP4, WEBM max 15s / 50MB).
    - **Agreement & Submit Bar:** Confirmation checkbox and submission action button.
  - **Seller Dashboard Integration:** Integrated Verification Progress Card into [`resources/views/seller/dashboard.blade.php`](../resources/views/seller/dashboard.blade.php) displaying real-time status and "Verify Now" CTA.
  - **Routes & Navigation:** Registered protected routes `GET /seller/verification` (`seller.verification.create`) and `POST /seller/verification` (`seller.verification.store`) in [`routes/web.php`](../routes/web.php), and added `"Verify Identity"` link to header navbar in [`resources/views/layouts/app.blade.php`](../resources/views/layouts/app.blade.php).

---

## [0.20.0-alpha] - 2026-08-21

### Added
- Built real **Super Admin Resource Approval Panel** connected to the uploaded assets database:
  - **Controller:** Created [`app/Http/Controllers/AdminResourceController.php`](../app/Http/Controllers/AdminResourceController.php) handling asset moderation queue listing (`index()`), approval (`approve()`), and rejection (`reject()`).
  - **View:** Built responsive glassmorphism view in [`resources/views/admin/resources/index.blade.php`](../resources/views/admin/resources/index.blade.php).

---

## [0.19.0-alpha] - 2026-08-21

### Added
- Built real **Seller Dashboard** connected to uploaded assets and MySQL database:
  - **Controller:** Created [`app/Http/Controllers/SellerDashboardController.php`](../app/Http/Controllers/SellerDashboardController.php) handling dashboard rendering (`index()`) and asset deletion (`destroy()`).
  - **View:** Built responsive glassmorphism view in [`resources/views/seller/dashboard.blade.php`](../resources/views/seller/dashboard.blade.php).

---

## [0.18.0-alpha] - 2026-08-21

### Added
- Built real **Seller Resource Upload System** connected to MySQL database:
  - **Controller:** Created [`app/Http/Controllers/ResourceController.php`](../app/Http/Controllers/ResourceController.php) handling asset creation (`create()`) and storage (`store()`).
  - **View:** Built glassmorphism upload form in [`resources/views/resource/create.blade.php`](../resources/views/resource/create.blade.php).

---

## [0.17.0-alpha] - 2026-08-21

### Added
- Built new **Seller Profile View** in [`resources/views/seller/profile.blade.php`](../resources/views/seller/profile.blade.php) and demo route `/seller/demo` in [`routes/web.php`](../routes/web.php).

---

## [0.16.0-alpha] - 2026-08-21

### Added
- Built new **Resource Details View** in [`resources/views/resource/show.blade.php`](../resources/views/resource/show.blade.php) and route `/resource/demo` in [`routes/web.php`](../routes/web.php).

---

## [0.15.0-alpha] - 2026-08-21

### Added
- Completed **Milestone 11 – Premium Homepage Polish** in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.healthy-alpha] - 2026-08-21

---

## [Planned Milestones]

### Phase 22: Admin Seller Verification Review Panel
- Build Super Admin verification review panel to inspect uploaded NIDs, face selfies, and videos to approve or decline seller KYC requests.
