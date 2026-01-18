# 🧪 Testing Guide - Profile & Sidebar Features

## 📋 Pre-Testing Checklist

✅ Database: Refreshed dengan seeding data
✅ Assets: Built with npm run build
✅ Routes: All profile routes registered
✅ Files: All profile views created (admin, petugas, peminjam)
✅ Controllers: ProfileController updated dengan role-based logic

---

## 🔑 Test Credentials

```
ADMIN:
Email: admin@example.com
Password: password

PETUGAS:
Email: petugas@example.com
Password: password

PEMINJAM:
Email: peminjam@example.com
Password: password
```

---

## 🧪 Test Scenarios

### TEST 1: Sidebar Positioning & Layout
**Expected Result:** Sidebar fixed, tidak bergeser saat scroll

Steps:
1. Login sebagai Admin
2. Perhatikan sidebar di sisi kiri (width 264px)
3. Scroll content area
4. ✅ Verify sidebar tetap fixed dan tidak bergerak

**File Terkait:**
- `resources/views/layouts/app.blade.php` - Fixed sidebar positioning
- `resources/views/layouts/sidebar.blade.php` - Sidebar styling

---

### TEST 2: Navbar Dropdown Menu
**Expected Result:** Dropdown menu berfungsi dengan smooth transition

Steps:
1. Lihat top-right navbar dengan nama user
2. Klik nama user → dropdown muncul
3. Verify 3 menu items: "Profil Saya", "Edit Profil", "Logout"
4. Klik di area lain → dropdown hilang
5. ✅ Verify Alpine.js transitions berfungsi smooth

**File Terkait:**
- `resources/views/layouts/navbar.blade.php`

---

### TEST 3: Admin Profile Display
**Expected Result:** Menampilkan dashboard profil Admin dengan stats

Steps:
1. Login sebagai **admin@example.com**
2. Klik navbar dropdown → "Profil Saya"
3. Verify header dengan gradient purple
4. Verify informasi ditampilkan:
   - Avatar dengan initial "A"
   - Nama user
   - Role: "Administrator"
   - Join date
5. Verify statistik sistem:
   - Total Users
   - Total Alat
   - Total Peminjaman
   - Total Pengembalian
6. Verify hak istimewa list
7. Verify quick actions buttons

**Expected Stats for Test Data:**
- Users: 3+ (admin, petugas, peminjam, etc)
- Alat: depends on seeded data
- Peminjaman: depends on seeded data
- Pengembalian: depends on seeded data

**File Terkait:**
- `resources/views/admin/profile-show.blade.php`

---

### TEST 4: Petugas Profile Display
**Expected Result:** Menampilkan dashboard profil Petugas dengan stats

Steps:
1. Logout dan login sebagai **petugas@example.com**
2. Klik navbar dropdown → "Profil Saya"
3. Verify header dengan gradient red/orange
4. Verify informasi:
   - Avatar dengan initial "P"
   - Nama user
   - Role: "Petugas"
   - Join date
5. Verify work statistics:
   - Peminjaman Menunggu
   - Pengembalian Disetujui
6. Verify tanggung jawab list
7. Verify quick actions (Kelola Peminjaman, Kelola Pengembalian, Lihat Alat)

**File Terkait:**
- `resources/views/petugas/profile-show.blade.php`

---

### TEST 5: Peminjam Profile Display
**Expected Result:** Menampilkan dashboard profil Peminjam

Steps:
1. Logout dan login sebagai **peminjam@example.com**
2. Klik navbar dropdown → "Profil Saya"
3. Verify header dengan gradient blue
4. Verify informasi:
   - Avatar dengan initial "P"
   - Nama user
   - Role: "Peminjam"
   - Join date
5. Verify peminjaman statistics:
   - Total Peminjaman
   - Total Pengembalian
   - Alat Tersedia
   - Peminjaman Menunggu
   - Peminjaman Disetujui
6. Verify quick actions buttons

**File Terkait:**
- `resources/views/peminjam/profile-show.blade.php`

---

### TEST 6: Admin Edit Profile
**Expected Result:** Form edit dengan proper validation

Steps:
1. Login sebagai **admin@example.com**
2. Klik navbar dropdown → "Edit Profil"
3. Verify form sections:
   - **Informasi Personal:** Nama, Email, Role (disabled), Tanggal Bergabung (disabled)
   - **Ubah Password:** Current password, Password baru, Confirm password
   - **Zona Bahaya:** Hapus Akun button

**Test Case 6a: Update Nama & Email**
- Ubah nama menjadi "Admin Baru"
- Ubah email menjadi "admin_baru@example.com"
- Click "Simpan Perubahan"
- ✅ Verify success message
- ✅ Verify data tersimpan (reload page untuk confirm)

**Test Case 6b: Ubah Password**
- Isi "Current Password": password
- Isi "Password Baru": newpassword123
- Isi "Confirm Password": newpassword123
- Click "Ubah Password"
- ✅ Verify success message
- ✅ Try login dengan password baru

**Test Case 6c: Form Validation**
- Kosongkan nama field
- Click "Simpan Perubahan"
- ✅ Verify error message: "Nama tidak boleh kosong"

**Test Case 6d: Email Validation**
- Ubah email menjadi format invalid
- ✅ Verify error message

**File Terkait:**
- `resources/views/admin/profile-edit.blade.php`
- `app/Http/Controllers/ProfileController.php` - update() method
- `app/Http/Requests/ProfileUpdateRequest.php`

