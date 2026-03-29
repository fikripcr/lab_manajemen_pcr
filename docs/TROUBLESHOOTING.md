# Troubleshooting Guide - Laravel Boilerplate

**Last Updated:** March 2026  
**Laravel Version:** 12.46.0  

Panduan troubleshooting untuk masalah umum yang mungkin terjadi saat development.

---

## Table of Contents

1. [Installation Issues](#installation-issues)
2. [Database Issues](#database-issues)
3. [Authentication Issues](#authentication-issues)
4. [Frontend Issues](#frontend-issues)
5. [File Upload Issues](#file-upload-issues)
6. [Permission Issues](#permission-issues)
7. [Queue & Job Issues](#queue--job-issues)
8. [Performance Issues](#performance-issues)
9. [Error Logging & Debugging](#error-logging--debugging)

---

## Installation Issues

### Issue: Composer Install Failed

**Error:** `The requested PHP version 8.4 is not supported by this platform`

**Solution:**
```bash
# Check PHP version
php -v

# If wrong version, update PHP or use correct PHP binary
which php
# Output: /usr/bin/php8.4

# Use specific PHP version
/usr/bin/php8.4 /usr/local/bin/composer install
```

---

### Issue: Node Modules Installation Failed

**Error:** `npm ERR! code ENOENT` atau `npm ERR! Cannot find module`

**Solution:**
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules dan package-lock.json
rm -rf node_modules package-lock.json

# Install ulang
npm install

# Jika masih gagal, gunakan npm legacy
npm install --legacy-peer-deps
```

---

### Issue: APP_KEY Not Generated

**Error:** `No application encryption key has been generated`

**Solution:**
```bash
# Generate key
php artisan key:generate

# Jika .env tidak writable
chmod 644 .env
php artisan key:generate

# Verify di .env
grep APP_KEY .env
# Output: APP_KEY=base64:xxxxxxxxxxxxx
```

---

### Issue: Storage Link Not Working

**Error:** `Unable to generate file URL` atau file upload tidak bisa diakses

**Solution:**
```bash
# Create storage link
php artisan storage:link

# Jika error "File already exists"
rm -rf public/storage
php artisan storage:link

# Verify
ls -la public/storage
```

---

## Database Issues

### Issue: Migration Failed - Key Too Long

**Error:** `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long`

**Solution:**
```php
// Di file migration, gunakan Schema::defaultStringLength()
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

public function up(): void
{
    Schema::defaultStringLength(191);
    
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique(); // Akan menggunakan 191 chars max
        // ...
    });
}
```

Atau di `AppServiceProvider`:
```php
use Illuminate\Support\Facades\Schema;

public function boot(): void
{
    Schema::defaultStringLength(191);
}
```

---

### Issue: Database Connection Refused

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**

1. **Check MySQL status:**
```bash
# Linux
systemctl status mysql
sudo systemctl start mysql

# Docker
docker-compose ps
docker-compose up -d mysql
```

2. **Verify .env configuration:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  # Bukan 'localhost'
DB_PORT=3306
DB_DATABASE=laravel_boilerplate
DB_USERNAME=root
DB_PASSWORD=your_password
```

3. **Test connection:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO object
```

---

### Issue: Seeder Class Not Found

**Error:** `Class 'Database\Seeders\SomeSeeder' not found`

**Solution:**
```bash
# Dump autoload
composer dump-autoload

# Clear cache
php artisan config:clear
php artisan cache:clear

# Run seeder again
php artisan db:seed --class=SomeSeeder
```

---

### Issue: Foreign Key Constraint Failed

**Error:** `SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add a child row`

**Solution:**

1. **Check data yang akan di-insert:**
```php
// Pastikan parent record sudah ada
$parent = ParentModel::find($parentId);
if (!$parent) {
    throw new \Exception("Parent record not found");
}
```

2. **Disable foreign key checks (hanya untuk development):**
```php
Schema::disableForeignKeyConstraints();

// Run migration/seeder

Schema::enableForeignKeyConstraints();
```

3. **Check migration order:**
```bash
# Pastikan tabel parent dibuat sebelum child
php artisan migrate:status
```

---

## Authentication Issues

### Issue: Login Failed - Invalid Credentials

**Error:** `Kredensial tidak valid` meskipun password benar

**Solution:**

1. **Check password hashing:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->password;
# Harus dimulai dengan $2y$ (bcrypt)

>>> Hash::check('password', $user->password);
# Harus true
```

2. **Reset password manual:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->password = Hash::make('newpassword');
>>> $user->save();
```

3. **Gunakan artisan command:**
```bash
php artisan user:reset-password admin@example.com newpassword
```

---

### Issue: Token Expired (Sanctum)

**Error:** `Unauthenticated. Token tidak valid atau sudah expired`

**Solution:**

1. **Check token expiry configuration:**
```env
# Di config/sanctum.php atau .env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
SESSION_DOMAIN=localhost
```

2. **Regenerate token:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $token = $user->createToken('New Token')->plainTextToken;
>>> echo $token;
```

3. **Clear old tokens:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->tokens()->delete();
```

---

### Issue: Permission Denied (Spatie)

**Error:** `User does not have the right permissions`

**Solution:**

1. **Check permission assignment:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->hasPermissionTo('edit-products');
>>> $user->getPermissionNames();
>>> $user->getRoleNames();
```

2. **Sync permissions:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->syncPermissions(['edit-products', 'delete-products']);

# Or sync roles
>>> $user->syncRoles(['admin']);
```

3. **Clear permission cache:**
```bash
php artisan permission:cache-reset
```

---

### Issue: Account Expired

**Error:** `Akun Anda sudah kadaluarsa`

**Solution:**

1. **Check account expiration date:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->account_expires_at;
```

2. **Extend account:**
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->account_expires_at = now()->addYear();
>>> $user->save();
```

---

## Frontend Issues

### Issue: Vite Manifest Not Found

**Error:** `Vite manifest not found at: public/build/manifest.json`

**Solution:**
```bash
# Build assets
npm run build

# Jika masih error
rm -rf node_modules public/build
npm install
npm run build
```

---

### Issue: CSS/JS Not Loading

**Error:** Halaman tanpa styling atau JavaScript tidak jalan

**Solution:**

1. **Check asset paths di blade:**
```blade
{{-- BENAR --}}
@vite(['resources/tabler-core/js/tabler.js', 'resources/tabler-core/css/tabler.css'])

{{-- SALAH --}}
<script src="{{ asset('resources/tabler-core/js/tabler.js') }}"></script>
```

2. **Clear view cache:**
```bash
php artisan view:clear
php artisan config:clear
```

3. **Rebuild assets:**
```bash
npm run build
```

---

### Issue: Select2 Not Working

**Error:** Dropdown tidak searchable atau error JavaScript

**Solution:**

1. **Add select2 class:**
```blade
<select name="category_id" class="form-control select2">
    {{-- options --}}
</select>
```

2. **Initialize in modal (jika di dalam modal):**
```js
// Di tabler.js sudah ada handler untuk ini
// Tapi jika custom, pastikan:
$('.select2').select2({
    dropdownParent: $('#modal-id')
});
```

---

### Issue: Flatpickr Not Working

**Error:** Date picker tidak muncul

**Solution:**

1. **Add flatpickr class:**
```blade
<input type="text" name="date" class="form-control flatpickr">
```

2. **Check if script loaded:**
```js
console.log(typeof flatpickr); // Harus 'function'
```

---

### Issue: FilePond Not Uploading

**Error:** File tidak ter-upload saat form submit

**Solution:**

1. **Add filepond-input class:**
```blade
<input type="file" name="documents[]" class="filepond-input" multiple>
```

2. **Check form enctype:**
```blade
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="documents[]" class="filepond-input">
</form>
```

3. **Check backend:**
```php
if ($request->hasFile('documents')) {
    foreach ($request->file('documents') as $file) {
        // Process file
    }
}
```

---

## File Upload Issues

### Issue: Upload Failed - File Too Large

**Error:** `The file may not be greater than X kilobytes` atau `POST Content-Length exceeded`

**Solution:**

1. **Increase PHP upload limits:**
```ini
; Di php.ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

2. **Increase Nginx limit (jika pakai Nginx):**
```nginx
http {
    client_max_body_size 50M;
}
```

3. **Increase Apache limit (jika pakai Apache):**
```apache
# Di .htaccess
php_value upload_max_filesize 50M
php_value post_max_size 50M
```

4. **Restart web server:**
```bash
sudo systemctl restart php8.4-fpm
sudo systemctl restart nginx
# or
sudo systemctl restart apache2
```

---

### Issue: Media Library Not Working

**Error:** `Please provide a valid file` atau file tidak tersimpan

**Solution:**

1. **Check disk configuration:**
```env
FILESYSTEM_DISK=local
# or
FILESYSTEM_DISK=public
```

2. **Check storage permissions:**
```bash
chmod -R 775 storage/app
chown -R www-data:www-data storage/app
```

3. **Verify media model:**
```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images');
    }
}
```

---

### Issue: QR Code Generation Failed

**Error:** `Please install the GD extension`

**Solution:**

```bash
# Install GD extension
sudo apt install php8.4-gd

# Verify
php -m | grep gd

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

## Permission Issues

### Issue: Permission Denied - storage/logs

**Error:** `Permission denied: storage/logs/laravel.log`

**Solution:**
```bash
# Set correct permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Alternative (development only)
chmod -R 777 storage bootstrap/cache
```

---

### Issue: Permission Denied - sessions

**Error:** `Unable to create a session file`

**Solution:**
```bash
# Check session driver
# Di .env
SESSION_DRIVER=file

# Create sessions directory
mkdir -p storage/framework/sessions
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions
```

---

### Issue: Permission Denied - views cache

**Error:** `Unable to write in the storage directory`

**Solution:**
```bash
# Clear view cache
php artisan view:clear

# Set permissions
chmod -R 775 storage/framework/views
chown -R www-data:www-data storage/framework/views
```

---

## Queue & Job Issues

### Issue: Queue Worker Not Processing Jobs

**Error:** Jobs stuck in `jobs` table

**Solution:**

1. **Start queue worker:**
```bash
php artisan queue:work --tries=3

# Background process
php artisan queue:work --daemon --tries=3 &
```

2. **Check queue connection:**
```env
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=sync  # Untuk development (no queue)
```

3. **Run migrations:**
```bash
php artisan queue:table
php artisan migrate
```

4. **Clear failed jobs:**
```bash
php artisan queue:flush
```

---

### Issue: Job Failed - Memory Limit

**Error:** `Allowed memory size exhausted`

**Solution:**

1. **Increase PHP memory:**
```ini
; Di php.ini
memory_limit = 512M
```

2. **Run worker with memory limit:**
```bash
php artisan queue:work --memory=512
```

3. **Optimize job:**
```php
class ProcessLargeFile implements ShouldQueue
{
    // Use chunking
    public function handle(): void
    {
        Model::chunk(100, function ($models) {
            foreach ($models as $model) {
                // Process
            }
        });
    }
}
```

---

## Performance Issues

### Issue: Slow Page Load - N+1 Query

**Symptom:** Halaman load lama, banyak query di debugbar

**Solution:**

1. **Enable eager loading:**
```php
// ❌ BAD - N+1 query
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name;
}

// ✅ GOOD - Eager loading
$products = Product::with('category')->get();
```

2. **Check with debugbar:**
```env
APP_DEBUG=true
DEBUGBAR_ENABLED=true
```

---

### Issue: Slow DataTables

**Symptom:** DataTable load lama saat banyak data

**Solution:**

1. **Ensure server-side processing:**
```js
$('#table').DataTable({
    processing: true,
    serverSide: true,  // WAJIB
    ajax: '/api/data'
});
```

2. **Add indexes di database:**
```php
$table->index('created_at');
$table->index(['status', 'created_at']);
```

3. **Limit eager loading:**
```php
// Load only needed relationships
Model::with(['relation:id,name'])->select('id', 'name')->get();
```

---

### Issue: High Memory Usage

**Symptom:** Server slow, OOM errors

**Solution:**

1. **Clear caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

2. **Optimize for production:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Check for memory leaks:**
```bash
# Monitor memory
php artisan tinker
>>> memory_get_usage()
>>> memory_get_peak_usage()
```

---

## Error Logging & Debugging

### Issue: Error Not Logged

**Symptom:** Error terjadi tapi tidak ada di log

**Solution:**

1. **Check logging configuration:**
```env
APP_DEBUG=true
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

2. **Check logs:**
```bash
# Laravel log
tail -f storage/logs/laravel.log

# Database error log
php artisan tinker
>>> App\Models\Sys\ErrorLog::latest()->first();
```

3. **Manual logging:**
```php
// Log error
logError($exception);
logError('Custom error message');

// Log activity
logActivity('module_name', 'Description', $subject);
```

---

### Issue: Debugbar Not Showing

**Symptom:** Debugbar tidak muncul meskipun APP_DEBUG=true

**Solution:**

1. **Enable debugbar:**
```env
APP_DEBUG=true
DEBUGBAR_ENABLED=true
```

2. **Clear config cache:**
```bash
php artisan config:clear
```

3. **Check middleware:**
```php
// Di bootstrap/app.php, pastikan debugbar middleware aktif
```

4. **Publish vendor assets:**
```bash
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

---

### Issue: Custom Error Pages Not Showing

**Symptom:** Error page default Laravel muncul, bukan custom page

**Solution:**

1. **Publish error pages:**
```bash
php artisan vendor:publish --tag=laravel-errors
```

2. **Check views directory:**
```bash
ls resources/views/errors/
# Harus ada: 403.blade.php, 404.blade.php, 500.blade.php, 503.blade.php
```

3. **Disable debug mode (production):**
```env
APP_DEBUG=false
```

---

### Issue: Tinker Not Working

**Error:** `PsySH error` atau tinker crash

**Solution:**

```bash
# Clear autoload
composer dump-autoload

# Clear cache
php artisan clear-compiled
php artisan cache:clear

# Try again
php artisan tinker
```

---

## Quick Reference Commands

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled
```

### Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Database Commands
```bash
php artisan migrate --force
php artisan migrate:rollback
php artisan migrate:refresh --seed
php artisan db:seed --class=SomeSeeder
php artisan db:wipe  # Hapus semua data (hati-hati!)
```

### Permission Commands
```bash
php artisan permission:cache-reset
php artisan permission:show
```

### Queue Commands
```bash
php artisan queue:work --tries=3
php artisan queue:restart
php artisan queue:flush
php artisan queue:failed
php artisan queue:retry <id>
```

---

## Getting Help

Jika masalah tidak tercantum di sini:

1. **Check Laravel Logs:**
   - `storage/logs/laravel.log`
   - `storage/logs/laravel-YYYY-MM-DD.log`

2. **Check Database Error Log:**
   ```bash
   php artisan tinker
   >>> App\Models\Sys\ErrorLog::latest()->limit(10)->get();
   ```

3. **Enable Debug Mode:**
   ```env
   APP_DEBUG=true
   ```

4. **Check Browser Console:**
   - Tekan F12 di browser
   - Lihat tab Console untuk JavaScript errors

5. **Check Network Tab:**
   - Tekan F12 di browser
   - Lihat tab Network untuk failed requests

---

## Next Steps

1. 📖 Baca [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md) untuk best practices
2. 📖 Baca [PROJECT_ARCHITECTURE.md](./PROJECT_ARCHITECTURE.md) untuk arsitektur
3. 📖 Baca [SETUP_GUIDE.md](./SETUP_GUIDE.md) untuk setup ulang
