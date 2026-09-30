# 🎨 Noksha — Creative Digital Asset Marketplace & AI Synthesis Platform

A full-stack creative design marketplace and in-browser asset synthesis platform built with **Laravel**, **Tailwind CSS**, and **HTML5 Canvas Engine**. Noksha bridges Bangladeshi graphic designers and clients with seamless template licensing, design contests, mobile financial recharge gateways, and an interactive AI customizer workspace.

---

## ⚡ Key Highlights & Architecture

- **Interactive Canvas Engine (`/templates/{id}/ai-edit`):** Client-side dynamic typographical overlay, color tint presets, shimmer loaders, and 1-click high-res `.jpg` downloads without third-party API latency.
- **Dual Wallet & Credit Architecture:** Independent tracking for local BDT cash (`wallets`) and generative tokens (`ai_credits`) with real-time deduction hooks and low-balance recharge alerts.
- **Creator Economy & Payouts (`/seller/payouts`):** Automated 85/15 commission split on template purchases, revenue analytics, and withdrawal pipeline via bKash, Nagad, and Bank wire.
- **Super Admin Power Suite:**
  - **Super-User Bypass:** Unlimited AI customizer credits (`⚡ Unlimited Credits`) with zero deductions.
  - **1-Click System Purge:** Secure `RESET-NOKSHA` factory reset mechanism to wipe mock listings, transactions, and contest entries while safeguarding admin credentials.
  - **Admin Support Inbox (`/admin/messages`):** Dedicated ticket manager with read/replied status tracking and direct mail hooks.
  - **Platform Finance & Escrow (`/admin/finance`):** Global GMV, net revenue commission stats, and 1-click withdrawal approval/rejection.
- **Freelancer Contest & Escrow Protocol:** Community design contests featuring prize pool escrow, entry rating, winner declaration, source file handover, and escrow release with revision request controls.
- **Identity Verification & KYC (`/contributor/apply`):** Multistep contributor onboarding with government ID, photo verification, trust score, and administrative moderation workflow.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x / 12.x, Eloquent ORM, MySQL
- **Frontend:** Tailwind CSS (`bg-slate-950` dark theme), Alpine.js / Vanilla JS, Bootstrap 5.3
- **Synthesis:** HTML5 Canvas API (Client-side hardware-accelerated rendering)
- **Tooling:** Vite, Composer, Artisan, Sass

---

## 🚀 Quick Local Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/miadkhanofficial1/Noksha.git
   cd Noksha
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Configure your environment file:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Update your `.env` with your database credentials (e.g. `DB_DATABASE=noksha_db`, `DB_USERNAME=root`, `DB_PASSWORD=`).*

4. **Run database migrations & seed demo accounts:**
   ```bash
   php artisan migrate --seed
   ```

5. **Link storage directory for media assets:**
   ```bash
   php artisan storage:link
   ```

6. **Install Node packages & compile assets:**
   ```bash
   npm install
   npm run build
   # Or for active frontend development:
   # npm run dev
   ```

7. **Start the local development server:**
   ```bash
   php artisan serve
   ```
   Open your browser and navigate to **`http://localhost:8000`**.

---

## 🔑 Demo Access Credentials

| Role | Username / Email | Password | Primary Dashboard |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@noksha.com` | `password` | `/admin/dashboard` |
| **Verified Seller** | `seller@noksha.com` | `password` | `/seller/dashboard` & `/seller/payouts` |
| **Client / Buyer** | `buyer@noksha.com` | `password` | `/buyer/dashboard` & `/wallet` |

---

## 📂 Project Architecture

```
Noksha/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminFinanceController.php     # Payout approval, escrow management, GMV metrics
│   │   ├── AdminMessageController.php     # Support inquiries inbox & ticket status
│   │   ├── AdminSystemController.php      # 1-Click RESET-NOKSHA system purge engine
│   │   ├── AiEditorController.php         # Canvas synthesis & generative credit deduction
│   │   ├── ContestHandoverController.php  # Winner selection, source files, escrow release
│   │   ├── CheckoutController.php         # Direct wallet purchases & checkout flow
│   │   ├── PayoutController.php           # Seller revenue share (85%) & withdrawal requests
│   │   └── WalletController.php           # BDT wallet recharge & AI token pack purchases
│   ├── Models/
│   │   ├── AiCredit.php                   # User AI credit balance & token quota
│   │   ├── ContactMessage.php             # Support & inquiry messages
│   │   ├── Contest.php                    # Design contests & escrow prize pool
│   │   ├── Wallet.php                     # Local currency wallet (BDT)
│   │   ├── WalletTransaction.php          # Audit logs for all financial events
│   │   └── Withdrawal.php                 # Seller withdrawal requests (bKash/Nagad/Bank)
│   └── Services/
│       └── TagService.php                 # Local AI auto-tagging keyword engine
├── database/
│   ├── migrations/                        # Versioned schemas including wallet & credit tables
│   └── seeders/                           # Category and Demo User seeders
├── docs/                                  # Project documentation & defense package
├── resources/
│   └── views/
│       ├── admin/                         # Finance, Support Messages, System Control
│       ├── editor/
│       │   └── workspace.blade.php        # 2-Column AI Template Customizer & Canvas
│       ├── seller/
│       │   └── payouts.blade.php          # Seller revenue, commission analytics & payout
│       └── wallet/
│           └── index.blade.php            # Dual wallet hub, bKash/Nagad deposits & tokens
└── routes/
    └── web.php                            # Categorized marketplace & admin routes
