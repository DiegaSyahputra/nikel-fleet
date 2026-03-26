# Nikel Fleet — Vehicle Booking System

Aplikasi web pemesanan kendaraan untuk perusahaan tambang nikel. Mendukung monitoring kendaraan, approval berjenjang 2 level, dashboard grafik pemakaian, dan export laporan ke Excel.

---

## Versi Teknologi

| Komponen | Versi                   |
| -------- | ----------------------- |
| PHP      | **8.1** atau lebih baru |
| Laravel  | **11.x / 12.**          |
| MySQL    | **8.0**                 |
| Composer | **2.x**                 |

**Package tambahan yang wajib diinstall:**

```bash
composer require maatwebsite/excel
```

---

## Akun Default

> Password semua akun: **`password123`**

| Nama         | Email                | Role     | Jabatan              | Region                 |
| ------------ | -------------------- | -------- | -------------------- | ---------------------- |
| Admin Pusat  | `admin@demo.com`     | admin    | Staff Pool Kendaraan | Kantor Pusat Jakarta   |
| Budi Santoso | `approver1@demo.com` | approver | Kepala Bagian Umum   | Kantor Pusat Jakarta   |
| Sari Dewi    | `approver2@demo.com` | approver | Manajer Operasional  | Kantor Pusat Jakarta   |
| Eko Prasetyo | `approver3@demo.com` | approver | Kepala Cabang        | Kantor Cabang Sulawesi |

**Perbedaan akses berdasarkan role:**

| Fitur                     | Admin     | Approver           |
| ------------------------- | --------- | ------------------ |
| Dashboard & grafik        | ✓         | ✓                  |
| Lihat daftar pemesanan    | ✓ (semua) | ✓ (hanya miliknya) |
| Buat & batalkan pemesanan | ✓         | —                  |
| Setujui / tolak pemesanan | —         | ✓                  |
| Master kendaraan          | ✓         | —                  |
| Master driver             | ✓         | —                  |
| Master region             | ✓         | —                  |
| Master user               | ✓         | —                  |
| Laporan & export Excel    | ✓         | —                  |

---

## Cara Instalasi Lokal

### 1. Clone repository

```bash
git clone https://github.com/username/nikel-fleet.git
cd nikel-fleet
```

Atau ekstrak ZIP yang diterima ke dalam folder project Laravel baru:

```bash
laravel new nikel-fleet
cd nikel-fleet
# salin semua file dari ZIP ke folder ini (timpa yang sudah ada)
```

### 2. Install dependency

```bash
composer install
composer require maatwebsite/excel
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, sesuaikan koneksi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_car
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat database

```bash
mysql -u root -p -e "CREATE DATABASE booking_car CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

Perintah ini akan membuat semua tabel dan mengisi data awal (region, user, kendaraan, driver).

### 6. Daftarkan middleware Role

**Laravel 12** — buka `bootstrap/app.php`, tambahkan di dalam `withMiddleware`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

**Laravel 10** — buka `app/Http/Kernel.php`, tambahkan di `$routeMiddleware`:

```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

### 7. Publish konfigurasi Excel (opsional)

```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

### 8. Jalankan aplikasi

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

Login dengan `admin@demo.com` / `password123`

---

## Panduan Penggunaan

### Sebagai Admin

#### Membuat pemesanan kendaraan

1. Login dengan akun admin
2. Klik menu **Pemesanan** di sidebar
3. Klik tombol **Buat Pemesanan**
4. Isi semua field yang wajib:
    - Tanggal & jam berangkat dan kembali
    - Tujuan dan keperluan perjalanan
    - Jumlah penumpang
5. Pilih kendaraan yang tersedia (hanya kendaraan berstatus _Tersedia_ yang muncul)
6. Pilih driver yang tersedia
7. Pilih **Approver Level 1** dan **Approver Level 2** — keduanya harus berbeda orang
8. Klik **Kirim Pemesanan**

Pemesanan langsung masuk ke antrian approval Level 1.

#### Mengelola master data

Semua menu master data tersedia di sidebar bagian **Master Data**:

- **Kendaraan** — tambah, edit, lihat detail & riwayat pemakaian
- **Driver** — tambah, edit, pantau status & kadaluarsa SIM
- **Region** — tambah lokasi (kantor pusat, cabang, tambang)
- **User** — kelola akun admin & approver

#### Melihat laporan dan export Excel

1. Klik menu **Laporan**
2. Atur filter: status pemesanan, tanggal dari–sampai
3. Klik **Export Excel** — file `.xlsx` otomatis terunduh
4. File Excel berisi: nomor, kode pemesanan, pemohon, kendaraan, driver, tujuan, keperluan, approver L1 & L2, status, tanggal

---

### Sebagai Approver

#### Menyetujui atau menolak pemesanan

1. Login dengan akun approver
2. Lihat **badge merah** di menu Persetujuan — menunjukkan jumlah yang menunggu
3. Klik menu **Persetujuan**
4. Klik tombol **Proses** pada pemesanan yang ingin ditangani
5. Baca detail pemesanan (kendaraan, driver, tujuan, tanggal)
6. Tambahkan catatan jika diperlukan (opsional)
7. Klik **Setujui** atau **Tolak**

---

