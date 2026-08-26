# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0-rc1] - 2026-08-26

### Added & Updated (Production Polish & Release Candidate)
- **Role-Based Security & Authorization Guards**:
  - Enforced Super Admin role guards across [`AdminDashboardController.php`](../app/Http/Controllers/AdminDashboardController.php) and [`AdminResourceController.php`](../app/Http/Controllers/AdminResourceController.php) to restrict administrative controls strictly to `admin` / `super_admin` roles.
  - Verified customer order download security in [`OrderController.php`](../app/Http/Controllers/OrderController.php) ensuring paid digital files are streamed exclusively to verified purchasers.
- **Global UX & Form Loading Indicators**:
  - Updated [`resources/views/layouts/app.blade.php`](../resources/views/layouts/app.blade.php) with an automated form submission spinner script that disables submit buttons and displays a processing indicator to prevent double-submissions.
  - Integrated floating dismissible Global Toast notification container supporting `success`, `warning`, and `error` flash alerts.
- **Performance & Eager Loading**:
  - Verified eager loading (`with(['owner', 'category'])`) across all controllers to eliminate N+1 database queries.
- **Accessibility & Responsive Audit**:
  - Enhanced contrast, button wrapping, and focus state accessibility across Homepage, Search, Seller Dashboard, Buyer Dashboard, Cart, Wishlist, Checkout, Orders, Contests, Verification, and Executive Admin pages.

---

## [0.32.0-alpha] - 2026-08-26

### Added & Updated
- **AI-Inspired Local Auto Tagging & Smart Search System**:
  - Created `TagService.php` and `SearchController.php` with smart search and keyword dictionary.
