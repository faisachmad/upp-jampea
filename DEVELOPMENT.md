# Panduan Development SAPOJAM UPP Jampea

Panduan untuk developer yang ingin berkontribusi atau melanjutkan development aplikasi SAPOJAM UPP Jampea.

---

## 📋 Daftar Isi

- [Getting Started](#getting-started)
- [Project Structure](#project-structure)
- [Architecture Overview](#architecture-overview)
- [Coding Standards](#coding-standards)
- [Development Workflow](#development-workflow)
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)

---

## 🚀 Getting Started

### Prerequisites

Pastikan Anda sudah install:
- PHP 8.3+
- PostgreSQL 16
- Composer 2.7+
- Node.js 20+
- Git

### Clone & Setup

```bash
# Clone repository
git clone <repository-url>
cd sapojam-upp-jampea

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database (edit .env terlebih dahulu)
php artisan migrate
php artisan db:seed

# Build assets
npm run dev

# Start server
php artisan serve --port=8181
```

### IDE Setup (Recommended)

**VS Code Extensions**:
- Laravel Extension Pack
- PHP Intelephense
- Tailwind CSS IntelliSense
- Alpine.js IntelliSense
- PostgreSQL
- GitLens

**PHPStorm Plugins**:
- Laravel Plugin
- .env files support
- Tailwind CSS
- Alpine.js support

---

## 📁 Project Structure

### Directory Overview

```
sapojam-upp-jampea/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # API controllers (autocomplete)
│   │   │   ├── Master/           # Master data controllers
│   │   │   ├── KunjunganController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   └── Requests/             # Form Request validation (coming soon)
│   ├── Models/                   # Eloquent models
│   ├── Providers/
│   └── View/                     # View composers (if needed)
│
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Data seeders
│
├── resources/
│   ├── css/
│   │   └── app.css              # Tailwind CSS
│   ├── js/
│   │   └── app.js               # Alpine.js & Vite entry
│   └── views/
│       ├── components/          # Blade components (sidebar, topbar)
│       ├── layouts/
│       │   └── app.blade.php    # Main layout
│       ├── master/              # Master data views
│       │   ├── kapal/
│       │   ├── pelabuhan/
│       │   ├── nakhoda/
│       │   └── barang-b3/
│       ├── kunjungan/           # Kunjungan views
│       └── auth/                # Authentication views
│
├── routes/
│   ├── web.php                  # Web routes
│   ├── auth.php                 # Auth routes (Breeze)
│   └── console.php              # Artisan commands
│
├── public/                      # Public assets
├── storage/                     # Storage (logs, cache, uploads)
├── tests/                       # Tests (feature & unit)
│
├── .env.example                 # Environment template
├── composer.json                # PHP dependencies
├── package.json                 # JS dependencies
├── tailwind.config.js           # Tailwind configuration
└── vite.config.js               # Vite configuration
```

### Key Files

| File | Purpose |
|------|---------|
| `routes/web.php` | Define all web routes |
| `app/Models/*.php` | Eloquent models with relationships |
| `database/migrations/*.php` | Database schema definitions |
| `resources/views/layouts/app.blade.php` | Master layout template |
| `tailwind.config.js` | Tailwind CSS customization |
| `vite.config.js` | Asset bundling configuration |

---

## 🏗 Architecture Overview

### MVC Pattern

```
Request → Route → Controller → Model → Database
                      ↓
                    View (Blade) → Response
```

**Example Flow**:
1. User akses `/kunjungan/create`
2. Route match ke `KunjunganController@create`
3. Controller load data (pelabuhans, jenis_pelayarans)
4. Return view `kunjungan.create` dengan data
5. Blade render HTML + Alpine.js
6. User submit form → POST `/kunjungan`
7. Controller validate → Create kunjungan (with transaction) → Redirect

### Database Transactions

Untuk menjaga integritas data, gunakan transaction saat create/update/delete data yang terkait:

```php
use Illuminate\Support\Facades\DB;

public function store(Request $request)
{
    DB::beginTransaction();
    
    try {
        // Create main record
        $kunjungan = Kunjungan::create($validatedData);
        
        // Create related records
        foreach ($request->muatan as $muatan) {
            $kunjungan->kunjunganMuatans()->create($muatan);
        }
        
        DB::commit();
        return redirect()->route('kunjungan.show', $kunjungan);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}
```

### Eloquent Relationships

**hasMany Example**:
```php
// Kunjungan.php
public function kunjunganMuatans()
{
    return $this->hasMany(KunjunganMuatan::class);
}
```

**belongsTo Example**:
```php
// KunjunganMuatan.php
public function kunjungan()
{
    return $this->belongsTo(Kunjungan::class);
}
```

**Eager Loading (N+1 Prevention)**:
```php
// Bad (N+1 queries)
$kunjungans = Kunjungan::all();
foreach ($kunjungans as $k) {
    echo $k->pelabuhan->nama; // N additional queries
}

// Good (2 queries)
$kunjungans = Kunjungan::with('pelabuhan')->get();
foreach ($kunjungans as $k) {
    echo $k->pelabuhan->nama; // No additional query
}
```

### Query Scopes

Reusable query logic in models:

```php
// Kunjungan.php
public function scopeByPeriode($query, $bulan, $tahun)
{
    return $query->where('bulan', $bulan)
                 ->where('tahun', $tahun);
}

public function scopeByPelabuhan($query, $pelabuhan_id)
{
    return $query->where('pelabuhan_id', $pelabuhan_id);
}

// Usage in controller
$kunjungans = Kunjungan::byPeriode(3, 2026)
                       ->byPelabuhan(1)
                       ->get();
```

---

## 📝 Coding Standards

### PHP (PSR-12)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Display a listing of kunjungans.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::query();

        // Apply filters
        if ($request->filled('bulan')) {
            $query->byPeriode($request->bulan, $request->tahun);
        }

        // Eager load relationships
        $kunjungans = $query->with([
            'pelabuhan',
            'kapal',
            'jenisPelayaran',
            'nakhoda',
        ])->latest('tgl_datang')->paginate(20);

        return view('kunjungan.index', compact('kunjungans'));
    }
}
```

**Rules**:
- 4 spaces indentation (NOT tabs)
- CamelCase for classes: `KunjunganController`
- camelCase for methods: `index()`, `store()`
- snake_case for variables: `$pelabuhan_id`, `$kunjungans`
- DocBlocks for public methods
- Type hints for parameters and return types

### Blade Templates

```blade
{{-- Use dash-separated file names --}}
{{-- resources/views/kunjungan/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Kunjungan')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Tambah Kunjungan</h1>
        
        {{-- Form here --}}
    </div>
@endsection

@push('scripts')
<script>
    // Page-specific JavaScript
</script>
@endpush
```

**Rules**:
- Use `@extends` for layout inheritance
- Use `@section` for content blocks
- Use `@push` for scripts/styles
- Avoid inline styles, use Tailwind classes
- Comment complex logic with `{{-- --}}`

### JavaScript (Alpine.js)

```html
<div x-data="{
    currentTab: 1,
    muatans: [],
    addMuatan() {
        this.muatans.push({
            tipe: '',
            jenis_barang: '',
            ton_m3: ''
        });
    },
    removeMuatan(index) {
        this.muatans.splice(index, 1);
    }
}">
    <!-- Tab navigation -->
    <button @click="currentTab = 1">Tab 1</button>
    
    <!-- Tab content -->
    <div x-show="currentTab === 1">
        <!-- Content -->
    </div>
    
    <!-- Dynamic muatan list -->
    <template x-for="(muatan, index) in muatans" :key="index">
        <div>
            <input x-model="muatan.jenis_barang" />
            <button @click="removeMuatan(index)">Hapus</button>
        </div>
    </template>
    
    <button @click="addMuatan()">Tambah</button>
</div>
```

**Rules**:
- Use Alpine.js for reactive UI
- Use `x-data` for component state
- Use `x-model` for two-way binding
- Use `x-show` for conditional display
- Use `@click` shorthand (not `x-on:click`)

### CSS (Tailwind)

```html
<!-- Use utility classes -->
<div class="bg-white rounded-lg shadow-md p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Title</h2>
    <p class="text-gray-600 mb-4">Description</p>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Action
    </button>
</div>
```

**Rules**:
- Prefer Tailwind utilities over custom CSS
- Use consistent spacing scale (4, 6, 8, etc.)
- Use semantic colors (blue for primary, red for danger)
- Responsive design: `md:`, `lg:` prefixes

---

## 🔄 Development Workflow

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/laporan-pelra

# Make changes and commit
git add .
git commit -m "feat: add PELRA report generation"

# Push to remote
git push origin feature/laporan-pelra

# Create Pull Request on GitHub/GitLab
```

### Commit Messages (Conventional Commits)

```
feat: add new feature
fix: bug fix
docs: documentation changes
style: code style changes (formatting)
refactor: code refactoring
test: add or update tests
chore: maintenance tasks
```

**Examples**:
```
feat: add dashboard statistics chart
fix: resolve autocomplete bug for kapal search
docs: update API documentation for pelabuhan endpoint
refactor: optimize kunjungan query with eager loading
```

### Branch Strategy

- `main` - Production-ready code
- `develop` - Development branch
- `feature/*` - New features
- `fix/*` - Bug fixes
- `hotfix/*` - Urgent production fixes

### Code Review Checklist

Before merging:
- [ ] Code follows PSR-12 standards
- [ ] All tests passing
- [ ] No console errors/warnings
- [ ] Database migrations tested
- [ ] Documentation updated
- [ ] No hardcoded values
- [ ] Error handling implemented
- [ ] Security vulnerabilities checked

---

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/KunjunganTest.php

# Run with coverage
php artisan test --coverage
```

### Feature Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kunjungan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KunjunganTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_kunjungan_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get('/kunjungan');
        
        $response->assertStatus(200);
        $response->assertViewIs('kunjungan.index');
    }

    public function test_user_can_create_kunjungan()
    {
        $user = User::factory()->create();
        
        $data = [
            'pelabuhan_id' => 1,
            'jenis_pelayaran_id' => 1,
            'kapal_id' => 1,
            'bulan' => 3,
            'tahun' => 2026,
            'tgl_datang' => '2026-03-12',
            'jam_datang' => '08:00',
            // ... other required fields
        ];
        
        $response = $this->actingAs($user)
                         ->post('/kunjungan', $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('kunjungans', [
            'pelabuhan_id' => 1,
            'kapal_id' => 1,
        ]);
    }
}
```

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Kunjungan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KunjunganModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_kunjungan_has_pelabuhan_relationship()
    {
        $kunjungan = Kunjungan::factory()->create();
        
        $this->assertInstanceOf(\App\Models\Pelabuhan::class, $kunjungan->pelabuhan);
    }

    public function test_scope_by_periode_filters_correctly()
    {
        Kunjungan::factory()->create(['bulan' => 3, 'tahun' => 2026]);
        Kunjungan::factory()->create(['bulan' => 4, 'tahun' => 2026]);
        
        $results = Kunjungan::byPeriode(3, 2026)->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals(3, $results->first()->bulan);
    }
}
```

---

## 🚀 Deployment

### Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Database migrations up-to-date
- [ ] .env configured for production
- [ ] APP_DEBUG=false
- [ ] Assets compiled (`npm run build`)
- [ ] Cache cleared
- [ ] Permissions set correctly

### Deployment Steps

```bash
# 1. Pull latest code
git pull origin main

# 2. Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# 3. Run migrations (CAREFUL!)
php artisan migrate --force

# 4. Build assets
npm run build

# 5. Clear & cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set permissions
chmod -R 775 storage bootstrap/cache

# 7. Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx
```

### Zero-Downtime Deployment (Advanced)

Use Laravel Envoy or Deployer for automated deployment:

```bash
# Install Envoy
composer require laravel/envoy --dev

# Deploy
envoy run deploy
```

### Rollback Strategy

```bash
# Rollback migration (last batch)
php artisan migrate:rollback

# Rollback to specific migration
php artisan migrate:rollback --step=5

# Git rollback
git reset --hard HEAD~1
git push origin main --force
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. Mix Manifest Not Found

**Error**: `Unable to locate Mix file: /build/manifest.json`

**Solution**:
```bash
npm run build
php artisan cache:clear
```

#### 2. Class Not Found

**Error**: `Class 'App\Models\Kunjungan' not found`

**Solution**:
```bash
composer dump-autoload
```

#### 3. Database Connection Error

**Error**: `SQLSTATE[08006] Connection refused`

**Solution**:
- Check PostgreSQL is running: `sudo systemctl status postgresql`
- Verify .env credentials
- Check `pg_hba.conf` for access rules

#### 4. Permission Denied

**Error**: `file_put_contents(...): Failed to open stream: Permission denied`

**Solution**:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

#### 5. CSRF Token Mismatch

**Error**: `419 | Page Expired`

**Solution**:
- Clear cache: `php artisan cache:clear`
- Check session driver in .env: `SESSION_DRIVER=file`
- Ensure `@csrf` in forms

### Debugging Tools

**Laravel Debugbar** (Development only):
```bash
composer require barryvdh/laravel-debugbar --dev
```

**Laravel Telescope** (Advanced debugging):
```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

**Logs**:
```bash
# View real-time logs
tail -f storage/logs/laravel.log

# Search for errors
grep "ERROR" storage/logs/laravel.log
```

---

## 📚 Additional Resources

### Laravel Documentation

- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
- [Blade Templates](https://laravel.com/docs/12.x/blade)
- [Validation](https://laravel.com/docs/12.x/validation)

### Frontend Resources

- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [Vite](https://vitejs.dev/)

### PostgreSQL

- [PostgreSQL 16 Docs](https://www.postgresql.org/docs/16/)
- [pgAdmin](https://www.pgadmin.org/)

### Tools

- [Laravel Pint - Code Formatter](https://github.com/laravel/pint)
- [PHPStan - Static Analysis](https://phpstan.org/)
- [Larastan](https://github.com/nunomaduro/larastan)

---

## 🎯 Next Steps (Roadmap)

### FASE 4 - Laporan (Coming Soon)

**TODO**:
- [ ] Create `LaporanController`
- [ ] Build laporan views (6 jenis)
- [ ] Implement Excel export (Laravel Excel)
- [ ] Implement PDF export (DomPDF/SnappyPDF)
- [ ] Add filter & preview

**Files to Create**:
```
app/Http/Controllers/LaporanController.php
resources/views/laporan/index.blade.php
resources/views/laporan/pelra.blade.php
resources/views/laporan/dn.blade.php
resources/views/laporan/ln.blade.php
resources/views/laporan/perintis.blade.php
resources/views/laporan/ferry-asdp.blade.php
resources/views/laporan/ferry-djpd.blade.php
```

### FASE 5 - Dashboard (Coming Soon)

**TODO**:
- [ ] Create dashboard statistics
- [ ] Implement charts (Chart.js/ApexCharts)
- [ ] Add widgets (total kunjungan, top kapal, etc.)
- [ ] Real-time updates (optional)

**Files to Create**:
```
app/Http/Controllers/DashboardController.php
resources/views/dashboard.blade.php
resources/js/charts.js
```

### Future Enhancements

- [ ] Multi-user roles & permissions (Admin, Operator, Viewer)
- [ ] Email notifications
- [ ] Activity logs
- [ ] Advanced search & filters
- [ ] Batch import (Excel/CSV)
- [ ] API documentation (Swagger/OpenAPI)
- [ ] Mobile responsive optimization
- [ ] PWA support

---

## 👥 Contributing

### How to Contribute

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'feat: add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Code of Conduct

- Write clean, readable code
- Follow coding standards
- Add tests for new features
- Update documentation
- Be respectful to other contributors

---

## 📄 License

This project is proprietary software owned by **UPP Jampea**.  
For internal use only. All rights reserved.

---

## 📞 Contact

**Development Team**  
UPP Jampea IT Division  
Email: dev@uppjampea.id

---

**Happy Coding! 🚀**

---

**© 2026 UPP Jampea - Development Guide v1.0**
