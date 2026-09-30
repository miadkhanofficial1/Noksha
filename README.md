# 🎨 Noksha — Creative Digital Asset Marketplace & AI Synthesis Platform

A full-stack creative digital asset marketplace, freelancer contest platform, and in-browser AI design synthesis workspace built with **Laravel**, **Tailwind CSS**, and the **HTML5 Canvas Engine**. Noksha connects Bangladeshi graphic designers and clients with seamless template licensing, escrow-backed design contests, manual mobile financial deposit verification (bKash & Nagad), and an interactive client-side asset customizer.

---

## ⚡ Key Highlights & Architecture

- **Permanent Pure Dark Theme (`bg-slate-950`):** Designed as a unified dark SaaS application with `slate-950` root background, `slate-900/70` backdrop-blurred cards, `slate-800` borders, vibrant neon status badges, and strict SVG constraints (`w-5 h-5` / `w-4 h-4 shrink-0`). Light mode toggles have been removed for consistent visual branding.
- **Strict Role Isolation & Dynamic Dashboard:**
  - **Buyer Mode:** Clean shopping dashboard with live statistics: *Available AI Credits*, *Total Purchases / Spent (৳)*, *Total Downloads*, *Saved Wishlists*, and zero seller clutter.
  - **Contributor / Seller Mode:** Full creative studio tracking: *Total Uploads (Live & Approved)*, *Lifetime Template Sales (Gross Volume)*, *Seller Royalties Ready for Cashout*, and *Template Deliveries*.
  - **Contributor Mode Switcher:** Verified creators seamlessly toggle between Buyer and Contributor modes from the top profile dropdown.
- **Contributor KYC Verification Protocol (`/contributor/apply`):**
  - Multi-tier onboarding workflow with government NID / Passport verification, professional portfolio links, biography, and document upload.
  - State machine: `none` $\rightarrow$ `pending` (admin escrow/identity review) $\rightarrow$ `approved` (creator badge, upload permissions, and payouts unlocked) or `rejected` (rejection reason recorded with re-application cooldown).
  - Strict route middleware (`EnsureApprovedContributor`) protecting all creator studio and payout routes (`/seller/*`, `/resource/upload`).
- **Financial Balance Isolation & Royalties Protection:**
  - **`wallet_balance` (Shopping Balance):** Deposited by buyers to buy templates and AI generation credits. **Cannot be cashed out.**
  - **`earnings_balance` (Seller Royalty Earnings):** Royalties earned from template sales (85% net creator cut, 15% platform fee). **Only this balance is eligible for withdrawal.**
- **Manual MFS Deposit Verification Pipeline (bKash & Nagad):**
  - Users deposit BDT funds via bKash / Nagad send-money or merchant numbers and submit their sender mobile number and Transaction ID (TrxID).
  - Administrators verify transaction validity in `/admin/finance` and approve or decline deposits with automated ledger tracking.
- **Realtime / Database Notification Pipeline:**
  - Database-driven notification system (`notifications` table) with delivery channel integration.
  - Automatically notifies users on new design contest launches with prize bounty and direct deep links (`NewContestLaunchedNotification`), KYC application approvals/rejections, and wallet transactions.
  - Navbar bell counter badge displays live unread counts with 1-click AJAX read-and-redirect.
- **Interactive AI Template Customizer (`/templates/{id}/ai-edit`):**
  - Client-side dynamic typographical overlay, color tint presets (Cyberpunk, Warm Sunset, Electric Indigo, Noir), and instant high-res `.jpg` export powered by HTML5 Canvas.
  - Tokenized AI generation quota with instant low-balance recharge modals.
  - Super Admin bypass with unlimited customizer credits (`⚡ Unlimited Credits`).
- **Escrow-Backed Design Contests (`/contests`):**
  - Community design contests featuring prize pool escrow, entry rating, winner declaration, source file handover, and escrow release with revision request controls.
- **Administrative Control Suite (`/admin`):**
  - Financial ledger analytics: Global GMV, net revenue commission, pending payout queue, and deposit verification.
  - Support ticket inbox (`/admin/messages`) with read/reply status tracking.
  - Contributor KYC moderation dashboard (`/admin/users?status=pending_kyc`).

---

## 🛠️ Tech Stack

- **Backend Framework:** Laravel 11.x / 12.x (PHP 8.2+)
- **Database & ORM:** MySQL 8.x, Eloquent ORM
- **Frontend Architecture:** Tailwind CSS (`bg-slate-950`), Vite, Alpine.js / Vanilla JS, Bootstrap 5.3 (Navigation & Modals)
- **Canvas Engine:** HTML5 Canvas 2D Context API (Client-side hardware acceleration)
- **Dependency Management:** Composer, NPM

---

## 🚀 Quick Local Setup

Follow these steps to set up Noksha in your local development environment:

