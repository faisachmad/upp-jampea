# Changelog

All notable changes to SILAPOR UPP Jampea will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Planned
- Modul Laporan (6 jenis pelayaran)
- Dashboard & Analytics
- Export to Excel/PDF
- Advanced Search & Filters
- Multi-user Roles & Permissions
- Email Notifications
- Activity Logs

---

## [1.0.0] - 2026-03-12

### Added - FASE 1: Foundation
- ✅ Database schema design (9 tables)
- ✅ Laravel 12 authentication with Breeze
- ✅ PostgreSQL 16 database setup
- ✅ Main layout with sidebar & topbar components
- ✅ Tailwind CSS 4 integration
- ✅ Alpine.js 3 integration
- ✅ Vite asset bundling

**Migrations**:
- `create_pelabuhans_table` - Master pelabuhan
- `create_jenis_pelayarans_table` - Master jenis pelayaran
- `create_kapals_table` - Master kapal
- `create_nakhodas_table` - Master nakhoda
- `create_barang_b3s_table` - Master barang B3
- `create_kunjungans_table` - Transaksi kunjungan
- `create_kunjungan_muatans_table` - Detail muatan
- `create_kunjungan_b3s_table` - Detail B3

**Seeders**:
- `PelabuhanSeeder` - 6 pelabuhan internal UPP Jampea
- `JenisPelayaranSeeder` - 6 jenis pelayaran tetap
- `KapalSeeder` - 10 sample data kapal
- `BarangB3Seeder` - 20 jenis barang B3
- `DatabaseSeeder` - User admin default

### Added - FASE 2: Master Data CRUD
- ✅ Master Kapal CRUD (KapalController)
  - Index with search & filter (jenis, status)
  - Create, Edit, Delete with validation
  - Fields: nama, jenis (KLM/KM/KMP/MV), GT, DWT, call_sign, dll
- ✅ Master Pelabuhan CRUD (PelabuhanController)
  - Index with search & filter (tipe, status)
  - CRUD operations with validation
  - Fields: kode, nama, tipe (UPP/POSKER/WILKER/LUAR), is_active
- ✅ Master Nakhoda CRUD (NakhodaController)
  - Index with search & filter (kapal, status)
  - CRUD with kapal relationship
  - Fields: nama, kapal_id, is_active
- ✅ Master Barang B3 CRUD (BarangB3Controller)
  - Index with search & filter (kelas)
  - CRUD with UN Number validation
  - Fields: nama, un_number, kelas, kategori

**Views Created** (12 files):
- `resources/views/master/kapal/` (index, create, edit)
- `resources/views/master/pelabuhan/` (index, create, edit)
- `resources/views/master/nakhoda/` (index, create, edit)
- `resources/views/master/barang-b3/` (index, create, edit)

**Routes**: 28 routes (7 per resource controller)

### Added - FASE 3: Form Input Kunjungan
- ✅ KunjunganController with database transactions
  - `index()` - List with filters (bulan, tahun, pelabuhan, jenis)
  - `create()` - Load form data (pelabuhans, jenis_pelayarans)
  - `store()` - Save kunjungan + muatans + b3s in transaction
  - `show()` - Detail view with 8 eager loaded relationships
  - `edit()` - Edit form (coming soon)
  - `update()` - Update kunjungan (coming soon)
  - `destroy()` - Delete with cascade (muatans, b3s)

- ✅ Multi-tab Wizard Form (`create.blade.php`)
  - **Tab 1**: Data Kunjungan (pelabuhan, jenis, kapal autocomplete, nakhoda, periode)
  - **Tab 2**: Kedatangan & Keberangkatan (tgl/jam datang/tolak, pelabuhan asal/tujuan, SPB)
  - **Tab 3**: Penumpang & Kendaraan (4 penumpang fields, 12 kendaraan fields)
  - **Tab 4**: Data Muatan (lanjutan_ton + dynamic repeater)
  - **Tab 5**: Barang B3 (dynamic repeater)
  - Alpine.js components: `kunjunganForm()`, `autocomplete()`, `autocompleteNakhoda()`

- ✅ Detail View (`show.blade.php`)
  - 7 sections: Info Umum, Kedatangan/Keberangkatan, Penumpang, Kendaraan, Muatan, B3, Actions
  - Color-coded cards & tables
  - Delete confirmation dialog

- ✅ List View (`index.blade.php`)
  - Filter form (5 fields: bulan, tahun, pelabuhan_id, jenis_pelayaran_id)
  - Data table with 7 columns
  - Pagination (20 per page)
  - Detail & Delete actions

**Views Created** (3 files):
- `resources/views/kunjungan/create.blade.php` (545+ lines)
- `resources/views/kunjungan/show.blade.php` (300+ lines)
- `resources/views/kunjungan/index.blade.php` (150+ lines)

**Routes**: 7 routes (resource controller)