## Alur Status Pemesanan

```
Admin buat pemesanan
        │
        ▼
  [pending_l1]  ──(L1 tolak)──►  [rejected]
        │
   L1 setuju
        │
        ▼
  [pending_l2]  ──(L2 tolak)──►  [rejected]
        │
   L2 setuju
        │
        ▼
   [approved]
```

Saat status berubah ke `approved`, status kendaraan otomatis berubah menjadi _Sedang Digunakan_ dan driver menjadi _Sedang Bertugas_.

Admin dapat membatalkan pemesanan (`cancelled`) selama statusnya masih `pending_l1` atau `pending_l2`.

---

## Struktur Folder

```
app/
├── Exports/
│   └── BookingsExport.php          ← export Excel dengan styling header
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│       |    └── AuthenticatedSessionController.php
│   │   ├── ApprovalController.php
│   │   ├── BookingController.php
│   │   ├── Controller.php
│   │   ├── DashboardController.php
│   │   ├── DriverController.php
│   │   ├── RegionController.php
│   │   ├── ReportController.php
│   │   └── UserController.php
│   │   ├── VehicleController.php
│   └── Middleware/
│       └── RoleMiddleware.php       ← guard role:admin
│   └── Requests/
        ├── Auth/
│           └── LoginRequest.php
├── Models/
│   ├── Booking.php                  ← generateCode(), getStatusLabel(), isPendingFor()
│   ├── BookingApproval.php
│   ├── Driver.php                   ← isLicenseExpiringSoon()
│   ├── Region.php                   ← getTypeLabel()
│   ├── User.php                     ← isAdmin(), pendingApprovals()
│   └── Vehicle.php                  ← getStatusLabel(), getStatusBadgeClass()
└── Providers/
|    └── AppServiceProvider.php
└── Services/
    └── ApprovalService.php          ← logika inti approval berjenjang

database/
├── migrations/
│   ├── ..._create_regions_table.php
│   ├── ..._create_users_table.php
│   ├── ..._create_vehicles_table.php
│   ├── ..._create_drivers_table.php
│   ├── ..._create_bookings_table.php
│   └── ..._create_booking_approvals_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── approvals/index.blade.php
├── auth/login.blade.php
├── bookings/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── show.blade.php               ← timeline approval terintegrasi
├── dashboard/index.blade.php        ← grafik Chart.js (bulanan + per kendaraan)
├── drivers/{index,create,edit,show,_form}.blade.php
├── layouts/app.blade.php            ← sidebar navigasi utama
├── regions/{index,create,edit,show,_form}.blade.php
├── reports/index.blade.php
└── users/{index,create,edit,show,_form}.blade.php
├── vehicles/{index,create,edit,show,_form}.blade.php

routes/auth.php
routes/web.php
```

---

## Daftar Fitur

- [x] Autentikasi dengan 2 role — admin dan approver
- [x] Dashboard dengan grafik pemesanan bulanan dan pemakaian per kendaraan (Chart.js)
- [x] Pemesanan kendaraan oleh admin — pilih kendaraan, driver, dan 2 approver
- [x] Approval berjenjang 2 level dengan catatan dan timestamp
- [x] Approver dapat setujui / tolak melalui aplikasi
- [x] Badge notifikasi jumlah approval yang menunggu di sidebar
- [x] Laporan periodik pemesanan dengan filter status dan tanggal
- [x] Export laporan ke Excel dengan styling header (Maatwebsite/Laravel-Excel)
- [x] Master data kendaraan — CRUD, filter, riwayat pemakaian, cek relasi sebelum hapus
- [x] Master data driver — CRUD, peringatan SIM hampir kadaluarsa, cek relasi sebelum hapus
- [x] Master data region — CRUD, counter isi per region, cek relasi sebelum hapus
- [x] Master data user — CRUD, password opsional saat edit, proteksi hapus akun aktif

---

## Catatan Teknis

**Kendaraan & driver tidak bisa dihapus** jika masih terkait dengan pemesanan aktif (`pending_l1`, `pending_l2`, `approved`).

**Region tidak bisa dihapus** selama masih ada user, kendaraan, atau driver yang terdaftar di sana.

**User tidak bisa dihapus** jika masih memiliki riwayat pemesanan atau persetujuan. Admin juga tidak dapat menghapus akunnya sendiri.

**Password saat edit user** — jika field password dikosongkan, password lama tetap digunakan.

**Kode pemesanan** dibuat otomatis dengan format `BK-YYYYMM-XXXX`, contoh: `BK-202412-0001`.

**Peringatan SIM kadaluarsa** muncul otomatis di halaman daftar driver jika ada SIM yang akan habis dalam 30 hari ke depan.

## Penutup

Aplikasi ini dikembangkan sebagai bagian dari technical test untuk posisi Fullstack Developer Intern. Fokus utama implementasi adalah pada fitur inti sistem, yaitu pemesanan kendaraan dan approval berjenjang, dengan pendekatan sederhana namun tetap memperhatikan struktur, konsistensi, dan best practice pengembangan aplikasi web.

Dengan pengembangan lebih lanjut, sistem ini dapat ditingkatkan menjadi solusi manajemen vehicle yang lebih kompleks dan terintegrasi.
