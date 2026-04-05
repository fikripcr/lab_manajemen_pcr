# Modular Architecture Guide

## Simple Contract Pattern

This project uses a simple contract pattern for cross-module dependencies. No over-engineering, just clean interfaces.

## Structure

```
app/
├── Contracts/                    # Module interfaces
│   ├── Hr/
│   │   └── StrukturOrganisasiServiceInterface.php
│   └── Pemutu/
│       └── PegawaiServiceInterface.php
│
├── Services/
│   ├── Hr/
│   │   └── StrukturOrganisasiService.php  (implements StrukturOrganisasiServiceInterface)
│   └── Pemutu/
│       └── PegawaiService.php             (implements PegawaiServiceInterface)
│
└── Providers/
    └── AppServiceProvider.php   (bindings here)
```

## How It Works

### 1. Define Contract (Interface)

```php
// app/Contracts/Hr/StrukturOrganisasiServiceInterface.php
namespace App\Contracts\Hr;

interface StrukturOrganisasiServiceInterface
{
    public function getHierarchicalList($parentId = null, $prefix = '', $excludeId = null, array $types = []);
}
```

### 2. Implement Contract

```php
// app/Services/Hr/StrukturOrganisasiService.php
namespace App\Services\Hr;

use App\Contracts\Hr\StrukturOrganisasiServiceInterface;

class StrukturOrganisasiService implements StrukturOrganisasiServiceInterface
{
    public function getHierarchicalList(...) { ... }
}
```

### 3. Bind in ServiceProvider

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(
        StrukturOrganisasiServiceInterface::class,
        StrukturOrganisasiService::class
    );
}
```

### 4. Use Interface in Controllers

```php
// app/Http/Controllers/Pemutu/SomeController.php
use App\Contracts\Hr\StrukturOrganisasiServiceInterface;

class SomeController extends Controller
{
    public function __construct(
        protected StrukturOrganisasiServiceInterface $strukturOrganisasiService
    ) {}
}
```

## Cross-Module Dependencies

### Pemutu → HR
- **StrukturOrganisasiServiceInterface** - Org unit tree structure
- Used by: EvaluasiDiri, Summary, TimMutu, Peningkatan, Pengendalian, Ami, Pegawai, Standar controllers

### Pemutu Internal
- **PegawaiServiceInterface** - Employee management (Pemutu's own service)
- Used by: Peningkatan, Pengendalian, Pegawai controllers

## Benefits

✅ **Simple** - Just interfaces + bindings  
✅ **Maintainable** - Change implementation without touching controllers  
✅ **Testable** - Mock interfaces in tests  
✅ **Extractable** - HR can become separate package later  
✅ **No Overhead** - No events, no complex patterns, no DTOs  

## Adding New Module

1. Create `app/Contracts/ModuleName/ServiceInterface.php`
2. Implement in `app/Services/ModuleName/Service.php`
3. Bind in `AppServiceProvider@register()`
4. Use interface in controllers

That's it. Keep it simple.
