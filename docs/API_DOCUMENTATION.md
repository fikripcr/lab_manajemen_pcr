# API Documentation - Laravel Boilerplate

**Last Updated:** March 2026  
**Laravel Version:** 12.46.0  
**API Version:** v1  

Dokumentasi lengkap untuk API endpoints yang tersedia di Laravel Boilerplate.

---

## Table of Contents

1. [Authentication](#authentication)
2. [Users API](#users-api)
3. [Roles & Permissions API](#roles--permissions-api)
4. [Products API](#products-api)
5. [Media API](#media-api)
6. [Activity Logs API](#activity-logs-api)
7. [Error Responses](#error-responses)
8. [Rate Limiting](#rate-limiting)

---

## Base URL

```
Local Development: http://localhost/api/v1
Production: https://your-domain.com/api/v1
```

---

## Authentication

### API Token Authentication (Laravel Sanctum)

#### 1. Login & Get Token

**Endpoint:** `POST /api/v1/auth/login`

**Request:**
```json
{
    "email": "user@example.com",
    "password": "password",
    "device_name": "Chrome Desktop"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "1|abc123def456...",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "roles": ["admin"]
        }
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "Kredensial tidak valid"
}
```

#### 2. Logout & Revoke Token

**Endpoint:** `POST /api/v1/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

#### 3. Get Current User

**Endpoint:** `GET /api/v1/auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "email_verified_at": "2026-03-24T10:00:00.000000Z",
        "created_at": "2026-03-24T10:00:00.000000Z",
        "updated_at": "2026-03-24T10:00:00.000000Z",
        "roles": ["admin"],
        "permissions": ["view-users", "create-users", "edit-users"]
    }
}
```

---

## Users API

### 1. Get All Users

**Endpoint:** `GET /api/v1/users`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `per_page` | integer | 15 | Items per page |
| `search` | string | - | Search by name/email |
| `role` | string | - | Filter by role |
| `sort_by` | string | `created_at` | Sort field |
| `sort_order` | string | `desc` | Sort order (asc/desc) |

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "hashid": "Xj9a2S",
                "name": "John Doe",
                "email": "john@example.com",
                "email_verified_at": "2026-03-24T10:00:00.000000Z",
                "created_at": "2026-03-24T10:00:00.000000Z",
                "updated_at": "2026-03-24T10:00:00.000000Z",
                "roles": ["admin"],
                "permissions": ["view-users", "create-users"]
            }
        ],
        "first_page_url": "/api/v1/users?page=1",
        "from": 1,
        "last_page": 5,
        "last_page_url": "/api/v1/users?page=5",
        "links": [
            {"url": null, "label": "&laquo; Previous", "active": false},
            {"url": "/api/v1/users?page=1", "label": "1", "active": true},
            {"url": "/api/v1/users?page=2", "label": "2", "active": false}
        ],
        "next_page_url": "/api/v1/users?page=2",
        "path": "/api/v1/users",
        "per_page": 15,
        "prev_page_url": null,
        "to": 15,
        "total": 75
    }
}
```

### 2. Get Single User

**Endpoint:** `GET /api/v1/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

> ⚠️ **Note:** `{id}` menggunakan encrypted hashid (contoh: `Xj9a2S`), bukan ID integer.

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "hashid": "Xj9a2S",
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2026-03-24T10:00:00.000000Z",
        "created_at": "2026-03-24T10:00:00.000000Z",
        "updated_at": "2026-03-24T10:00:00.000000Z",
        "roles": ["admin"],
        "permissions": ["view-users", "create-users"],
        "created_by": "System",
        "updated_by": "Admin"
    }
}
```

### 3. Create User

**Endpoint:** `POST /api/v1/users`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!",
    "role": "user",
    "email_verified": false
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "User berhasil dibuat",
    "data": {
        "id": 2,
        "hashid": "Yk8b3T",
        "name": "Jane Doe",
        "email": "jane@example.com",
        "roles": ["user"]
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "email": ["Alamat email sudah digunakan."],
        "password": ["Password minimal 8 karakter."]
    }
}
```

### 4. Update User

**Endpoint:** `PUT /api/v1/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "name": "Jane Smith",
    "email": "jane.smith@example.com",
    "role": "admin"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "User berhasil diupdate",
    "data": {
        "id": 2,
        "hashid": "Yk8b3T",
        "name": "Jane Smith",
        "email": "jane.smith@example.com",
        "roles": ["admin"]
    }
}
```

### 5. Delete User

**Endpoint:** `DELETE /api/v1/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "User berhasil dihapus"
}
```

### 6. Assign Role to User

**Endpoint:** `POST /api/v1/users/{id}/roles`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "roles": ["admin", "manager"]
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Role berhasil ditugaskan",
    "data": {
        "user_id": 1,
        "roles": ["admin", "manager"]
    }
}
```

### 7. Remove Role from User

**Endpoint:** `DELETE /api/v1/users/{id}/roles/{role}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Role berhasil dihapus"
}
```

### 8. Give Permission to User

**Endpoint:** `POST /api/v1/users/{id}/permissions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "permissions": ["view-products", "edit-products"]
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Permission berhasil ditugaskan"
}
```

---

## Roles & Permissions API

### 1. Get All Roles

**Endpoint:** `GET /api/v1/roles`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "admin",
            "display_name": "Administrator",
            "permissions": ["view-users", "create-users", "edit-users", "delete-users"],
            "users_count": 5
        },
        {
            "id": 2,
            "name": "user",
            "display_name": "User",
            "permissions": ["view-profile"],
            "users_count": 150
        }
    ]
}
```

### 2. Create Role

**Endpoint:** `POST /api/v1/roles`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "name": "manager",
    "display_name": "Manager",
    "permissions": ["view-users", "view-products", "edit-products"]
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Role berhasil dibuat",
    "data": {
        "id": 3,
        "name": "manager",
        "display_name": "Manager",
        "permissions": ["view-users", "view-products", "edit-products"]
    }
}
```