### 1. Clone the repository
```bash
git clone https://github.com/miadkhanofficial1/Noksha.git
cd Noksha
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Configure the environment
```bash
cp .env.example .env
php artisan key:generate
```
Open your `.env` file and set your database connection:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=noksha
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run database migrations
Execute database migrations without seeders:
```bash
php artisan migrate
```

### 5. Create storage symlink
Link public storage for user avatars, contest attachments, and KYC documents:
```bash
php artisan storage:link
```

### 6. Install Node dependencies & build frontend assets
```bash
npm install
npm run build
```
> For active frontend development with hot-reloading:
> ```bash
> npm run dev
> ```

### 7. Start the local server
```bash
php artisan serve
```
Open your browser and navigate to **`http://localhost:8000`**.

---

## 👑 Administrator Account Setup

After running migrations, register a standard account via the `/register` page, then promote it to Super Admin using Laravel Tinker:

```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->update([
    'role' => 'admin',
    'is_admin' => true,
    'contributor_status' => 'approved',
    'active_mode' => 'seller',
]);
```
This unlocks the **Super Admin Console** (`/admin/dashboard`), **Finance & Payouts** (`/admin/finance`), **KYC Moderation** (`/admin/users`), and unlimited AI credits.

---

## 📂 Core Directory Structure

```
Noksha/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminContestController.php       # Contest approval, rejection, and management
│   │   │   ├── AdminFinanceController.php       # Deposit verification, withdrawal approval, GMV metrics
│   │   │   ├── AdminMessageController.php       # Support inbox & ticket status management
│   │   │   ├── AdminVerificationController.php  # Contributor KYC review & decision engine
│   │   │   ├── AiEditorController.php           # Canvas synthesis & generative credit deduction
│   │   │   ├── CheckoutController.php           # Cart checkout & wallet template purchases (85% royalty split)
│   │   │   ├── ContestController.php            # Contest creation, showcase, and leaderboard
│   │   │   ├── ContestHandoverController.php    # Contest winner selection, source files, escrow release
│   │   │   ├── DashboardController.php          # Dynamic buyer vs. seller dashboard routing
│   │   │   ├── NotificationController.php       # User notification center & AJAX mark-as-read
│   │   │   ├── PayoutController.php             # Creator earnings cashout (bKash/Nagad/Bank)
│   │   │   ├── SellerVerificationController.php # Contributor KYC application submission
│   │   │   └── WalletController.php             # MFS deposit requests (TrxID) & AI credit packs
│   │   └── Middleware/
│   │       └── EnsureApprovedContributor.php    # Route guard isolating seller & payout tools
│   ├── Models/
│   │   ├── AiCredit.php                         # AI generation token quota & ledger
│   │   ├── Contest.php                          # Design contest escrow records & timeline
│   │   ├── ContestEntry.php                     # Designer submissions, ratings, and award status
│   │   ├── Notification.php                     # Central database notification model & payload parser
│   │   ├── Resource.php                         # Digital marketplace templates & assets
│   │   ├── SellerVerification.php               # Contributor KYC documents & verification state
│   │   ├── User.php                             # User accounts, roles, accessors, and relationships
│   │   ├── Wallet.php                           # Central BDT shopping balance
│   │   ├── WalletTransaction.php                # Immutable financial audit logs
│   │   └── Withdrawal.php                       # Creator cashout requests & processing state
│   └── Notifications/
│       └── NewContestLaunchedNotification.php   # Broadcast notification on contest launch
├── database/
│   └── migrations/                              # Versioned database schemas
├── resources/
│   ├── views/
│   │   ├── admin/                               # Admin dashboards, finance, KYC, messages
│   │   ├── contests/                            # Contest hub, creation form, entries gallery
│   │   ├── editor/                              # HTML5 Canvas AI customizer workspace
│   │   ├── layouts/                             # Dark application shells & navigation partials
│   │   ├── notifications/                       # User notification center (Today / Yesterday / Earlier)
│   │   ├── seller/                              # Payouts ledger, cashout form, KYC verification view
│   │   └── wallet/                              # Central wallet hub, MFS recharge, AI token packs
│   └── scss/ & css/                             # Custom Tailwind & Bootstrap dark styling
└── routes/
    └── web.php                                  # Authenticated, guest, contributor, and admin routes
```

---

## 🔒 Security & Access Control Summary

| Route Path | Allowed Roles | Access Conditions | Unauthorized Action |
| :--- | :--- | :--- | :--- |
| `/dashboard` | All authenticated users | Renders Buyer view or Contributor view based on approved status & active mode | Defaults to Buyer view |
| `/resource/upload` | Approved Contributors, Admins | `role == 'contributor'` AND `contributor_status == 'approved'` | Redirects to `/contributor/apply` with warning |
| `/seller/dashboard` | Approved Contributors, Admins | Approved contributor status verified by middleware | Redirects to `/contributor/apply` with warning |
| `/seller/payouts` | Approved Contributors, Admins | Approved contributor status verified by middleware | Redirects to `/contributor/apply` with warning |
| `/contributor/apply` | All authenticated users | Accessible to apply or re-apply after cooldown | Accessible |
| `/admin/*` | Super Admins | `is_admin == true` or `role == 'admin'` | HTTP 403 Forbidden |

---

## 📜 License

This project is licensed under the [MIT License](LICENSE).
