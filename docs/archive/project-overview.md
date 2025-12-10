# Project Overview & Setup

## Introduction

This repository serves as a comprehensive Laravel base template that implements essential features for efficient web application development. It provides a solid foundation with authentication, authorization, CRUD operations, and various utility features that can be reused across multiple projects.

## Features Overview

This template includes preconfigured implementations for:

* **Authentication System** with Laravel Breeze & Google OAuth
* **Role-Based Access Control** using spatie/laravel-permission
* **User Management** with profiles and impersonation
* **CRUD Operations** with Resource Controllers
* **Form Validation** with Custom Request Classes
* **Frontend Architecture** with Blade Components & Layouts
* **Data Export/Import** with Maatwebsite/Excel
* **Dynamic Tables** with Yajra DataTables
* **ID Encryption** with vinkla/hashids
* **Media Management** with Spatie Media Library
* **Activity Logging** with spatie/activity-log
* **Notifications** with Database Channel
* **Performance Optimization** with local libraries and caching
* **Custom Error Pages** for better UX
* **Soft Delete** for data integrity
* **Debugging Tools** with Laravel Debugbar
* **Security** with environment files and encryption

## System Requirements

* PHP >= 8.2
* Laravel 12
* Composer
* Node.js & npm
* MySQL 8.0+ or PostgreSQL
* Git

### Additional Requirements

* MySQL dump utility (for database backups)
* ZIP utility (for file backups)

## Installation

### 1\. Clone the Repository

```bash
git clone <repository-url>
cd lab_manajemen_pcr
```

### 2\. Install PHP Dependencies

```bash
composer install
```

### 3\. Install Frontend Dependencies

```bash
npm install
```

### 4\. Environment Configuration

* Copy `.env.example` to `.env`
* Generate application key:

```bash
php artisan key:generate
```

### 5\. Database Setup

* Configure your database credentials in `.env`
* Run migrations with seeders:

```bash
php artisan migrate --seed
```

### 6\. Build Assets

```bash
npm run build
```

## Project Structure

### Controller Organization

* `app/Http/Controllers/Admin/` \- Administrative functionality
* `app/Http/Controllers/Auth/` \- Authentication functionality
* `app/Http/Controllers/Guest/` \- Public functionality
* `app/Http/Controllers/Sys/` \- System functionality \(monitoring\, backups\, logs\, etc\.\)

### Route Structure

* `routes/admin.php` \- Administrative routes
* `routes/auth.php` \- Authentication routes
* `routes/guest.php` \- Public routes
* `routes/web.php` \- Main route configuration
* `routes/sys.php` \- System routes \(monitoring\, backups\, etc\.\)

### Frontend Assets

* `public/assets-admin/` \- Administrative UI assets
* `public/assets-guest/` \- Public UI assets

### View Organization

* `resources/views/components/` \- Reusable Blade components
* `resources/views/layouts/` \- Layout files \(admin/auth/guest\)
* `resources/views/pages/` \- Page\-specific views \(admin/auth/guest/sys\)

### Request Validation

* `app/Http/Requests/` \- Custom request validation classes
* `app/Http/Requests/Sys/` \- System\-level validation requests

## 

### Key Seeders

The project includes several important seeders:

* `DatabaseSeeder` \- Main seeder that orchestrates all others
* `UserSeeder` \- Creates default users
* `PermissionSeeder` \- Sets up all permissions
* `RoleSeeder` \- Creates default roles
* `RolePermissionSeeder` \- Assigns permissions to roles
* `SysSeeder` \- System\-level permissions and configurations
* `SysSuperAdminSeeder` \- Super admin user and role

## Security Best Practices

* Never commit `.env` files to Git
* Always set `APP_DEBUG=false` in production
* Regenerate the application key during deployment
* Secure sensitive configuration values
* Use environment-specific configurations
* Implement proper access controls and permissions