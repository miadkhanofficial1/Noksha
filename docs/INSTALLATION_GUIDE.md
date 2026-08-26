# Installation & Setup Guide — Noksha (নকশা)

This guide provides step-by-step instructions to set up and run the **Noksha (নকশা)** Laravel 12 application on a local development machine using **Laragon / XAMPP**, **PHP 8.3+**, **MySQL**, and **Node.js**.

---

## 1. Prerequisites & System Requirements

- **PHP Version:** PHP 8.3 or higher (`php -v`)
- **Web Server:** Laragon (Recommended) or Apache/Nginx
- **Database:** MySQL 8.0+ or MariaDB 10.4+
- **Dependency Managers:**
  - Composer 2.x (`composer --version`)
  - Node.js 18.x+ & NPM (`npm --version`)
- **PHP Extensions Required:** `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`.

---

## 2. Step-by-Step Installation

### Step 1: Clone or Open Project
Navigate to the project root directory:
```bash
cd "C:\Users\MIAD KHAN\Desktop\project\Noksha"
```

### Step 2: Install Composer Dependencies
Run Composer to install all backend PHP packages:
```bash
composer install
```
*(If running on Laragon with custom binary: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe C:\ProgramData\ComposerSetup\bin\composer.phar install`)*

### Step 3: Configure Environment (.env)
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
Ensure your `.env` contains the following database configuration:
```env
APP_NAME=Noksha
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=noksha_db
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### Step 4: Generate Application Key
Generate the encryption key:
```bash
php artisan key:generate
```

### Step 5: Database Setup & Migration
1. Open Laragon / phpMyAdmin and create a database named `noksha_db`.
2. Run database migrations to construct tables:
```bash
php artisan migrate
```
3. *(Optional)* Seed initial category and demo data:
```bash
php artisan db:seed
```

### Step 6: Create Storage Symbolic Link
Create a public storage link for user avatar and preview asset uploads:
```bash
php artisan storage:link
```

### Step 7: Install Node Dependencies & Build Assets
Install frontend NPM packages and compile Vite production CSS/JS:
```bash
npm install
npm run build
```

### Step 8: Launch Local Server
Start the Laravel development server:
```bash
php artisan serve
```
Access the application in your browser at:
`http://127.0.0.1:8000` or `http://localhost:8000`

---

## 3. Demo Admin Credentials

| Role | Username / Email | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@noksha.com` | `password` | `/admin/dashboard` |
| **Seller** | `seller@noksha.com` | `password` | `/seller/dashboard` |
| **Buyer** | `buyer@noksha.com` | `password` | `/buyer/dashboard` |
