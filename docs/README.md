# Noksha (নকশা) - AI-Powered Graphics Template Marketplace Web Application

> **University Final Year Project**  
> **Project Name:** Noksha (নকশা)  
> **Repository Directory:** `C:\Users\MIAD KHAN\Desktop\project\Noksha`  
> **Local URL:** [http://noksha.test](http://noksha.test) | [http://localhost:8000](http://localhost:8000)

---

## 📌 Project Overview

**Noksha (নকশা)** is an AI-powered web marketplace designed for graphics templates, UI design assets, vector illustrations, and digital media. The platform connects graphic designers, digital creators, and buyers with AI-driven capabilities to streamline template discovery, previewing, customization, and asset management.

This repository serves as the official codebase and documentation hub for the **Noksha** university final year project.

---

## 🎯 Key Objectives

1. **Digital Asset Marketplace:** Enable creators to upload, license, and sell graphic design templates and creative assets.
2. **AI Integration:** Provide AI-driven features for automated tag generation, design recommendations, search enhancement, and asset previews.
3. **User & Vendor Dashboards:** Seamless dashboard experiences for content creators, buyers, and system administrators.
4. **Modern UI/UX:** Responsive, modern design aesthetic built with Bootstrap 5 and custom Noksha branding.

---

## 📂 Repository Structure

```text
Noksha/
├── app/            # Laravel 12 application logic (Controllers, Models, Providers)
├── assets/         # Static media assets, icons, logos, and UI guidelines
├── backup/         # Database and code backup archives
├── bootstrap/      # Framework startup files (app.php, providers.php)
├── config/         # System configurations (app.php, view.php, database.php)
├── docs/           # Official project documentation
│   ├── README.md       # Project overview (this document)
│   ├── INSTALL.md      # Installation and Laragon setup guide
│   ├── SRS.md          # Software Requirements Specification
│   ├── CHANGELOG.md    # Development version history & progress
│   └── PRESENTATION.md # University defense slide deck outline
├── prompts/        # Stored AI prompts and engineering instructions
│   └── README.md       # Prompts guidelines and usage documentation
├── public/         # Public web root (index.php, .htaccess, Vite build assets)
├── resources/      # Blade views, SASS styles (Bootstrap 5), and JavaScript
├── routes/         # Application routes (web.php, console.php)
├── screenshots/    # Project UI screenshots, wireframes, and mockups
├── storage/        # Application storage, logs, and compiled views
├── .env            # Environment configuration (MySQL noksha_db settings)
├── .gitignore      # Laravel version control exclusion rules
└── vite.config.js  # Vite 6 asset bundling configuration
```

---

## 🛠️ Tech Stack & Configuration

- **Framework:** Laravel 12 (`laravel/framework ^12.0`)
- **PHP Compatibility:** PHP 8.3 (`php ^8.3`)
- **Frontend Stack:** Bootstrap 5.3, Vite 6, Sass, Axios
- **Database Engine:** MySQL (`noksha_db`)
- **Web Server:** Laragon (Apache/Nginx with `noksha.test` Virtual Host)

---

## 🗄️ Database Credentials (`.env`)

| Parameter | Value |
| :--- | :--- |
| **DB_CONNECTION** | `mysql` |
| **DB_HOST** | `127.0.0.1` |
| **DB_PORT** | `3306` |
| **DB_DATABASE** | `noksha_db` |
| **DB_USERNAME** | `root` |
| **DB_PASSWORD** | *(empty)* |

---

## 📜 Documentation Index

- **[Installation & Laragon Guide](INSTALL.md):** Setup instructions for local development and MySQL migration.
- **[Software Requirements Specification (SRS)](SRS.md):** Detailed functional and non-functional specifications.
- **[Changelog](CHANGELOG.md):** Historical record of project updates and milestones.
- **[Presentation Document](PRESENTATION.md):** Final defense slide structure and project pitch.
- **[Prompt Engineering Hub](../prompts/README.md):** Repository of development and AI integration prompts.
