# Installation Guide — Barangay San Jose IS

Follow these steps in order. **Do not skip any step.**

## Prerequisites

You must have these installed BEFORE running anything:

| Tool        | Check installed | How to get it |
|-------------|-----------------|---------------|
| PHP 8.2+    | `php --version` | https://www.php.net/downloads |
| Composer    | `composer --version` | https://getcomposer.org |
| Node 18+    | `node --version` | https://nodejs.org |
| npm         | `npm --version`  | comes with Node |
| MySQL 8     | `mysql --version` | https://dev.mysql.com or use XAMPP/Laragon |

If any of these commands return "command not found", you must install them first.

> **Windows users:** XAMPP includes PHP + MySQL. Install Composer and Node separately.
> **Mac users:** `brew install php@8.2 composer node mysql`.

---

## Step 1 — Extract the project

Unzip `barangay-system-complete.zip` into a folder of your choice. Open a terminal/Command Prompt **inside the unzipped folder**.

You should see these files when you run `ls` (Mac/Linux) or `dir` (Windows):

```
artisan          composer.json     package.json
app/             bootstrap/        config/
database/        public/           resources/
routes/          storage/          vite.config.js
tailwind.config.js   README.md     INSTALL.md
```

If you only see ONE folder inside (like `barangay-system/`), then `cd` into it first. The terminal must be at the level where `artisan` and `composer.json` live.

---

## Step 2 — Install PHP dependencies

```bash
composer install
```

This downloads Laravel and its dependencies into `vendor/`. Takes 1-3 minutes the first time.

**Error: `Could not open input file: artisan`**
→ You're in the wrong directory. `cd` into the folder containing `artisan` first.

---

## Step 3 — Install JavaScript dependencies

```bash
npm install
```

This downloads Tailwind, Vite, etc. into `node_modules/`. Takes 1-2 minutes.

**Error: `Could not read package.json`**
→ Same as above — you're in the wrong directory.

---

## Step 4 — Configure environment

```bash
# Copy the example env file
cp .env.example .env
# Windows: copy .env.example .env
```

Open `.env` in a text editor and update the database section:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barangay_db
DB_USERNAME=root
DB_PASSWORD=
```

Adjust `DB_USERNAME` and `DB_PASSWORD` to match your MySQL setup.

---

## Step 5 — Create the database

Open MySQL (via phpMyAdmin, MySQL Workbench, or the command line) and run:

```sql
CREATE DATABASE barangay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Step 6 — Generate the application key

```bash
php artisan key:generate
```

This fills in `APP_KEY` inside `.env`. Required for sessions and the document hash system.

---

## Step 7 — Run migrations & seeders

```bash
php artisan migrate --seed
```

This creates all tables (residents, households, clearances, blotters, archived_residents, etc.) and inserts sample data including default user accounts.

**Default credentials:**

| Role  | Email                       | Password   |
|-------|-----------------------------|------------|
| Admin | admin@barangay.gov.ph       | admin123   |
| Staff | staff@barangay.gov.ph       | staff123   |

> If you imported `database/barangay_db.sql` directly instead of running migrations, the password hashes in the SQL file are placeholders and will not match. Always run `migrate --seed` for first install.

---

## Step 8 — Start the development servers

You need TWO terminals running side by side.

**Terminal 1 — Laravel:**
```bash
php artisan serve
```
Output: `Server running on http://127.0.0.1:8000`

**Terminal 2 — Vite (Tailwind + JS hot reload):**
```bash
npm run dev
```
Output: `Local: http://localhost:5173/`

Leave both terminals open while developing.

---

## Step 9 — Open the app

Open your browser to **http://127.0.0.1:8000** and log in with the default credentials.

---

## Troubleshooting

### `Could not open input file: artisan`
Your terminal is not inside the project folder. `cd` to the folder containing `artisan`.

### `SQLSTATE[HY000] [1049] Unknown database`
The `barangay_db` database doesn't exist yet. Go back to Step 5.

### `SQLSTATE[HY000] [1045] Access denied for user`
Wrong DB username/password in `.env`. Update and retry.

### Login fails after import — wrong password
Run:
```bash
php artisan tinker
>>> \App\Models\User::where('email','admin@barangay.gov.ph')->update(['password'=>bcrypt('admin123')]);
>>> \App\Models\User::where('email','staff@barangay.gov.ph')->update(['password'=>bcrypt('staff123')]);
>>> exit
```

### `Vite manifest not found` or no styles
Run `npm run dev` in a second terminal (Step 8 Terminal 2).

### Permission errors on `storage/` or `bootstrap/cache/`
```bash
chmod -R 775 storage bootstrap/cache       # Mac/Linux
# Windows: usually no action needed
```

### `Class not found` errors
```bash
composer dump-autoload
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Reset everything and start over
```bash
php artisan migrate:fresh --seed
```

---

## Production build (optional)

When deploying:

```bash
npm run build              # compile assets
php artisan config:cache   # cache config
php artisan route:cache    # cache routes
php artisan view:cache     # cache views
```

In `.env`, set `APP_ENV=production` and `APP_DEBUG=false`.

---

## Project structure quick reference

```
app/
├── Http/
│   ├── Controllers/   ← Resident, Clearance, Blotter, ArchivedResident, etc.
│   └── Middleware/    ← Auth middleware (admin, active.user)
├── Models/            ← Eloquent models
└── Rules/             ← PhilippineMobileNumber validation
config/                ← Laravel config files
database/
├── migrations/        ← Schema definitions
├── seeders/           ← Sample data
└── factories/         ← Test data generators
resources/
├── css/app.css        ← Tailwind entry
├── js/app.js          ← JavaScript entry
└── views/             ← Blade templates
routes/web.php         ← All web routes
storage/               ← Logs, caches, sessions (writable)
public/index.php       ← Web server entry point
```

See `README.md` for feature documentation, `CHANGES.md` for the fix history.
