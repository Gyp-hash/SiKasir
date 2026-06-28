# 🍢 SiKasir Angkringan — Smart Business Management

## Deskripsi Singkat

**SiKasir Angkringan** adalah aplikasi web berbasis **Point of Sale (POS)** yang dirancang khusus untuk mengelola bisnis angkringan secara digital dan efisien. Aplikasi ini menyediakan sistem manajemen lengkap mulai dari pencatatan transaksi penjualan, pengelolaan produk & kategori, manajemen stok, pencatatan pengeluaran, hingga pembuatan laporan bisnis dalam format PDF dan Excel.

Aplikasi ini memiliki **dua peran pengguna** yang dibedakan aksesnya:

- **Owner (Pemilik Usaha)** — Mengelola seluruh aspek bisnis: produk, kategori, stok, pengeluaran, dan laporan.
- **Kasir** — Melakukan transaksi penjualan melalui sistem POS dan melihat riwayat transaksi.

Sistem dilengkapi dengan fitur autentikasi berbasis **username**, middleware role-based access control, serta validasi pengguna aktif untuk menjaga keamanan akses.

---

## Teknologi yang Digunakan

| Komponen         | Teknologi / Versi                                 |
| ---------------- | ------------------------------------------------- |
| **Bahasa**       | PHP 8.3                                           |
| **Framework**    | Laravel 12                                        |
| **Database**     | MySQL                                             |
| **Frontend**     | Blade Template Engine, Bootstrap 5, CSS3           |
| **JavaScript**   | Vanilla JS (Client-Side Scripting & DOM Manipulation) |
| **Charting**     | Chart.js (grafik penjualan & pengeluaran)         |
| **PDF Export**   | barryvdh/laravel-dompdf 3.1                       |
| **Excel Export** | maatwebsite/excel 3.1                             |
| **Session**      | Database-driven session                           |
| **Hosting**      | Railway (HTTPS)                                   |

---

## Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login berbasis **username** (bukan email)
- Logout dengan invalidasi session
- **Middleware Role** — Membatasi akses berdasarkan peran (Owner / Kasir)
- **Middleware Active User** — Memblokir pengguna yang dinonaktifkan
- Redirect otomatis ke dashboard sesuai role

### 👑 Modul Owner
- **Dashboard** — Statistik penjualan hari ini & bulan ini, pengeluaran, laba bersih estimasi, grafik 7 hari terakhir (Chart.js), top 5 produk terlaris, produk stok rendah, aktivitas terbaru
- **CRUD Kategori** — Create, Read, Update, Delete kategori produk
- **CRUD Produk** — Manajemen produk lengkap dengan upload gambar, harga jual, harga modal, stok, stok minimum, status aktif/nonaktif
- **Manajemen Stok** — Riwayat pergerakan stok (IN/OUT/ADJUSTMENT), fitur restock produk
- **Pencatatan Pengeluaran** — Catat pengeluaran bisnis dengan 8 kategori (Bahan Baku, Operasional, Gaji, Sewa, dll.)
- **Laporan** — Laporan penjualan, pengeluaran, dan stok dengan filter tanggal, export PDF & Excel

### 💳 Modul Kasir
- **Dashboard** — Ringkasan transaksi kasir hari ini
- **Point of Sale (POS)** — Interface penjualan dengan keranjang belanja (session-based), search produk, validasi stok real-time, diskon, pembayaran tunai, dan kembalian otomatis
- **Riwayat Transaksi** — Daftar transaksi yang dilakukan oleh kasir, detail transaksi dengan struk

### 📊 Laporan & Export
- Laporan Penjualan (filter tanggal, export PDF/Excel)
- Laporan Pengeluaran (filter tanggal & kategori, export PDF/Excel)
- Laporan Stok (filter tanggal, produk & tipe pergerakan, export PDF/Excel)

---

## Cara Mengakses Website

Website sudah **di-deploy dan di-hosting** melalui **Railway** dengan HTTPS aktif. Untuk mencoba langsung, kunjungi:

🔗 **https://sikasir-production-85a3.up.railway.app/login**

### Akun Default

| Role    | Username | Password   |
| ------- | -------- | ---------- |
| Owner   | `owner`  | `password` |
| Kasir   | `kasir`  | `password` |

