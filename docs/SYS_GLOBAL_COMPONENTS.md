# Global Sys Components - Reusable System-Wide Services

**Last Updated:** March 2026  
**Location:** `app/Models/Sys/`, `app/Services/Sys/`  
**Purpose:** System-wide components yang dapat digunakan di semua modul

---

## 📋 Overview

Folder **Sys** berisi components yang **truly global** dan dapat digunakan di semua modul:
- ✅ **Approval System** - Workflow approval polymorphic
- ✅ **Periode System** - Manajemen periode/milestone
- ✅ **Notification System** - Notifikasi polymorphic
- ✅ **User Management** - User administration
- ✅ **Backup Service** - Backup management
- ✅ **Error Logging** - Error tracking
- ✅ **Activity Logging** - Activity audit trail

---

## 📁 Structure

```
app/
├── Models/Sys/
│   ├── SysApproval.php          ← GLOBAL approval
│   ├── SysPeriode.php           ← GLOBAL period management
│   ├── Notification.php         ← GLOBAL notification
│   ├── User.php                 ← GLOBAL user
│   ├── Activity.php             ← GLOBAL activity log
│   ├── ErrorLog.php             ← GLOBAL error log
│   ├── Media.php                ← GLOBAL media
│   ├── Role.php                 ← GLOBAL role
│   ├── Permission.php           ← GLOBAL permission
│   └── ...
│
└── Services/Sys/
    ├── ApprovalService.php      ← GLOBAL approval logic
    ├── PeriodeService.php       ← GLOBAL period logic
    ├── NotificationService.php  ← GLOBAL notification logic
    ├── UserService.php          ← GLOBAL user logic
    ├── BackupService.php        ← GLOBAL backup logic
    ├── ErrorLogService.php      ← GLOBAL error log logic
    └── ...
```

---

## 🎯 Component 1: Approval System

### **Model:** `SysApproval`

**Table:** `sys_approvals`

**Purpose:** Workflow approval polymorphic untuk semua modul

**Usage:**
```php
// Di model apapun
public function sysApprovals()
{
    return $this->morphMany(SysApproval::class, 'subject');
}

// Usage
$dokumen->sysApprovals()->create([
    'pegawai_id' => 1,
    'pejabat' => 'John Doe',
    'jabatan' => 'Manager',
    'status' => 'Pending'
]);
```

### **Service:** `ApprovalService`

**Methods:**
- `syncApprovers()` - CREATE, UPDATE, DELETE approvers
- `processApproval()` - Approve/Reject
- `isFullyApproved()` - Check if all approved
- `getInboxQuery()` - Get inbox for employee
- `getStatusSummary()` - Get status summary

**Usage:**
```php
// Di controller
public function __construct(protected ApprovalService $approvalService) {}

public function store(Request $request, Dokumen $dokumen)
{
    $result = $this->approvalService->syncApprovers(
        $dokumen, 
        $request->input('approvers', [])
    );
    
    return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
}
```

**Documentation:** [GLOBAL_APPROVAL_SERVICE.md](./GLOBAL_APPROVAL_SERVICE.md)

---

## 🎯 Component 2: Periode System

### **Model:** `SysPeriode`

**Table:** `sys_periodes`

**Purpose:** Manajemen periode/milestone untuk semua modul

**Fields:**
- `name` - Nama periode (e.g., "SPMI 2026 - Akademik")
- `type` - Tipe periode (spmi, kpi, pmb, layanan, event)
- `year` - Tahun
- `start_date`, `end_date` - Tanggal mulai/selesai
- `is_active` - Status aktif
- `metadata` - JSON untuk data fleksibel

**Usage:**
```php
// SPMI Period
SysPeriode::create([
    'name' => 'SPMI 2026 - Akademik',
    'type' => 'spmi',
    'year' => 2026,
    'metadata' => [
        'jenis_periode' => 'Akademik',
        'ed_awal' => '2026-01-01',
        'ed_akhir' => '2026-03-31',
        'ami_awal' => '2026-04-01',
        'ami_akhir' => '2026-06-30',
    ]
]);

// KPI Period
SysPeriode::create([
    'name' => 'KPI Q1 2026',
    'type' => 'kpi',
    'year' => 2026,
    'start_date' => '2026-01-01',
    'end_date' => '2026-03-31',
    'is_active' => true
]);

// PMB Period
SysPeriode::create([
    'name' => 'PMB 2026/2027',
    'type' => 'pmb',
    'year' => 2026,
    'metadata' => [
        'gelombang' => '1',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-06-30'
    ]
]);
```

### **Scopes:**
```php
// Get active periodes
SysPeriode::active()->get();

// Get by type
SysPeriode::type('spmi')->get();

// Get by year
SysPeriode::year(2026)->get();

// Get current (tanggal sekarang di antara start dan end)
SysPeriode::current()->get();
```

### **Service:** `PeriodeService`

**Methods:**
- `getAll()` - Get all periodes with filters
- `getActivePeriode($type)` - Get active periode by type
- `getCurrentPeriode($type)` - Get current periode
- `create()` - Create new periode
- `update()` - Update periode
- `setActive()` - Set periode as active (auto deactivate others)
- `getAvailableYears()` - Get available years
- `getStatistics()` - Get statistics

