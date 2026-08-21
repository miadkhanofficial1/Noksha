# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.6.0-alpha] - 2026-08-21

### Added
- Upgraded homepage Hero Section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php) with a premium Figma-level Bootstrap 5 UI:
  - Custom purple gradient background (`#6C4CF1` → `#8B5CF6`).
  - Large bilingual headline: *"বাংলার সেরা Graphic Marketplace"*.
  - Subtitle: *"AI-powered marketplace for templates, UI kits, vectors and digital assets."*
  - Large rounded-pill search bar with search icon and action button.
  - Dual CTA buttons: *"Explore Templates"* and *"Become Seller"* (routed to seller registration).
  - Right-side modern glassmorphism UI canvas mockup (pure CSS/HTML glass backdrop, Figma/PSD/AI/SVG badges, verified indicators, ratings, and floating overlays).
  - Bottom statistics grid displaying `10K+ Templates`, `2K+ Creators`, `50K+ Downloads`.

---

## [0.5.0-alpha] - 2026-08-21

### Added
- Built full authentication foundation and workflow controllers in `app/Http/Controllers/Auth/`:
  - `RegisterController.php`: Handles registration with Name, unique Username validation, Email, optional Phone, and Password confirmation. Enforces default role `role = 'user'` (dual Buyer & Contributor capability) and fires `Registered` event.
  - `LoginController.php`: Supports login by either **Email or Username** with password authentication and session regeneration / destruction.
  - `VerificationController.php`: Implements email verification notices, signed verification links, and resend notifications.
  - `ForgotPasswordController.php` & `ResetPasswordController.php`: Full password reset email request and password token reset handling.
  - `SocialAuthController.php`: Prepared Google OAuth login architecture placeholder structure (`auth.google` & `auth.google.callback`).
  - `OtpController.php` & `OtpService.php`: Prepared 6-digit OTP code generation and verification architecture stub for future SMS gateway integration.
- Updated `app/Models/User.php` to implement `MustVerifyEmail` and extend `Authenticatable`.
- Built Bootstrap 5 styled bilingual (Bangla + English) responsive Auth views in `resources/views/auth/`:
  - `register.blade.php`, `login.blade.php`, `verify.blade.php`, `passwords/email.blade.php`, `passwords/reset.blade.php`, `otp.blade.php`.
- Integrated active guest sign-in / registration links and authenticated user profile dropdown in [`resources/views/layouts/app.blade.php`](../resources/views/layouts/app.blade.php).

---

## [0.4.0-alpha] - 2026-08-21

### Added
- Designed core database architecture with 8 Laravel 12 migration files in `database/migrations/`:
  - `2026_08_21_000001_create_users_table.php` (User accounts with roles, avatar, trust score, verification, and soft deletes).
  - `2026_08_21_000002_create_categories_table.php` (Nested categories hierarchy with `parent_id` foreign key).
  - `2026_08_21_000003_create_resources_table.php` (Graphic templates, preview images, file paths, tags, prices, statuses, download/view counts).
  - `2026_08_21_000004_create_carts_table.php` (User shopping cart items with unique constraints).
  - `2026_08_21_000005_create_orders_table.php` (Customer orders, payment statuses, total prices, and unique order numbers).
  - `2026_08_21_000006_create_order_items_table.php` (Line items linking orders to digital resources).
  - `2026_08_21_000007_create_reviews_table.php` (Ratings & feedback on resources with user constraints).
  - `2026_08_21_000008_create_notifications_table.php` (System notifications for users with read status).
- Created 8 Eloquent models in `app/Models/` with foreign keys, type casts, and full bidirectional relationships.

---

## [0.3.0-alpha] - 2026-08-21

### Added
- Created comprehensive Laravel `.gitignore` file excluding `node_modules`, `public/build`, `vendor`, `.env`, storage logs, and IDE configuration folders.
- Configured `.env` & `.env.example` for MySQL integration (`noksha_db`).
- Added complete Laragon setup instructions and Virtual Host configuration for `noksha.test` in [`docs/INSTALL.md`](INSTALL.md).
- Updated [`docs/README.md`](README.md) with stack specifications and database credentials.

---

## [0.2.0-alpha] - 2026-08-21

### Added
- Converted project folder into a modern **Laravel 12** project structure.
- Configured PHP 8.3 target compatibility in `composer.json` and `.env`.
- Integrated **Vite 6** with Bootstrap 5 and Sass processing (`vite.config.js`).
- Created reusable base layout (`resources/views/layouts/app.blade.php`) and welcome homepage (`resources/views/welcome.blade.php`).

---

## [0.1.0-alpha] - 2026-08-21

### Added
- Initialized core repository folder structure: `docs/`, `prompts/`, `assets/`, `screenshots/`, `backup/`.
- Created preliminary project documentation (`README.md`, `INSTALL.md`, `SRS.md`, `CHANGELOG.md`, `PRESENTATION.md`).

---

## [Planned Milestones]

### Phase 7: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
