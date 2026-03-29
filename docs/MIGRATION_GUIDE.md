# 🔄 Migration Guide - From Module-Specific to Sys Components

**Last Updated:** March 2026  
**Status:** ⚠️ IN PROGRESS - Requires careful migration  

---

## 📋 Overview

This guide explains how to migrate from module-specific models to **global Sys components** without breaking existing functionality.

---

## ⚠️ IMPORTANT: Read Before Migrating

### **DO NOT:**
- ❌ Delete existing models immediately
- ❌ Change existing controllers/views all at once
- ❌ Run migration on production without testing
- ❌ Skip data backup

### **DO:**
- ✅ Follow phased approach
- ✅ Test thoroughly in staging
- ✅ Backup data before migration
- ✅ Keep backward compatibility during transition
- ✅ Update one module at a time

---

## 🎯 Migration Phases

### **Phase 1: ✅ COMPLETED** - Create Sys Components

- [x] Create `SysApproval` model & migration
- [x] Create `SysPeriode` model & migration
- [x] Create `ApprovalService` & `PeriodeService`
- [x] Add backward compatibility wrappers

**Status:** ✅ **DONE**

---

### **Phase 2: ⏳ TODO** - Parallel Run

**Goal:** Run old and new systems side-by-side

#### **Step 1: Update Service to Support Both**

```php
// app/Services/Pemutu/PeriodeSpmiService.php
public function getAll(?int $year = null)
{
    // Try SysPeriode first (if exists)
    $sysPeriodes = SysPeriode::type('spmi')
        ->when($year, fn($q) => $q->year($year))
        ->get();

    if ($sysPeriodes->isNotEmpty()) {
        return $sysPeriodes;
    }

    // Fallback to legacy PeriodeSpmi
    return PeriodeSpmi::query()
        ->when($year, fn($q) => $q->where('periode', $year))
        ->get();
}
```

#### **Step 2: Test Both Systems**

- ✅ Ensure old code still works
- ✅ Test new SysPeriode functionality
- ✅ Verify data consistency

**Timeline:** 1-2 weeks

---

### **Phase 3: ⏳ TODO** - Data Migration

**Goal:** Migrate existing data to Sys tables

#### **Step 1: Create Migration Command**

```bash
php artisan make:command MigratePeriodesToSys
```

```php
// app/Console/Commands/MigratePeriodesToSys.php
public function handle()
{
    // Migrate PeriodeSpmi
    PeriodeSpmi::chunk(100, function ($periodes) {
        foreach ($periodes as $periode) {
            SysPeriode::create($periode->toSysPeriodeData());
            $this->info("Migrated PeriodeSpmi #{$periode->periodespmi_id}");
        }
    });

    // Migrate PeriodeKpi
    PeriodeKpi::chunk(100, function ($periodes) {
        foreach ($periodes as $periode) {
            SysPeriode::create($periode->toSysPeriodeData());
            $this->info("Migrated PeriodeKpi #{$periode->periode_kpi_id}");
        }
    });

    // Migrate PMB Periode
    // ...

    $this->info('Migration completed!');
}
```

#### **Step 2: Run Migration Command**

```bash
# In staging first!
php artisan migrate:periodes-to-sys

# Verify data
php artisan tinker
>>> SysPeriode::count()
>>> SysPeriode::type('spmi')->count()
```

#### **Step 3: Verify Data**

```php
// Check counts match
PeriodeSpmi::count() === SysPeriode::type('spmi')->count()

// Check data integrity
$old = PeriodeSpmi::find(1);
$new = $old->getSysPeriode();
assert($new->year === $old->periode);
```

**Timeline:** 1 week

---

### **Phase 4: ⏳ TODO** - Switch to Sys Components

**Goal:** Update controllers and views to use Sys components

#### **Step 1: Update Controllers**

```php
// BEFORE
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\PeriodeSpmiService;

class PeriodeSpmiController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService
    ) {}
}

// AFTER
use App\Models\Sys\SysPeriode;
use App\Services\Sys\PeriodeService;

class PeriodeController extends Controller
{
    public function __construct(
        protected PeriodeService $periodeService
    ) {}

    public function index(Request $request)
    {
        $periodes = $this->periodeService->getAll(type: 'spmi');
        // ...
    }
}
```

#### **Step 2: Update Views**

```blade
{{-- BEFORE --}}
@foreach($periodes as $periode)
    <td>{{ $periode->periode }}</td>
    <td>{{ $periode->jenis_periode }}</td>
@endforeach

{{-- AFTER --}}
@foreach($periodes as $periode)
    <td>{{ $periode->year }}</td>
    <td>{{ $periode->getMeta('jenis_periode') }}</td>
@endforeach
```

#### **Step 3: Update Routes**

