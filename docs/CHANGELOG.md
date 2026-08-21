# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.11.0-alpha] - 2026-08-21

### Added
- Added new **Trending Resources + AI Recommendation Section (আজকের জনপ্রিয় ডিজাইন)** immediately below the Categories section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php):
  - Soft gradient background (`#FFFFFF` → `#F8F5FF`).
  - Section Header with red flame badge (*"Trending"*), bilingual title (*Trending Resources / আজকের জনপ্রিয় ডিজাইন*), and subtitle (*"AI-selected high-performing assets loved by creators."*).
  - Responsive Asymmetric Layout:
    - **Left Column (7 Columns `col-lg-7`):** Large featured card (*Fintech Mobile App UI Kit*) with `#6C4CF1 → #8B5CF6` preview gradient, *"Editor's Pick"* badge, floating glass *"🔥 Trending"* badge, ⭐ 4.9 rating (240 reviews), 5.2k downloads, *"Free"* price tag, and purple CTA button (*"View Details"*).
    - **Right Column (5 Columns `col-lg-5`):** Two stacked AI cards:
      1. *Corporate Business Flyer* (`#EC4899 → #8B5CF6`, *"AI Recommended"*, ⭐ 4.8, 2.1k downloads, ৳299).
      2. *Instagram Story Bundle* (`#10B981 → #059669`, *"Fast Growing"*, ⭐ 5.0, 3.4k downloads, ৳199).
  - **Bottom AI Strip:** Translucent glassmorphism recommendation strip with AI robot icon, title (*"Smart Recommendation Engine"*), and rounded CTA button (*"Explore AI Picks"*).
  - Enhanced micro-interactions: `0.35s` ease hover lift (`translateY(-8px) scale(1.02)`), floating keyframes, and soft purple glow shadow (`box-shadow: 0 25px 45px -10px rgba(108, 76, 241, 0.22)`).

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

### Phase 12: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
