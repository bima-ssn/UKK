# ERD (Entity Relationship Diagram) - Aplikasi Peminjaman Alat

## 📊 Diagram Relasi Database

```
┌─────────────┐
│    users    │
├─────────────┤
│ id (PK)     │
│ name        │
│ email (UK)  │
│ password    │
│ role        │
└──────┬──────┘
       │
       │ 1:N
       │
       ▼
┌─────────────┐      ┌─────────────┐
│ peminjaman  │      │log_aktivitas│
├─────────────┤      ├─────────────┤
│ id (PK)     │      │ id (PK)     │
│ user_id(FK) │◄─────┤ user_id(FK) │
│ tanggal_... │      │ aktivitas   │
│ status      │      │ waktu       │
└──────┬──────┘      └─────────────┘
       │
       │ 1:N
       │
       ▼
┌──────────────────┐
│detail_peminjaman │
├──────────────────┤
│ id (PK)          │
│ peminjaman_id(FK)│
│ alat_id (FK)     │
│ jumlah           │
└──────┬───────────┘
       │
       │ N:1
       │
       ▼
┌─────────────┐      ┌─────────────┐
│    alat     │      │  kategori   │
├─────────────┤      ├─────────────┤
│ id (PK)     │      │ id (PK)     │
│ kategori_id │◄─────┤ nama_kategori│
│ nama_alat   │      │ keterangan  │
│ stok        │      └─────────────┘
│ kondisi     │
└──────┬──────┘
       │
       │ 1:1
       │
       ▼
┌─────────────┐
│pengembalian │
├─────────────┤
│ id (PK)     │
│ peminjaman_ │
│   id (FK)   │
│ tanggal_... │
│ denda       │
└─────────────┘
```

## 🔗 Relasi Tabel

### 1. users → peminjaman (1:N)
- **Jenis:** One to Many
- **FK:** `peminjaman.user_id` → `users.id`
- **Cascade:** ON DELETE CASCADE
- **Keterangan:** Satu user bisa memiliki banyak peminjaman

### 2. users → log_aktivitas (1:N)
- **Jenis:** One to Many
- **FK:** `log_aktivitas.user_id` → `users.id`
- **Cascade:** ON DELETE SET NULL
- **Keterangan:** Satu user bisa memiliki banyak log aktivitas

### 3. kategori → alat (1:N)
- **Jenis:** One to Many
- **FK:** `alat.kategori_id` → `kategori.id`
- **Cascade:** ON DELETE CASCADE
- **Keterangan:** Satu kategori bisa memiliki banyak alat

### 4. peminjaman → detail_peminjaman (1:N)
- **Jenis:** One to Many
- **FK:** `detail_peminjaman.peminjaman_id` → `peminjaman.id`
- **Cascade:** ON DELETE CASCADE
- **Keterangan:** Satu peminjaman bisa memiliki banyak detail alat

### 5. alat → detail_peminjaman (1:N)
- **Jenis:** One to Many
- **FK:** `detail_peminjaman.alat_id` → `alat.id`
- **Cascade:** ON DELETE CASCADE
- **Keterangan:** Satu alat bisa dipinjam dalam banyak peminjaman

### 6. peminjaman → pengembalian (1:1)
- **Jenis:** One to One
- **FK:** `pengembalian.peminjaman_id` → `peminjaman.id`
- **Cascade:** ON DELETE CASCADE
- **Unique:** `peminjaman_id` UNIQUE
- **Keterangan:** Satu peminjaman hanya bisa dikembalikan sekali

## 📋 Atribut Tabel

### users
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `name` (VARCHAR(255), NOT NULL)
- `email` (VARCHAR(255), UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL)
- `role` (ENUM: 'admin', 'petugas', 'peminjam', DEFAULT: 'peminjam')
- `email_verified_at` (TIMESTAMP, NULLABLE)
- `remember_token` (VARCHAR(100), NULLABLE)
- `created_at`, `updated_at` (TIMESTAMP)

### kategori
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `nama_kategori` (VARCHAR(255), UNIQUE, NOT NULL)
- `keterangan` (TEXT, NULLABLE)
- `created_at`, `updated_at` (TIMESTAMP)

