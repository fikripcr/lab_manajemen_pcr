# 📚 Dokumentasi Konfigurasi Dokumen SPMI

## 🎯 Overview

Sistem konfigurasi baru untuk Dokumen SPMI menggunakan **configuration-based approach** untuk menggantikan scattered if-else logic dan helper functions.

## 📦 File Structure

```
app/
├── Config/
│   └── PemutuDokumenConfig.php    # Main configuration class
resources/
├── views/
│   ├── components/
│   │   └── pemutu/
│   │       ├── dokumen-config.blade.php      # Configuration wrapper
│   │       ├── dokumen-workspace.blade.php   # Optimized workspace
│   │       └── helpers.blade.php             # Blade helpers
│   └── pages/
│       └── pemutu/
│           └── dokumen/
│               ├── index.blade.php           # Original (legacy)
│               └── index-optimized.blade.php # New optimized version
```

## 🚀 Usage

### **1. Basic Configuration Access**

```php
use App\Config\PemutuDokumenConfig;

// Get configuration for a document type
$config = PemutuDokumenConfig::for('standar');

// Access properties
$config->label();              // 'Standar'
$config->labelFull();          // 'Standar Mutu'
$config->category();           // 'standar'
$config->isTreeBased();        // true
$config->hasPoin();            // true
$config->hasDefaultPoin();     // true
$config->getDefaultPoin();     // ['Rasional', 'Ruang Lingkup', ...]
$config->canGenerateIndikator(); // true
$config->showApproval();       // true
$config->icon();               // 'ti ti-book'
```

### **2. In Blade Templates**

```blade
@php
    $config = App\Config\PemutuDokumenConfig::for($activeJenis);
@endphp

{{-- Simple checks --}}
@if($config->isTreeBased())
    {{-- Show tree view --}}
@endif

@if($config->hasDefaultPoin())
    {{-- Show default poin creator --}}
@endif

@if($config->canGenerateIndikator())
    {{-- Show indikator button --}}
@endif

{{-- Loop through default poin --}}
@foreach($config->getDefaultPoin() as $index => $judul)
    <div class="poin-item">
        {{ $index + 1 }}. {{ $judul }}
    </div>
@endforeach
```

### **3. Using Blade Helpers**

```blade
{{-- Import helpers --}}
@include('components.pemutu.helpers')

{{-- Use helper functions --}}
@if(pemutu('standar')->hasPoin())
    {{-- Has poin --}}
@endif

@foreach(pemutuDefaultPoin('standar') as $poin)
    {{ $poin }}
@endforeach

@if(pemutuCanMap('misi', 'visi'))
    {{-- Can map --}}
@endif
```

### **4. Check Mapping Relationships**

```php
$config = PemutuDokumenConfig::for('formulir');

// Get all mappable types
$mappableTo = $config->mappableTo();  // ['standar', 'manual_prosedur']

// Check specific mapping
$config->canMapTo('standar');         // true
$config->canMapTo('kebijakan');       // false

// Parent types
$parentTypes = $config->parentTypes(); // ['standar', 'manual_prosedur']
$config->canHaveParent('standar');     // true
```

### **5. Get Document Types by Category**

```php
// All document types
$all = PemutuDokumenConfig::all();

// Tree-based types
$treeBased = PemutuDokumenConfig::treeBasedTypes();
// Returns: ['kebijakan', 'standar', 'manual_prosedur', 'formulir']

// Indikator generators
$generators = PemutuDokumenConfig::indikatorGeneratorTypes();
// Returns: ['standar', 'renop']

// By category
$standarCategory = PemutuDokumenConfig::byCategory('standar');
// Returns: ['standar', 'manual_prosedur', 'formulir']
```

## 📋 Configuration Properties

| Property | Type | Description |
|----------|------|-------------|
| `label` | string | Short label (e.g., 'Standar') |
| `label_full` | string | Full label (e.g., 'Standar Mutu') |
| `category` | string | Tab category ('standar' or 'kebijakan') |
| `tree_based` | bool | Use tree view (true) or single doc view (false) |
| `has_poin` | bool | Has poin (DokSub) |
| `has_default_poin` | bool | Auto-generate default poin |
| `default_poin` | array | List of default poin titles |
| `can_generate_indikator` | bool | Can generate indicators |
| `indikator_poin_index` | int | Which poin generates indikator (0-based) |
| `indikator_via_label` | bool | Use label system instead of poin |
| `mappable_to` | array | Which types this can map to |
| `parent_types` | array | Valid parent document types |
| `child_types` | array | Valid child document types |
| `show_approval` | bool | Show approval tab |
| `icon` | string | Tabler icon class |
| `tabs` | array | Available tabs for this type |

