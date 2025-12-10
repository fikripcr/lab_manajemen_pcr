# Lab Manajemen PCR - Quick Start

Laravel boilerplate dengan authentication, CRUD, permissions, dan monitoring built-in.

## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL 8.0+

## Installation

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database di .env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 4. Run migrations & seeders
php artisan migrate --seed

# 5. Build assets
npm run build

# 6. Start server
php artisan serve
```

**Default Login:**
- Super Admin: `superadmin@example.com` / `password`
- Admin: `admin@example.com` / `password`

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # Admin features
│   ├── Auth/           # Authentication
│   ├── Guest/          # Public pages
│   ├── Sys/            # System management
│   └── Api/            # API endpoints
├── Services/           # Business logic (Service Pattern)
│   └── Sys/           # System services
├── Models/            # Eloquent models
└── Helpers/           # Helper functions

resources/
├── js/
│   ├── admin.js       # Admin entry point
│   ├── sys.js         # Sys entry point
│   ├── global.js      # Shared dependencies
│   └── components/    # Reusable JS components
├── css/
│   ├── admin.css      # Admin styles
│   └── sys.css        # Sys styles
└── views/
    ├── layouts/       # Layout templates
    ├── pages/         # Page views
    └── components/    # Blade components

routes/
├── web.php           # Main routes
├── admin.php         # Admin routes
├── sys.php           # System routes
└── api.php           # API routes
```

## Key Features

- **Multi-Context Architecture**: Admin, Auth, Guest, Sys
- **Service Pattern**: Business logic separated from controllers
- **Spatie Permissions**: Role-based access control
- **Vite Asset Bundling**: Modern asset compilation
- **DataTables**: Server-side pagination
- **Media Library**: File uploads with Spatie
- **Activity Logging**: Track user actions
- **Notifications**: Database notifications
- **Error Logging**: Custom error tracking

## Next Steps

1. **Development Patterns**: Read `DEVELOPMENT_GUIDE.md`
2. **Frontend Guide**: Read `FRONTEND_GUIDE.md`
3. **Features Reference**: Read `FEATURES.md`
4. **Old Docs**: Check `docs/archive/` for detailed docs

## Common Commands

```bash
# Development
npm run dev              # Watch assets
php artisan serve        # Start server

# Production
npm run build           # Build assets
php artisan optimize    # Cache config/routes

# Database
php artisan migrate:fresh --seed  # Reset database
php artisan db:seed --class=UserSeeder  # Run specific seeder

# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Troubleshooting

**Assets not loading?**
```bash
npm run build
php artisan config:clear
```

**Permission errors?**
```bash
php artisan permission:cache-reset
```

**Database errors?**
```bash
php artisan migrate:fresh --seed
```

## Support

- **Detailed Docs**: `docs/archive/` (old comprehensive docs)
- **Code Examples**: Look at existing controllers/services
- **Patterns**: See `DEVELOPMENT_GUIDE.md`
