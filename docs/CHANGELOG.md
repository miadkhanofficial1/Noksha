# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.4] - 2026-08-26 — Single User & Contributor Architecture Refactoring

### Added & Updated
- **Single User & Contributor Architecture**:
  - Refactored account architecture so every account starts as a single unified `User` (`role = 'user'`). Removed separate Buyer and Seller account types on registration.
  - Added `contributor_status` column (`none`, `pending`, `approved`, `rejected`) to `users` table via migration [`database/migrations/2026_08_26_000016_add_contributor_status_to_users_table.php`](../database/migrations/2026_08_26_000016_add_contributor_status_to_users_table.php).
  - Added helper methods `$user->isContributor()` and `$user->isVerifiedCreator()` in [`app/Models/User.php`](../app/Models/User.php).
- **Unified User & Contributor Dashboard (`/dashboard`)**:
  - Created [`app/Http/Controllers/DashboardController.php`](../app/Http/Controllers/DashboardController.php) and view [`resources/views/dashboard/index.blade.php`](../resources/views/dashboard/index.blade.php):
    - **Buyer Features (Always Visible):** Dashboard Overview, Order History, Purchased & Free Downloads, Saved Wishlist, and Cart counters.
    - **Contributor Features (Hidden until verified):** Upload Resource button, Portfolio stats, Uploaded Resources management table, Recent Sales, and Feedback Reviews.
- **Contributor Verification Flow & Naming Standard**:
  - Replaced all "Seller" references with **Contributor** (e.g. *Contributor ✓*, *Apply to Become Contributor*, *Verified Creator*).
  - Updated [`resources/views/seller/verification.blade.php`](../resources/views/seller/verification.blade.php) and [`resources/views/seller/profile.blade.php`](../resources/views/seller/profile.blade.php).
- **Clean Navbar Links**:
  - Updated [`resources/views/layouts/app.blade.php`](../resources/views/layouts/app.blade.php): Cleaned up navbar links for guests (Home, Templates, Categories, AI Tools, Login, Register) and logged-in users (Home, Templates, Dashboard, Contests, Wishlist, Cart).

---

## [1.0.3] - 2026-08-26 — Comprehensive Project-Wide Bilingual Localization (200+ Texts)

### Added & Updated
- Structured domain translation dictionaries and localized views.
