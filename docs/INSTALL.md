# Installation & Setup Guide - Noksha (নকশা)

This document provides complete instructions for setting up and running **Noksha (নকশা) - AI-Powered Graphics Template Marketplace Web Application** using **Laragon** on Windows.

---

## 📋 System Requirements

| Component | Version | Status |
| :--- | :--- | :--- |
| **Framework** | Laravel 12 | ✅ Configured |
| **PHP** | PHP 8.3 | ✅ Configured |
| **Asset Compiler** | Vite 6 & Bootstrap 5 | ✅ Installed & Built |
| **Database** | MySQL (`noksha_db`) | ⚙️ Configured in `.env` |
| **Environment Server** | Laragon (Apache/Nginx) | 🚀 Ready for Virtual Host |

---

## 🐊 Laragon & Virtual Host (`noksha.test`) Setup Instructions

### Step 1: Place Project in Laragon Root
Copy or link the Noksha project folder into Laragon's `www` root:
```text
C:\laragon\www\Noksha
```
*(Alternatively, keep the project at `C:\Users\MIAD KHAN\Desktop\project\Noksha` and add a symlink or custom vhost pointing `DocumentRoot` to `C:\Users\MIAD KHAN\Desktop\project\Noksha\public`).*

### Step 2: Configure Virtual Host (`noksha.test`)
1. Open **Laragon**.
2. Click **Menu -> Apache -> Virtual Hosts** (or **Nginx -> Virtual Hosts**).
3. Ensure **Auto Virtual Hosts** is checked.
4. Laragon auto-generates the configuration at `C:\laragon\etc\apache2\alias\noksha.conf` or `C:\laragon\etc\apache2\sites-enabled\auto.noksha.test.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/Users/MIAD KHAN/Desktop/project/Noksha/public"
    ServerName noksha.test
    ServerAlias *.noksha.test
    <Directory "C:/Users/MIAD KHAN/Desktop/project/Noksha/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

5. Verify `hosts` file (`C:\Windows\System32\drivers\etc\hosts`) contains:
```text
127.0.0.1 noksha.test
```

### Step 3: Create MySQL Database (`noksha_db`)
1. In Laragon, click **Start All**.
2. Open **HeidiSQL** or phpMyAdmin (click **Database** button in Laragon).
3. Execute SQL statement to create the database:
```sql
CREATE DATABASE IF NOT EXISTS noksha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## ⚡ Database Migrations & Running

### Step 4: Run Database Migrations
Once MySQL is running on `127.0.0.1:3306`:
```bash
php artisan migrate
```

### Step 5: Launch Application
Open your browser and navigate to:
- **Virtual Host Domain:** [http://noksha.test](http://noksha.test)
- **Built-in Dev Server (Alternative):**
  ```bash
  php artisan serve
  ```
  Access at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Step 6: Frontend Development Watcher
To modify Bootstrap styles or JavaScript with instant live reload:
```bash
npm run dev
```