---

### TEST 7: Petugas Edit Profile
**Expected Result:** Form edit dengan styling merah sesuai role

Steps:
1. Login sebagai **petugas@example.com**
2. Klik navbar dropdown → "Edit Profil"
3. Verify form sama seperti Admin tapi dengan focus ring merah
4. Test update nama, email, password (sama seperti TEST 6)

**File Terkait:**
- `resources/views/petugas/profile-edit.blade.php`

---

### TEST 8: Peminjam Edit Profile
**Expected Result:** Form edit dengan styling biru sesuai role

Steps:
1. Login sebagai **peminjam@example.com**
2. Klik navbar dropdown → "Edit Profil"
3. Verify form sama seperti Admin tapi dengan focus ring biru
4. Test update nama, email, password (sama seperti TEST 6)

**File Terkait:**
- `resources/views/peminjam/profile-edit.blade.php`

---

### TEST 9: Delete Account
**Expected Result:** Confirm dialog dan redirect ke login

Steps:
1. Login sebagai test user
2. Click "Edit Profil"
3. Scroll ke "Zona Bahaya"
4. Click "Hapus Akun Saya"
5. Verify confirmation dialog
6. Confirm deletion
7. Enter password untuk confirm
8. ✅ Verify logout dan redirect ke login page
9. ✅ Verify akun sudah tidak bisa login

---

### TEST 10: Sidebar Navigation
**Expected Result:** Menu items active sesuai current route

Steps:
1. Login sebagai Admin
2. Click menu "Dashboard" di sidebar
3. ✅ Verify menu highlighted
4. Click menu "Data Alat"
5. ✅ Verify menu highlighted
6. Repeat untuk Petugas dan Peminjam dengan menu mereka masing-masing

**File Terkait:**
- `resources/views/layouts/sidebar.blade.php`

---

### TEST 11: Responsive Design
**Expected Result:** UI tetap baik di mobile

Steps:
1. Open DevTools (F12)
2. Toggle Device Toolbar (mobile view)
3. Verify sidebar masih terlihat/accessible
4. Verify navbar dropdown tetap responsive
5. Verify profile form tetap readable

---

### TEST 12: Session & Auth
**Expected Result:** Profile hanya accessible saat authenticated

Steps:
1. Logout
2. Try akses `/profile` → ✅ Redirect ke login
3. Try akses `/profile/edit` → ✅ Redirect ke login
4. Login → ✅ Bisa akses profile

---

## 📊 Summary Test Results

| Test # | Feature | Status | Notes |
|--------|---------|--------|-------|
| 1 | Sidebar Positioning | ⚪ Pending | |
| 2 | Navbar Dropdown | ⚪ Pending | |
| 3 | Admin Profile | ⚪ Pending | |
| 4 | Petugas Profile | ⚪ Pending | |
| 5 | Peminjam Profile | ⚪ Pending | |
| 6a | Update Profile Info | ⚪ Pending | |
| 6b | Change Password | ⚪ Pending | |
| 6c | Form Validation | ⚪ Pending | |
| 7 | Petugas Edit | ⚪ Pending | |
| 8 | Peminjam Edit | ⚪ Pending | |
| 9 | Delete Account | ⚪ Pending | |
| 10 | Sidebar Navigation | ⚪ Pending | |
| 11 | Responsive Design | ⚪ Pending | |
| 12 | Auth & Session | ⚪ Pending | |

---

## 🔍 Debugging Tips

Jika ada masalah:

1. **Sidebar tidak fixed?**
   - Check `resources/views/layouts/app.blade.php` line 14-15
   - Verify class `fixed left-0 top-0 bottom-0 w-64 z-40`

2. **Dropdown tidak berfungsi?**
   - Check Alpine.js loaded di app.js
   - Verify `x-data="{ open: false }"` di navbar
   - Check browser console untuk errors

3. **Profile edit form kosong?**
   - Check ProfileController.php edit() method
   - Verify view file exists (admin/petugas/peminjam profile-edit.blade.php)
   - Check route cache: `php artisan route:cache`

4. **Password tidak bisa diubah?**
   - Check ProfileUpdateRequest.php validation rules
   - Verify password field punya validation rule
   - Check ProfileController update() method

5. **No styles?**
   - Run `npm run build`
   - Check Tailwind CSS output
   - Verify `@vite` directive di app.blade.php

---

## 🚀 Commands Untuk Testing

```bash
# Refresh database dengan test data
php artisan migrate:refresh --seed

# Build assets
npm run build

# Run dev server
php artisan serve

# Check routes
php artisan route:list | grep profile

# Clear cache if needed
php artisan cache:clear
php artisan route:cache
```

---

## ✅ Completion Checklist

- [ ] TEST 1: Sidebar Positioning passed
- [ ] TEST 2: Navbar Dropdown passed
- [ ] TEST 3: Admin Profile Display passed
- [ ] TEST 4: Petugas Profile Display passed
- [ ] TEST 5: Peminjam Profile Display passed
- [ ] TEST 6: Admin Edit Profile (all sub-tests) passed
- [ ] TEST 7: Petugas Edit Profile passed
- [ ] TEST 8: Peminjam Edit Profile passed
- [ ] TEST 9: Delete Account passed
- [ ] TEST 10: Sidebar Navigation passed
- [ ] TEST 11: Responsive Design passed
- [ ] TEST 12: Session & Auth passed

**All tests passed = Feature ready for production** ✨