```php
// routes/web.php

// BEFORE
Route::prefix('periode-spmi')->name('periode-spmi.')->group(function () {
    Route::get('/', [PeriodeSpmiController::class, 'index'])->name('index');
});

// AFTER
Route::prefix('sys/periodes')->name('sys.periodes.')->group(function () {
    Route::get('/', [PeriodeController::class, 'index'])->name('index');
    Route::get('/create', [PeriodeController::class, 'create'])->name('create');
    Route::post('/', [PeriodeController::class, 'store'])->name('store');
    Route::get('/{sysPeriode}/edit', [PeriodeController::class, 'edit'])->name('edit');
    Route::put('/{sysPeriode}', [PeriodeController::class, 'update'])->name('update');
    Route::delete('/{sysPeriode}', [PeriodeController::class, 'destroy'])->name('destroy');
});
```

**Timeline:** 2-3 weeks

---

### **Phase 5: ⏳ TODO** - Cleanup

**Goal:** Remove old models and tables

#### **Step 1: Deprecate Old Models**

```php
/**
 * @deprecated Use SysPeriode instead
 */
class PeriodeSpmi extends Model
{
    // Keep for backward compatibility during transition
}
```

#### **Step 2: Update All References**

Search and replace:
- `PeriodeSpmi` → `SysPeriode`
- `PeriodeKpi` → `SysPeriode`
- `PeriodeSpmiService` → `PeriodeService`

#### **Step 3: Remove Old Tables**

```bash
# Create drop migration
php artisan make:migration drop_old_periode_tables

# In migration
public function up()
{
    Schema::dropIfExists('pemutu_periode_spmi');
    Schema::dropIfExists('pemutu_periode_kpi');
}
```

#### **Step 4: Remove Old Code**

- Delete old model files
- Delete old service files
- Update tests
- Update documentation

**Timeline:** 1 week

---

## 📊 Migration Status by Module

| Module | Component | Phase 1 | Phase 2 | Phase 3 | Phase 4 | Phase 5 | Status |
|--------|-----------|---------|---------|---------|---------|---------|--------|
| **All** | Approval | ✅ | ⏳ | ⏳ | ⏳ | ⏳ | In Progress |
| **Pemutu** | PeriodeSpmi | ✅ | ⏳ | ⏳ | ⏳ | ⏳ | In Progress |
| **Pemutu** | PeriodeKpi | ✅ | ⏳ | ⏳ | ⏳ | ⏳ | In Progress |
| **PMB** | Periode | ✅ | ⏳ | ⏳ | ⏳ | ⏳ | Planned |
| **Eoffice** | LayananPeriode | ✅ | ⏳ | ⏳ | ⏳ | ⏳ | Planned |

---

## 🔧 Helper Methods for Migration

### **In Old Models (e.g., PeriodeSpmi)**

```php
/**
 * Convert to SysPeriode format
 */
public function toSysPeriodeData(): array
{
    return [
        'name' => "SPMI {$this->periode} - {$this->jenis_periode}",
        'type' => 'spmi',
        'year' => $this->periode,
        'metadata' => [
            'jenis_periode' => $this->jenis_periode,
            'legacy_id' => $this->periodespmi_id,
            'legacy_table' => 'pemutu_periode_spmi',
        ]
    ];
}

/**
 * Get related SysPeriode if exists
 */
public function getSysPeriode(): ?SysPeriode
{
    return SysPeriode::where('type', 'spmi')
        ->where('year', $this->periode)
        ->whereJsonContains('metadata->legacy_id', $this->periodespmi_id)
        ->first();
}
```

### **In New Models (e.g., SysPeriode)**

```php
/**
 * Get legacy PeriodeSpmi if exists
 */
public function getLegacyPeriodeSpmi(): ?PeriodeSpmi
{
    if ($this->type !== 'spmi') {
        return null;
    }

    $legacyId = $this->getMeta('legacy_id');
    
    if (!$legacyId) {
        return null;
    }

    return PeriodeSpmi::find($legacyId);
}
```

---

## ✅ Migration Checklist

### **Before Migration:**
- [ ] Backup database
- [ ] Test in staging environment
- [ ] Create rollback plan
- [ ] Notify stakeholders
- [ ] Schedule maintenance window (if needed)

### **During Migration:**
- [ ] Run data migration command
- [ ] Verify data counts
- [ ] Verify data integrity
- [ ] Test critical flows
- [ ] Monitor error logs

### **After Migration:**
- [ ] Update documentation
- [ ] Train team on new system
- [ ] Remove deprecated code
- [ ] Update CI/CD pipelines
- [ ] Monitor performance

---

## 🚨 Rollback Plan

If something goes wrong:

### **Step 1: Revert Code**
```bash
git revert <migration-commit>
```

### **Step 2: Restore Database**
```bash
# From backup
mysql -u root -p database_name < backup.sql
```

### **Step 3: Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### **Step 4: Verify**
```bash
# Check old system works
php artisan tinker
>>> PeriodeSpmi::count()
```

---

## 📞 Support & Questions

If you encounter issues during migration:

1. Check documentation: `/docs/SYS_COMPONENTS_GUIDE.md`
2. Review migration guide: `/docs/MIGRATION_GUIDE.md`
3. Contact technical lead
4. Create issue in project tracker

---

**© 2026 Laravel Boilerplate - Migration Guide**
