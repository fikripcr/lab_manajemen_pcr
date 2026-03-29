# Setup Guide - Laravel Boilerplate

**Last Updated:** March 2026  
**Laravel Version:** 12.46.0  
**PHP Version:** 8.4.18  

Panduan lengkap untuk setup development environment untuk project Laravel Boilerplate ini.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Quick Start (Docker)](#quick-start-docker)
3. [Manual Installation](#manual-installation)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [Frontend Assets](#frontend-assets)
7. [Running the Application](#running-the-application)
8. [Testing Installation](#testing-installation)
9. [Common Issues](#common-issues)

---

## Prerequisites

### Required Software

| Software | Minimum Version | Recommended Version |
|----------|----------------|---------------------|
| PHP | 8.4.0 | 8.4.18+ |
| Composer | 2.0 | 2.8+ |
| Node.js | 18.x | 20.x |
| npm | 9.x | 10.x |
| MySQL/MariaDB | 10.6 | 10.11+ |
| Docker (Optional) | 20.x | 24.x |
| Docker Compose (Optional) | 2.0 | 2.20+ |

### PHP Extensions

Pastikan ekstensi berikut terinstall:

```bash
php-bcmath
php-ctype
php-curl
php-dom
php-fileinfo
php-gd (untuk QR Code & Media Library)
php-json
php-mbstring
php-mysql
php-openssl
php-pdo
php-tokenizer
php-xml
php-zip
```

Install dengan apt (Ubuntu/Debian):
```bash
sudo apt install php8.4-bcmath php8.4-curl php8.4-gd php8.4-mysql php8.4-mbstring php8.4-xml php8.4-zip
```

---

## Quick Start (Docker)

Jika menggunakan Docker, ini adalah cara tercepat untuk menjalankan aplikasi:

### 1. Clone Repository

```bash
git clone <repository-url>
cd www-laravel-boilerplate
```

### 2. Copy Environment File

```bash
cp .env.example .env
```

### 3. Start Docker Containers

```bash
docker-compose up -d
```

### 4. Install Dependencies & Run Migrations

```bash
# Masuk ke container
docker-compose exec app php artisan migrate --seed
```

### 5. Build Frontend Assets

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

### 6. Access Application

Buka browser dan akses: `http://localhost:8080`

**Default Login:**
- Email: `admin@example.com`
- Password: `password`

---

## Manual Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd www-laravel-boilerplate
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Copy Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_boilerplate
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 7. Create Database

```bash
mysql -u root -p -e "CREATE DATABASE laravel_boilerplate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 8. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 9. Build Frontend Assets

```bash
npm run build
```

### 10. Create Storage Symlink

```bash
php artisan storage:link
```

### 11. Set Permissions

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (PowerShell as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

---

## Environment Configuration

### Required Environment Variables

Salin file `.env.example` ke `.env` dan pastikan variabel berikut dikonfigurasi:

```env
# Application
APP_NAME="Laravel Boilerplate"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_boilerplate
DB_USERNAME=root
DB_PASSWORD=

# Mail (untuk notifikasi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth (Optional - untuk login dengan Google)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback

# File Upload
FILESYSTEM_DISK=local

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Generate APP_KEY

Jika `APP_KEY` masih kosong:

```bash
php artisan key:generate
```

---

## Database Setup

### Migration Structure

Database menggunakan prefix untuk setiap modul:

| Prefix | Modul | Contoh Tabel |
|--------|-------|--------------|
| `sys_` | System | `sys_users`, `sys_roles`, `sys_permissions` |
| `hr_` | Human Resource | `hr_pegawai`, `hr_riwayat_approval` |
| `pemutu_` | Penjaminan Mutu | `pemutu_dokumen`, `pemutu_indikator` |
| `pmb_` | Penerimaan Mahasiswa Baru | `pmb_pendaftar`, `pmb_verifikasi` |
| `lab_` | Laboratorium | `lab_inventaris`, `lab_peminjaman` |
| `cbt_` | Computer Based Test | `cbt_ujian`, `cbt_soal` |
| `cms_` | Content Management | `cms_artikel`, `cms_kategori` |
| `eoffice_` | E-Office | `eoffice_surat`, `eoffice_disposisi` |

### Running Migrations

```bash
# Semua migrations
php artisan migrate

# Rollback semua
php artisan migrate:rollback

# Reset dan migrate ulang
php artisan migrate:reset
php artisan migrate

# Fresh migration (hapus semua data!)
php artisan migrate:fresh --seed
```

### Default Users (dari Seeder)

Setelah menjalankan seeder, berikut user yang tersedia:

| Email | Password | Role |
|-------|----------|------|
| `admin@example.com` | `password` | Super Admin |
| `user@example.com` | `password` | User |

---

## Frontend Assets

### Asset Structure

```
resources/
├── css/
│   ├── auth.css          # Halaman auth (login/register)
│   └── public.css        # Halaman publik
├── js/
│   ├── auth.js           # Logic halaman auth
│   └── public.js         # Logic halaman publik
├── tabler-core/
│   ├── css/
│   │   └── tabler.css    # Dashboard admin
│   └── js/
│       └── tabler.js     # Dashboard admin (jQuery, Axios, dll)
```

### Development Mode

Untuk development dengan hot reload:

```bash
npm run dev
```

### Production Build

Untuk production:

```bash
npm run build
```

### Asset Locations

| Asset Type | Location |
|------------|----------|
| Admin CSS/JS | `public/assets-admin/` |
| Guest CSS/JS | `public/assets-guest/` |
| Images | `public/images/` |
| Storage Files | `storage/app/` |

---

## Running the Application

### Development Server

```bash
# Start Laravel development server
php artisan serve

# Start on specific port
php artisan serve --port=8080
```

### Queue Worker (Optional)

Jika menggunakan queue untuk background jobs:

```bash
php artisan queue:work --tries=3
```

### Vite Development Server

```bash
npm run dev
```

### Full Development Stack

Jalankan semua service sekaligus:

```bash
composer run dev
```

Ini akan menjalankan:
- Laravel server
- Queue worker
- Vite dev server
- Pail (log viewer)

---

## Testing Installation

### 1. Check PHP Version

```bash
php -v
# Output: PHP 8.4.18+
```

### 2. Check Laravel Version

```bash
php artisan --version
# Output: Laravel Framework 12.46.0
```

### 3. Check Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO object without error
```

### 4. Run Tests

```bash
composer test
```

### 5. Access Application

Buka browser dan akses `http://localhost:8000` (atau port yang Anda gunakan).

- Jika muncul halaman login → ✅ Setup berhasil
- Coba login dengan `admin@example.com` / `password` → ✅ Login berhasil

---

## Common Issues

### Issue: Migration Failed

**Error:** `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long`

**Solution:**
```env
# Di .env
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

### Issue: Permission Denied

**Error:** `Permission denied: storage/logs/laravel.log`

**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Class Not Found

**Error:** `Class 'SomeClass' not found`

**Solution:**
```bash
composer dump-autoload
```

### Issue: Vite Manifest Not Found

**Error:** `Vite manifest not found at: public/build/manifest.json`

**Solution:**
```bash
npm run build
```

### Issue: SQLSTATE[HY000] [2002] Connection refused

**Error:** Database connection failed

**Solution:**
1. Pastikan MySQL/MariaDB running
2. Cek kredensial di `.env`
3. Pastikan database sudah dibuat

```bash
# Check MySQL status
systemctl status mysql

# Start MySQL
sudo systemctl start mysql
```

### Issue: GD Library Not Found

**Error:** `Please install the GD extension` (untuk QR Code/Media Library)

**Solution:**
```bash
# Ubuntu/Debian
sudo apt install php8.4-gd

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

### Issue: Hashids Not Working

**Error:** `Target class [hashids] does not exist`

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Next Steps

Setelah setup berhasil:

1. 📖 Baca [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md) untuk best practices development
2. 📖 Baca [PROJECT_ARCHITECTURE.md](./PROJECT_ARCHITECTURE.md) untuk memahami arsitektur
3. 🔧 Customize sesuai kebutuhan project Anda

---

## Support

Jika mengalami masalah yang tidak tercantum di sini:

1. Cek [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
2. Lihat log error di `storage/logs/laravel.log`
3. Cek tabel `sys_error_log` untuk error yang tersimpan di database
