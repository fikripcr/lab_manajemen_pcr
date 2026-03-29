# Global Approval Service - Reusable Approval Management

**Last Updated:** March 2026  
**Service:** `App\Services\Sys\ApprovalService`  
**Location:** `app/Services/Sys/`  
**Pattern:** Polymorphic Approval System

---

## 📋 Overview

**ApprovalService** adalah service **GLOBAL** di folder **Sys** yang dapat digunakan di **SEMUA modul** untuk mengelola approval workflow. Tidak perlu membuat logic approval yang sama berulang kali!

---

## 📁 Service Location

```
app/Services/
├── Sys/
│   ├── ApprovalService.php       ← GLOBAL approval service
│   ├── UserService.php
│   ├── NotificationService.php
│   └── ...
├── Pemutu/
│   ├── DokumenApprovalService.php → Delegate to Sys\ApprovalService
│   └── ...
├── Hr/
│   └── ...
└── ...
```

---

## 🎯 Features

| Feature | Description |
|---------|-------------|
| **Polymorphic** | Bisa digunakan di model apapun (Dokumen, Perizinan, Pendaftaran, dll) |
| **CRUD Operations** | CREATE, UPDATE, DELETE approver dalam satu method |
| **Status Management** | Pending → Approved/Rejected |
| **Inbox System** | Get approval inbox untuk pegawai tertentu |
| **Validation** | Check apakah semua approver sudah approve |
| **QR Code** | Auto-generate QR code jika sudah fully approved |

---

## 📖 Usage Examples

### **1. Basic Usage (Di Controller)**

```php
<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Services\Common\ApprovalService;
use App\Models\Pemutu\Dokumen;

class DokumenApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    public function store(DokumenApprovalRequest $request, Dokumen $dokumen)
    {
        // Sync approvers (CREATE, UPDATE, DELETE)
        $result = $this->approvalService->syncApprovers($dokumen, $request->input('approvers', []));

        // Log activity
        if (!empty($result['messages'])) {
            logActivity('pemutu', "Memperbarui approval: {$dokumen->judul}. ".implode('. ', $result['messages']));
        }

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }
}
```

---

### **2. Usage di Modul HR (Perizinan)**

```php
<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Services\Common\ApprovalService;
use App\Models\Hr\Perizinan;

class PerizinanApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    public function store(Request $request, Perizinan $perizinan)
    {
        $result = $this->approvalService->syncApprovers($perizinan, $request->input('approvers', []));

        logActivity('hr_perizinan', "Memperbarui approval perizinan: {$perizinan->nama}");

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }
}
```

---

### **3. Usage di Modul PMB (Pendaftaran)**

```php
<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Services\Common\ApprovalService;
use App\Models\Pmb\Pendaftaran;

class PendaftaranApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    public function store(Request $request, Pendaftaran $pendaftaran)
    {
        $result = $this->approvalService->syncApprovers($pendaftaran, $request->input('approvers', []));

        logActivity('pmb_pendaftaran', "Memperbarui approval pendaftaran: {$pendaftaran->no_pendaftaran}");

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }
}
```

---

### **4. Usage di Modul Lab (Request Software)**

```php
<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Services\Common\ApprovalService;
use App\Models\Lab\RequestSoftware;

class RequestApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    public function store(Request $request, RequestSoftware $requestSoftware)
    {
        $result = $this->approvalService->syncApprovers($requestSoftware, $request->input('approvers', []));

        logActivity('lab_request', "Memperbarui approval request: {$requestSoftware->nama_software}");

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }
}
```

---

## 🔧 Available Methods

### **1. `syncApprovers(Model $model, array $approvers)`**

**Purpose:** Sinkronisasi daftar approver (CREATE, UPDATE, DELETE)

**Parameters:**
- `$model` - Model yang akan di-approve (Dokumen, Perizinan, dll)
- `$approvers` - Array dari form:
  ```php
  [
      [
          'id' => 123, // Optional (untuk update)
          'pegawai_id' => 'encrypted_id',
          'jabatan' => 'Manager'
      ],
      // ...
  ]
  ```

**Returns:**
```php
[
    'added' => ['John Doe', 'Jane Smith'],
    'updated' => ['Bob Williams'],
    'deleted_count' => 1,
    'messages' => [
        'Menambah 2 approver: John Doe, Jane Smith',
        'Mengupdate 1 approver: Bob Williams',
        'Menghapus 1 approver'
    ]
]
```

---

### **2. `deleteApproval(RiwayatApproval $approval)`**

**Purpose:** Hapus single approval

**Returns:** `string` - Nama subject yang dihapus

---

### **3. `getInboxQuery(int $pegawaiId)`**

**Purpose:** Get inbox query untuk pegawai tertentu

**Returns:** `Builder` - Query builder untuk DataTables