### Added - API Endpoints
- ✅ `/api/kapal/search` - Autocomplete kapal (search by nama, call_sign, pemilik)
- ✅ `/api/pelabuhan/search` - Autocomplete pelabuhan (search by kode, nama)
- ✅ `/api/pelabuhan/internal` - Get internal pelabuhans only (UPP/POSKER/WILKER)

**Controllers Created**:
- `app/Http/Controllers/Api/KapalSearchController.php`
- `app/Http/Controllers/Api/PelabuhanSearchController.php`

**Routes**: 3 API routes

### Fixed
- 🐛 Layout incompatibility: Changed `{{ $slot }}` to `@yield('content')` in `layouts/app.blade.php`
- 🐛 Added `@stack('scripts')` for JavaScript injection from views
- 🐛 Topbar title: Changed from `:title="$title ?? ..."` to `title="@yield('title', ...)"`

### Changed
- Layout pattern: Using traditional `@extends/@section` instead of Blade components
- Session driver: Set to `file` (not database) for production safety
- Timezone: Set to `Asia/Makassar` (WITA)
- Locale: Set to `id` (Indonesian)

### Security
- ✅ CSRF protection on all forms
- ✅ Session-based authentication (Laravel Breeze)
- ✅ Database transaction for data integrity
- ✅ Input validation on all controllers
- ✅ Foreign key constraints in database
- ✅ Cascade delete protection on master data

### Performance
- ✅ Eager loading relationships (N+1 prevention)
- ✅ Query scopes for reusable filters
- ✅ Database indexes on foreign keys & search fields
- ✅ Pagination on list views (20 per page)
- ✅ Autocomplete with debounce & limit 10 results

### Documentation
- ✅ README.md - Overview & quick start
- ✅ INSTALLATION.md - Panduan instalasi lengkap (development & production)
- ✅ DATABASE.md - Struktur database, ERD, relasi, indexing
- ✅ API.md - Dokumentasi API endpoints dengan contoh
- ✅ USER_GUIDE.md - Panduan pengguna lengkap step-by-step
- ✅ DEVELOPMENT.md - Panduan development, architecture, coding standards
- ✅ CHANGELOG.md - Version history (this file)

---

## Version History Summary

| Version | Date | Description |
|---------|------|-------------|
| 1.0.0 | 2026-03-12 | Initial release - FASE 1-3 complete |
| 1.1.0 | TBD | FASE 4 - Laporan & FASE 5 - Dashboard |

---

## Git Commit Timeline

### March 12, 2026
```
Initial commit - Laravel 12 setup
feat: add database migrations (9 tables)
feat: add database seeders (master data)
feat: add authentication with Breeze
feat: add layout (sidebar, topbar)
feat: add Master Kapal CRUD
feat: add Master Pelabuhan CRUD
feat: add Master Nakhoda CRUD
feat: add Master Barang B3 CRUD
feat: add KunjunganController (index, create, store, show, destroy)
feat: add wizard form for kunjungan (5 tabs)
feat: add autocomplete API endpoints
feat: add kunjungan detail & list views
fix: resolve layout incompatibility (slot → yield)
docs: add comprehensive documentation (6 markdown files)
```

---

## Future Releases

### v1.1.0 - Laporan & Dashboard (Planned Q2 2026)
**Planned Features**:
- [ ] Modul Laporan (6 jenis pelayaran)
- [ ] Export to Excel (Laravel Excel)
- [ ] Export to PDF (DomPDF)
- [ ] Dashboard statistics & charts
- [ ] Top 10 kapal & pelabuhan
- [ ] Trend analysis

### v1.2.0 - Enhancements (Planned Q3 2026)
**Planned Features**:
- [ ] Multi-user roles (Admin, Operator, Viewer)
- [ ] Email notifications
- [ ] Activity logs
- [ ] Advanced search
- [ ] Batch import (Excel/CSV)

### v2.0.0 - Major Update (Planned 2027)
**Planned Features**:
- [ ] REST API full dokumentasi
- [ ] Mobile app (Flutter/React Native)
- [ ] Real-time notifications (WebSocket)
- [ ] PWA support
- [ ] Multi-language (ID/EN)

---

## Migration Notes

### Upgrading from v0.x to v1.0.0
Not applicable - this is the initial release.

### Breaking Changes
None - initial release.

---

## Contributors

- **Development Team**: UPP Jampea IT Division
- **Project Manager**: [Name]
- **Lead Developer**: [Name]
- **Database Designer**: [Name]
- **UI/UX Designer**: [Name]

---

## Acknowledgments

- Laravel Community
- Tailwind CSS Team
- Alpine.js Team
- PostgreSQL Community

---

**For detailed changes, see Git commit history:**
```bash
git log --oneline --graph --all
```

---

**Last Updated**: March 12, 2026  
**Maintained By**: UPP Jampea Development Team
