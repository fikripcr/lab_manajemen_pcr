# Panduan Global Search Universal

## Deskripsi
Sistem global search baru ini dirancang agar fleksibel dan dapat digunakan oleh berbagai komponen tanpa perlu penulisan JS khusus. Data dan konfigurasi ditentukan oleh server, bukan oleh kode JS.

## Cara Kerja
1. Komponen JS GlobalSearch menerima konfigurasi dari server
2. Template item di-render berdasarkan data dan konfigurasi yang dikirim dari server
3. Tidak perlu menulis JS khusus untuk setiap jenis pencarian

## Struktur Data dari Server
Endpoint global search harus mengembalikan JSON dengan format berikut:

```json
{
  "users": [
    {
      "name": "Nama User",
      "email": "email@example.com",
      "url": "/users/1",
      "avatar": "/path/to/avatar.jpg",
      "additional_field": "value"
    }
  ],
  "posts": [
    {
      "title": "Judul Post",
      "description": "Deskripsi Post",
      "url": "/posts/1",
      "additional_field": "value"
    }
  ]
}
```

## Konfigurasi Template dari Server
Kita dapat mengirimkan konfigurasi template tambahan dalam header atau response JSON untuk menentukan bagaimana setiap jenis item ditampilkan.

## Implementasi di Controller

Contoh implementasi di GlobalSearchController:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        // Cari data untuk berbagai kategori
        $results = [];
        
        if (!empty($query)) {
            // Cari users
            $results['users'] = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'url' => route('users.show', $user->id),
                        'avatar' => $user->avatar_url ?? null,
                    ];
                })
                ->toArray();
                
            // Cari posts
            $results['posts'] = Post::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'description' => str_limit(strip_tags($post->content), 100),
                        'url' => route('posts.show', $post->id),
                    ];
                })
                ->toArray();
        }
        
        return response()->json($results);
    }
}
```

## Konfigurasi Lanjutan (Opsional)

Jika Anda ingin menentukan template atau ikon khusus untuk kategori tertentu, Anda bisa menambahkan konfigurasi ke dalam response:

```php
public function search(Request $request)
{
    // ... logika pencarian ...
    
    $response = [
        'results' => $results,
        'config' => [
            'posts' => [
                'icon' => 'bx bx-file',
                'label' => 'Artikel',
                'template' => 'custom_post_template' // Jika Anda memiliki template khusus
            ]
        ]
    ];
    
    return response()->json($response);
}
```

## Cara Menggunakan di Template

Untuk menggunakan global search, Anda cukup menambahkan komponen modal ke template Anda:

```blade
<x-sys.modal-global-search />
```

Dan panggil dari elemen HTML:

```html
<a href="javascript:void(0)" onclick="openGlobalSearchModal()">Search</a>
```

## Penyesuaian Konfigurasi JS

Jika Anda ingin menyesuaikan konfigurasi secara dinamis, Anda dapat melakukannya dari JS:

```javascript
// Tambah konfigurasi baru
window.globalSearch.addSearchConfig('products', {
    icon: 'bx bx-package',
    label: 'Products',
    itemTemplate: (item) => `
        <a href="${item.url}" class="list-group-item list-group-item-action">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-xs bg-label-success rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-package"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">${item.name}</h6>
                    <small class="text-muted">${item.description}</small>
                </div>
            </div>
        </a>
    `
});
```

Dengan sistem ini, siapa pun yang ingin menambahkan kategori pencarian baru hanya perlu:
1. Menambahkan logika pencarian di controller
2. Menambahkan data ke response JSON
3. (Opsional) Menyesuaikan konfigurasi template jika diperlukan