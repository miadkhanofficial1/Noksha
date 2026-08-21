# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.9.0-alpha] - 2026-08-21

### Added
- Redesigned **Featured Templates (জনপ্রিয় টেমপ্লেট)** section into a premium Figma-level Marketplace UI in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php):
  - Updated responsive column layout: Desktop 3 cols (`col-lg-4`), Tablet 2 cols (`col-md-6`), Mobile 1 col (`col-12`).
  - Added top purple gradient accent connector line (`linear-gradient(90deg, #6C4CF1, #8B5CF6, #9F7AEA)`) for smooth visual flow from Hero Section.
  - Cards feature 24px border radius (`1.5rem`), soft purple borders (`rgba(108, 76, 241, 0.12)`), floating white category badges, SVG gradient previews, star rating badges, download counters, and price tags (`Free`, `৳199`, `৳299`, `৳499`).
  - Smooth 0.3s CSS hover animations (`translateY(-8px)` with soft purple shadow glow).
  - Purple CTA button (*"View Details"*) styled with `#6C4CF1`.

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

### Phase 10: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
