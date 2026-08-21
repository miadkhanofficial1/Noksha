# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.8.0-alpha] - 2026-08-21

### Added
- Refined UI finish of [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php) to a premium Figma-level standard:
  - Enhanced Hero background with a 3-stop smooth gradient (`#6C4CF1` → `#8B5CF6` → `#9F7AEA`) and multi-layer radial glow overlays.
  - Added pure CSS `@keyframes fadeInUp` entrance animation and floating keyframe animation (`@keyframes floatSlow`) for glassmorphism cards.
  - Upgraded Hero Search Bar with glassmorphism styling (`backdrop-filter: blur(16px)`), white translucent container, and focus ring expansion.
  - Upgraded CTA buttons with white/purple gradient fill, subtle depth shadows, and lift transitions.
  - Glassmorphism studio illustration card updated with enhanced backdrop blur (`24px`), border highlights, and floating badge layering.
  - Upgraded Featured Template Cards:
    - Increased preview container height to `210px` with SVG scaling hover effects.
    - 24px border radius (`1.5rem`) and soft purple shadow glow on hover (`translateY(-10px)`).
    - Cleaner price tags and warning-badge rating layouts.

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

### Phase 9: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