### 3. Update Role

**Endpoint:** `PUT /api/v1/roles/{role}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "display_name": "Senior Manager",
    "permissions": ["view-users", "view-products", "edit-products", "delete-products"]
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Role berhasil diupdate"
}
```

### 4. Delete Role

**Endpoint:** `DELETE /api/v1/roles/{role}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Role berhasil dihapus"
}
```

### 5. Get All Permissions

**Endpoint:** `GET /api/v1/permissions`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "view-users",
            "display_name": "View Users",
            "group": "user_management"
        },
        {
            "id": 2,
            "name": "create-users",
            "display_name": "Create Users",
            "group": "user_management"
        },
        {
            "id": 3,
            "name": "view-products",
            "display_name": "View Products",
            "group": "product_management"
        }
    ]
}
```

### 6. Create Permission

**Endpoint:** `POST /api/v1/permissions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request:**
```json
{
    "name": "export-products",
    "display_name": "Export Products",
    "group": "product_management"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Permission berhasil dibuat"
}
```

---

## Products API

### 1. Get All Products

**Endpoint:** `GET /api/v1/products`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `per_page` | integer | 15 | Items per page |
| `search` | string | - | Search by name/code |
| `category_id` | integer | - | Filter by category |
| `min_price` | number | - | Minimum price |
| `max_price` | number | - | Maximum price |
| `in_stock` | boolean | - | Filter in-stock items |
| `sort_by` | string | `created_at` | Sort field |
| `sort_order` | string | `desc` | Sort order |

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "hashid": "Pr0d1",
                "name": "Product A",
                "code": "PROD-001",
                "description": "Description for Product A",
                "price": 100000,
                "price_formatted": "Rp 100.000",
                "stock": 50,
                "stock_status": "in_stock",
                "category": {
                    "id": 1,
                    "name": "Electronics"
                },
                "images": [
                    {
                        "url": "/storage/products/product-1.jpg",
                        "thumb_url": "/storage/products/conversions/product-1-thumb.jpg"
                    }
                ],
                "created_at": "2026-03-24T10:00:00.000000Z",
                "updated_at": "2026-03-24T10:00:00.000000Z"
            }
        ],
        "last_page": 10,
        "total": 150
    }
}
```

### 2. Get Single Product

**Endpoint:** `GET /api/v1/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "hashid": "Pr0d1",
        "name": "Product A",
        "code": "PROD-001",
        "description": "Description for Product A",
        "price": 100000,
        "stock": 50,
        "category": {
            "id": 1,
            "name": "Electronics"
        },
        "images": [
            {
                "id": 1,
                "file_name": "product-1.jpg",
                "mime_type": "image/jpeg",
                "size": 102400,
                "url": "/storage/products/product-1.jpg",
                "thumb_url": "/storage/products/conversions/product-1-thumb.jpg",
                "preview_url": "/storage/products/conversions/product-1-preview.jpg"
            }
        ],
        "attachments": [],
        "created_by": "Admin",
        "created_at": "2026-03-24T10:00:00.000000Z",
        "updated_at": "2026-03-24T10:00:00.000000Z"
    }
}
```

### 3. Create Product

**Endpoint:** `POST /api/v1/products`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request (multipart/form-data):**
```
name: Product A
code: PROD-001
description: Description for Product A
price: 100000
stock: 50
category_id: 1
product_images[]: [file]
product_attachments[]: [file]
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Product berhasil dibuat",
    "data": {
        "id": 1,
        "hashid": "Pr0d1",
        "name": "Product A",
        "code": "PROD-001",
        "price": 100000,
        "stock": 50
    }
}
```

### 4. Update Product

**Endpoint:** `PUT /api/v1/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request:**
```
name: Product A Updated
price: 150000
stock: 45
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Product berhasil diupdate"
}
```

### 5. Delete Product

**Endpoint:** `DELETE /api/v1/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Product berhasil dihapus"
}
```

---

## Media API

### 1. Upload Single File

**Endpoint:** `POST /api/v1/media/upload`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request:**
```
file: [file]
collection: product_images
model_type: App\\Models\\Product
model_id: 1
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "File berhasil diupload",
    "data": {
        "id": 1,
        "file_name": "product-image.jpg",
        "mime_type": "image/jpeg",
        "size": 102400,
        "size_formatted": "100 KB",
        "url": "/storage/products/product-image.jpg",
        "thumb_url": "/storage/products/conversions/product-image-thumb.jpg",
        "preview_url": "/storage/products/conversions/product-image-preview.jpg"
    }
}
```

