# Oil Management System - Dokumentasi Lengkap

Sistem manajemen inventori berbasis web yang dibangun dengan **Laravel**, dirancang untuk mengelola stok bahan baku, transaksi inventori, lokasi gudang, dan perpindahan barang antar gudang dalam lingkungan industri minyak.

---

## Daftar Isi

1. [Arsitektur Sistem](#arsitektur-sistem)
2. [Instalasi & Konfigurasi](#instalasi--konfigurasi)
3. [Struktur Database](#struktur-database)
4. [Models](#models)
5. [Controllers](#controllers)
6. [Services](#services)
7. [Repositories](#repositories)
8. [Routes (Routing)](#routes-routing)
9. [Views (Tampilan)](#views-tampilan)
10. [Interfaces](#interfaces)
11. [Alur Bisnis Utama](#alur-bisnis-utama)
12. [Hak Akses (Role)](#hak-akses-role)

---

## Arsitektur Sistem

Sistem ini menggunakan pola arsitektur **Repository Pattern** dengan **Service Layer** untuk memisahkan logika bisnis dari akses data:

```
Request → Controller → Service → Repository → Model → Database
```

- **Controller** : Menerima HTTP request, validasi input, dan mengembalikan response
- **Service** : Logika bisnis utama (kalkulasi stok, validasi, dll.)
- **Repository** : Abstraksi akses database (query Eloquent)
- **Model** : Representasi tabel database dan relasi
- **Interface** : Kontrak yang harus diimplementasikan oleh Service dan Repository

---

## Instalasi & Konfigurasi

```bash
# Clone repository
git clone <url>

# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install

# Salin file environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Build assets frontend
npm run dev
```

---

## Struktur Database

### Tabel `users`
Menyimpan data pengguna sistem.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key auto-increment |
| name | varchar | Nama lengkap pengguna |
| email | varchar(unique) | Email untuk login |
| email_verified_at | timestamp | Waktu verifikasi email |
| password | varchar | Password yang sudah di-hash |
| orgn_code | varchar | Kode organisasi/divisi |
| gander | varchar | Jenis kelamin |
| mobile | varchar | Nomor HP |
| designation | varchar | Jabatan |
| image | varchar | Path foto profil |
| status | boolean | Status aktif/nonaktif |
| department_id | bigint (FK) | ID departemen |
| remember_token | varchar | Token remember me |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `departments`
Menyimpan data departemen organisasi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| name | varchar | Nama departemen |
| code | varchar | Kode unik departemen |
| deleted_at | timestamp | Soft delete |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `warehouses`
Menyimpan data gudang/lokasi penyimpanan fisik.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| name | varchar | Nama gudang |
| tag | varchar | Tag/label gudang |
| department_id | bigint (FK) | Departemen yang memiliki gudang |
| deleted_at | timestamp | Soft delete |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `ic_item_mst`
Master data item/barang inventori.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| item_id | integer | ID item unik |
| item_no | varchar(30) | Nomor kode item |
| item_desc | varchar(100) | Deskripsi/nama item |
| orgn_code | varchar(10) | Kode organisasi pemilik |
| item_uom | varchar(10) | Satuan unit (kg, liter, pcs, dll.) |
| inactive_ind | integer | 0=aktif, 1=nonaktif |
| item_glclass | varchar(20) | Kelas GL (General Ledger) |
| item_usedby | varchar(20) | Digunakan oleh divisi mana |
| current_stock | double | Stok saat ini (dikalkulasi otomatis) |
| department_id | bigint (FK) | Departemen pemilik item |
| deleted_at | timestamp | Soft delete |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `ic_trans_inv`
Menyimpan semua transaksi pergerakan inventori.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| request_id | varchar | ID referensi dokumen sumber |
| trans_code | varchar | Kode unik transaksi (auto-generate) |
| item_id | integer (FK) | ID item yang terlibat |
| item_no | varchar | Nomor item (disalin dari master) |
| item_desc | varchar | Nama item (disalin dari master) |
| item_uom | varchar | Satuan item |
| orgn_code | varchar(10) | Kode organisasi |
| warehouse_id | bigint | ID gudang |
| whse_code | varchar | Kode gudang |
| whse_loc | varchar | Lokasi di dalam gudang |
| warehouse_tag | varchar | Tag gudang |
| doc_type | varchar | Jenis dokumen: PORC/CONS/ADJI/TRANSFER |
| adj_type | varchar | Tipe adjustment: CONS/PORC |
| reason_code | varchar | Kode alasan transaksi |
| creation_date | datetime | Tanggal pembuatan |
| trans_date | date | Tanggal transaksi |
| tgl | varchar(2) | Hari dari trans_date |
| bln | varchar(2) | Bulan dari trans_date |
| thn | varchar(4) | Tahun dari trans_date |
| periode | varchar(20) | Format periode "YYYY-MM" |
| trans_qty | double | Kuantitas transaksi (input user) |
| catatan | text | Catatan/keterangan |
| bb_qty | double | Saldo awal (Beginning Balance) |
| in_qty | double | Kuantitas masuk |
| out_qty | double | Kuantitas keluar |
| eb_qty | double | Saldo akhir (Ending Balance) |
| created_by | varchar | Username pembuat |
| update_date | datetime | Tanggal update |
| update_by | varchar | Username yang update |
| status | varchar(20) | Status: NEW/UPDATED/deleted |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `item_locations`
Menyimpan data lokasi fisik stok barang (per batch/lot vendor).

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| item_id | bigint (FK) | ID item dari ic_item_mst |
| warehouse_id | bigint (FK) | ID gudang penyimpanan |
| orgn_code | varchar | Kode organisasi |
| vendor_lot | varchar | Nomor lot dari vendor |
| production_date | date | Tanggal produksi barang |
| exp_date | date | Tanggal kadaluarsa (+1 tahun dari produksi) |
| type | varchar | Tipe penyimpanan |
| received_date | date | Tanggal barang diterima |
| package | varchar | Tipe kemasan |
| qty_unit | double | Kuantitas dalam unit |
| qty_weight | double | Kuantitas dalam berat (kg) |
| notes | text | Catatan tambahan |
| deleted_at | timestamp | Soft delete |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

### Tabel `transfer_requests`
Menyimpan permintaan perpindahan barang antar gudang.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| item_id | bigint (FK) | ID item yang diminta |
| requested_qty | double | Jumlah yang diminta |
| source_warehouse_id | bigint (FK) | Gudang asal |
| destination_warehouse_id | bigint (FK) | Gudang tujuan |
| department_id | bigint (FK) | Departemen peminta |
| requested_by | bigint (FK→users) | User yang membuat permintaan |
| status | varchar | Status: pending/approved/rejected |
| requested_date | date | Tanggal permintaan |
| notes | text | Catatan |
| deleted_at | timestamp | Soft delete |
| created_at / updated_at | timestamp | Waktu buat/ubah |

---

## Models

### `User` — `app/Models/User.php`

Model pengguna sistem yang mendukung autentikasi Laravel.

**Fillable fields:** `name`, `email`, `password`, `orgn_code`, `gander`, `mobile`, `designation`, `imgae`, `status`, `department_id`

**Casts:**
- `email_verified_at` → `datetime`
- `password` → `hashed` (otomatis di-hash saat disimpan)

**Relationships:**
```php
// Setiap user dimiliki oleh satu departemen
public function department(): BelongsTo
```
Relasi ke model `Departments` melalui kolom `department_id`

---

### `Departments` — `app/Models/Departments.php`

Model untuk data departemen/divisi organisasi.

**Table:** `departments`
**Traits:** `SoftDeletes` (data tidak benar-benar dihapus, hanya `deleted_at` diisi)

**Fillable fields:** `name`, `code`

**Relationships:**
```php
// Satu departemen memiliki banyak user
public function users(): HasMany

// Satu departemen memiliki banyak gudang
public function warehouses(): HasMany

// Satu departemen memiliki banyak transfer request
public function transfer_requests(): HasMany
```

---

### `Warehouses` — `app/Models/Warehouses.php`

Model untuk data gudang penyimpanan fisik.

**Table:** `warehouses`
**Traits:** `SoftDeletes`

**Fillable fields:** `name`, `tag`, `department_id`

**Relationships:**
```php
// Gudang dimiliki oleh satu departemen
public function department(): BelongsTo

// Gudang memiliki banyak lokasi item
public function item_locations(): HasMany

// Gudang sebagai sumber transfer request
public function source_warehouse(): HasMany  // via source_warehouse_id

// Gudang sebagai tujuan transfer request
public function destination_warehouse(): HasMany  // via destination_warehouse_id
```

---

### `IcItemMst` — `app/Models/IcItemMst.php`

Model master data inventori (item/barang).

**Table:** `ic_item_mst`
**Traits:** `SoftDeletes`

**Fillable fields:** `item_id`, `item_no`, `item_desc`, `orgn_code`, `item_uom`, `inactive_ind`, `item_glclass`, `item_usedby`, `current_stock`, `department_id`

**Casts:**
- `item_id` → `integer`
- `inactive_ind` → `integer`
- `current_stock` → `double`

**Relationships:**
```php
// Item memiliki banyak transaksi
public function transaction(): HasMany  // → IcTransInv via item_id

// Item memiliki banyak lokasi penyimpanan
public function itemLocations(): HasMany  // → ItemLocations via item_id

// Item dimiliki oleh satu departemen
public function department(): BelongsTo  // → Departments via department_id
```

---

### `IcTransInv` — `app/Models/IcTransInv.php`

Model untuk setiap record transaksi inventori (masuk/keluar/penyesuaian).

**Table:** `ic_trans_inv`

**Fillable fields:** Semua kolom kecuali `id` (lihat tabel database di atas)

**Casts:**
- `item_id` → `integer`
- `creation_date`, `update_date` → `datetime`
- `trans_date` → `date`
- `trans_qty`, `bb_qty`, `in_qty`, `out_qty`, `eb_qty` → `double`

**Relationships:**
```php
// Transaksi dimiliki oleh satu item
public function item(): BelongsTo  // → IcItemMst via item_id
```

---

### `ItemLocations` — `app/Models/ItemLocations.php`

Model untuk lokasi fisik penyimpanan stok per batch/lot.

**Table:** `item_locations`
**Traits:** `SoftDeletes`

**Fillable fields:** `item_id`, `warehouse_id`, `orgn_code`, `vendor_lot`, `production_date`, `exp_date`, `type`, `qty_unit`, `received_date`, `package`, `qty_weight`, `notes`

**Casts:**
- `production_date`, `received_date`, `exp_date` → `date`
- `qty_unit`, `qty_weight` → `double`

**Relationships:**
```php
// Lokasi dimiliki oleh satu item
public function item(): BelongsTo  // → IcItemMst via item_id

// Lokasi ada di satu gudang
public function warehouse(): BelongsTo  // → Warehouses via warehouse_id
```

---

### `TransferRequests` — `app/Models/TransferRequests.php`

Model untuk permintaan pemindahan barang antar gudang.

**Table:** `transfer_requests`
**Traits:** `SoftDeletes`

**Fillable fields:** `item_id`, `requested_qty`, `source_warehouse_id`, `destination_warehouse_id`, `department_id`, `requested_by`, `status`, `requested_date`, `notes`

**Relationships:**
```php
// Item yang diminta
public function item(): BelongsTo  // → IcItemMst

// Gudang asal barang
public function source_warehouse(): BelongsTo  // → Warehouses via source_warehouse_id

// Gudang tujuan barang
public function destination_warehouse(): BelongsTo  // → Warehouses via destination_warehouse_id

// Departemen peminta
public function department(): BelongsTo  // → Departments

// User yang membuat permintaan
public function requester(): BelongsTo  // → User via requested_by
```

---

## Controllers

### `DashbaordController` — `app/Http/Controllers/DashbaordController.php`

Mengelola halaman dashboard utama dengan tampilan analitik dan statistik inventori.

---

#### `index(DashboardRequest $request)`

**Tujuan:** Menampilkan dashboard dengan ringkasan statistik dan grafik top konsumsi.

**Parameter:**
- `$request` (DashboardRequest) — Request tervalidasi yang mengandung `month` (bulan) dan `year` (tahun) sebagai filter.

**Proses:**
1. Mengambil nilai `month` dan `year` dari request (default: bulan dan tahun saat ini).
2. Memanggil `DashboardService::getSummary()` untuk mendapatkan:
   - `total_item` — jumlah total item aktif
   - `total_consumption` — total barang keluar bulan ini
   - `total_receipt` — total barang masuk bulan ini
3. Memanggil `DashboardService::getTop10Consumption()` — 10 item dengan konsumsi tertinggi bulan ini.
4. Memanggil `DashboardService::getItemsWithConsumption()` — semua item beserta total konsumsi dan penerimaannya.
5. Mengembalikan view `pages/dashboard` dengan semua data tersebut.

**Return:** `View` — `pages/dashboard`

---

### `UserController` — `app/Http/Controllers/UserController.php`

Mengelola CRUD data pengguna. Hanya dapat diakses oleh **admin**.

---

#### `index()`

**Tujuan:** Menampilkan daftar semua pengguna.

**Proses:** Memanggil `UserService::getAll()` lalu mengirim data ke view.

**Return:** `View` — `pages/User/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan pengguna baru.

**Return:** `View` — `pages/User/create`

---

#### `store(StoreUserRequest $request)`

**Tujuan:** Menyimpan pengguna baru ke database.

**Parameter:**
- `$request` (StoreUserRequest) — Request tervalidasi dengan field: `name`, `email`, `password`, `orgn_code`, dll.

**Proses:**
1. Mengambil semua data dari request yang tervalidasi.
2. Hash password menggunakan `bcrypt()`.
3. Memanggil `UserService::create()` dengan data yang sudah diproses.
4. Redirect ke halaman daftar user dengan pesan sukses.

**Return:** `Redirect` → `/users`

---

#### `show(string $id)`

**Tujuan:** Menampilkan detail satu pengguna.

**Parameter:** `$id` — ID pengguna.

**Proses:** Memanggil `UserService::getById($id)` dan mengirim data ke view.

**Return:** `View` — `pages/User/show`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit pengguna.

**Parameter:** `$id` — ID pengguna.

**Proses:** Mengambil data pengguna via service, mengirim ke view edit.

**Return:** `View` — `pages/User/edit`

---

#### `update(UpdateUserRequest $request, string $id)`

**Tujuan:** Memperbarui data pengguna.

**Parameter:**
- `$request` (UpdateUserRequest) — Data yang sudah tervalidasi.
- `$id` — ID pengguna yang akan diupdate.

**Proses:**
1. Mengambil semua data dari request.
2. Jika ada password baru, hash terlebih dahulu.
3. Jika tidak ada password baru, hapus field password dari data update.
4. Memanggil `UserService::update()`.
5. Redirect ke daftar user dengan pesan sukses.

**Return:** `Redirect` → `/users`

---

#### `destroy(string $id)`

**Tujuan:** Menonaktifkan pengguna (soft delete via status flag).

**Parameter:** `$id` — ID pengguna.

**Proses:** Memanggil `UserService::delete($id)`. Repository mengubah `status` menjadi `false` (bukan delete sungguhan).

**Return:** `Redirect` → `/users`

---

### `ItemMasterController` — `app/Http/Controllers/ItemMasterController.php`

Mengelola master data item/barang inventori.

---

#### `index()`

**Tujuan:** Menampilkan daftar item sesuai organisasi user yang login.

**Proses:**
1. Mengambil `department_id` dari user yang sedang login.
2. Memanggil `ItemMasterService::getByOrgnCode($department_id)`.
   - Jika `department_id = '1'` (admin/IT), tampilkan semua item.
   - Jika departemen lain, filter hanya item milik departemen tersebut.
3. Mengirim data ke view.

**Return:** `View` — `pages/Item_Master/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan item baru.

**Return:** `View` — `pages/Item_Master/create`

---

#### `store(StoreItemRequest $request)`

**Tujuan:** Menyimpan item baru ke database.

**Proses:**
1. Mengambil data dari request yang tervalidasi.
2. Set `inactive_ind = 0` (item aktif secara default).
3. Memanggil `ItemMasterService::create()`.
4. Redirect ke index dengan pesan sukses.

**Return:** `Redirect` → `/item-master`

---

#### `show(string $id)`

**Tujuan:** Menampilkan detail satu item.

**Return:** `View` — `pages/Item_Master/show`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit item.

**Return:** `View` — `pages/Item_Master/edit`

---

#### `update(UpdateItemRequest $request, string $id)`

**Tujuan:** Memperbarui data item.

**Return:** `Redirect` → `/item-master`

---

#### `destroy(string $id)`

**Tujuan:** Soft-delete item.

**Return:** `Redirect` → `/item-master`

---

#### `detail($id, Request $request)`

**Tujuan:** Menampilkan riwayat transaksi bulanan untuk satu item secara detail.

**Parameter:**
- `$id` — ID item.
- `$request` — Mengandung optional `month` dan `year` sebagai filter.

**Proses:**
1. Mengambil `month` (default: bulan saat ini) dan `year` (default: tahun saat ini) dari request.
2. Memanggil `ItemMasterService::getTransactionByMonth($id, $month, $year)`.
3. Mengirim data transaksi yang sudah dikelompokkan per tanggal ke view.

**Return:** `View` — `pages/Item_Master/detail`

---

### `WarehousesController` — `app/Http/Controllers/WarehousesController.php`

Mengelola master data gudang. Hanya dapat diakses oleh **admin**.

---

#### `index()`

**Tujuan:** Menampilkan daftar semua gudang.

**Proses:** Memanggil `WarehouseService::getAll()`, log akses, kirim ke view.

**Return:** `View` — `pages/warehouse/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan gudang baru.

**Proses:** Mengambil semua departemen via `DepartmentService::getAll()` untuk dropdown.

**Return:** `View` — `pages/warehouse/create`

---

#### `store(StoreWarehouseRequest $request)`

**Tujuan:** Menyimpan gudang baru.

**Proses:** Validasi → `WarehouseService::create()` → Log transaksi → Redirect.

**Return:** `Redirect` → `/warehouses`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit gudang.

**Proses:** Ambil data gudang + semua departemen (untuk dropdown).

**Return:** `View` — `pages/warehouse/edit`

---

#### `update(UpdateWarehouseRequest $request, string $id)`

**Tujuan:** Memperbarui data gudang. Semua perubahan di-log.

**Return:** `Redirect` → `/warehouses`

---

#### `destroy(string $id)`

**Tujuan:** Soft-delete gudang.

**Return:** `Redirect` → `/warehouses`

---

### `DepartmentsController` — `app/Http/Controllers/DepartmentsController.php`

Mengelola master data departemen. Hanya dapat diakses oleh **admin**.

---

#### `index()`

**Tujuan:** Menampilkan daftar semua departemen.

**Return:** `View` — `pages/Department/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan departemen baru.

**Return:** `View` — `pages/Department/create`

---

#### `store(StoreDepartmentRequest $request)`

**Tujuan:** Menyimpan departemen baru. Semua operasi di-log.

**Return:** `Redirect` → `/departments`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit departemen.

**Return:** `View` — `pages/Department/edit`

---

#### `update(UpdateDepartmentRequest $request, string $id)`

**Tujuan:** Memperbarui data departemen.

**Return:** `Redirect` → `/departments`

---

#### `destroy(string $id)`

**Tujuan:** Soft-delete departemen.

**Return:** `Redirect` → `/departments`

---

### `ItemLocationsController` — `app/Http/Controllers/ItemLocationsController.php`

Mengelola lokasi fisik penyimpanan item di gudang (per lot/batch).

---

#### `index(Request $request)`

**Tujuan:** Menampilkan tabel lokasi item menggunakan DataTables (AJAX).

**Proses:**
1. Jika request adalah AJAX, ambil semua data lokasi item via `ItemLocationService::getAll()`.
2. Format data untuk DataTables:
   - Tanggal kadaluarsa dalam format `d-M-Y`
   - Nama item dari relasi `item`
   - Nama gudang dari relasi `warehouse`
   - Kuantitas, berat, dan satuan unit
   - Tombol aksi Edit/Delete
3. Return JSON untuk AJAX, return View untuk akses langsung.

**Return:** `JsonResponse` (AJAX) atau `View` — `pages/ItemLocation/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan lokasi item baru.

**Proses:** Mengambil daftar item, gudang, dan departemen untuk dropdown form.

**Return:** `View` — `pages/ItemLocation/create`

---

#### `store(StoreItemLocationRequest $request)`

**Tujuan:** Menyimpan lokasi item baru dan otomatis membuat transaksi PORC (penerimaan).

**Proses:**
1. Ambil data dari request yang tervalidasi.
2. Kalkulasi `exp_date` (tanggal kadaluarsa) = `production_date + 1 tahun`.
3. Panggil `ItemLocationService::create()` yang secara internal:
   - Membuat record `ItemLocations`
   - Otomatis membuat transaksi PORC di `ic_trans_inv`
   - Mengupdate `current_stock` di `ic_item_mst`
4. Redirect ke index.

**Return:** `Redirect` → `/item-locations`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit lokasi item.

**Proses:** Ambil data lokasi + semua item, gudang, dan departemen untuk dropdown.

**Return:** `View` — `pages/ItemLocation/edit`

---

#### `update(UpdateItemLocationRequest $request, string $id)`

**Tujuan:** Memperbarui data lokasi item.

**Proses:** Jika `production_date` berubah, `exp_date` akan dikalkulasi ulang (+1 tahun).

**Return:** `Redirect` → `/item-locations`

---

#### `destroy(string $id)`

**Tujuan:** Soft-delete lokasi item.

**Return:** `Redirect` → `/item-locations`

---

### `TransactionController` — `app/Http/Controllers/TransactionController.php`

Mengelola semua transaksi inventori (masuk, keluar, penyesuaian).

---

#### `index(Request $request)`

**Tujuan:** Menampilkan tabel transaksi menggunakan DataTables (AJAX) dengan filter tanggal.

**Parameter (AJAX):**
- `date_from` (optional) — Filter transaksi dari tanggal ini
- `date_to` (optional) — Filter transaksi sampai tanggal ini

**Proses:**
1. Jika request AJAX, ambil semua transaksi via service.
2. Filter berdasarkan `date_from` dan `date_to` jika ada.
3. Format data: tampilkan `in_qty` untuk PORC/TRANSFER, `out_qty` untuk CONS.
4. Return JSON dengan data yang sudah diformat.

**Return:** `JsonResponse` (AJAX) atau `View` — `pages/Transaction/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan transaksi baru.

**Proses:**
1. Cek `department_id` user yang login.
2. Jika admin (IT, `department_id = 1`), tampilkan semua item dan gudang.
3. Jika departemen lain, filter item dan gudang sesuai departemen user.
4. Kirim data ke view.

**Return:** `View` — `pages/Transaction/create`

---

#### `store(StoreTransactionRequest $request)`

**Tujuan:** Menyimpan transaksi baru dengan kalkulasi stok otomatis.

**Parameter:**
- `doc_type` — Jenis: `PORC` (masuk), `CONS` (konsumsi), `ADJI` (adjustment), `TRANSFER`
- `item_id` — ID item
- `warehouse_id` — ID gudang
- `trans_date` — Tanggal transaksi
- `trans_qty` — Jumlah transaksi
- `catatan` (optional) — Catatan
- `adj_type` (optional) — Untuk ADJI: `CONS` atau `PORC`
- `redirect_create` (optional) — Jika ada, redirect kembali ke form buat baru (mode batch input)

**Proses:**
1. Memanggil `TransactionService::create($data, $user->name)`.
2. Service menangani seluruh kalkulasi stok (lihat detail di section Services).
3. Jika `redirect_create`, redirect ke form baru.
4. Jika tidak, redirect ke halaman index transaksi.

**Return:** `Redirect` → `/transactions` atau `/transactions/create`

---

#### `show(string $id)`

**Tujuan:** Menampilkan detail satu transaksi beserta transaksi lain di tanggal yang sama untuk item yang sama.

**Proses:**
1. Ambil transaksi via `TransactionService::getById($id)`.
2. Ambil transaksi lain pada tanggal yang sama via `getSameDateTransactions()`.
3. Kirim kedua data ke view.

**Return:** `View` — `pages/Transaction/show`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit transaksi.

**Return:** `View` — `pages/Transaction/edit`

---

#### `update(UpdateTransactionRequest $request, string $id)`

**Tujuan:** Memperbarui data transaksi.

**Proses:**
1. Memanggil `TransactionService::update($id, $data, $user->name)`.
2. Service mengupdate `status = 'UPDATED'` dan merekomponen tanggal (`tgl`, `bln`, `thn`, `periode`).

**Return:** `Redirect` → `/transactions`

---

#### `destroy(string $id)`

**Tujuan:** Menghapus transaksi (soft delete via status='deleted').

**Proses:** Memanggil `TransactionService::delete($id)` yang akan membalik dampak stok.

**Return:** `Redirect` → `/transactions`

---

#### `adjustmentStock()`

**Tujuan:** Menampilkan form penyesuaian stok (ADJI).

**Return:** `View` — `pages/Transaction/adjustment_stock`

---

#### `storeAdjustmentStock(StoreAdjustmentStockRequest $request)`

**Tujuan:** Menyimpan transaksi penyesuaian stok.

**Parameter:**
- `adj_type` — `CONS` (koreksi ke atas) atau `PORC` (koreksi ke bawah)

**Proses:** Membuat transaksi dengan `doc_type = 'ADJI'` melalui service yang sama.

**Return:** `Redirect` → `/transactions`

---

### `TransferRequestsController` — `app/Http/Controllers/TransferRequestsController.php`

Mengelola permintaan pemindahan barang antar gudang.

---

#### `index(Request $request)`

**Tujuan:** Menampilkan tabel transfer request via DataTables dengan filter tanggal.

**Data yang ditampilkan:** Nama item, gudang asal, gudang tujuan, departemen, peminta, tanggal, kuantitas, status.

**Return:** `JsonResponse` (AJAX) atau `View` — `pages/transfer_requests/index`

---

#### `create()`

**Tujuan:** Menampilkan form pembuatan transfer request.

**Proses:**
1. Cek role user.
2. Admin melihat semua item dan gudang.
3. User biasa hanya melihat item dan gudang sesuai departemennya.

**Return:** `View` — `pages/transfer_requests/create`

---

#### `store(Request $request)`

**Tujuan:** Menyimpan transfer request baru.

**Proses:**
1. Set `requested_date` = tanggal hari ini.
2. Set `requested_by` = ID user yang login.
3. Set `status = 'pending'` (default).
4. Simpan via `TransferRequestService::create()`.

**Return:** `Redirect` → `/transfer-requests`

---

#### `show(string $id)`

**Tujuan:** Menampilkan detail transfer request beserta semua data relasi.

**Return:** `View` — `pages/transfer_requests/show`

---

#### `edit(string $id)`

**Tujuan:** Menampilkan form edit transfer request.

**Return:** `View` — `pages/transfer_requests/edit`

---

#### `update(Request $request, string $id)`

**Tujuan:** Memperbarui data transfer request.

**Return:** `Redirect` → `/transfer-requests`

---

#### `destroy(string $id)`

**Tujuan:** Soft-delete transfer request.

**Return:** `Redirect` → `/transfer-requests`

---

#### `getStock(Request $request)`

**Tujuan:** Endpoint AJAX untuk mengambil stok item di gudang tertentu secara real-time (digunakan saat user memilih item dan gudang di form).

**Parameter (Query String):**
- `item_id` — ID item
- `warehouse_id` — ID gudang

**Proses:**
1. Query tabel `ic_trans_inv` untuk item_id dan warehouse_id tersebut.
2. Ambil `eb_qty` (saldo akhir) dan `item_uom` (satuan) dari transaksi terakhir.
3. Return sebagai JSON.

**Return:** `JsonResponse` — `{ qty: 100.5, uom: 'kg' }`

---

### `ProfileController` — `app/Http/Controllers/ProfileController.php`

Mengelola profil pengguna yang sedang login (dari Laravel Breeze).

---

#### `edit(Request $request)`

**Tujuan:** Menampilkan halaman edit profil user.

**Return:** `View` — `profile/edit`

---

#### `update(ProfileUpdateRequest $request)`

**Tujuan:** Memperbarui data profil.

**Proses:**
1. Update `name` dan `email`.
2. Jika email berubah, reset `email_verified_at` menjadi `null` (paksa verifikasi ulang).

**Return:** `Redirect` → `/profile`

---

#### `destroy(Request $request)`

**Tujuan:** Menghapus akun sendiri setelah konfirmasi password.

**Proses:**
1. Verifikasi password melalui `Auth::guard('web')->validate()`.
2. Logout user.
3. Soft-delete akun.
4. Redirect ke halaman login.

**Return:** `Redirect` → `/`

---

## Services

Services berisi logika bisnis utama. Setiap service mengimplementasikan sebuah interface.

### `ItemMasterService` — `app/Services/ItemMasterService.php`

---

#### `getTransactionByMonth(int $id, int $month, int $year)`

**Tujuan:** Mengambil riwayat transaksi satu item per bulan dengan kalkulasi stok harian.

**Parameter:**
- `$id` — ID item (`item_id` di ic_item_mst)
- `$month` — Bulan (1-12)
- `$year` — Tahun (contoh: 2026)

**Proses:**
1. Ambil semua transaksi item tersebut untuk bulan dan tahun yang diminta.
2. Kelompokkan transaksi berdasarkan tanggal (`thn-bln-tgl`).
3. Untuk setiap kelompok tanggal, pisahkan berdasarkan `doc_type`:
   - **CONS** → masuk ke `consume_qty` (out_qty)
   - **PORC** → masuk ke `receive_qty` (in_qty)
   - **ADJI** → masuk ke `adj_qty`
4. Ambil `bb_qty` (saldo awal) dan `eb_qty` (saldo akhir) dari transaksi di tanggal tersebut.
5. Tambahkan baris kosong untuk tanggal-tanggal yang tidak ada transaksinya (tampilan kalender penuh).
6. Urutkan berdasarkan tanggal ascending.

**Return:** `Collection` — Kumpulan data per tanggal dengan `bb_qty`, `consume_qty`, `receive_qty`, `adj_qty`, `eb_qty`

---

#### `getByOrgnCode(string $department_id)`

**Tujuan:** Memfilter item berdasarkan departemen.

**Logika:** Jika `$department_id = '1'` (admin IT), kembalikan semua item. Selainnya, filter berdasarkan `department_id`.

---

### `ItemLocationService` — `app/Services/ItemLocationService.php`

---

#### `create(array $data)`

**Tujuan:** Membuat lokasi item baru **sekaligus** membuat transaksi PORC otomatis.

**Proses (dalam satu database transaction):**
1. Kalkulasi `exp_date` = `production_date + 1 tahun`.
2. Hitung `bb_qty` = saldo terakhir item di gudang ini dari `ic_trans_inv`.
3. Simpan record `ItemLocations`.
4. Buat transaksi PORC di `ic_trans_inv`:
   - `doc_type = 'PORC'`
   - `in_qty = qty_weight` (dari data yang diinput)
   - `bb_qty` = saldo sebelumnya
   - `eb_qty = bb_qty + in_qty`
5. Update `current_stock` di `ic_item_mst`.

**Return:** Instance `ItemLocations` yang baru dibuat.

---

#### `calculateExpDate(string $productionDateStr)` *(private)*

**Tujuan:** Menghitung tanggal kadaluarsa dari tanggal produksi.

**Proses:** Parse tanggal → tambah 1 tahun dengan Carbon → return sebagai string `Y-m-d`.

---

#### `generateTransCode()` *(private)*

**Tujuan:** Membuat kode transaksi unik dengan format `PORC-YYMMNNNN`.

**Format:** `PORC-` + 2 digit tahun + 2 digit bulan + 4 digit nomor urut per bulan (dimulai dari 0001).

**Contoh:** `PORC-260700001` (bulan Juli 2026, nomor urut 1)

---

### `TransactionService` — `app/Services/TransactionService.php`

Service paling kompleks — mengelola seluruh logika kalkulasi stok inventori.

---

#### `create(array $data, string $createdBy)`

**Tujuan:** Membuat transaksi baru dengan kalkulasi stok yang tepat, termasuk validasi dan rekalkulasi jika transaksi back-dating.

**Parameter:**
- `$data['doc_type']` — `PORC`, `CONS`, `ADJI`, atau `TRANSFER`
- `$data['item_id']` — ID item
- `$data['warehouse_id']` — ID gudang
- `$data['trans_date']` — Tanggal transaksi
- `$data['trans_qty']` — Kuantitas
- `$data['adj_type']` (optional) — Untuk ADJI: `CONS` atau `PORC`
- `$createdBy` — Nama user pembuat

**Proses (dalam database transaction):**
1. Set `creation_date` = sekarang, `created_by`, `status = 'NEW'`.
2. Pecah `trans_date` menjadi `tgl`, `bln`, `thn`, dan `periode` (format `YYYY-MM`).
3. Salin `item_no`, `item_desc`, `item_uom` dari model `IcItemMst`.
4. Ambil `bb_qty` = `eb_qty` transaksi terakhir untuk item+gudang ini.
5. Hitung kuantitas berdasarkan `doc_type`:
   - **PORC**: `in_qty = trans_qty`, `eb_qty = bb_qty + in_qty`
   - **CONS**: `out_qty = trans_qty`, `eb_qty = bb_qty - out_qty`
   - **TRANSFER**: `in_qty = trans_qty`, `eb_qty = bb_qty + in_qty`
   - **ADJI** dengan `adj_type=CONS`: `in_qty = trans_qty`, `eb_qty = bb_qty + in_qty`
   - **ADJI** dengan `adj_type=PORC`: `out_qty = trans_qty`, `eb_qty = bb_qty - out_qty`
6. **Jika transaksi back-dating** (ada transaksi lebih baru untuk item+gudang yang sama):
   - Panggil `simulateRecalculate()` — validasi bahwa stok tidak akan negatif.
   - Jika valid, simpan transaksi lalu panggil `recalculateStockFrom()` untuk update semua transaksi lebih baru.
7. **Jika CONS**: Cari `ItemLocation` dengan tanggal kadaluarsa paling dekat → kurangi `qty_weight`.
8. Update `current_stock` di `ic_item_mst`.

---

#### `simulateRecalculate(string $itemId, Carbon $fromDate, float $transQty, string $docType, ?string $adjType)` *(private)*

**Tujuan:** Mensimulasikan dampak transaksi baru terhadap semua transaksi yang lebih baru **tanpa** menyimpan ke database. Digunakan untuk validasi sebelum back-dating.

**Proses:**
1. Ambil semua transaksi untuk item yang sama dengan `trans_date >= fromDate`, urutkan ascending.
2. Hitung saldo baru setelah menambahkan transaksi baru di tanggal `fromDate`.
3. Iterasi ke depan — simulasikan rekalkulasi setiap transaksi.
4. Jika ada saldo yang menjadi negatif, **throw Exception**.

**Return:** `void` (melempar exception jika tidak valid)

---

#### `recalculateStockFrom(string $itemId, Carbon $fromDate)` *(private)*

**Tujuan:** Merekalkulas ulang semua transaksi dari tanggal tertentu ke depan setelah ada back-dating.

**Proses:**
1. Ambil `eb_qty` dari transaksi tepat sebelum `fromDate` sebagai saldo awal baru.
2. Ambil semua transaksi dari `fromDate` ke depan, urutkan ascending.
3. Untuk setiap transaksi:
   - Set `bb_qty = eb_qty_sebelumnya`
   - Hitung `eb_qty` baru berdasarkan `doc_type` dan `trans_qty`
   - Simpan perubahan ke database
4. Update `current_stock` di `ic_item_mst` = `eb_qty` transaksi terakhir.

---

#### `delete(int $id)`

**Tujuan:** Menghapus transaksi dan membalik dampaknya terhadap stok.

**Proses (dalam database transaction):**
1. Ambil data transaksi.
2. Balik dampak stok:
   - Jika PORC/TRANSFER: kurangi `current_stock` sebesar `in_qty`
   - Jika CONS: tambah `current_stock` sebesar `out_qty`
   - Jika ADJI: balikkan sesuai `adj_type`
3. Set `status = 'deleted'` (bukan delete sungguhan).

---

### `DashboardService` — `app/Services/DashboardService.php`

---

#### `getSummary(int $month, int $year)`

**Tujuan:** Mengembalikan statistik ringkasan untuk dashboard.

**Return:** `array` — `['total_item' => int, 'total_consumption' => float, 'total_receipt' => float]`

---

#### `getTotalItem()`

**Tujuan:** Menghitung total item aktif. Admin melihat semua; user lain hanya item departemennya.

---

#### `getTotalConsumption(int $month, int $year)`

**Tujuan:** Menjumlahkan `out_qty` dari semua transaksi bulan tersebut, kecuali `doc_type = 'ADJ'`.

---

#### `getTotalReceipt(int $month, int $year)`

**Tujuan:** Menjumlahkan `in_qty` dari semua transaksi bulan tersebut, kecuali `doc_type = 'ADJ'`.

---

#### `getTop10Consumption(int $month, int $year)`

**Tujuan:** Mengambil 10 item dengan total konsumsi tertinggi untuk bulan tersebut.

**Proses:**
1. Query `ic_trans_inv` dengan filter `bln`, `thn`, dan `doc_type = 'CONS'`.
2. Group by `item_id`, sum `out_qty`.
3. Order by `out_qty` DESC, limit 10.
4. Pad (tambahkan baris kosong) hingga 10 baris jika kurang dari 10 item.

---

#### `getItemsWithConsumption(int $month, int $year)`

**Tujuan:** Mengambil semua item aktif beserta total konsumsi dan penerimaannya di bulan tersebut, diurutkan berdasarkan konsumsi tertinggi.

---

### `TransferRequestService` — `app/Services/TransferRequestService.php`

Service sederhana untuk CRUD transfer request, tanpa logika bisnis kompleks.

- `getAll()` — Semua request, diurutkan `requested_date DESC`
- `getById(int $id)` — Satu request
- `create(array $data)` — Buat request
- `update(int $id, array $data)` — Update request
- `delete(int $id)` — Soft-delete request

---

## Repositories

Repositories bertanggung jawab hanya untuk akses data (query Eloquent). Tidak ada logika bisnis di sini.

### `UserRepository` — `app/Repositories/Eloquent/UserRepository.php`

- `getAll()` → `User::orderBy('name')->get()`
- `getById(int $id)` → `User::findOrFail($id)`
- `create(array $data)` → `User::create($data)`
- `update(int $id, array $data)` → Cari user → update
- `delete(int $id)` → Set `status = false` (bukan `deleted_at`)

---

### `ItemMasterRepository` — `app/Repositories/Eloquent/ItemMasterRepository.php`

- `getAll()` → Semua item
- `getById(int $id)` → `IcItemMst::findOrFail($id)`
- `create(array $data)` → Buat item baru
- `update(int $id, array $data)` → Update item
- `delete(int $id)` → Soft-delete
- `getByOrgnCode(string $department_id)` → Filter berdasarkan `department_id`; jika `'1'` return semua

---

### `DepartmentRepository` — `app/Repositories/Eloquent/DepartmentRepository.php`

- `getAll()` → `Departments::orderBy('name', 'ASC')->get()`
- `getById(int $id)` → `Departments::findOrFail($id)`
- `create(array $data)` → Buat departemen baru
- `update(int $id, array $data)` → Update departemen
- `delete(int $id)` → Soft-delete

---

### `WarehouseRepository` — `app/Repositories/Eloquent/WarehouseRepository.php`

- `getAll()` → `Warehouses::orderBy('name')->get()`
- `getById(int $id)` → `Warehouses::findOrFail($id)`
- `getByOrgnCode(string $department_id)` → Filter gudang berdasarkan departemen
- `create(array $data)` → Buat gudang baru
- `update(int $id, array $data)` → Update gudang
- `delete(int $id)` → Soft-delete

---

### `ItemLocationRepository` — `app/Repositories/Eloquent/ItemLocationRepository.php`

- `getAll()` → Semua lokasi, urut `received_date DESC`
- `getAllGroupBy()` → Kombinasi unik `item_id / warehouse_id / orgn_code`
- `getById(int $id)` → Satu lokasi
- `getByOrgnCode(string $orgnCode)` → Filter berdasarkan `orgn_code`
- `create(array $data)` → Buat lokasi baru
- `update(int $id, array $data)` → Update lokasi
- `delete(int $id)` → Soft-delete

---

### `TransactionRepository` — `app/Repositories/Eloquent/TransactionRepository.php`

- `getAll()` → Semua transaksi, urut `creation_date DESC`
- `getById(int $id)` → Satu transaksi
- `getSameDateTransactions(string $transDate, int $itemId)` → Transaksi item pada tanggal tertentu
- `getByItemId(int $itemId)` → Semua transaksi item + load relasi `item`
- `create(array $data)` → Buat transaksi baru
- `update(int $id, array $data)` → Update transaksi
- `delete(int $id)` → Set `status = 'deleted'`

---

### `TransferRequestRepository` — `app/Repositories/Eloquent/TransferRequestRepository.php`

- `getAll()` → Semua request, urut `requested_date DESC`
- `getById(int $id)` → Satu request
- `create(array $data)` → Buat request
- `update(int $id, array $data)` → Update request
- `delete(int $id)` → Soft-delete

---

## Routes (Routing)

File: `routes/web.php`

### Rute Publik (tanpa autentikasi)

| Method | URI | Aksi |
|--------|-----|------|
| GET | `/` | Redirect ke halaman login |

### Rute Autentikasi (Laravel Breeze)

| Method | URI | Aksi |
|--------|-----|------|
| GET | `/login` | Form login |
| POST | `/login` | Proses login |
| POST | `/logout` | Logout |
| GET | `/register` | Form registrasi |
| POST | `/register` | Proses registrasi |

### Rute Dashboard (auth + verified)

| Method | URI | Controller | Middleware |
|--------|-----|------------|------------|
| GET | `/dashboard` | `DashbaordController@index` | auth, verified |

### Rute Profil (auth)

| Method | URI | Controller |
|--------|-----|------------|
| GET | `/profile` | `ProfileController@edit` |
| PATCH | `/profile` | `ProfileController@update` |
| DELETE | `/profile` | `ProfileController@destroy` |

### Rute AJAX

| Method | URI | Controller | Middleware |
|--------|-----|------------|------------|
| GET | `/get-stock` | `TransferRequestsController@getStock` | auth |

### Rute Staff / Manager / Admin

| Method | URI | Controller | Keterangan |
|--------|-----|------------|------------|
| GET | `/transactions` | `TransactionController@index` | Daftar transaksi |
| POST | `/transactions` | `TransactionController@store` | Buat transaksi |
| GET | `/transactions/create` | `TransactionController@create` | Form buat transaksi |
| GET | `/transactions/{id}` | `TransactionController@show` | Detail transaksi |
| GET | `/transactions/{id}/edit` | `TransactionController@edit` | Form edit |
| PUT | `/transactions/{id}` | `TransactionController@update` | Update transaksi |
| DELETE | `/transactions/{id}` | `TransactionController@destroy` | Hapus transaksi |
| GET | `/transactions/adjustment-stock` | `TransactionController@adjustmentStock` | Form adjustment |
| POST | `/transactions/adjustment-stock` | `TransactionController@storeAdjustmentStock` | Simpan adjustment |
| GET | `/item-master/{id}/detail` | `ItemMasterController@detail` | Riwayat item |
| GET/POST/PUT/DELETE | `/item-locations` | `ItemLocationsController` | Resource CRUD |
| GET/POST/PUT/DELETE | `/transfer-requests` | `TransferRequestsController` | Resource CRUD |

### Rute Manager / Admin

| Method | URI | Controller |
|--------|-----|------------|
| GET/POST/PUT/DELETE | `/item-master` | `ItemMasterController` (resource) |

### Rute Admin Only

| Method | URI | Controller |
|--------|-----|------------|
| GET/POST/PUT/DELETE | `/users` | `UserController` (resource) |
| GET/POST/PUT/DELETE | `/warehouses` | `WarehousesController` (resource) |
| GET/POST/PUT/DELETE | `/departments` | `DepartmentsController` (resource) |

---

## Views (Tampilan)

### Layout Utama

| File | Keterangan |
|------|-----------|
| `layouts/app.blade.php` | Layout utama untuk halaman setelah login |
| `layouts/guest.blade.php` | Layout untuk halaman autentikasi (login, register) |
| `layouts/navbar.blade.php` | Navbar atas dengan menu navigasi |
| `layouts/template.blade.php` | Template dasar yang di-extend semua halaman |
| `layouts/style.blade.php` | Semua include CSS (Bootstrap, DataTables, dll.) |
| `layouts/script.blade.php` | Semua include JS (jQuery, DataTables, Chart.js, dll.) |

### Halaman Dashboard

| File | Keterangan |
|------|-----------|
| `pages/dashboard.blade.php` | Dashboard utama: statistik ringkasan, grafik top 10 konsumsi, tabel semua item |

### Halaman Pengguna (Admin Only)

| File | Keterangan |
|------|-----------|
| `pages/User/index.blade.php` | Tabel daftar semua pengguna |
| `pages/User/create.blade.php` | Form pembuatan pengguna baru |
| `pages/User/edit.blade.php` | Form edit data pengguna |
| `pages/User/show.blade.php` | Halaman detail satu pengguna |

### Halaman Item Master

| File | Keterangan |
|------|-----------|
| `pages/Item_Master/index.blade.php` | Daftar semua item inventori |
| `pages/Item_Master/create.blade.php` | Form buat item baru |
| `pages/Item_Master/edit.blade.php` | Form edit item |
| `pages/Item_Master/show.blade.php` | Detail item |
| `pages/Item_Master/detail.blade.php` | Riwayat transaksi bulanan item dengan kalkulasi saldo harian |

### Halaman Lokasi Item

| File | Keterangan |
|------|-----------|
| `pages/ItemLocation/index.blade.php` | Tabel DataTables lokasi stok per lot/batch |
| `pages/ItemLocation/create.blade.php` | Form buat lokasi item baru |
| `pages/ItemLocation/edit.blade.php` | Form edit lokasi item |

### Halaman Transaksi

| File | Keterangan |
|------|-----------|
| `pages/Transaction/index.blade.php` | Tabel DataTables semua transaksi dengan filter tanggal |
| `pages/Transaction/create.blade.php` | Form buat transaksi (PORC/CONS/TRANSFER/ADJI) |
| `pages/Transaction/edit.blade.php` | Form edit transaksi |
| `pages/Transaction/show.blade.php` | Detail transaksi + transaksi lain di tanggal yang sama |
| `pages/Transaction/adjustment_stock.blade.php` | Form khusus penyesuaian stok (ADJI) |

### Halaman Transfer Request

| File | Keterangan |
|------|-----------|
| `pages/transfer_requests/index.blade.php` | Tabel DataTables semua transfer request |
| `pages/transfer_requests/create.blade.php` | Form buat transfer request |
| `pages/transfer_requests/edit.blade.php` | Form edit transfer request |
| `pages/transfer_requests/show.blade.php` | Detail transfer request |

### Halaman Gudang (Admin Only)

| File | Keterangan |
|------|-----------|
| `pages/warehouse/index.blade.php` | Daftar semua gudang |
| `pages/warehouse/create.blade.php` | Form buat gudang baru |
| `pages/warehouse/edit.blade.php` | Form edit gudang |

### Halaman Departemen (Admin Only)

| File | Keterangan |
|------|-----------|
| `pages/Department/index.blade.php` | Daftar semua departemen |
| `pages/Department/create.blade.php` | Form buat departemen baru |
| `pages/Department/edit.blade.php` | Form edit departemen |

### Halaman Profil

| File | Keterangan |
|------|-----------|
| `profile/edit.blade.php` | Halaman manajemen profil (nama, email, password, hapus akun) |

---

## Interfaces

Semua interface berada di `app/Repositories/Interfaces/` dan `app/Services/Interfaces/`.

### Repository Interfaces

Semua repository interface mendefinisikan kontrak CRUD standar:

```php
public function getAll();
public function getById(int $id);
public function create(array $data);
public function update(int $id, array $data);
public function delete(int $id);
```

**Interface tambahan per modul:**

| Interface | Method Tambahan |
|-----------|----------------|
| `ItemMasterRepositoryInterface` | `getByOrgnCode(string $department_id)` |
| `WarehouseRepositoryInterface` | `getByOrgnCode(string $department_id)` |
| `ItemLocationRepositoryInterface` | `getAllGroupBy()`, `getByOrgnCode(string $orgnCode)` |
| `TransactionRepositoryInterface` | `getSameDateTransactions(string, int)`, `getByItemId(int)` |

### Service Interfaces

Sama seperti repository interfaces, ditambah:

| Interface | Method Tambahan |
|-----------|----------------|
| `ItemMasterServiceInterface` | `getTransactionByMonth(int, int, int)`, `getByOrgnCode(string)` |
| `WarehouseServiceInterface` | `getByOrgnCode(string)` |
| `ItemLocationServiceInterface` | `getAllGroupBy()`, `getByOrgnCode(string)` |
| `TransactionServiceInterface` | `getSameDateTransactions(string, int)`, `getByItemId(string)`, `create(array, string)`, `update(int, array, string)` |

---

## Alur Bisnis Utama

### 1. Penerimaan Barang (PORC)

```
User mengisi form Item Location
    ↓
ItemLocationsController::store()
    ↓
ItemLocationService::create()
    ↓
Hitung exp_date = production_date + 1 tahun
    ↓
Simpan ItemLocations ke database
    ↓
Otomatis buat transaksi PORC:
  bb_qty = saldo terakhir item di gudang
  in_qty = qty_weight (input user)
  eb_qty = bb_qty + in_qty
    ↓
Update current_stock di ic_item_mst
```

### 2. Konsumsi Barang (CONS)

```
User mengisi form Transaction (doc_type = CONS)
    ↓
TransactionController::store()
    ↓
TransactionService::create()
    ↓
Ambil bb_qty dari transaksi terakhir item+gudang
    ↓
out_qty = trans_qty (input user)
eb_qty  = bb_qty - out_qty
    ↓
Validasi: eb_qty tidak boleh < 0
    ↓
Simpan transaksi ke ic_trans_inv
    ↓
Cari ItemLocation dengan exp_date terdekat
Kurangi qty_weight pada ItemLocation tersebut (FIFO by expiry)
    ↓
Update current_stock di ic_item_mst
```

### 3. Back-Dating Transaction

```
User membuat transaksi dengan tanggal lampau
    ↓
TransactionService::create() mendeteksi ada transaksi lebih baru
    ↓
simulateRecalculate():
  Simulasi dampak transaksi baru terhadap semua transaksi lebih baru
  Jika ada saldo negatif → THROW EXCEPTION (batalkan)
    ↓
Simpan transaksi baru
    ↓
recalculateStockFrom():
  Untuk setiap transaksi dari tanggal tersebut ke depan:
    bb_qty = eb_qty transaksi sebelumnya
    eb_qty = bb_qty ± trans_qty (sesuai doc_type)
  Simpan semua perubahan
    ↓
Update current_stock = eb_qty transaksi terakhir
```

### 4. Penyesuaian Stok (ADJI)

```
User mengisi form Adjustment Stock
    ↓
Pilih adj_type:
  - CONS: koreksi penambahan stok  (bb + in = eb)
  - PORC: koreksi pengurangan stok (bb - out = eb)
    ↓
TransactionController::storeAdjustmentStock()
    ↓
Proses sama dengan create transaksi biasa
```

### 5. Transfer Request

```
User membuat Transfer Request
    ↓
Input: item, gudang asal, gudang tujuan, jumlah
    ↓
Status awal = 'pending'
    ↓
Tampil di halaman Transfer Requests untuk diproses
(Transfer request tidak otomatis membuat transaksi;
 harus dibuat manual oleh petugas gudang)
```

---

## Hak Akses (Role)

Sistem menggunakan middleware role-based access control:

| Role | Akses |
|------|-------|
| **admin** | Semua fitur: Users, Warehouses, Departments, Item Master, Item Locations, Transactions, Transfer Requests, Dashboard |
| **manager** | Item Master, Item Locations, Transactions, Transfer Requests, Dashboard |
| **staff** | Item Locations, Transactions, Transfer Requests, Dashboard |

**Catatan Khusus:**
- User dengan `department_id = 1` (admin IT) dapat melihat semua item dari semua departemen.
- User dengan departemen lain hanya melihat item, gudang, dan data yang terkait dengan departemennya.
- Route `/get-stock` dapat diakses oleh semua user yang sudah login (untuk AJAX di form transfer request).
