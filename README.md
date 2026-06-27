# Art Gallery Management System (AGMS)

A web-based Art Gallery Management System built with **Laravel 13**, **Bootstrap 5**, and **MySQL**. Features artwork management with image uploads, tagging, carousel gallery view, role-based access control, and activity logging.

---

## Requirements

Before you begin, make sure you have the following installed:

- **PHP** 8.2 or higher
- **Composer** (https://getcomposer.org)
- **Node.js** 18+ and **NPM** (https://nodejs.org)
- **MySQL** or **MariaDB** — XAMPP is recommended for local development
- **Git**

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/CinFail/Art-Gallery.git artGallery
cd artGallery
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Set Up Environment File

> ⚠️ The `.env` file is **not included** in the repository for security reasons. You must create it manually from the example file below.

**On Mac / Linux / Git Bash:**
```bash
cp .env.example .env
```

**On Windows Command Prompt:**
```cmd
copy .env.example .env
```

**On Windows PowerShell:**
```powershell
Copy-Item .env.example .env
```

Then open `.env` and update the database section to match your setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agms
DB_USERNAME=root
DB_PASSWORD=
```

> If your MySQL has a password, fill in `DB_PASSWORD`.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create the Database

Open **phpMyAdmin** (or any MySQL client) and create a new database:

```sql
CREATE DATABASE agms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Run Migrations and Seeders

This creates all tables and loads default roles, users, and sample data:

```bash
php artisan migrate --seed
```

### 8. Create Storage Symlink

Required for uploaded artwork images to be publicly accessible:

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

```bash
npm run build
```

> For local development with hot reload, use `npm run dev` instead.

---

## Running the Application

```bash
php artisan serve
```

Open your browser and go to:

```
http://127.0.0.1:8000
```

---

## Default Login Credentials

| Role          | Email              | Password |
|---------------|--------------------|----------|
| Administrator | admin@agms.com     | password |
| Staff         | staff@agms.com     | password |
| Viewer        | viewer@agms.com    | password |

> Change these passwords after your first login, especially in a production environment.

---

## Role Permissions

| Feature          | Administrator | Staff | Viewer |
|------------------|:---:|:---:|:---:|
| View Artists     | ✅ | ✅ | ✅ |
| Manage Artists   | ✅ | ✅ | ❌ |
| View Artworks    | ✅ | ✅ | ✅ |
| Manage Artworks  | ✅ | ✅ | ❌ |
| View Groups      | ✅ | ✅ | ✅ |
| Manage Groups    | ✅ | ✅ | ❌ |
| View Customers   | ✅ | ✅ | ❌ |
| Manage Customers | ✅ | ✅ | ❌ |
| Manage Users     | ✅ | ❌ | ❌ |
| Manage Roles     | ✅ | ❌ | ❌ |
| Activity Logs    | All logs | Viewer-role users only | Hidden |

---

## Features

- **Artwork Gallery** — Responsive card grid with image carousel at the top
- **Image Uploads** — Upload artwork photos (JPG, PNG, WebP — max 2 MB)
- **Tags** — Create colored tags and filter the gallery by them
- **Artwork Groups** — Organize artworks into named collections
- **Artists & Customers** — Full CRUD management for both
- **Role-Based Access** — Administrator, Staff, and Viewer roles via Spatie Permissions
- **Activity Logs** — Scoped audit trail: Admins see all, Staff see only Viewer-role user logs, Viewers have no log access

---

## Troubleshooting

**Images not showing after upload?**
```bash
php artisan storage:link
```

**Class not found / missing package errors?**
```bash
composer install
```

**Vite assets not loading (blank page or broken styles)?**
```bash
npm install && npm run build
```

**`.env` file missing after cloning?**
This is normal — `.env` is never committed to git. Follow Step 4 above to create it from `.env.example`.

**500 error on first run?**
Make sure you ran `php artisan key:generate` and that your `.env` database credentials are correct.

**Permission errors on storage or cache?**
Ensure `storage/` and `bootstrap/cache/` directories are writable by your web server.
