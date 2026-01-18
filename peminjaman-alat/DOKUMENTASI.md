# Dokumentasi Aplikasi Peminjaman Alat

## 📋 Status Implementasi

### ✅ Telah Selesai

#### Backend
- ✅ Database migrations (users, kategori, alat, peminjaman, detail_peminjaman, pengembalian, log_aktivitas)
- ✅ Eloquent Models dengan relationships lengkap
- ✅ Middleware untuk role-based access (admin, petugas, peminjam)
- ✅ Controllers lengkap untuk semua role:
  - Admin: UserController, KategoriController, AlatController, PeminjamanController, PengembalianController, LaporanController, LogAktivitasController
  - Petugas: PeminjamanController, PengembalianController
  - Peminjam: AlatController, PeminjamanController, PengembalianController
- ✅ Routes dengan middleware protection
- ✅ Database seeders dengan data awal
- ✅ Activity logging system (LogActivity trait)
- ✅ PDF report generation (DomPDF)

#### Frontend
- ✅ Layout dengan Bootstrap 5
- ✅ Sidebar navigation berdasarkan role
- ✅ Dashboard untuk semua role
- ✅ Views untuk Admin:
  - Users (index, create, edit, show)
  - Kategori (index, create, edit, show)
  - Alat (index, create, edit, show)
  - Laporan (index + PDF views)
- ✅ Views untuk Petugas:
  - Dashboard
- ✅ Views untuk Peminjam:
  - Dashboard

### ⚠️ Perlu Dilengkapi

#### Views yang masih perlu dibuat:

**Admin:**
- [ ] Peminjaman (index, create, edit, show)
- [ ] Pengembalian (index, create, edit, show)
- [ ] Log Aktivitas (index, show)

**Petugas:**
- [ ] Peminjaman (index, show)
- [ ] Pengembalian (index, create, show)
- [ ] Alat (index, show)

**Peminjam:**
- [ ] Alat (index, show)
- [ ] Peminjaman (index, create, show)
- [ ] Pengembalian (index, show)

**Auth Views:**
- Perlu update untuk menggunakan Bootstrap 5 (saat ini masih Tailwind)

## 🗄️ Database Schema

### ERD (Entity Relationship Diagram)

```
users
├── id (PK)
├── name
├── email
├── password
├── role (admin, petugas, peminjam)
└── timestamps

kategori
├── id (PK)
├── nama_kategori
├── keterangan
└── timestamps

alat
├── id (PK)
├── kategori_id (FK -> kategori.id)
├── nama_alat
├── stok
├── kondisi (baik, rusak, perbaikan)
└── timestamps

peminjaman
├── id (PK)
├── user_id (FK -> users.id)
├── tanggal_pinjam
├── tanggal_kembali
├── status (menunggu, disetujui, ditolak, dikembalikan)
├── keterangan
└── timestamps

detail_peminjaman
├── id (PK)
├── peminjaman_id (FK -> peminjaman.id)
├── alat_id (FK -> alat.id)
├── jumlah
└── timestamps

pengembalian
├── id (PK)
├── peminjaman_id (FK -> peminjaman.id)
├── tanggal_dikembalikan
├── denda
├── keterangan
└── timestamps

log_aktivitas
├── id (PK)
├── user_id (FK -> users.id)
├── aktivitas
├── waktu
├── ip_address
└── user_agent
```

## 🔄 Flowchart Proses

### 1. Proses Login
```
START
  ↓
Tampilkan Form Login
  ↓
Input Email & Password
  ↓
Validasi Credentials
  ↓
[Valid?]
  ├─ YES → Redirect berdasarkan Role
  │         ├─ Admin → /admin/dashboard
  │         ├─ Petugas → /petugas/dashboard
  │         └─ Peminjam → /peminjam/dashboard
  └─ NO → Tampilkan Error
  ↓
END
```

