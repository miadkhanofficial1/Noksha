<div align="center">

# Noksha (নকশা) — AI-Powered Graphics Template Marketplace

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**Noksha (নকশা)** is a modern, full-stack, AI-inspired Digital Graphics Template Marketplace connecting designers, creators, and buyers with automated tagging, intelligent search, identity verification (KYC), design contests, real-time notifications, and an executive administration console.

---

</div>

## 📌 Features Overview

- 🎨 **Digital Asset Marketplace:** Instant publishing & downloading for PSD templates, Figma UI kits, SVG vectors, and 3D assets.
- 🤖 **Local AI Auto-Tag Engine:** Keyword extraction & automated tagging (`#ui`, `#fintech`, `#mobile`, `#figma`) without external paid APIs.
- 🛡️ **Pro Seller KYC Verification:** Government ID document, selfie, and video upload flow with Super Admin review queue.
- 🏆 **Design Contests & Leaderboard:** Sponsored design challenges with cash prize pools, submission galleries, and Top 10 Creator Leaderboards.
- 🔔 **Real-Time Notification Center:** In-app alert system with Today/Yesterday grouping, navbar bell preview, and instant redirect URL triggers.
- 📊 **Super Admin Executive Dashboard:** Central marketplace console with Chart.js analytics, metric counters, user status toggles (Suspend/Activate), and broadcast alerts.
- 🛒 **Cart, Wishlist, & Checkout:** Instant digital asset checkout flow with bKash/Nagad payment gateway support and direct streaming downloads.
- ⭐️ **Reviews & Ratings System:** Verified purchaser star ratings (1-5), review summaries, and seller feedback lists.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12.x (PHP 8.3+)
- **Frontend:** Bootstrap 5.3, Bootstrap Icons, Custom SCSS, Glassmorphism UI
- **Database:** MySQL 8.0 / MariaDB
- **Build Tool:** Vite 6.x
- **Analytics Visualizations:** Chart.js 4.x
- **Authentication:** Laravel Auth, Socialite (Google OAuth SSO), OTP Verification

---

## 🚀 Quick Start & Installation

```bash
# 1. Clone the repository
git clone https://github.com/miadkhanofficial1/Noksha.git
cd Noksha

# 2. Install PHP Composer dependencies
composer install

# 3. Configure environment file
cp .env.example .env
php artisan key:generate

# 4. Run database migrations & seeders
php artisan migrate --seed

# 5. Link storage disk
php artisan storage:link

# 6. Install Node modules & build Vite production assets
npm install
npm run build

# 7. Start local server
php artisan serve
```
Access application at `http://localhost:8000`.

---

## 📂 Project Structure

```
Noksha/
├── app/
│   ├── Http/Controllers/       # Resource, Search, Order, Contest, Notification, Admin Controllers
│   ├── Models/                 # User, Resource, Contest, Order, SellerVerification, Notification Models
│   └── Services/               # TagService Local AI Auto-Tagging Engine
├── database/
│   └── migrations/             # 15 Versioned Database Migration Schemas
├── docs/                       # University Submission Documentation Package
│   ├── PROJECT_OVERVIEW.md
│   ├── INSTALLATION_GUIDE.md
│   ├── SYSTEM_ARCHITECTURE.md
│   ├── DATABASE_DESIGN.md
│   ├── ROUTES_REFERENCE.md
│   ├── TESTING_REPORT.md
│   ├── DEPLOYMENT_GUIDE.md
│   ├── VIVA_GUIDE.md
│   ├── SUBMISSION_CHECKLIST.md
│   └── CHANGELOG.md
├── resources/
│   ├── views/                  # Blade Views (Home, Search, Cart, Contests, Admin, Notifications)
│   └── scss/                   # Custom SCSS Styles & Bootstrap 5 Customizations
└── routes/
    └── web.php                 # 65 Categorized Web Application Routes
```

---

## 🔑 Demo Access Credentials

| Role | Username / Email | Password | Access Dashboard |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@noksha.com` | `password` | `/admin/dashboard` |
| **Seller** | `seller@noksha.com` | `password` | `/seller/dashboard` |
| **Buyer** | `buyer@noksha.com` | `password` | `/buyer/dashboard` |

---

## 📚 Complete Documentation Package

Comprehensive documentation for university evaluation and project defense is available inside the [`docs/`](./docs) directory:
- [📄 Project Overview](./docs/PROJECT_OVERVIEW.md)
- [💻 Installation Guide](./docs/INSTALLATION_GUIDE.md)
- [📐 System Architecture & Flow Charts](./docs/SYSTEM_ARCHITECTURE.md)
- [🗄️ Database Design & ERD](./docs/DATABASE_DESIGN.md)
- [🛣️ Complete 65 Routes Reference](./docs/ROUTES_REFERENCE.md)
- [🧪 Automated & Manual Testing Report](./docs/TESTING_REPORT.md)
- [🚀 Local & Production Deployment Guide](./docs/DEPLOYMENT_GUIDE.md)
- [🎓 University Viva Preparation Guide (English + বাংলা)](./docs/VIVA_GUIDE.md)
- [✅ Final Submission Checklist](./docs/SUBMISSION_CHECKLIST.md)

---

## 🔮 Future Scope & Enhancements

- **Direct Payment Gateway Integrations:** SSLCommerz and Stripe live payment gateway webhooks.
- **AI Image Recognition Engine:** Vision transformer model for automatic visual similarity searching.
- **Creator Subscription Plans:** Monthly/Yearly creator membership subscriptions.

---

## 📜 License

This project is open-source software licensed under the [MIT License](LICENSE).