### alat
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `kategori_id` (FK → kategori.id, CASCADE DELETE)
- `nama_alat` (VARCHAR(255), NOT NULL)
- `stok` (INT, DEFAULT: 0)
- `kondisi` (ENUM: 'baik', 'rusak', 'perbaikan', DEFAULT: 'baik')
- `created_at`, `updated_at` (TIMESTAMP)
- **Index:** kategori_id, stok

### peminjaman
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `user_id` (FK → users.id, CASCADE DELETE)
- `tanggal_pinjam` (DATE, NOT NULL)
- `tanggal_kembali` (DATE, NOT NULL)
- `status` (ENUM: 'menunggu', 'disetujui', 'ditolak', 'dikembalikan', DEFAULT: 'menunggu')
- `keterangan` (TEXT, NULLABLE)
- `created_at`, `updated_at` (TIMESTAMP)
- **Index:** user_id, status

### detail_peminjaman
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `peminjaman_id` (FK → peminjaman.id, CASCADE DELETE)
- `alat_id` (FK → alat.id, CASCADE DELETE)
- `jumlah` (INT, NOT NULL)
- `created_at`, `updated_at` (TIMESTAMP)

### pengembalian
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `peminjaman_id` (FK → peminjaman.id, UNIQUE, CASCADE DELETE)
- `tanggal_dikembalikan` (DATE, NOT NULL)
- `denda` (DECIMAL(10,2), DEFAULT: 0.00)
- `keterangan` (TEXT, NULLABLE)
- `created_at`, `updated_at` (TIMESTAMP)

### log_aktivitas
- `id` (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- `user_id` (FK → users.id, SET NULL ON DELETE)
- `aktivitas` (VARCHAR(255), NOT NULL)
- `waktu` (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- `ip_address` (VARCHAR(45), NULLABLE)
- `user_agent` (TEXT, NULLABLE)
- `created_at`, `updated_at` (TIMESTAMP)
- **Index:** user_id, waktu

## 🔧 Database Objects

### Function: hitung_denda()
**Parameter:**
- `tanggal_kembali` (DATE)
- `tanggal_dikembalikan` (DATE)

**Return:** DECIMAL(10,2)

**Logika:**
- Jika terlambat: denda = hari_terlambat × Rp 10.000
- Jika tidak terlambat: denda = 0

### Procedure: proses_peminjaman()
**Parameter IN:**
- `p_user_id` (BIGINT UNSIGNED)
- `p_tanggal_pinjam` (DATE)
- `p_tanggal_kembali` (DATE)
- `p_keterangan` (TEXT)

**Parameter OUT:**
- `p_peminjaman_id` (BIGINT UNSIGNED)
- `p_status` (VARCHAR(50))

**Fungsi:** Membuat peminjaman baru dengan transaction safety

### Trigger: after_peminjaman_approved
**Event:** AFTER UPDATE ON peminjaman
**Kondisi:** NEW.status = 'disetujui' AND OLD.status = 'menunggu'
**Aksi:** Mengurangi stok alat sesuai jumlah yang dipinjam

### Trigger: after_pengembalian_insert
**Event:** AFTER INSERT ON pengembalian
**Aksi:**
1. Menambah kembali stok alat
2. Update status peminjaman menjadi 'dikembalikan'

## 📈 Normalisasi Database

### 1NF (First Normal Form)
✅ Semua kolom atomic (tidak ada multi-value)
✅ Tidak ada duplikasi data

### 2NF (Second Normal Form)
✅ Semua atribut non-key fully dependent pada primary key
✅ Tidak ada partial dependency

### 3NF (Third Normal Form)
✅ Tidak ada transitive dependency
✅ Semua atribut non-key hanya dependent pada primary key

## 🔍 Index Strategy

**Primary Index:**
- Semua tabel memiliki PRIMARY KEY pada kolom `id`

**Foreign Key Index:**
- Semua foreign key memiliki index untuk performa JOIN

**Additional Index:**
- `users.role` - Untuk filter berdasarkan role
- `alat.stok` - Untuk query alat tersedia
- `peminjaman.status` - Untuk filter status peminjaman
- `log_aktivitas.waktu` - Untuk query log berdasarkan waktu

---

*Dokumentasi dibuat untuk UKK SMK RPL 2025/2026*

