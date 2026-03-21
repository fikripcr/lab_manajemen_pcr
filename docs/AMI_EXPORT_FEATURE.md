# AMI Export Feature Documentation

## 📋 Overview

Fitur export untuk Audit Mutu Internal (AMI) yang menghasilkan 3 jenis dokumen:

1. **PTK (Penemuan Temuan dan Ketidaksesuaian)** - Format DOCX
2. **Temuan Audit** - Format Excel (XLSX), khusus indikator KTS
3. **Temuan Positif** - Format Excel (XLSX), untuk Terpenuhi & Terlampaui

**Implementation Pattern:** Mengikuti pola `TestController` - direct PhpWord/PhpSpreadsheet generation tanpa template file.

---

## 🎯 Fitur

### 1. PTK (Penemuan Temuan dan Ketidaksesuaian) - DOCX

**Deskripsi:** Dokumen formal yang berisi seluruh temuan ketidaksesuaian (KTS) dengan detail lengkap.

**Konten:**
- Header dengan informasi periode, jenis, unit, dan tanggal cetak
- Tabel dengan kolom:
  - No
  - No Indikator
  - Pernyataan Indikator
  - Unit Audit
  - Temuan
  - Akar Sebab
  - Akibat
  - Rencana Tindakan Perbaikan (RTP)
  - Jadwal Pelaksanaan

**File Output:** `PTK_{periode}_{timestamp}.docx`

**Route:** `GET /pemutu/ami/{periode}/export-ptk`

**Query Parameters:**
- `unit_id` (optional) - Filter berdasarkan unit
- `dok_id` (optional) - Filter berdasarkan dokumen/standar

---

### 2. Temuan Audit - KTS (Excel)

**Deskripsi:** List seluruh indikator dengan hasil KTS (Tidak Terpenuhi) dalam format tabel Excel.

**Konten:**
- Header dengan styling merah (danger color)
- Kolom:
  - No
  - Kode Indikator
  - Judul Standar
  - Judul Indikator
  - Unit Audit
  - Hasil Temuan
  - Status AMI (KTS)

**File Output:** `Temuan_Audit_KTS_{timestamp}.xlsx`

**Route:** `GET /pemutu/ami/{periode}/export-temuan-audit`

**Query Parameters:**
- `unit_id` (optional)
- `dok_id` (optional)

---

### 3. Temuan Positif (Excel)

**Deskripsi:** List indikator dengan hasil positif (Terpenuhi & Terlampaui).

**Konten:**
- Header dengan styling hijau (success color)
- Kolom:
  - No
  - Kode Indikator
  - Judul Standar
  - Judul Indikator
  - Unit Audit
  - Hasil Temuan
  - Status AMI (Terpenuhi/Terlampaui)
- Row coloring berdasarkan status:
  - Terlampaui: Light blue background
  - Terpenuhi: Light green background

**File Output:** `Temuan_Positif_{timestamp}.xlsx`

**Route:** `GET /pemutu/ami/{periode}/export-temuan-positif`

**Query Parameters:**
- `unit_id` (optional)
- `dok_id` (optional)

---

## 📁 File Structure

```
app/
├── Http/Controllers/Pemutu/
│   └── AmiController.php           # +3 export methods
├── Services/Pemutu/
│   └── AmiExportService.php        # NEW: Service untuk export logic
└── Exports/Pemutu/
    ├── TemuanAuditExport.php       # NEW: Excel export KTS
    └── TemuanPositifExport.php     # NEW: Excel export positive
```

---

## 🔧 Implementation Details

### AmiExportService

Service ini menangani logic export untuk ketiga jenis dokumen.

**Pattern:** Direct PhpWord generation (mengikuti pola `TestController`)

**Methods:**

```php
// Get KTS indicators
getKtsIndicators(PeriodeSpmi $periode, ?int $unitId, ?int $dokId)

// Get positive indicators (Terpenuhi & Terlampaui)
getPositiveIndicators(PeriodeSpmi $periode, ?int $unitId, ?int $dokId)

// Export PTK (DOCX) - direct PhpWord generation
exportPtk(PeriodeSpmi $periode, ?int $unitId, ?int $dokId)

// Export Temuan Audit (Excel)
exportTemuanAudit(PeriodeSpmi $periode, ?int $unitId, ?int $dokId)

// Export Temuan Positif (Excel)
exportTemuanPositif(PeriodeSpmi $periode, ?int $unitId, ?int $dokId)
```

### DOCX Generation (PTK)

**Pattern:** Direct PhpWord - NO template file needed

**Structure:**
```php
$phpWord = new PhpWord();
$section = $phpWord->addSection(['margin' => 1440]);

// Add title
$section->addText('PENEMUAN TEMUAN DAN KETIDAKSESUAIAN (PTK)', 
    ['size' => 16, 'bold' => true], 
    ['alignment' => 'center']
);

// Add info block
$section->addText('Periode: ...', 'normalText', 'normalText');

// Add table
$table = $section->addTable(['borderSize' => 6, 'cellMargin' => 80]);

// Add rows
foreach ($ktsIndicators as $index => $indOrg) {
    $row = $table->addRow();
    $row->addCell()->addText(...);
}

// Save
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filePath);
```

