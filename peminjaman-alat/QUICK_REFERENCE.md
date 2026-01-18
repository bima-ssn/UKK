# 🔧 Quick Reference - Profile System

## File Locations

### Profile Views
```
resources/views/
├── admin/profile-show.blade.php      (Admin profile detail)
├── admin/profile-edit.blade.php      (Admin edit form)
├── petugas/profile-show.blade.php    (Petugas profile detail)
├── petugas/profile-edit.blade.php    (Petugas edit form)
├── peminjam/profile-show.blade.php   (Peminjam profile detail)
├── peminjam/profile-edit.blade.php   (Peminjam edit form)
└── layouts/
    ├── app.blade.php                  (Main layout with sidebar)
    ├── sidebar.blade.php              (Fixed sidebar)
    └── navbar.blade.php               (Top navigation with dropdown)
```

### Controllers
```
app/Http/Controllers/
└── ProfileController.php
    - show() : Display profile detail per role
    - edit() : Display edit form per role
    - update() : Handle update (name, email, password)
    - destroy() : Delete account
```

### Requests
```
app/Http/Requests/
└── ProfileUpdateRequest.php
    - name: required|string|max:255
    - email: required|email|unique|max:255
    - current_password: nullable|current_password
    - password: nullable|confirmed|min:8
```

---

## Key Features Implemented

### 1. Fixed Sidebar
- CSS: `position: fixed; left: 0; top: 0; bottom: 0; width: 16rem; z-index: 40`
- Content margin: `margin-left: 16rem` (ml-64)
- Responsive: Mobile users dapat scroll horizontal

### 2. Dropdown Navigation
- Framework: Alpine.js
- State: `x-data="{ open: false }"`
- Toggle: `@click="open = !open"`
- Transitions: x-transition directives

### 3. Role-Based Views
- Profile Display: 3 separate show views
- Profile Edit: 3 separate edit forms
- Styling: Color-coded per role
  - Admin: Purple (667eea, 764ba2)
  - Petugas: Red (f093fb, f5576c)
  - Peminjam: Blue (4facfe, 00f2fe)

### 4. Password Management
- Validation: current_password required
- Hashing: Laravel's Hash::make()
- Confirmation: password_confirmation field
- Update: Only if password provided

### 5. Form Validation
- Server-side: ProfileUpdateRequest validation
- Client-side: HTML5 validation (email type, required, etc)
- Error display: Flash messages & inline errors

---

## Routes Configuration

```php
// Profile Routes (in routes/web.php)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
```

---

## View Rendering Logic

### ProfileController::show()
```php
public function show(Request $request): View
{
    $user = $request->user();
    
    if ($user->isAdmin()) {
        $stats = [ /* admin stats */ ];
        return view('admin.profile-show', compact('stats'));
    }
    elseif ($user->isPetugas()) {
        $stats = [ /* petugas stats */ ];
        return view('petugas.profile-show', compact('stats'));
    }
    else {
        $stats = [ /* peminjam stats */ ];
        return view('peminjam.profile-show', compact('stats'));
    }
}
```

### ProfileController::edit()
```php
public function edit(Request $request): View
{
    $user = $request->user();
    
    if ($user->isAdmin()) {
        return view('admin.profile-edit', ['user' => $user]);
    }
    elseif ($user->isPetugas()) {
        return view('petugas.profile-edit', ['user' => $user]);
    }
    else {
        return view('peminjam.profile-edit', ['user' => $user]);
    }
}
```

---

## Statistics Queries

### Admin Stats
```php
$stats = [
    'users' => User::count(),
    'alats' => Alat::count(),
    'peminjamans' => Peminjaman::count(),
    'pengembalians' => Pengembalian::count(),
];
```

### Petugas Stats
```php
$stats = [
    'peminjamans' => Peminjaman::where('status', 'menunggu')->count(),
    'pengembalians' => Peminjaman::where('status', 'disetujui')
        ->whereDoesntHave('pengembalian')->count(),
];
```

### Peminjam Stats
```php
$stats = [
    'peminjamans' => Peminjaman::where('user_id', auth()->id())->count(),
    'pengembalians' => Pengembalian::whereHas('peminjaman', 
        fn($q) => $q->where('user_id', auth()->id()))->count(),
    'alats' => Alat::count(),
    'menunggu' => Peminjaman::where('user_id', auth()->id())
        ->where('status', 'menunggu')->count(),
    'disetujui' => Peminjaman::where('user_id', auth()->id())
        ->where('status', 'disetujui')->count(),
];
```