**Usage:**
```php
// Di controller
public function __construct(protected PeriodeService $periodeService) {}

public function index()
{
    // Get all SPMI periodes
    $periodes = $this->periodeService->getAll(type: 'spmi');
    
    // Get active periode
    $activePeriode = $this->periodeService->getActivePeriode('spmi');
    
    // Get current periode
    $currentPeriode = $this->periodeService->getCurrentPeriode();
    
    return view('periodes.index', compact('periodes', 'activePeriode', 'currentPeriode'));
}

public function store(StorePeriodeRequest $request)
{
    $periode = $this->periodeService->create($request->validated());
    
    return jsonSuccess('Periode berhasil dibuat.', route('sys.periodes.index'));
}

public function update(UpdatePeriodeRequest $request, SysPeriode $periode)
{
    $this->periodeService->update($periode, $request->validated());
    
    return jsonSuccess('Periode berhasil diupdate.', url()->previous());
}

public function activate(SysPeriode $periode)
{
    $this->periodeService->setActive($periode);
    
    return jsonSuccess('Periode berhasil diaktifkan.', url()->previous());
}
```

---

## 🎯 Component 3: Notification System

### **Model:** `Notification`

**Table:** `sys_notifications`

**Purpose:** Notifikasi polymorphic untuk semua modul

**Usage:**
```php
// Send notification
$user->notify(new CustomNotification($data));

// Get unread notifications
$unreadNotifications = $user->unreadNotifications;

// Mark as read
$notification->markAsRead();
```

### **Service:** `NotificationService`

**Methods:**
- `send()` - Send notification
- `markAsRead()` - Mark as read
- `markAllAsRead()` - Mark all as read
- `getUnreadCount()` - Get unread count

**Usage:**
```php
// Di controller
public function __construct(protected NotificationService $notificationService) {}

public function sendTestNotification()
{
    $this->notificationService->send(
        auth()->user(),
        'Test Notification',
        'This is a test notification',
        ['type' => 'test']
    );
    
    return jsonSuccess('Notifikasi berhasil dikirim.');
}
```

---

## 📊 Database Schema

**Migration File:** `database/migrations/2026_03_24_000000_create_sys_global_tables.php`

### **sys_approvals**
```sql
CREATE TABLE sys_approvals (
    sys_approval_id BIGINT PRIMARY KEY,
    model VARCHAR(255),
    model_id BIGINT,
    pegawai_id BIGINT,
    pejabat VARCHAR(255),
    jabatan VARCHAR(255),
    status ENUM('Draft', 'Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    catatan TEXT,
    created_by VARCHAR(255),
    updated_by VARCHAR(255),
    deleted_by VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    INDEX (model, model_id),
    INDEX (pegawai_id),
    INDEX (status)
);
```

### **sys_periodes**
```sql
CREATE TABLE sys_periodes (
    sys_periode_id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    type VARCHAR(50),
    year INT,
    start_date DATE,
    end_date DATE,
    is_active BOOLEAN DEFAULT FALSE,
    metadata JSON,
    created_by VARCHAR(255),
    updated_by VARCHAR(255),
    deleted_by VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    INDEX (type, year),
    INDEX (is_active),
    INDEX (start_date, end_date)
);
```

### **sys_periodeables** (Pivot Table)
```sql
CREATE TABLE sys_periodeables (
    id BIGINT PRIMARY KEY,
    sys_periode_id BIGINT,
    periodable_type VARCHAR(255),
    periodable_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    UNIQUE (sys_periode_id, periodable_type, periodable_id),
    FOREIGN KEY (sys_periode_id) REFERENCES sys_periodes(sys_periode_id) ON DELETE CASCADE
);
```

### **sys_notifications** (Already exists in create_sys_tables.php)
```sql
CREATE TABLE sys_notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255),
    notifiable_type VARCHAR(255),
    notifiable_id BIGINT,
    data JSON,
    read_at TIMESTAMP,
    created_by VARCHAR(255),
    updated_by VARCHAR(255),
    deleted_by VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    INDEX (notifiable_type, notifiable_id),
    INDEX (read_at)
);
```

---

## 🚀 Migration Guide

### **Step 1: Run Migrations**
```bash
php artisan migrate
```

### **Step 2: Update Models**

**Add relationship:**
```php
// Di model apapun
public function sysApprovals()
{
    return $this->morphMany(\App\Models\Sys\SysApproval::class, 'subject');
}

public function sysPeriodes()
{
    return $this->morphToMany(\App\Models\Sys\SysPeriode::class, 'periodable');
}
```

### **Step 3: Update Controllers**

**Inject service:**
```php
public function __construct(
    protected ApprovalService $approvalService,
    protected PeriodeService $periodeService
) {}
```

**Use service:**
```php
// Approval
$result = $this->approvalService->syncApprovers($model, $approvers);

// Periode
$activePeriode = $this->periodeService->getActivePeriode('spmi');
```

---

## ✅ Benefits

| Benefit | Description |
|---------|-------------|
| **Centralized** | Semua system components di satu tempat |
| **Reusable** | Bisa dipakai di semua modul |
| **Separatable** | Siap dipisah ke project terpisah |
| **Consistent** | Prefix `sys_` untuk semua system tables |
| **Maintainable** | Fix bug di satu tempat |
| **Scalable** | Mudah tambah modul baru |

---

## 📋 Checklist Implementasi

### **✅ COMPLETED**
- [x] SysApproval model & migration
- [x] ApprovalService
- [x] SysPeriode model & migration
- [x] PeriodeService
- [x] Notification consolidation
- [x] Documentation

### **⏳ TODO (Per Module)**
- [ ] Pemutu: Migrate PeriodeSpmi → SysPeriode
- [ ] Pemutu: Migrate PeriodeKpi → SysPeriode
- [ ] PMB: Migrate Periode → SysPeriode
- [ ] Eoffice: Migrate LayananPeriode → SysPeriode
- [ ] All modules: Update imports to use Sys models

---

**© 2026 Laravel Boilerplate - Global Sys Components**