**Example:**
```php
$inboxQuery = $this->approvalService->getInboxQuery(auth()->user()->pegawai_id);

return DataTables::of($inboxQuery)->make(true);
```

---

### **4. `processApproval(int $approvalId, string $status, ?string $catatan)`**

**Purpose:** Proses approval action (approve/reject)

**Parameters:**
- `$approvalId` - ID approval
- `$status` - 'Approved' atau 'Rejected'
- `$catatan` - Catatan approval (optional)

**Returns:** `RiwayatApproval` - Approval yang sudah diupdate

**Example:**
```php
$approval = $this->approvalService->processApproval($approvalId, 'Approved', 'Semua lengkap');

logActivity('approval', "Approval {$approval->id} diproses: {$approval->status}");
```

---

### **5. `isFullyApproved(Model $model)`**

**Purpose:** Check apakah semua approver sudah approve

**Returns:** `bool`

**Example:**
```php
if ($this->approvalService->isFullyApproved($dokumen)) {
    // Generate QR code, send notification, etc
}
```

---

### **6. `getStatusSummary(Model $model)`**

**Purpose:** Get summary status approval

**Returns:**
```php
[
    'total' => 5,
    'approved' => 3,
    'rejected' => 1,
    'pending' => 1,
    'is_sah' => false // true jika semua approved
]
```

---

### **7. `getApprovalFormData(Model $model)`**

**Purpose:** Get data untuk form approval

**Returns:**
```php
[
    'pegawais' => Collection,
    'isSah' => bool,
    'qrCode' => string|null,
    'existingApprovals' => Collection,
    'approvals' => Collection
]
```

---

### **8. `getPublicVerificationData(Model $model)`**

**Purpose:** Get data untuk halaman verifikasi publik

**Returns:**
```php
[
    'approvals' => Collection,
    'status' => 'Sah dan Tervalidasi',
    'icon' => 'ti ti-shield-check',
    'color' => 'green',
    'desc' => 'Dokumen ini merupakan Dokumen Master yang sah...'
]
```

---

## 📋 Form Structure

**Blade Form Example:**

```blade
<form class="ajax-form" action="{{ route('dokumen.approve', $dokumen) }}" method="POST">
    @csrf
    
    <div id="approver-container">
        @foreach($existingApprovals as $index => $approval)
            <div class="approver-row">
                <!-- Hidden ID untuk update -->
                <input type="hidden" name="approvers[{{ $index }}][id]" 
                       value="{{ $approval->riwayatapproval_id }}">
                
                <!-- Pegawai Select -->
                <select name="approvers[{{ $index }}][pegawai_id]" required>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->encrypted_pegawai_id }}" 
                                {{ $p->pegawai_id == $approval->pegawai_id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                
                <!-- Jabatan Input -->
                <input type="text" name="approvers[{{ $index }}][jabatan]" 
                       value="{{ $approval->jabatan }}" required>
                
                <!-- Delete Button (hanya untuk status Pending) -->
                @if($approval->status === 'Pending')
                    <button type="button" class="btn-remove-approver">🗑️</button>
                @endif
            </div>
        @endforeach
    </div>
    
    <button type="button" id="btn-add-approver">Tambah Approver</button>
    <button type="submit">Simpan</button>
</form>
```

---

## 🎯 Modules Using ApprovalService

| Module | Models | Status |
|--------|--------|--------|
| **Pemutu** | Dokumen, DokSub | ✅ Implemented |
| **HR** | Perizinan, Lembur | ⚠️ Can implement |
| **PMB** | Pendaftaran | ⚠️ Can implement |
| **Lab** | RequestSoftware, SuratBebasLab, Kegiatan | ⚠️ Can implement |
| **Event** | Kegiatan | ⚠️ Can implement |
| **Cms** | Artikel | ⚠️ Can implement |

---

## ✅ Benefits

| Benefit | Description |
|---------|-------------|
| **DRY Principle** | Don't Repeat Yourself - satu logic untuk semua modul |
| **Consistency** | Semua modul pakai pattern yang sama |
| **Maintainability** | Fix bug di satu tempat, semua modul terfix |
| **Reusability** | Tinggal inject service, langsung pakai |
| **Testability** | Mudah unit test karena terpusat |

---

## 🚀 Quick Start untuk Modul Baru

**Step 1:** Inject ApprovalService di controller

```php
public function __construct(protected ApprovalService $approvalService) {}
```

**Step 2:** Call method `syncApprovers()`

```php
$result = $this->approvalService->syncApprovers($model, $approvers);
```

**Step 3:** Log activity & return response

```php
logActivity('module', "Memperbarui approval: {$model->judul}");
return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
```

**Done!** ✅ Approval system sudah jalan di modul Anda!

---

**© 2026 Laravel Boilerplate - Global Approval System**