---

## User Model Methods

```php
// app/Models/User.php
public function isAdmin() {
    return $this->role === 'admin';
}

public function isPetugas() {
    return $this->role === 'petugas';
}

public function isPeminjam() {
    return $this->role === 'peminjam';
}
```

---

## CSS Classes Used

### Sidebar
- `fixed left-0 top-0 bottom-0 w-64 z-40` → Fixed positioning
- `bg-gradient-to-b from-gray-800 to-gray-900` → Gradient background
- `text-white` → Text color

### Profile Headers
- `profile-header` → Custom gradient styling
- Role-specific gradients in `@push('styles')`

### Forms
- `px-4 py-2 border border-gray-300 rounded-lg` → Base input styling
- `focus:ring-2 focus:ring-[color]-500 focus:border-transparent` → Focus state
- Color per role: purple/red/blue

### Buttons
- `px-6 py-2 bg-[color]-600 hover:bg-[color]-700 text-white rounded-lg font-semibold transition-colors`
- Color per role: purple/red/blue

---

## Alpine.js Features

```html
<!-- Dropdown Toggle -->
<div x-data="{ open: false }">
    <button @click="open = !open">
        {{ auth()->user()->name }}
        <svg :class="{ 'rotate-180': open }">...</svg>
    </button>
    
    <div x-show="open" 
         x-transition:enter="..." 
         x-transition:leave="..."
         @click.away="open = false">
        <!-- Menu items -->
    </div>
</div>
```

---

## Common Tasks

### Change Primary Color
1. Open: `resources/views/[role]/profile-show.blade.php`
2. Update: `@push('styles')` section gradient color
3. Update: Button colors in form pages
4. Update: Sidebar badge color in `sidebar.blade.php`

### Add New Statistic
1. Add query in `ProfileController::show()`
2. Add to `$stats` array
3. Display in corresponding profile-show.blade.php view

### Change Form Validation
1. Update: `ProfileUpdateRequest::rules()` method
2. Update: Form views if new fields added

### Add New Role
1. Create: `[role]/profile-show.blade.php`
2. Create: `[role]/profile-edit.blade.php`
3. Update: `ProfileController` show/edit methods
4. Add: Role badge styling in `sidebar.blade.php`

---

## Testing Commands

```bash
# Run database seeding
php artisan migrate:refresh --seed

# Build assets
npm run build

# Serve application
php artisan serve

# Check routes
php artisan route:list | grep profile

# Clear cache
php artisan cache:clear
php artisan route:cache
```

---

## Browser DevTools Tips

### Check Alpine.js
```javascript
// In console
window.Alpine
// Should return Alpine object if working correctly
```

### Debug Dropdown
```javascript
// In console while on dropdown
// Check if x-show is toggling
// Monitor open state changes
```

### Check CSS Classes
```javascript
// Right-click element → Inspect
// Check computed styles for fixed positioning
// Verify color values matching design
```

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Sidebar tidak fixed | Check `position: fixed` di app.blade.php |
| Dropdown tidak toggle | Verify Alpine.js loaded, check x-data binding |
| Profile page kosong | Check route caching: `php artisan route:clear` |
| Password tidak update | Check ProfileUpdateRequest validation rules |
| Styling tidak match | Run `npm run build`, clear browser cache |
| Auth failed | Verify auth middleware active, check session |

---

## Performance Tips

- Sidebar: Fixed → No reflow/repaint saat scroll
- Dropdown: Alpine.js → Lightweight DOM manipulation
- Stats: Single queries → No N+1 problems
- Assets: Built with Vite → Fast bundling

---

## Security Checklist

✅ CSRF protection (@csrf in forms)
✅ Password hashing (Hash::make)
✅ Password validation (current_password)
✅ Email uniqueness validation
✅ Auth middleware on routes
✅ Method spoofing for DELETE
✅ Input validation on all fields

---

## Future Enhancement Ideas

- [ ] Profile picture upload
- [ ] Two-factor authentication
- [ ] Activity log display
- [ ] Notification preferences
- [ ] Email verification reminder
- [ ] Account recovery options
- [ ] Login history
- [ ] Device management

---

## Version Info

- Laravel: 11.x
- Alpine.js: 3.x
- Tailwind CSS: 3.x
- PHP: 8.x+

Last Updated: January 18, 2026
Status: ✅ Production Ready
