# 📘 Sys Components - Complete Developer Guide

**Last Updated:** March 2026  
**Version:** 1.0.0  
**For:** New Developers & System Integrators  

---

## 🎯 Quick Start

### **What is Sys Components?**

Sys Components adalah kumpulan **global services** yang dapat digunakan di semua modul untuk:
- ✅ Approval workflow
- ✅ Period/milestone management
- ✅ Notifications
- ✅ User management
- ✅ Error logging
- ✅ Activity logging
- ✅ Backup management

**Location:**
- Models: `app/Models/Sys/`
- Services: `app/Services/Sys/`
- Migrations: `database/migrations/*_sys*.php`

---

## 📋 Table of Contents

1. [Approval System](#1-approval-system) - Workflow approval polymorphic
2. [Periode System](#2-periode-system) - Manajemen periode/milestone
3. [Notification System](#3-notification-system) - Notifikasi polymorphic
4. [User Management](#4-user-management) - User administration
5. [Error Logging](#5-error-logging) - Error tracking
6. [Activity Logging](#6-activity-logging) - Activity audit trail
7. [Backup Service](#7-backup-service) - Backup management
8. [Helper Functions](#8-helper-functions) - Global helpers

---

## 1. Approval System

### **Overview**

Approval system digunakan untuk mengelola workflow approval di semua modul dengan pola **polymorphic** (satu sistem untuk semua).

### **Use Cases:**
- ✅ Dokumen approval (Pemutu)
- ✅ Perizinan approval (HR)
- ✅ Pendaftaran approval (PMB)
- ✅ Request approval (Lab)
- ✅ Dan modul lainnya

---

### **Setup**

#### **Step 1: Add Relationship to Model**

```php
<?php

namespace App\Models\Pemutu;

use App\Models\Sys\SysApproval;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    /**
     * Get all approvals for this document
     */
    public function sysApprovals()
    {
        return $this->morphMany(SysApproval::class, 'subject', 'model', 'model_id');
    }
}
```

#### **Step 2: Inject Service in Controller**

```php
<?php

namespace App\Http\Controllers\Pemutu;

use App\Services\Sys\ApprovalService;

class DokumenApprovalController extends Controller
{
    public function __construct(
        protected ApprovalService $approvalService
    ) {}
}
```

---

### **Basic Usage**

#### **Create Approvers**

```php
// In Controller
public function store(Request $request, Dokumen $dokumen)
{
    $approvers = [
        [
            'pegawai_id' => 'encrypted_pegawai_id',
            'jabatan' => 'Manager'
        ],
        [
            'pegawai_id' => 'encrypted_pegawai_id_2',
            'jabatan' => 'Director'
        ]
    ];

    $result = $this->approvalService->syncApprovers($dokumen, $approvers);

    // $result structure:
    // [
    //     'added' => ['John Doe', 'Jane Smith'],
    //     'updated' => [],
    //     'deleted_count' => 0,
    //     'messages' => ['Menambah 2 approver: John Doe, Jane Smith']
    // ]

    logActivity('dokumen', "Approval created: {$dokumen->judul}");

    return jsonSuccess('Approver berhasil ditambahkan.', url()->previous());
}
```

---

#### **Update Approvers**

```php
public function update(Request $request, Dokumen $dokumen)
{
    $approvers = [
        [
            'id' => 123, // Existing approval ID
            'pegawai_id' => 'new_pegawai_id',
            'jabatan' => 'Senior Manager' // Updated position
        ],
        [
            // New approver (no ID)
            'pegawai_id' => 'new_pegawai_id_2',
            'jabatan' => 'CEO'
        ]
    ];

    $result = $this->approvalService->syncApprovers($dokumen, $approvers);

    // Will:
    // - Update approver with ID 123
    // - Add new approver
    // - Delete approvers not in the list

    return jsonSuccess('Approver berhasil diupdate.', url()->previous());
}
```

---

#### **Delete Approver**

```php
public function destroy(RiwayatApproval $approval)
{
    $subjectName = $this->approvalService->deleteApproval($approval);

    logActivity('dokumen', "Approval deleted: {$subjectName}");

    return jsonSuccess('Approval berhasil dihapus.');
}
```

---

#### **Process Approval (Approve/Reject)**

```php
public function process(Request $request, SysApproval $approval)
{
    $validated = $request->validate([
        'status' => 'required|in:Approved,Rejected',
        'catatan' => 'nullable|string|max:1000'
    ]);

    $updatedApproval = $this->approvalService->processApproval(
        $approval->sys_approval_id,
        $validated['status'],
        $validated['catatan'] ?? null
    );

    logActivity('approval', "Approval processed: {$updatedApproval->status}");

    return jsonSuccess('Approval berhasil diproses.');
}
```

---

#### **Check Approval Status**

```php
// Check if fully approved
if ($this->approvalService->isFullyApproved($dokumen)) {
    // All approvers have approved
    // Generate QR code, send notification, etc.
}

// Get status summary
$summary = $this->approvalService->getStatusSummary($dokumen);

// $summary = [
//     'total' => 5,
//     'approved' => 3,
//     'rejected' => 1,
//     'pending' => 1,
//     'is_sah' => false
// ]
```

---

#### **Get Approval Inbox**

```php
// In controller for approval inbox page
public function inbox()
{
    $pegawaiId = auth()->user()->pegawai_id;
    
    $inboxQuery = $this->approvalService->getInboxQuery($pegawaiId);
    
    // Use with DataTables
    return DataTables::of($inboxQuery)->make(true);
}
```

---

### **Blade Form Example**

```blade
<form class="ajax-form" action="{{ route('dokumen.approve', $dokumen) }}" method="POST">
    @csrf
    
    <div id="approver-container">
        @foreach($existingApprovals as $index => $approval)
            <div class="approver-row">
                <!-- Hidden ID for updates -->
                <input type="hidden" name="approvers[{{ $index }}][id]" 
                       value="{{ $approval->sys_approval_id }}">
                
                <!-- Pegawai Select -->
                <select name="approvers[{{ $index }}][pegawai_id]" required>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->encrypted_pegawai_id }}" 
                                {{ $p->pegawai_id == $approval->pegawai_id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                
                <!-- Position Input -->
                <input type="text" name="approvers[{{ $index }}][jabatan]" 
                       value="{{ $approval->jabatan }}" required>
                
                <!-- Delete Button (only for Pending) -->
                @if($approval->status === 'Pending')
                    <button type="button" class="btn-remove-approver">🗑️</button>
                @endif
            </div>
        @endforeach
    </div>
    
    <button type="button" id="btn-add-approver">Tambah Approver</button>
    <button type="submit">Simpan</button>
</form>

<script>
// Add JavaScript to handle dynamic rows (see GLOBAL_APPROVAL_SERVICE.md)
</script>
```

---

### **Common Scenarios**

#### **Scenario 1: Single Approver**

```php
$approvers = [
    [
        'pegawai_id' => $managerId,
        'jabatan' => 'Manager'
    ]
];

$this->approvalService->syncApprovers($document, $approvers);
```

#### **Scenario 2: Multiple Approvers (Sequential)**

```php
$approvers = [
    [
        'pegawai_id' => $supervisorId,
        'jabatan' => 'Supervisor'
    ],
    [
        'pegawai_id' => $managerId,
        'jabatan' => 'Manager'
    ],
    [
        'pegawai_id' => $directorId,
        'jabatan' => 'Director'
    ]
];

$this->approvalService->syncApprovers($document, $approvers);
```

#### **Scenario 3: Replace All Approvers**

```php
// Just send new list - old pending ones will be deleted
$newApprovers = [
    [
        'pegawai_id' => $newApproverId,
        'jabatan' => 'New Approver'
    ]
];

$this->approvalService->syncApprovers($document, $newApprovers);
// Old pending approvers automatically deleted
```

---

## 2. Periode System

### **Overview**

Periode system digunakan untuk mengelola periode/milestone di semua modul dengan single source of truth.

### **Use Cases:**
- ✅ SPMI periods (Pemutu)
- ✅ KPI periods (Pemutu)
- ✅ PMB registration periods
- ✅ Event periods
- ✅ Service periods (Eoffice)

---

### **Setup**

#### **Step 1: Add Relationship to Model**

```php
<?php

namespace App\Models\Pemutu;

use App\Models\Sys\SysPeriode;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    /**
     * Get periods associated with this document
     */
    public function sysPeriodes()
    {
        return $this->morphToMany(SysPeriode::class, 'periodable', 'sys_periodeables');
    }
}
```

---

### **Basic Usage**

#### **Create Periode**

```php
public function store(StorePeriodeRequest $request)
{
    $periode = $this->periodeService->create([
        'name' => 'SPMI 2026 - Akademik',
        'type' => 'spmi',
        'year' => 2026,
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'is_active' => true,
        'metadata' => [
            'jenis_periode' => 'Akademik',
            'ed_awal' => '2026-01-01',
            'ed_akhir' => '2026-03-31',
            'ami_awal' => '2026-04-01',
            'ami_akhir' => '2026-06-30',
        ]
    ]);

    return jsonSuccess('Periode berhasil dibuat.', route('sys.periodes.index'));
}
```

---

#### **Get Active Periode**

```php
// Get active SPMI periode
$activePeriode = $this->periodeService->getActivePeriode('spmi');

// Get current periode (date is between start and end)
$currentPeriode = $this->periodeService->getCurrentPeriode('spmi');

// Get all periodes by type
$spmiPeriodes = $this->periodeService->getAll(type: 'spmi');

// Get periodes by year
$periodes2026 = $this->periodeService->getAll(year: 2026);
```

---

#### **Update Periode**

```php
public function update(UpdatePeriodeRequest $request, SysPeriode $periode)
{
    $this->periodeService->update($periode, $request->validated());

    return jsonSuccess('Periode berhasil diupdate.', url()->previous());
}
```

---

#### **Activate Periode**

```php
public function activate(SysPeriode $periode)
{
    // This will automatically deactivate other periodes of the same type
    $this->periodeService->setActive($periode);

    return jsonSuccess('Periode berhasil diaktifkan.', url()->previous());
}
```

---

#### **Get Metadata**

```php
$periode = SysPeriode::find(1);

// Get metadata value
$jenisPeriode = $periode->getMeta('jenis_periode');

// Get with default
$edAwal = $periode->getMeta('ed_awal', '2026-01-01');

// Set metadata
$periode->setMeta('new_key', 'new_value');
$periode->save();
```

---

#### **Get Statistics**

```php
$stats = $this->periodeService->getStatistics();

// $stats = [
//     'total' => 10,
//     'active' => 2,
//     'by_type' => ['spmi' => 5, 'kpi' => 3, 'pmb' => 2],
//     'by_year' => [2026 => 5, 2025 => 3, 2024 => 2]
// ]
```

---

### **Common Scenarios**

#### **Scenario 1: SPMI Period**

```php
SysPeriode::create([
    'name' => 'SPMI 2026 - Akademik',
    'type' => 'spmi',
    'year' => 2026,
    'is_active' => true,
    'metadata' => [
        'jenis_periode' => 'Akademik',
        'penetapan_awal' => '2026-01-01',
        'penetapan_akhir' => '2026-01-31',
        'ed_awal' => '2026-02-01',
        'ed_akhir' => '2026-03-31',
        'ami_awal' => '2026-04-01',
        'ami_akhir' => '2026-05-31',
        'pengendalian_awal' => '2026-06-01',
        'pengendalian_akhir' => '2026-06-30',
        'peningkatan_awal' => '2026-07-01',
        'peningkatan_akhir' => '2026-07-31',
    ]
]);
```

#### **Scenario 2: KPI Period**

```php
SysPeriode::create([
    'name' => 'KPI Q1 2026',
    'type' => 'kpi',
    'year' => 2026,
    'start_date' => '2026-01-01',
    'end_date' => '2026-03-31',
    'is_active' => true,
    'metadata' => [
        'quarter' => 'Q1',
        'target_count' => 10,
    ]
]);
```

#### **Scenario 3: PMB Period**

```php
SysPeriode::create([
    'name' => 'PMB 2026/2027 - Gelombang 1',
    'type' => 'pmb',
    'year' => 2026,
    'start_date' => '2026-01-01',
    'end_date' => '2026-06-30',
    'is_active' => true,
    'metadata' => [
        'gelombang' => '1',
        'tanggal_mulai_pendaftaran' => '2026-01-01',
        'tanggal_selesai_pendaftaran' => '2026-05-31',
        'tanggal_ujian' => '2026-06-15',
    ]
]);
```

---

## 3. Notification System

### **Overview**

Notification system digunakan untuk mengirim notifikasi ke users di semua modul.

---

### **Basic Usage**

#### **Send Notification**

```php
// Method 1: Using notify() method
$user->notify(new CustomNotification($data));

// Method 2: Using Notification facade
Notification::send($users, new CustomNotification($data));

// Method 3: Using NotificationService
$this->notificationService->send(
    $user,
    'Test Notification',
    'This is a test notification',
    ['type' => 'test', 'url' => '/dashboard']
);
```

---

#### **Get Notifications**

```php
// Get all notifications
$notifications = $user->notifications;

// Get unread notifications
$unreadNotifications = $user->unreadNotifications;

// Get read notifications
$readNotifications = $user->notifications->whereNotNull('read_at');

// Get unread count
$unreadCount = $user->unreadNotifications()->count();
```

---

#### **Mark as Read**

```php
// Mark single notification as read
$notification->markAsRead();

// Mark all as read
$user->unreadNotifications->each->markAsRead();

// Mark specific notifications as read
$user->notifications()
    ->whereIn('id', $notificationIds)
    ->update(['read_at' => now()]);
```

---

### **Create Notification Class**

```bash
php artisan make:notification DokumenApprovedNotification
```

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DokumenApprovedNotification extends Notification
{
    use Queueable;

    protected $dokumen;

    public function __construct($dokumen)
    {
        $this->dokumen = $dokumen;
    }

    public function via($notifiable)
    {
        return ['database']; // or ['mail', 'database']
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Dokumen Disetujui',
            'message' => "Dokumen {$this->dokumen->judul} telah disetujui",
            'type' => 'dokumen_approved',
            'url' => route('pemutu.dokumen.show', $this->dokumen),
            'dokumen_id' => $this->dokumen->id,
        ];
    }
}
```

---

## 4. User Management

### **Overview**

User management system untuk administrasi users di semua modul.

---

### **Basic Usage**

#### **Get Users**

```php
// Get all users
$users = $this->userService->getUserList();

// Get user by ID
$user = $this->userService->getUserById($userId);

// Get users with filters
$users = $this->userService->getUserList([
    'search' => 'john',
    'role' => 'admin',
    'per_page' => 20
]);
```

---

#### **Create User**

```php
public function store(UserRequest $request)
{
    $user = $this->userService->createUser([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'roles' => ['admin'],
        'expired_at' => $request->expired_at,
        'avatar' => $request->file('avatar')
    ]);

    return jsonSuccess('User berhasil dibuat.', route('sys.users.index'));
}
```

---

#### **Update User**

```php
public function update(UserRequest $request, User $user)
{
    $updatedUser = $this->userService->updateUser($user->id, [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->filled('password') ? $request->password : null,
        'roles' => $request->roles,
        'expired_at' => $request->expired_at,
        'avatar' => $request->file('avatar')
    ]);

    return jsonSuccess('User berhasil diupdate.', url()->previous());
}
```

---

#### **Delete User**

```php
public function destroy(User $user)
{
    $this->userService->deleteUser($user->id);

    return jsonSuccess('User berhasil dihapus.');
}
```

---

## 5. Error Logging

### **Overview**

Error logging system untuk tracking errors di aplikasi.

---

### **Basic Usage**

#### **Log Error**

```php
// Automatically logged by global exception handler
// No need to manually log errors

// Manual logging (if needed)
try {
    // Some code
} catch (\Exception $e) {
    logError($e, 'error', ['context' => 'custom context']);
}
```

---

#### **Get Error Logs**

```php
// Get recent errors
$errors = $this->errorLogService->getRecentErrors(limit: 50);

// Get errors by date range
$errors = $this->errorLogService->getErrorsByDate(
    from: '2026-01-01',
    to: '2026-01-31'
);

// Get errors by level
$errors = $this->errorLogService->getErrorsByLevel('critical');
```

---

## 6. Activity Logging

### **Overview**

Activity logging system untuk audit trail di semua modul.

---

### **Basic Usage**

#### **Log Activity**

```php
// Simple log
logActivity('user_management', 'Created user: John Doe');

// With subject
logActivity('dokumen_management', 'Created dokumen', $dokumen);

// With extra properties
logActivity('dokumen_management', 'Updated dokumen', $dokumen, [
    'old_attributes' => $oldAttributes,
    'new_attributes' => $newAttributes,
    'changed_by' => auth()->user()->name
]);
```

---

#### **Get Activity Logs**

```php
// Get recent activities
$activities = $this->activityLogsService->getRecentActivities(limit: 50);

// Get activities by log name
$userActivities = $this->activityLogsService->getActivitiesByLogName('user_management');

// Get activities by subject
$dokumenActivities = $this->activityLogsService->getActivitiesBySubject($dokumen);
```

---

## 7. Backup Service

### **Overview**

Backup management system untuk database dan file backups.

---

### **Basic Usage**

#### **Create Backup**

```php
public function createBackup(Request $request)
{
    $backup = $this->backupService->create([
        'type' => 'database', // or 'files', 'full'
        'description' => 'Monthly backup',
    ]);

    return jsonSuccess('Backup berhasil dibuat.', route('sys.backup.index'));
}
```

---

#### **Restore Backup**

```php
public function restore(Backup $backup)
{
    $this->backupService->restore($backup);

    return jsonSuccess('Backup berhasil dipulihkan.');
}
```

---

#### **Download Backup**

```php
public function download(Backup $backup)
{
    return $this->backupService->download($backup);
}
```

---

## 8. Helper Functions

### **Global Helpers**

#### **ID Encryption**

```php
// Encrypt ID
$encryptedId = encryptId(123); // Returns: "Xj9a2S"

// Decrypt ID
$originalId = decryptId("Xj9a2S"); // Returns: 123

// Smart decrypt (if encrypted)
$id = decryptIdIfEncrypted($value);
```

---

#### **Date Formatting**

```php
// Format date to Indonesian
formatTanggalIndo($date); // "Senin, 24 Maret 2026 14:30"

// Format time only
formatWaktuSaja($time); // "14:30"
```

---

#### **JSON Response**

```php
// Success response
return jsonSuccess('Data berhasil disimpan');
return jsonSuccess('Data berhasil disimpan.', route('products.index'));

// Error response
return jsonError('Data tidak ditemukan');
return jsonError('Data tidak ditemukan.', 404);
```

---

#### **Activity Logging**

```php
logActivity('module_name', 'Description', $subject, ['extra' => 'data']);
```

---

#### **Error Logging**

```php
logError($exception);
logError('Custom error message', 'error', ['context' => 'data']);
```

---

#### **File Download**

```php
return downloadStorageFile($filePath, 'custom_filename.pdf');
```

---

## 📚 Additional Resources

- **GLOBAL_APPROVAL_SERVICE.md** - Detailed approval system guide
- **SYS_GLOBAL_COMPONENTS.md** - Architecture overview
- **DEVELOPMENT_GUIDE.md** - Development best practices
- **PROJECT_ARCHITECTURE.md** - Global architecture

---

**© 2026 Laravel Boilerplate - Sys Components Documentation**
