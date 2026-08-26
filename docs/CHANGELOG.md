# Changelog - Noksha (নকশা)

All notable changes to the **Noksha (নকশা)** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-08-26 — Milestone 28 (Final University Submission Package)

### Added & Completed
- **Complete University Submission Documentation Package**:
  - Created [`docs/PROJECT_OVERVIEW.md`](../docs/PROJECT_OVERVIEW.md): Detailed problem statement, solution overview, key objectives, and feature matrix.
  - Created [`docs/INSTALLATION_GUIDE.md`](../docs/INSTALLATION_GUIDE.md): Complete setup guide for Laragon, PHP 8.3, MySQL, Node.js, `.env`, migration, and Vite asset compilation.
  - Created [`docs/SYSTEM_ARCHITECTURE.md`](../docs/SYSTEM_ARCHITECTURE.md): System sitemaps and high-level Mermaid workflow diagrams for User Auth, Seller Upload, Buyer Checkout, and Super Admin Moderation.
  - Created [`docs/DATABASE_DESIGN.md`](../docs/DATABASE_DESIGN.md): Database Entity Relationship Diagram (ERD) and table schema definitions for `users`, `resources`, `categories`, `orders`, `order_items`, `reviews`, `seller_verifications`, `contests`, `contest_submissions`, and `notifications`.
  - Created [`docs/ROUTES_REFERENCE.md`](../docs/ROUTES_REFERENCE.md): Sitemap reference documenting all 65 HTTP routes categorized by Public, Buyer, Seller, and Super Admin roles.
  - Created [`docs/TESTING_REPORT.md`](../docs/TESTING_REPORT.md): Structured test suite execution matrix with 42 passed test cases across 10 modules.
  - Created [`docs/DEPLOYMENT_GUIDE.md`](../docs/DEPLOYMENT_GUIDE.md): Production deployment guides for Local, cPanel Shared Hosting, and Cloud VPS (Ubuntu 24.04 + Nginx + Let's Encrypt).
  - Created [`docs/VIVA_GUIDE.md`](../docs/VIVA_GUIDE.md): Comprehensive bilingual (English + বাংলা) viva preparation guide containing 30 common defense questions and technical answers.
  - Created [`docs/SUBMISSION_CHECKLIST.md`](../docs/SUBMISSION_CHECKLIST.md): Final university submission readiness checklist with 100/100 score.
- **Root README Upgrade**:
  - Created upgraded root [`README.md`](../README.md) featuring tech stack badges, features summary, quick start guide, project structure, demo credentials, documentation sitemap, and future scope.

---

## [1.0.0-rc1] - 2026-08-26

### Added & Updated
- **Production Polish & Security Hardening**: Enforced role guards, added global toast alerts, and automated form loading indicators.