### 2. Proses Peminjaman Alat
```
START
  ↓
Peminjam pilih alat & isi form
  ↓
Submit form peminjaman
  ↓
Validasi data & cek stok
  ↓
[Stok cukup?]
  ├─ YES → Simpan peminjaman (status: menunggu)
  │         ↓
  │         Notifikasi ke Admin/Petugas
  │         ↓
  │         Admin/Petugas review
  │         ↓
  │         [Setujui?]
  │         ├─ YES → Update status: disetujui
  │         │         Kurangi stok alat
  │         │         Log aktivitas
  │         └─ NO → Update status: ditolak
  │                   Log aktivitas
  └─ NO → Tampilkan error stok tidak cukup
  ↓
END
```

### 3. Proses Pengembalian + Perhitungan Denda
```
START
  ↓
Petugas/Admin pilih peminjaman
  ↓
Input tanggal dikembalikan
  ↓
Hitung selisih hari
  ↓
[Terlambat?]
  ├─ YES → Hitung denda
  │         Denda = hari_terlambat × Rp 10.000
  └─ NO → Denda = 0
  ↓
Simpan pengembalian
  ↓
Kembalikan stok alat
  ↓
Update status peminjaman: dikembalikan
  ↓
Log aktivitas
  ↓
END
```

## 📝 Pseudocode

### Perhitungan Denda
```
FUNCTION hitungDenda(tanggalKembali, tanggalDikembalikan)
    IF tanggalDikembalikan > tanggalKembali THEN
        hariTerlambat = SELISIH_HARI(tanggalDikembalikan, tanggalKembali)
        denda = hariTerlambat × 10000
    ELSE
        denda = 0
    END IF
    RETURN denda
END FUNCTION
```

### Validasi Stok Saat Peminjaman
```
FUNCTION validasiStok(detailPeminjaman[])
    FOR EACH detail IN detailPeminjaman DO
        alat = GET_ALAT(detail.alat_id)
        IF alat.stok < detail.jumlah THEN
            RETURN FALSE, "Stok tidak cukup untuk " + alat.nama_alat
        END IF
    END FOR
    RETURN TRUE
END FUNCTION
```

## 🚀 Cara Menjalankan

1. Install dependencies:
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database di `.env`

4. Run migrations & seeders:
```bash
php artisan migrate
php artisan db:seed
```

5. Build assets:
```bash
npm run build
```

6. Start server:
```bash
php artisan serve
```

## 📊 Testing Checklist

### Admin
- [ ] Login sebagai admin
- [ ] CRUD User
- [ ] CRUD Kategori
- [ ] CRUD Alat
- [ ] CRUD Peminjaman
- [ ] CRUD Pengembalian
- [ ] Setujui/Tolak peminjaman
- [ ] Cetak laporan PDF
- [ ] Lihat log aktivitas

### Petugas
- [ ] Login sebagai petugas
- [ ] Lihat daftar peminjaman
- [ ] Setujui/Tolak peminjaman
- [ ] Catat pengembalian
- [ ] Lihat daftar alat

### Peminjam
- [ ] Login sebagai peminjam
- [ ] Lihat daftar alat
- [ ] Ajukan peminjaman
- [ ] Lihat status peminjaman
- [ ] Lihat riwayat pengembalian

## 🐛 Known Issues

- Beberapa views masih perlu dibuat (lihat checklist di atas)
- Auth views masih menggunakan Tailwind, perlu diubah ke Bootstrap 5
- Perlu penambahan validasi lebih ketat di beberapa form

## 📈 Rencana Pengembangan

1. ✅ Menyelesaikan semua views yang belum dibuat
2. ✅ Update auth views ke Bootstrap 5
3. ✅ Penambahan fitur notifikasi email
4. ✅ Penambahan fitur export Excel
5. ✅ Penambahan fitur grafik/chart di dashboard
6. ✅ Penambahan unit testing
7. ✅ Penambahan API untuk mobile app

## 📞 Kontak

Untuk pertanyaan atau issue, silakan buat issue di repository.

