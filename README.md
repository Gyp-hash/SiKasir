# SiKasir Angkringan Smart Business Managementt

Sprint 1 berisi autentikasi dasar Laravel 12:

- Login berbasis `username`
- Logout
- Role `owner`
- Role `kasir`
- Middleware role
- Middleware user aktif
- Seeder akun default
- Dashboard awal owner dan kasir

Sprint 2 menambahkan:

- CRUD kategori
- CRUD produk
- Relasi kategori ke produk
- Upload gambar produk
- Search produk
- Pagination Bootstrap 5
- Validasi form dengan Form Request
- Akses modul hanya untuk owner

## Akun Default

| Role | Username | Password |
| --- | --- | --- |
| Owner | `owner` | `password` |
| Kasir | `kasir` | `password` |

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Setelah server berjalan:

- Login: `/login`
- Dashboard owner: `/owner/dashboard`
- Dashboard kasir: `/kasir/dashboard`
- Kategori owner: `/owner/categories`
- Produk owner: `/owner/products`


ILOVE PEM WEB
I LOVE PAK BANU