## 🔄 Migration Guide

### **Before (Old Way)**

```blade
{{-- Scattered if-else --}}
@if(in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur', 'kebijakan']))
    {{-- Tree view --}}
@endif

@if($activeJenis === 'standar')
    {{-- Standar specific --}}
@elseif($activeJenis === 'formulir')
    {{-- Formulir specific --}}
@elseif($activeJenis === 'renop')
    {{-- Renop specific --}}
@endif

{{-- Helper functions --}}
@if(pemutuTabByJenis($jenis) === 'standar')
@endif

@if(pemutuMappableJenis($jenis))
@endif
```

### **After (New Way)**

```blade
@php
    $config = PemutuDokumenConfig::for($activeJenis);
@endphp

{{-- Clean configuration access --}}
@if($config->isTreeBased())
    {{-- Tree view --}}
@endif

@if($activeJenis === 'standar')
    {{-- Standar specific --}}
@endif

{{-- Direct property access --}}
@if($config->category() === 'standar')
@endif

@if(count($config->mappableTo()) > 0)
@endif
```

## 🎨 Component Usage

### **Dokumen Config Component**

```blade
{{-- Wrap content with config context --}}
<x-pemutu.dokumen-config :jenis="$activeJenis">
    {{-- Config is available in slot --}}
    @if($config->isTreeBased())
        {{-- Tree view --}}
    @endif
</x-pemutu.dokumen-config>
```

### **Dokumen Workspace Component**

```blade
{{-- Reusable workspace component --}}
<x-pemutu.dokumen-workspace 
    :item="$dokumen" 
    :type="'dokumen'"
    :customConfig="['show_approval' => false]"
>
    {{-- Tab content here --}}
</x-pemutu.dokumen-workspace>
```

## ✅ Benefits

### **Before:**
- ❌ 31 scattered helper functions
- ❌ Complex if-else chains in views
- ❌ Hard to maintain and extend
- ❌ Logic duplicated across files
- ❌ No single source of truth

### **After:**
- ✅ Single configuration class
- ✅ Clean, readable code
- ✅ Easy to add new document types
- ✅ Centralized business logic
- ✅ Type-safe with IDE support
- ✅ Testable configuration

## 🧪 Testing

```php
// Test configuration
$config = PemutuDokumenConfig::for('standar');

assert($config->isTreeBased() === true);
assert($config->hasDefaultPoin() === true);
assert($config->canGenerateIndikator() === true);
assert(count($config->getDefaultPoin()) === 5);
assert($config->getIndikatorPoinIndex() === 4);

// Test mapping
assert($config->canMapTo('kebijakan') === true);
assert($config->canHaveParent('kebijakan') === true);
```

## 📝 Adding New Document Type

```php
// Add to PemutuDokumenConfig::$config array
'new_type' => [
    'label'           => 'New Type',
    'label_full'      => 'New Document Type',
    'category'        => 'standar',
    'tree_based'      => true,
    'has_poin'        => true,
    'has_default_poin'=> false,
    'default_poin'    => [],
    'can_generate_indikator' => false,
    'mappable_to'     => ['standar'],
    'parent_types'    => ['standar'],
    'child_types'     => [],
    'show_approval'   => true,
    'icon'            => 'ti ti-file',
],
```

That's it! The configuration system automatically handles the rest.

## 🔧 Backward Compatibility

Old helper functions are kept for backward compatibility but marked as deprecated:

```php
// Old (deprecated)
pemutuTabByJenis($jenis);
pemutuMappableJenis($jenis);
pemutuDefaultSubDocuments($jenis);

// New (recommended)
pemutu($jenis)->category();
pemutu($jenis)->mappableTo();
pemutu($jenis)->getDefaultPoin();
```

---

**Last Updated:** March 2026
**Version:** 2.0.0