```

---

## 🌐 Core Modules & Capabilities

### 1. Interactive AI Template Customizer (`/templates/{id}/ai-edit`)
- **Real-Time Client Rendering:** Live overlay of headline, sub-headline, and badge text layers directly over graphics templates using HTML5 Canvas.
- **Color Filters & Tinting:** Dynamic visual styling including Cyberpunk, Warm Sunset, Electric Indigo, and Noir presets.
- **Credit Integration:** Automated per-generation deduction from user AI credit balance with instant low-token modal alerts.
- **Super Admin Bypass:** Unlimited generation quota badge (`⚡ Unlimited Credits`) with zero token deductions.

### 2. Dual Wallet & Financial Architecture (`/wallet`)
- **Dual Balances:** Separate accounting for local currency funds (`BDT ৳`) and AI synthesis generation tokens (`AI Credits`).
- **Flexible Recharging:** Simulated instant wallet deposits through mobile financial services (bKash, Nagad) and card gateways.
- **AI Credit Packs:** One-click conversion of wallet balance into token tiers (Starter, Creator, Studio).

### 3. Creator Economy & Payout Engine (`/seller/payouts`)
- **Automated Revenue Split:** 85% of each sale credited immediately to creator's earnings wallet; 15% platform commission retained.
- **Multi-Method Withdrawals:** Cashout pipeline supporting bKash Personal/Merchant, Nagad, and Bank direct transfer.
- **Transparent Audit Trails:** Real-time logging of pending, approved, and rejected payouts with reference IDs.

### 4. Super Admin Management Suite (`/admin`)
- **Platform Financial Analytics (`/admin/finance`):** Real-time monitoring of Gross Merchandise Value (GMV), platform net revenue, pending payout pipeline, and escrow.
- **Support Inbox (`/admin/messages`):** Central customer support ticket dashboard with status toggles (`New`, `Read`, `Replied`) and quick reply mail triggers.
- **1-Click System Purge:** Secure administrative factory reset (`RESET-NOKSHA`) wiping seed listings, contest entries, and test transactions without touching Super Admin privileges.

---

## 📚 Complete Documentation Package

Comprehensive documentation for university evaluation, system architecture, and viva defense is available in the [`docs/`](./docs) directory:

- [📄 Project Overview](./docs/PROJECT_OVERVIEW.md)
- [💻 Detailed Installation Guide](./docs/INSTALLATION_GUIDE.md)
- [📐 System Architecture & Flow Charts](./docs/SYSTEM_ARCHITECTURE.md)
- [🗄️ Database Design & ERD](./docs/DATABASE_DESIGN.md)
- [🛣️ Complete Routes Reference](./docs/ROUTES_REFERENCE.md)
- [🧪 Automated & Manual Testing Report](./docs/TESTING_REPORT.md)
- [🚀 Local & Production Deployment Guide](./docs/DEPLOYMENT_GUIDE.md)
- [🎓 University Viva Preparation Guide (English + বাংলা)](./docs/VIVA_GUIDE.md)
- [✅ Final Submission Checklist](./docs/SUBMISSION_CHECKLIST.md)

---

## 📜 License

This project is open-source software licensed under the [MIT License](LICENSE).
