# Panduan JS Custom Library di Proyek

## Daftar Isi
1. [Struktur JS dalam Proyek](#struktur-js-dalam-proyek)
2. [Menambahkan Library Baru](#menambahkan-library-baru)
3. [Mengoptimalkan Asset Bundling](#mengoptimalkan-asset-bundling)
4. [Contoh Implementasi](#contoh-implementasi)

## Struktur JS dalam Proyek

Proyek ini menggunakan Vite sebagai build tool untuk mengelola dan mengoptimalkan aset JavaScript. Struktur utama berkas JS terletak di dalam direktori `resources/js/`.

### Struktur berkas:
```
resources/js/
├── admin.js          # Entry point untuk halaman admin
├── sys.js            # Entry point untuk halaman sistem
├── global.js         # Fungsi-fungsi global yang digunakan di semua halaman
├── components/       # Komponen-komponen JS kustom
│   ├── CustomDataTables.js
│   ├── CustomSweetAlerts.js
│   ├── FormFeatures.js
│   └── ...
└── ...
```

### Konfigurasi Vite
File `vite.config.js` menentukan berkas-berkas JavaScript yang akan dikompilasi dan di-bundle:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/components/FormFeatures.js',
                'resources/js/components/CustomDataTables.js',
                'resources/js/components/CustomSweetAlerts.js',
                'resources/js/admin.js',
                'resources/js/global.js',
                'resources/js/sys.js',
            ],
            refresh: true,
        }),
    ],
});
```

## Menambahkan Library Baru

### 1. Instalasi Library
Gunakan npm atau yarn untuk menginstal library JavaScript:

```bash
npm install nama-library
```

Contoh:
```bash
npm install choices.js
```

### 2. Import dan Ekspor di Berkas JS
Tambahkan import di berkas JS entry point yang sesuai dan ekspor ke window object jika diperlukan:

```javascript
// resources/js/sys.js
import Choices from 'choices.js';
window.Choices = Choices;
```

Atau jika ingin menggunakan import langsung di komponen:
```javascript
// resources/js/components/CustomComponent.js
import Choices from 'choices.js';

export default class CustomComponent {
    constructor() {
        // Gunakan Choices di sini
        this.choicesInstance = new Choices('#myElement');
    }
}
```

### 3. Gunakan di Blade Template
Setelah library tersedia, Anda bisa menggunakannya di dalam script Anda:

```blade
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const choices = new Choices('#mySelect');
        });
    </script>
@endpush
```

## Mengoptimalkan Asset Bundling

### 1. Bundle Splitting
Vite otomatis mengoptimalkan bundle dengan:
- Code splitting untuk mengurangi ukuran bundle awal
- Tree-shaking untuk menghapus kode yang tidak digunakan

### 2. Dynamic Import
Gunakan dynamic import untuk memuat komponen atau library hanya saat diperlukan:

```javascript
// resources/js/sys.js
window.initToastEditor = function(selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};
```

### 3. ESM Module
Gunakan ES Module untuk mendukung tree-shaking:

```javascript
// resources/js/components/CustomDataTables.js
import $ from 'jquery';
import 'datatables.net';
export default class CustomDataTables {
    // kode di sini
}
```

## Contoh Implementasi

### Implementasi Choices.js

1. Instalasi library:
```bash
npm install choices.js
```

2. Tambahkan import ke berkas `resources/js/sys.js`:
```javascript
import Choices from 'choices.js';
window.Choices = Choices;
```

3. Gunakan di template:
```blade
<select id="singleSelect" class="form-select">
    <option value="">Select an option</option>
    <option value="option1">Option 1</option>
    <option value="option2">Option 2</option>
</select>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const singleSelect = new Choices('#singleSelect', {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for an option...',
                shouldSort: false,
            });
        });
    </script>
@endpush
```

### Implementasi CustomDataTables

1. Buat komponen baru di `resources/js/components/`:
```javascript
// resources/js/components/CustomDataTables.js
export default class CustomDataTables {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.options = {
            route: options.route || '',
            checkbox: options.checkbox || false,
            // ... konfigurasi lainnya
        };
        this.init();
    }

    init() {
        // Implementasi DataTables di sini
    }
}
```

2. Tambahkan ke konfigurasi Vite:
```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: [
                // ... berkas lainnya
                'resources/js/components/CustomDataTables.js', // tambahkan baris ini
            ],
            refresh: true,
        }),
    ],
});
```

3. Gunakan di berkas JS utama:
```javascript
// resources/js/sys.js
import CustomDataTables from './components/CustomDataTables.js';
window.CustomDataTables = CustomDataTables;
```

4. Gunakan di Blade:
```blade
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new CustomDataTables('users-table', {
                route: '{{ route('users.data') }}',
                // ... konfigurasi lainnya
            });
        });
    </script>
@endpush
```

## Best Practices

1. **Gunakan Nama Berkas Konsisten**: Gunakan format PascalCase untuk nama kelas dan camelCase untuk fungsi.

2. **Komponen Moduler**: Buat komponen-komponen JS yang reusable dan self-contained.

3. **Manfaatkan Dynamic Import**: Untuk fitur yang tidak selalu dibutuhkan, gunakan dynamic import untuk mengurangi ukuran bundle awal.

4. **Global Scope Hanya Jika Diperlukan**: Hanya ekspor ke window object jika benar-benar diperlukan di template Blade.

5. **Buat Dokumentasi**: Selalu dokumentasikan komponen dan library yang ditambahkan.

## Troubleshooting

### Bundle Terlalu Besar
- Pastikan hanya mengimpor bagian dari library yang digunakan
- Gunakan dynamic import untuk komponen yang jarang digunakan

### Module Tidak Ditemukan
- Pastikan import path benar
- Pastikan berkas diinput ke dalam konfigurasi Vite

### Script Tidak Berjalan
- Pastikan script dijalankan setelah DOM siap
- Pastikan dependensi yang diperlukan telah dimuat