### Halaman yang Tersedia

| Halaman              | URL Path                     |
| -------------------- | ---------------------------- |
| Login                | `/login`                     |
| Dashboard Owner      | `/owner/dashboard`           |
| Kategori (Owner)     | `/owner/categories`          |
| Produk (Owner)       | `/owner/products`            |
| Stok (Owner)         | `/owner/stock`               |
| Pengeluaran (Owner)  | `/owner/expenses`            |
| Laporan Penjualan    | `/owner/reports/sales`       |
| Laporan Pengeluaran  | `/owner/reports/expenses`    |
| Laporan Stok         | `/owner/reports/stocks`      |
| Dashboard Kasir      | `/kasir/dashboard`           |
| POS (Kasir)          | `/kasir/pos`                 |
| Riwayat Transaksi    | `/kasir/transactions`        |

### Menjalankan di Lokal (Opsional)

Jika ingin menjalankan secara lokal, pastikan sudah terinstall PHP ≥ 8.3, Composer, dan MySQL:

```bash
git clone <repository-url> sikasir-angkringan
cd sikasir-angkringan
composer install
cp .env.example .env
php artisan key:generate
# Buat database MySQL: sikasir_angkringan, lalu sesuaikan .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

## Struktur Direktori Project

```
sikasir-angkringan/
├── app/
│   ├── Exports/                          # Export Excel (Maatwebsite)
│   │   ├── ExpensesExport.php            #   Export laporan pengeluaran
│   │   ├── SalesExport.php               #   Export laporan penjualan
│   │   └── StocksExport.php              #   Export laporan stok
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php   #   Login & logout
│   │   │   ├── Kasir/
│   │   │   │   ├── DashboardController.php   # Dashboard kasir
│   │   │   │   ├── PosController.php         # Point of Sale (POS)
│   │   │   │   └── TransactionHistoryController.php  # Riwayat transaksi
│   │   │   ├── Owner/
│   │   │   │   ├── CategoryController.php    # CRUD kategori
│   │   │   │   ├── DashboardController.php   # Dashboard owner + statistik
│   │   │   │   ├── ExpenseController.php     # Manajemen pengeluaran
│   │   │   │   ├── ProductController.php     # CRUD produk
│   │   │   │   ├── ReportController.php      # Laporan + export PDF/Excel
│   │   │   │   └── StockMovementController.php # Manajemen stok
│   │   │   └── Controller.php            # Base controller
│   │   ├── Middleware/
│   │   │   ├── EnsureUserIsActive.php    # Cek user aktif / nonaktif
│   │   │   └── RoleMiddleware.php        # Cek role (owner/kasir)
│   │   └── Requests/
│   │       ├── CategoryRequest.php       # Validasi form kategori
│   │       └── ProductRequest.php        # Validasi form produk
│   ├── Models/
│   │   ├── Category.php                  # Model kategori produk
│   │   ├── Expense.php                   # Model pengeluaran
│   │   ├── Product.php                   # Model produk
│   │   ├── StockMovement.php             # Model pergerakan stok
│   │   ├── Transaction.php               # Model transaksi
│   │   ├── TransactionDetail.php         # Model detail transaksi
│   │   └── User.php                      # Model user (role: owner/kasir)
│   ├── Providers/
│   │   └── AppServiceProvider.php        # HTTPS force, Bootstrap pagination
│   └── Services/
│       └── TransactionService.php        # Business logic transaksi & checkout
├── bootstrap/
│   └── app.php                           # Middleware registration, trust proxies
├── config/                               # Konfigurasi aplikasi
│   ├── app.php                           #   Config utama (timezone, locale)
│   ├── auth.php                          #   Config autentikasi
│   ├── database.php                      #   Config koneksi database
│   ├── filesystems.php                   #   Config filesystem & storage
│   ├── session.php                       #   Config session (database driver)
│   └── ...                               #   Config lainnya
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php               # Tabel users
│   │   ├── create_cache_table.php               # Tabel cache
│   │   ├── create_sessions_table.php            # Tabel sessions
│   │   ├── create_categories_table.php          # Tabel kategori
│   │   ├── create_products_table.php            # Tabel produk
│   │   ├── create_transactions_table.php        # Tabel transaksi
│   │   ├── create_transaction_details_table.php # Tabel detail transaksi
│   │   ├── create_stock_movements_table.php     # Tabel pergerakan stok
│   │   └── create_expenses_table.php            # Tabel pengeluaran
│   └── seeders/
│       ├── DatabaseSeeder.php            # Seeder utama
│       ├── DemoDataSeeder.php            # Data demo (6 kategori, 28 produk)
│       └── UserSeeder.php                # Seeder akun default (owner & kasir)
├── public/
│   ├── index.php                         # Entry point aplikasi
│   └── storage/                          # Symlink ke storage (gambar produk)
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php           # Halaman login
│       ├── kasir/
│       │   ├── dashboard.blade.php       # Dashboard kasir
│       │   ├── pos/
│       │   │   └── index.blade.php       # Halaman POS kasir
│       │   └── transactions/
│       │       ├── index.blade.php       # Daftar riwayat transaksi
│       │       └── show.blade.php        # Detail / struk transaksi
│       ├── layouts/
│       │   └── app.blade.php             # Layout utama (navbar, sidebar)
│       └── owner/
│           ├── categories/
│           │   ├── create.blade.php      # Form tambah kategori
│           │   ├── edit.blade.php        # Form edit kategori
│           │   ├── form.blade.php        # Partial form kategori
│           │   └── index.blade.php       # Daftar kategori
│           ├── dashboard.blade.php       # Dashboard owner
│           ├── expenses/
│           │   ├── create.blade.php      # Form tambah pengeluaran
│           │   ├── index.blade.php       # Daftar pengeluaran
│           │   └── show.blade.php        # Detail pengeluaran
│           ├── products/
│           │   ├── create.blade.php      # Form tambah produk
│           │   ├── edit.blade.php        # Form edit produk
│           │   ├── form.blade.php        # Partial form produk
│           │   └── index.blade.php       # Daftar produk
│           ├── reports/
│           │   ├── expenses.blade.php    # Laporan pengeluaran
│           │   ├── pdf/
│           │   │   ├── expenses.blade.php # Template PDF pengeluaran
│           │   │   ├── sales.blade.php   # Template PDF penjualan
│           │   │   └── stocks.blade.php  # Template PDF stok
│           │   ├── sales.blade.php       # Laporan penjualan
│           │   └── stocks.blade.php      # Laporan stok
│           └── stock/
│               ├── index.blade.php       # Riwayat pergerakan stok
│               └── restock.blade.php     # Form restock produk
├── routes/
│   ├── console.php                       # Console routes
│   └── web.php                           # Web routes (semua endpoint)
├── storage/                              # File storage (upload gambar, logs)
├── vendor/                               # Dependency Composer
├── .env.example                          # Template konfigurasi environment
├── .gitignore                            # File yang diabaikan Git
├── .php-version                          # Versi PHP (8.3)
├── artisan                               # CLI Laravel
├── composer.json                         # Dependency & metadata project
└── composer.lock                         # Lock file dependency
```

---

## Skema Database (ERD)

Aplikasi ini menggunakan **7 tabel utama** dengan relasi sebagai berikut:

```
┌──────────────┐       ┌──────────────────┐       ┌───────────────┐
│    users     │       │   categories     │       │   products    │
├──────────────┤       ├──────────────────┤       ├───────────────┤
│ id           │       │ id               │       │ id            │
│ name         │       │ name             │──1:N──│ category_id   │
│ username     │       │ description      │       │ name          │
│ password     │       │ timestamps       │       │ image         │
│ role         │       └──────────────────┘       │ selling_price │
│ is_active    │                                  │ capital_price │
│ timestamps   │──1:N──┐                          │ stock         │
└──────────────┘       │                          │ minimum_stock │
       │               │                          │ status        │
       │1:N            │                          │ timestamps    │
       │               │                          └───────────────┘
       ▼               ▼                                 │
