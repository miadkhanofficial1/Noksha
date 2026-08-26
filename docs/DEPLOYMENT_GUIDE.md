# Production Deployment Guide — Noksha (নকশা)

This document provides deployment guidelines for deploying **Noksha (নকশা)** across local environments, cPanel Shared Hosting, and Cloud Virtual Private Servers (VPS).

---

## 1. Local Deployment (Laragon / XAMPP)

1. Clone project into web server root (`C:\laragon\www\Noksha` or `htdocs`).
2. Run `composer install --no-dev --optimize-autoloader`.
3. Configure `.env` with local database credentials (`noksha_db`).
4. Run `php artisan migrate --force`.
5. Run `php artisan storage:link`.
6. Run `npm install` and `npm run build`.
7. Serve application via `php artisan serve` or local vhost `noksha.test`.

---

## 2. Shared Hosting Deployment (cPanel)

### Step 1: Prepare Files
1. Run `composer install --no-dev --optimize-autoloader` locally.
2. Run `npm run build` to generate compiled assets in `public/build`.
3. Compress all project files into a `noksha_release.zip` file (excluding `node_modules`, `.git`, `.env`).

### Step 2: Upload to cPanel
1. Open cPanel File Manager and extract `noksha_release.zip` into a folder above `public_html` (e.g. `/home/username/noksha_app`).
2. Move the contents of `noksha_app/public` into `public_html/`.

### Step 3: Configure Index.php
Edit `public_html/index.php` to point to the correct vendor and bootstrap paths:
```php
require __DIR__.'/../noksha_app/vendor/autoload.php';
$app = require_once __DIR__.'/../noksha_app/bootstrap/app.php';
```

### Step 4: Import Database & Environment
1. Create a MySQL database in cPanel MySQL Database Wizard.
2. Export local database `noksha_db.sql` and import into cPanel phpMyAdmin.
3. Configure `.env` inside `/home/username/noksha_app/.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_DATABASE=cpanel_noksha_db
DB_USERNAME=cpanel_noksha_user
DB_PASSWORD=SecurePassword123!
```

---

## 3. Cloud VPS Deployment (Ubuntu 24.04 LTS + Nginx + PHP 8.3)

### Step 1: Provision Nginx & PHP 8.3
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip composer git -y
```

### Step 2: Configure Nginx Virtual Host
Create `/etc/nginx/sites-available/noksha`:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/noksha/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```
Enable site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/noksha /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

### Step 3: SSL Certificate Setup
Install Let's Encrypt SSL certificate:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com
```
