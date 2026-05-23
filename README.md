# Barangay San Jose — Integrated Resident Information & Blockchain-Based Clearance Verification System

> A complete digital records-management and document-verification platform for Philippine barangays. Built as 3rd-year BSIT project on Laravel 11.

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20)](https://laravel.com/)
[![Tailwind](https://img.shields.io/badge/Tailwind-3-38B2AC)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8-4479A1)](https://www.mysql.com/)


---

##  Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Environment Setup](#-environment-setup)
- [Running the Application](#-running-the-application)
- [Default Accounts](#-default-accounts)
- [Project Structure](#-project-structure)
- [Documentation](#-documentation)
- [Deployment Guide](#-deployment-guide)
- [Troubleshooting](#-troubleshooting)
- [License](#-license)

---

##  Overview

The **Barangay System** is a web application that modernizes the daily operations of a Philippine barangay office. It replaces paper logbooks with a secure, searchable, audit-friendly digital workspace.

The system handles the **full lifecycle of a resident record** — from registration through document issuance to eventual archiving — and provides a **public verification portal** where any third party can confirm the authenticity of an issued document by scanning its QR code.

A standout feature is the **blockchain-simulated tamper detection**: every clearance carries a unique SHA-256 fingerprint that includes the server's secret key. The verification portal recomputes the fingerprint on scan and reports whether the document is verified, revoked, or tampered.

---

##  Key Features

### Identity & Records
- **Resident Management** — full demographic records with soft-delete + archive snapshots
- **Household Management** — group residents by physical residence, conditional owner-info for rentals
- **First-Time Job Seeker** flag under RA 11261
- **Audit-Friendly Archive** — read-only snapshots when residents transition out of active status

### Document Issuance
- **4 document types**: Barangay Clearance · Residency Certificate · Indigency Certificate · Certificate of Employment
- **SHA-256 Hash Signing** with server-side `APP_KEY` — un-forgeable by clients
- **Automatic QR Codes** on every printed document
- **RA 11261 fee waiver** — free Certificate of Employment for eligible First-Time Job Seekers
- **Immutable after issuance** — revoke instead of edit/delete

### Public Verification Portal
- **No-login QR scanner** (uses device camera via html5-qrcode)
- **Manual hash fallback** for damaged/unreadable QRs
- **Four verification states**: Verified · Revoked · Tampered · Invalid
- **Every scan logged** with IP and user agent for audit

### Public Safety
- **Blotter Recording** with strict immutability after creation
- **Status workflow**: Pending → Ongoing → Resolved (resolved cases are permanently read-only)
- **Min 50-char narration** validation prevents incomplete reports

### Reporting & Analytics
- **Live dashboard** with KPI cards, monthly trends, demographic breakdowns
- **CSV exports** for residents, clearances, blotters, households, and archived records
- **Activity feed** showing the last 10 system events

### Administration
- **Role-based access** (Admin / Staff)
- **User management** (admin-only)
- **System-wide activity log** with IP tracking
- **Self-account profile** management

### Data Quality
- **Philippine mobile validation** (11 digits, starts with 09) — both frontend and backend
- **Auto-search** on all list pages (debounced)
- **Conditional fields** (rental owner details, "Other" textboxes)



---

##  Tech Stack

| Layer            | Technology                                    | Version |
|------------------|-----------------------------------------------|---------|
| **Backend**      | PHP                                           | ^8.2    |
| **Framework**    | Laravel                                       | ^11.0   |
| **Database**     | MySQL                                         | ^8.0    |
| **Frontend CSS** | Tailwind CSS                                  | ^3.4    |
| **Build Tool**   | Vite                                          | ^5.0    |
| **JS Runtime**   | Node.js                                       | ^18 LTS |
| **QR Scanning**  | html5-qrcode                                  | 2.3.8   |
| **QR Generation**| qrcodejs                                      | 1.0.0   |
| **Cryptography** | PHP `hash()` SHA-256                          | native  |
| **Testing**      | PHPUnit                                       | ^11.0   |
| **Templates**    | Laravel Blade                                 | bundled |
| **Auth**         | Laravel session-based                         | bundled |

### Frontend Libraries

- **Tailwind CSS** for styling (with `@tailwindcss/forms`)
- **Vanilla JavaScript** — no framework, no build complexity
- **CDN-loaded** scanner and QR libraries (no npm bundle bloat)

---

##  Prerequisites

Before installing, ensure you have these installed:

| Tool        | Verify with             | Where to get it                  |
|-------------|-------------------------|----------------------------------|
| PHP 8.2+    | `php --version`         | https://www.php.net/downloads    |
| Composer 2  | `composer --version`    | https://getcomposer.org          |
| MySQL 8     | `mysql --version`       | https://dev.mysql.com (or XAMPP) |
| Node.js 18+ | `node --version`        | https://nodejs.org               |
| npm 9+      | `npm --version`         | (comes with Node)                |
| Git         | `git --version`         | https://git-scm.com              |

### Required PHP Extensions

Most are bundled with standard PHP installations:

- BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, **pdo_mysql**, Tokenizer, XML

Quick check:

```bash
php -m | grep -E "pdo_mysql|mbstring|openssl|json|curl"
```

---

##  Installation

### Step 1 — Get the Code

```bash
# Option A: Git clone
git clone https://github.com/alr3n/Barangay-System-with-Blockchain-Simulation.git
cd Barangay-System-with-Blockchain-Simulation

# Option B: Unzip the project archive
unzip Barangay-System-with-Blockchain-Simulation.zip
cd Barangay-System-with-Blockchain-Simulation
```

> **Important:** All subsequent commands must be run from the project root — the folder that contains `artisan`, `composer.json`, and `package.json`. If you see "Could not open input file: artisan", you're in the wrong directory.

### Step 2 — Install PHP Dependencies

```bash
composer install
```

This downloads Laravel and its packages into `vendor/`. Allow 1–3 minutes for the first install.

### Step 3 — Install JavaScript Dependencies

```bash
npm install
```

This downloads Tailwind, Vite, and PostCSS into `node_modules/`. Allow 1–2 minutes.

---

### Step 4 — Create the Database

Open MySQL (via phpMyAdmin, MySQL Workbench, or CLI) and create the database:

```sql
CREATE DATABASE barangay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 6 — Generate Application Key

```bash
php artisan key:generate
```

This populates `APP_KEY` in your `.env`. The key is also used in the document hash algorithm, so **never** change it after issuing documents — existing QR codes will become unverifiable.

### Step 7 — Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This creates all 8 tables and populates them with sample data:
- 3 user accounts (1 admin, 2 staff)
- 8 households, 18 residents
- 12 sample clearances, 4 sample blotters

---

## 🏃 Running the Application

You need **two terminals** running simultaneously during development.

### Terminal 1 — Laravel Server

```bash
php artisan serve
```

Expected output:

```
INFO  Server running on [http://127.0.0.1:8000].
```

### Terminal 2 — Vite Hot-Reload

```bash
npm run dev
```

Expected output:

```
VITE v5.x.x  ready in 200 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

### Open the App

Navigate to **http://127.0.0.1:8000** in your browser. You'll be redirected to the login page.

---

##  Default Accounts

After seeding, the following accounts are available:

| Role  | Email                       | Password   |
|-------|-----------------------------|------------|
| Admin | `admin@barangay.gov.ph`     | `admin123` |
| Staff | `staff@barangay.gov.ph`     | `staff123` |
| Staff | `juan@barangay.gov.ph`      | `staff123` |

>  **Change these passwords immediately** before any production deployment.

If login fails after a SQL import (passwords are placeholders), reset them via Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::where('email','admin@barangay.gov.ph')
    ->update(['password' => bcrypt('admin123')]);
\App\Models\User::where('email','staff@barangay.gov.ph')
    ->update(['password' => bcrypt('staff123')]);
exit
```

---

##  Project Structure

```
barangay-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/                  ← 11 HTTP controllers
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── ArchivedResidentController.php
│   │   │   ├── BlotterController.php
│   │   │   ├── ClearanceController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── HouseholdController.php
│   │   │   ├── ReportController.php
│   │   │   ├── ResidentController.php
│   │   │   ├── UserController.php
│   │   │   └── VerificationController.php
│   │   └── Middleware/
│   │       ├── ActiveUserMiddleware.php  ← Deactivated user check
│   │       └── AdminMiddleware.php       ← Role gate
│   ├── Models/                           ← 8 Eloquent models
│   │   ├── ActivityLog.php
│   │   ├── ArchivedResident.php
│   │   ├── Blotter.php
│   │   ├── Clearance.php
│   │   ├── Household.php
│   │   ├── Resident.php
│   │   ├── User.php
│   │   └── VerificationRecord.php
│   ├── Providers/
│   ├── Rules/
│   │   └── PhilippineMobileNumber.php    ← Custom validation
│   └── View/Components/
│       └── NavLink.php
├── bootstrap/
│   ├── app.php                           ← Middleware aliases, routing config
│   └── providers.php
├── config/                               ← Standard Laravel config
│   ├── app.php · auth.php · cache.php
│   ├── cors.php · database.php · filesystems.php
│   ├── logging.php · mail.php · queue.php
│   ├── services.php · session.php · view.php
├── database/
│   ├── factories/                        ← Test data generators
│   ├── migrations/                       ← 12 migration files
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_households_table.php
│   │   ├── 2024_01_01_000003_create_residents_table.php
│   │   ├── 2024_01_01_000004_create_clearances_table.php
│   │   ├── 2024_01_01_000005_create_verification_records_table.php
│   │   ├── 2024_01_01_000006_create_blotters_table.php
│   │   ├── 2024_01_01_000007_create_activity_logs_table.php
│   │   ├── 2024_01_02_000001_add_owner_details_to_households_table.php
│   │   ├── 2024_01_03_000001_create_archived_residents_table.php
│   │   ├── 2024_01_03_000002_add_first_time_job_seeker_to_residents_table.php
│   │   ├── 2024_01_03_000003_add_certificate_of_employment_to_clearances.php
│   │   └── 2024_01_03_000004_add_ftjs_waiver_to_clearances.php
│   └── seeders/                          ← 6 seeder classes
├── lang/                                 ← Localization
├── public/
│   ├── index.php                         ← Web entry point
│   ├── favicon.ico
│   └── robots.txt
├── resources/
│   ├── css/
│   │   └── app.css                       ← Tailwind entry
│   ├── js/
│   │   ├── app.js                        ← Main JS (auto-search, combobox, modals)
│   │   └── bootstrap.js                  ← Axios setup
│   └── views/                            ← 37 Blade templates
│       ├── archived_residents/           (2 views)
│       ├── auth/                         (login)
│       ├── blotter/                      (5 views)
│       ├── clearances/                   (4 views)
│       ├── components/                   (nav-link)
│       ├── dashboard/                    (index)
│       ├── errors/                       (4 error pages)
│       ├── households/                   (4 views)
│       ├── layouts/                      (app, auth, print)
│       ├── reports/                      (index)
│       ├── residents/                    (4 views)
│       ├── users/                        (5 views)
│       └── verification/                 (index, result)
├── routes/
│   ├── web.php                           ← All routes
│   └── console.php
├── storage/                              ← Writable: logs, cache, sessions
├── tests/                                ← Feature + Unit tests
├── .env.example                          ← Environment template
├── .gitignore
├── artisan                               ← CLI tool
├── CHANGES.md                            ← Revision history
├── composer.json                         ← PHP dependencies
├── INSTALL.md                            ← Step-by-step install guide
├── package.json                          ← Frontend dependencies
├── phpunit.xml                           ← Test config
├── postcss.config.js
├── README.md                             ← This file
├── tailwind.config.js
└── vite.config.js
```

### Key Files

| File                           | Purpose                                          |
|--------------------------------|--------------------------------------------------|
| `routes/web.php`               | Single source of truth for all URLs              |
| `bootstrap/app.php`            | Middleware registration, exception handling      |
| `config/app.php`               | App name, timezone, locale, providers            |
| `config/database.php`          | DB connection configs                            |
| `resources/views/layouts/app.blade.php` | Main shell with sidebar & topbar        |
| `app/Models/Resident.php`      | Core domain object — includes `archive()` method |
| `app/Models/Clearance.php`     | Contains `generateHash()` (SHA-256 algorithm)    |

---

##  Documentation

This repository ships with three documentation files:

| File              | Audience          | Contents                                                    |
|-------------------|-------------------|-------------------------------------------------------------|
| `README.md`       | Everyone          | Project overview, install, run                              |
| `INSTALL.md`      | First-time setup  | Detailed step-by-step install + troubleshooting              |
| `DOCUMENTATION.md`| Developers / QA   | Full module-by-module system documentation                  |
| `CHANGES.md`      | Maintainers       | Revision history (Round 1 and Round 2 fix logs)             |

For module-level details (workflow, APIs, validation, screenshot placeholders), see **`DOCUMENTATION.md`**.

---

## Deployment Guide

### Production Server Requirements

- PHP 8.2+ with required extensions
- MySQL 8 or MariaDB 10.6+
- Web server: Nginx or Apache
- Composer + Node available for build (or pre-build locally)
- HTTPS certificate (Let's Encrypt recommended)
- Minimum 1 vCPU, 2 GB RAM (4 GB recommended)

### Production Deployment Steps

```bash
# 1. Clone the project
git clone https://github.com/alren/Barangay-System-with-Blockchain-Simulation.git /var/www/Barangay-System-with-Blockchain-Simulation
cd /var/www/Barangay-System-with-Blockchain-Simulation

# 2. Install production-only dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 3. Configure environment
cp .env.example .env
# Edit .env: APP_ENV=production, APP_DEBUG=false, DB credentials, APP_URL

# 4. Generate key (CRITICAL — do this BEFORE issuing any documents)
php artisan key:generate

# 5. Set permissions
chown -R www-data:www-data /var/www/Barangay-System-with-Blockchain-Simulation
chmod -R 775 storage bootstrap/cache

# 6. Migrate the database
php artisan migrate --force

# 7. Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. (Optional) seed an initial admin
php artisan db:seed --class=UserSeeder --force
```

### Nginx Configuration (Example)

```nginx
server {
    listen 80;
    server_name barangay.example.gov.ph;
    root /var/www/barangay-system/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```


### Production Maintenance Commands

```bash
# Pull updates and re-deploy
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan queue:restart    # if using queues

# Database backup (run via cron daily)
mysqldump -u root -p barangay_db > /var/backups/barangay_$(date +%Y%m%d).sql
```

---

##  Troubleshooting

### `Could not open input file: artisan`
You're in the wrong directory. `cd` into the project root (the one with `artisan`, `composer.json`, `package.json`).

### `Could not read package.json`
Same as above — you're in the wrong directory.

### `SQLSTATE[HY000] [1049] Unknown database 'barangay_db'`
The database doesn't exist. Create it:
```sql
CREATE DATABASE barangay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### `SQLSTATE[HY000] [1045] Access denied`
Wrong DB credentials in `.env`. Verify `DB_USERNAME` and `DB_PASSWORD`.

### Login fails — passwords don't work
Reset via Tinker (see [Default Accounts](#-default-accounts) above).

### CSS/JS not loading or pages look unstyled
Run `npm run dev` in a second terminal during development, or `npm run build` for production.

### `Vite manifest not found`
You haven't built the assets. Run `npm run dev` (development) or `npm run build` (production).

### `Permission denied` on storage or bootstrap/cache
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache       # or www-data on production
```

### `Class not found` errors after pulling updates
```bash
composer dump-autoload
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset everything and start over
```bash
php artisan migrate:fresh --seed
```

### QR codes don't scan
- Check that the QR is sharp (use error correction level H, which is the default)
- Print at 100% scale — don't shrink the QR
- Verify the printed URL matches `APP_URL` in your `.env`

### Verification always returns "tampered"
The `APP_KEY` has changed since the document was issued. Hashes include `APP_KEY`, so rotating it invalidates all existing documents.

---

## Running Tests

```bash
# Run the test suite
php artisan test

# Run a specific test file
php artisan test tests/Feature/ResidentTest.php

# With coverage (requires Xdebug or PCOV)
php artisan test --coverage
```

---

##  License

This project is open-source software released under the **MIT License**. See `LICENSE` for the full text.

```
Copyright (c) 2026 Barangay San Jose Capstone Team

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

---