### 2. Upload Multiple Files

**Endpoint:** `POST /api/v1/media/upload-multiple`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request:**
```
files[]: [file1, file2, file3]
collection: product_images
model_type: App\\Models\\Product
model_id: 1
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "3 files berhasil diupload",
    "data": [
        {
            "id": 1,
            "file_name": "image1.jpg",
            "url": "/storage/products/image1.jpg"
        },
        {
            "id": 2,
            "file_name": "image2.jpg",
            "url": "/storage/products/image2.jpg"
        }
    ]
}
```

### 3. Delete Media

**Endpoint:** `DELETE /api/v1/media/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "File berhasil dihapus"
}
```

---

## Activity Logs API

### 1. Get Activity Logs

**Endpoint:** `GET /api/v1/activity-logs`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `per_page` | integer | 15 | Items per page |
| `log_name` | string | - | Filter by log name |
| `causer_id` | integer | - | Filter by user ID |
| `subject_type` | string | - | Filter by model type |
| `date_from` | date | - | Filter from date |
| `date_to` | date | - | Filter to date |

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "log_name": "user_management",
                "description": "Created user: John Doe",
                "subject_type": "App\\Models\\User",
                "subject_id": 1,
                "causer_type": "App\\Models\\User",
                "causer_id": 1,
                "causer": {
                    "id": 1,
                    "name": "Admin"
                },
                "properties": {
                    "attributes": {
                        "name": "John Doe",
                        "email": "john@example.com"
                    }
                },
                "ip_address": "127.0.0.1",
                "user_agent": "Mozilla/5.0...",
                "created_at": "2026-03-24T10:00:00.000000Z"
            }
        ],
        "last_page": 5,
        "total": 75
    }
}
```

---

## Error Responses

### Standard Error Response Format

```json
{
    "success": false,
    "message": "Error message here",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### HTTP Status Codes

| Code | Description | Example |
|------|-------------|---------|
| 200 | OK | Request berhasil |
| 201 | Created | Resource berhasil dibuat |
| 400 | Bad Request | Request tidak valid |
| 401 | Unauthorized | Token tidak valid/expired |
| 403 | Forbidden | Tidak ada permission |
| 404 | Not Found | Resource tidak ditemukan |
| 422 | Unprocessable Entity | Validasi gagal |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

### Error Examples

**401 Unauthorized:**
```json
{
    "success": false,
    "message": "Unauthenticated. Token tidak valid atau sudah expired."
}
```

**403 Forbidden:**
```json
{
    "success": false,
    "message": "Anda tidak memiliki permission untuk melakukan aksi ini."
}
```

**404 Not Found:**
```json
{
    "success": false,
    "message": "Resource tidak ditemukan."
}
```

**422 Validation Error:**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "name": ["Nama wajib diisi."],
        "email": [
            "Email wajib diisi.",
            "Format email tidak valid."
        ],
        "password": ["Password minimal 8 karakter."]
    }
}
```

**500 Internal Server Error:**
```json
{
    "success": false,
    "message": "Terjadi kesalahan sistem. Silakan hubungi administrator."
}
```

---

## Rate Limiting

### Default Rate Limits

| Endpoint Type | Limit | Window |
|---------------|-------|--------|
| Auth endpoints | 10 requests | 1 minute |
| API endpoints (authenticated) | 60 requests | 1 minute |
| API endpoints (guest) | 30 requests | 1 minute |
| File upload | 10 requests | 1 minute |

### Rate Limit Headers

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
Retry-After: 30
```

### Rate Limit Exceeded Response

**429 Too Many Requests:**
```json
{
    "success": false,
    "message": "Terlalu banyak request. Silakan coba lagi dalam 30 detik."
}
```

---

## Postman Collection

Import Postman collection berikut untuk testing API:

```json
{
    "info": {
        "name": "Laravel Boilerplate API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "variable": [
        {
            "key": "base_url",
            "value": "http://localhost/api/v1"
        },
        {
            "key": "token",
            "value": ""
        }
    ],
    "item": [
        {
            "name": "Auth",
            "item": [
                {
                    "name": "Login",
                    "request": {
                        "method": "POST",
                        "header": [],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"email\": \"admin@example.com\",\n    \"password\": \"password\",\n    \"device_name\": \"Postman\"\n}",
                            "options": {
                                "raw": {
                                    "language": "json"
                                }
                            }
                        },
                        "url": {
                            "raw": "{{base_url}}/auth/login",
                            "host": ["{{base_url}}"],
                            "path": ["auth", "login"]
                        }
                    }
                }
            ]
        }
    ]
}
```

---

## Next Steps

1. 📖 Baca [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md) untuk best practices
2. 📖 Baca [PROJECT_ARCHITECTURE.md](./PROJECT_ARCHITECTURE.md) untuk arsitektur
3. 📖 Baca [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) untuk common issues
