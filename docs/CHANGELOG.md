# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.17.0-alpha] - 2026-08-21

### Added
- Built new **Seller Profile View** in [`resources/views/seller/profile.blade.php`](../resources/views/seller/profile.blade.php) and demo route `/seller/demo` in [`routes/web.php`](../routes/web.php):
  - **Cover Banner & Avatar:** Purple geometric pattern banner (240px height) with overlapping 140px circular avatar, online indicator green dot, and Pro Verified badge.
  - **Seller Bio & Badges:** Title (*Noksha Studio*), Verified badge (*Pro Verified Author*), Trust Rating (*99.4% Positive Feedback*), and bio copy.
  - **Skill Chips:** Interactive pill chips (`Figma`, `UI/UX Design`, `Design Systems`, `Vector Illustration`, `Iconography`, `Dark Mode`, `Branding`).
  - **Stat Counters Box:** Translucent glassmorphism card displaying 12.4K Followers, 142 Resources, and 45.8K Total Downloads.
  - **Action Buttons:** Interactive *"Follow Author"* toggle button and *"Contact Seller"* modal trigger.
  - **Portfolio Grid:** 6 demo resource cards matching the purple Noksha design system with category tabs (`All Assets`, `UI Kits`, `Vectors`, `Social`, `3D`).
  - **Global Header Navigation Link:** Added `"Demo Seller Profile"` to header navigation bar in [`resources/views/layouts/app.blade.php`](../resources/views/layouts/app.blade.php).

---

## [0.16.0-alpha] - 2026-08-21

### Added
- Built new **Resource Details View** in [`resources/views/resource/show.blade.php`](../resources/views/resource/show.blade.php) and route `/resource/demo` in [`routes/web.php`](../routes/web.php).

---

## [0.15.0-alpha] - 2026-08-21

### Added
- Completed **Milestone 11 – Premium Homepage Polish** in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.14.0-alpha] - 2026-08-21

### Added
- Redesigned **Featured Templates Filter Bar** into a floating Figma/Dribbble-style UI in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.13.0-alpha] - 2026-08-21

### Fixed
- Fixed **Category Filter Engine Bug** in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.12.0-alpha] - 2026-08-21

### Added
- Integrated client-side **Live Interactive Search & Filter Engine** on homepage [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.11.0-alpha] - 2026-08-21

### Added
- Added new **Trending Resources + AI Recommendation Section (আজকের জনপ্রিয় ডিজাইন)** immediately below the Categories section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.10.0-alpha] - 2026-08-21

### Added
- Added new **Professional Categories Section (ক্যাটাগরি ব্রাউজ করুন)** immediately below the Featured Templates section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.9.0-alpha] - 2026-08-21

### Added
- Redesigned **Featured Templates (জনপ্রিয় টেমপ্লেট)** section into a premium Figma-level Marketplace UI in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.8.0-alpha] - 2026-08-21

### Added
- Refined UI finish of [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php) to a premium Figma-level standard.

---

## [0.7.0-alpha] - 2026-08-21

### Added
- Added new **Featured Templates (জনপ্রিয় টেমপ্লেট)** section immediately below the Hero Section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php).

---

## [0.6.0-alpha] - 2026-08-21

### Added
- Upgraded homepage Hero Section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php) with a premium Figma-level Bootstrap 5 UI.

---

## [0.5.0-alpha] - 2026-08-21

### Added
- Built full authentication foundation and workflow controllers in `app/Http/Controllers/Auth/`.

---

## [0.4.0-alpha] - 2026-08-21

### Added
- Designed core database architecture with 8 Laravel 12 migration files in `database/migrations/`.

---

## [0.3.0-alpha] - 2026-08-21

### Added
- Created comprehensive Laravel `.gitignore` file and MySQL environment settings.

---

## [0.2.0-alpha] - 2026-08-21

### Added
- Converted project folder into a modern **Laravel 12** project structure with Bootstrap 5 & Vite.

---

## [0.1.0-alpha] - 2026-08-21

### Added
- Initialized core repository folder structure and documentation files.

---

## [Planned Milestones]

### Phase 17: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
