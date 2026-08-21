# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.10.0-alpha] - 2026-08-21

### Added
- Added new **Professional Categories Section (ক্যাটাগরি ব্রাউজ করুন)** immediately below the Featured Templates section in [`resources/views/welcome.blade.php`](../resources/views/welcome.blade.php):
  - Light lavender section background (`#F8F5FF`).
  - Small purple pill badge (*"Categories"*), bilingual header (*Explore Categories / ক্যাটাগরি ব্রাউজ করুন*), and subtitle (*"Find templates by design type."*).
  - 8 premium category cards in responsive Bootstrap 5 layout (Desktop: 4 cols `col-lg-3`, Tablet: 2 cols `col-md-6`, Mobile: 2 cols `col-6`):
    1. **UI Kits** — 1.2k Templates (`bi-grid`, `#6366F1 → #8B5CF6`)
    2. **Logos** — 850 Templates (`bi-vector-pen`, `#EC4899 → #8B5CF6`)
    3. **Social Media** — 3.1k Templates (`bi-instagram`, `#10B981 → #059669`)
    4. **Posters** — 740 Templates (`bi-image`, `#F59E0B → #EF4444`)
    5. **Branding** — 620 Templates (`bi-palette`, `#3B82F6 → #06B6D4`)
    6. **Web Design** — 980 Templates (`bi-window`, `#8B5CF6 → #6366F1`)
    7. **3D Mockups** — 430 Templates (`bi-box`, `#7C3AED → #A855F7`)
    8. **Icons** — 2.4k Templates (`bi-stars`, `#14B8A6 → #0EA5E9`)
  - Modern 24px border radius (`1.5rem`), soft purple shadows, and smooth 0.3s hover lift animation (`translateY(-8px)`).
  - Centered bottom purple CTA button (*"View All Categories"*).

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

### Phase 11: Dashboards & Creator Hub
- Build Creator asset upload interface and revenue analytics.
- Build Customer purchase history and download center.
- Build Administrator moderation portal.