┌──────────────┐  ┌───────────────────┐                  │
│ transactions │  │  stock_movements  │                  │
├──────────────┤  ├───────────────────┤                  │
│ id           │  │ id                │                  │
│ code         │  │ product_id   ◄────┼──────────────────┘
│ user_id      │  │ type (IN/OUT/ADJ) │          │
│ trans_date   │  │ quantity          │          │
│ subtotal     │  │ stock_before      │          │1:N
│ discount     │  │ stock_after       │          │
│ total        │  │ notes             │          ▼
│ payment_mthd │  │ created_by        │  ┌────────────────────┐
│ cash_paid    │  │ timestamps        │  │ transaction_details│
│ change       │  └───────────────────┘  ├────────────────────┤
│ status       │                         │ id                 │
│ timestamps   │──1:N───────────────────▶│ transaction_id     │
└──────────────┘                         │ product_id         │
                                         │ quantity           │
┌──────────────┐                         │ price              │
│   expenses   │                         │ subtotal           │
├──────────────┤                         │ timestamps         │
│ id           │                         └────────────────────┘
│ expense_date │
│ category     │
│ description  │
│ amount       │
│ created_by   │
│ timestamps   │
└──────────────┘
```

---

## Daftar API Endpoint (Routes)

### Autentikasi
| Method | URI              | Aksi              |
| ------ | ---------------- | ------------------ |
| GET    | `/login`         | Halaman login      |
| POST   | `/login`         | Proses login       |
| POST   | `/logout`        | Proses logout      |

### Owner
| Method | URI                            | Aksi                          |
| ------ | ------------------------------ | ----------------------------- |
| GET    | `/owner/dashboard`             | Dashboard owner               |
| GET    | `/owner/categories`            | Daftar kategori               |
| GET    | `/owner/categories/create`     | Form tambah kategori          |
| POST   | `/owner/categories`            | Simpan kategori baru          |
| GET    | `/owner/categories/{id}/edit`  | Form edit kategori            |
| PUT    | `/owner/categories/{id}`       | Update kategori               |
| DELETE | `/owner/categories/{id}`       | Hapus kategori                |
| GET    | `/owner/products`              | Daftar produk                 |
| GET    | `/owner/products/create`       | Form tambah produk            |
| POST   | `/owner/products`              | Simpan produk baru            |
| GET    | `/owner/products/{id}/edit`    | Form edit produk              |
| PUT    | `/owner/products/{id}`         | Update produk                 |
| DELETE | `/owner/products/{id}`         | Hapus produk                  |
| GET    | `/owner/stock`                 | Riwayat pergerakan stok       |
| GET    | `/owner/stock/restock`         | Form restock                  |
| POST   | `/owner/stock/restock`         | Proses restock                |
| GET    | `/owner/expenses`              | Daftar pengeluaran            |
| GET    | `/owner/expenses/create`       | Form tambah pengeluaran       |
| POST   | `/owner/expenses`              | Simpan pengeluaran            |
| GET    | `/owner/expenses/{id}`         | Detail pengeluaran            |
| GET    | `/owner/reports/sales`         | Laporan penjualan             |
| GET    | `/owner/reports/expenses`      | Laporan pengeluaran           |
| GET    | `/owner/reports/stocks`        | Laporan stok                  |
| GET    | `/owner/reports/sales/pdf`     | Export PDF penjualan          |
| GET    | `/owner/reports/expenses/pdf`  | Export PDF pengeluaran        |
| GET    | `/owner/reports/stocks/pdf`    | Export PDF stok               |
| GET    | `/owner/reports/sales/excel`   | Export Excel penjualan        |
| GET    | `/owner/reports/expenses/excel`| Export Excel pengeluaran      |
| GET    | `/owner/reports/stocks/excel`  | Export Excel stok             |

### Kasir
| Method | URI                               | Aksi                        |
| ------ | --------------------------------- | --------------------------- |
| GET    | `/kasir/dashboard`                | Dashboard kasir             |
| GET    | `/kasir/pos`                      | Halaman POS                 |
| POST   | `/kasir/pos/cart/{product}`       | Tambah produk ke keranjang  |
| PATCH  | `/kasir/pos/cart/{product}`       | Update quantity keranjang   |
| DELETE | `/kasir/pos/cart/{product}`       | Hapus item dari keranjang   |
| POST   | `/kasir/pos/checkout`             | Proses checkout transaksi   |
| GET    | `/kasir/transactions`             | Riwayat transaksi           |
| GET    | `/kasir/transactions/{id}`        | Detail transaksi / struk    |

---

## Lisensi

MIT License
