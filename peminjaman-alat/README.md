# Aplikasi Peminjaman Alat - Laravel 12

Aplikasi web untuk manajemen peminjaman alat dengan 3 role pengguna: Admin, Petugas, dan Peminjam.

## 🚀 Fitur

### Admin
- ✅ Login & Logout
- ✅ CRUD User
- ✅ CRUD Alat
- ✅ CRUD Kategori Alat
- ✅ CRUD Data Peminjaman
- ✅ CRUD Pengembalian
- ✅ Menyetujui/Menolak Peminjaman
- ✅ Mencetak Laporan (PDF)
- ✅ Log Aktivitas Sistem

### Petugas
- ✅ Login & Logout
- ✅ Menyetujui/Menolak Peminjaman
- ✅ Memantau Pengembalian
- ✅ CRUD Data Peminjaman
- ✅ CRUD Pengembalian
- ✅ Melihat daftar alat

### Peminjam
- ✅ Login & Logout
- ✅ Melihat daftar alat
- ✅ Mengajukan Peminjaman
- ✅ Melihat status peminjaman
- ✅ Melihat riwayat pengembalian

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL 5.7+
- Node.js & npm

## 🔧 Installation

1. Clone repository
```bash
git clone <repository-url>
cd peminjaman-alat
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman_alat
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations & seeders
```bash
php artisan migrate
php artisan db:seed
```

6. Build assets
```bash
npm run build
```

7. Start server
```bash
php artisan serve
```

## 👤 Default Users

Setelah menjalankan seeder, Anda dapat login dengan:

- **Admin**: admin@example.com / password
- **Petugas**: petugas@example.com / password
- **Peminjam**: peminjam@example.com / password

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/     # Controller untuk Admin
│   │   ├── Petugas/   # Controller untuk Petugas
│   │   └── Peminjam/  # Controller untuk Peminjam
│   └── Middleware/    # Middleware untuk role-based access
├── Models/            # Eloquent Models
└── Traits/            # LogActivity trait

database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders

resources/
└── views/
    ├── admin/        # Views untuk Admin
    ├── petugas/      # Views untuk Petugas
    ├── peminjam/     # Views untuk Peminjam
    └── layouts/      # Layout templates
```

## 🗄️ Database Schema

### Tables
- `users` - Data pengguna
- `kategori` - Kategori alat
- `alat` - Data alat
- `peminjaman` - Data peminjaman
- `detail_peminjaman` - Detail alat yang dipinjam
- `pengembalian` - Data pengembalian
- `log_aktivitas` - Log aktivitas sistem

## 📝 Notes

- Semua views menggunakan Bootstrap 5
- PDF reports menggunakan DomPDF
- Activity logging otomatis untuk semua aksi penting
- Stok alat otomatis berkurang saat peminjaman disetujui
- Stok alat otomatis bertambah saat pengembalian
- Denda dihitung otomatis jika terlambat (Rp 10.000/hari)

## 🐛 Troubleshooting

Jika ada masalah:
1. Pastikan semua dependencies terinstall
2. Pastikan database sudah dibuat
3. Jalankan `php artisan migrate:fresh --seed` untuk reset database
4. Pastikan storage link sudah dibuat: `php artisan storage:link`

## 📄 License

MIT License