**Keuntungan:**
- ✅ Tidak perlu manage template file
- ✅ Lebih simple dan straightforward
- ✅ Mudah customize styling
- ✅ Konsisten dengan pola project (TestController)

---

## 🎨 Excel Styling

### TemuanAuditExport
- **Header:** Red background (#DC3545) with white bold text
- **Rows:** Alternating light gray and white
- **Auto-size:** Columns auto-sized based on content

### TemuanPositifExport
- **Header:** Green background (#198754) with white bold text
- **Rows:** Color-coded by status
  - Terlampaui: Light blue (#E7F1FF)
  - Terpenuhi: Light green (#D1E7DD)
- **Auto-size:** Columns auto-sized based on content

---

## 🚀 Usage

### From UI

1. Buka halaman AMI (`/pemutu/ami`)
2. Pilih tab Akademik atau Non-Akademik
3. **Set filter yang diinginkan:**
   - Unit/Dept
   - Standar/Dokumen
   - Hasil AMI
   - Status ED
4. Klik tombol **Export** (dropdown)
5. Pilih jenis export:
   - PTK (DOCX)
   - Temuan Audit - KTS (XLSX)
   - Temuan Positif (XLSX)

**Export akan menghormati SEMUA filter yang aktif!**

### Filter Behavior

| Export Type | Filter yang Dihormati |
|-------------|----------------------|
| **PTK** | Unit, Dokumen, ED Status (selalu KTS/ami_hasil_akhir=0) |
| **Temuan Audit** | Unit, Dokumen, ED Status (selalu KTS/ami_hasil_akhir=0) |
| **Temuan Positif** | Unit, Dokumen, ED Status (selalu Terpenuhi & Terlampaui) |

**Notes:**
- PTK dan Temuan Audit **selalu** export data KTS (ami_hasil_akhir = 0)
- Temuan Positif **selalu** export data Terpenuhi & Terlampaui (ami_hasil_akhir = 1, 2)
- Filter "Hasil AMI" di UI **tidak** mempengaruhi export (sudah fixed per type)
- Filter Unit, Dokumen, dan ED Status **selalu** dihormati

### Programmatic Usage

```php
// In controller
use App\Services\Pemutu\AmiExportService;
use App\Models\Pemutu\PeriodeSpmi;

public function export(PeriodeSpmi $periode)
{
    $exportService = app(AmiExportService::class);
    
    // Export PTK
    return $exportService->exportPtk($periode, $unitId = 5, $dokId = null);
    
    // Export Temuan Audit
    $export = $exportService->exportTemuanAudit($periode);
    return Excel::download($export, 'temuan.xlsx');
    
    // Export Temuan Positif
    $export = $exportService->exportTemuanPositif($periode);
    return Excel::download($export, 'positif.xlsx');
}
```

---

## 📊 Data Source

Data diambil dari relasi:

```
IndikatorOrgUnit (pivot table)
├── indikator (Indikator model)
│   ├── parent (Standar)
│   ├── labels
│   └── dokSubs
├── orgUnit (StrukturOrganisasi)
└── ami_hasil_akhir (0=KTS, 1=Terpenuhi, 2=Terlampaui)
```

**Filter KTS:**
```php
->where('ami_hasil_akhir', 0)
```

**Filter Positive:**
```php
->whereIn('ami_hasil_akhir', [1, 2])
```

---

## ⚠️ Error Handling

Service akan melakukan `throw new \App\Exceptions\DataNotFoundException` jika tidak ada data untuk diekspor. Exception ini akan otomatis ditangkap oleh **Global Exception Handler** (`bootstrap/app.php`) yang kemudian akan melakukan _redirect back_ beserta pesan error.

Oleh karena itu, di tingkat Controller **tidak perlu lagi menggunakan blok `try-catch`**.

**Contoh di Controller:**
```php
public function exportPtk(Request $request, PeriodeSpmi $periode)
{
    // ... setup parameter
    // Exception tidak perlu ditangkap di sini, karena sudah ditangani secara global
    return $this->AmiExportService->exportPtk($periode, $unitId, $dokId, $edStatus);
}
```

---

## 📝 Dependencies

- **maatwebsite/excel** ^3.1 - Excel export
- **phpoffice/phpword** ^1.4 - DOCX generation
- **PhpOffice\PhpSpreadsheet** - Excel manipulation (included with maatwebsite/excel)

---

## 🧪 Testing

### Manual Testing Checklist

- [ ] Export PTK dengan data KTS
- [ ] Export PTK tanpa data (should show error)
- [ ] Export Temuan Audit KTS
- [ ] Export Temuan Positif (Terpenuhi & Terlampaui)
- [ ] Export dengan filter unit
- [ ] Export dengan filter dokumen
- [ ] Export dengan kombinasi filter
- [ ] Verify Excel styling (colors, auto-size)
- [ ] Verify DOCX content (all fields populated)

---

## 🔮 Future Enhancements

Potential improvements:

1. **Custom Template Upload** - Allow admin to upload custom DOCX templates
2. **PDF Export** - Add PDF format option
3. **Bulk Export** - Export all periods at once
4. **Email Delivery** - Send exported files via email
5. **Export History** - Log export activities
6. **Chart/Graph** - Include visual charts in Excel exports

---

**Last Updated:** March 20, 2026
**Status:** ✅ Production Ready
