# SILAPOR - Sistem Laporan UPP Kelas III Jampea

Aplikasi untuk mengelola data kunjungan kapal dan generate laporan E-Performance UPP Kelas III Jampea.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Database:** PostgreSQL 16+
- **Frontend:** Blade Templates + Alpine.js + Tailwind CSS 4
- **Auth:** Laravel Breeze

## Requirements

- PHP 8.4+
- Composer
- PostgreSQL 16+
- Node.js 18+ & NPM

## Installation

### 1. Clone & Install Dependencies

```bash
cd silapor-upp-jampea
composer install
npm install
```

### 2. Environment Configuration

Copy `.env` file dan sesuaikan database credentials:

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=silapor_upp_jampea
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

**PENTING:** Pastikan SESSION_DRIVER, CACHE_STORE, dan QUEUE_CONNECTION sudah set seperti berikut (untuk production database safety):

```bash
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 3. Create Database

Buat database PostgreSQL:

```sql
CREATE DATABASE silapor_upp_jampea;
```

### 4. Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

Ini akan membuat semua tabel dan mengisi data master awal:
- 24 Pelabuhan (6 internal UPP + 18 pelabuhan luar)
- 6 Jenis Pelayaran (PELRA, DN, LN, Perintis, Ferry ASDP, Ferry DJPD)
- 33 Kapal
- 6 Barang B3
- 1 Admin user (email: admin@uppjampea.id, password: password)

### 5. Build Assets

Development mode (with hot reload):
```bash
npm run dev
```

Production build:
```bash
npm run build
```

### 6. Run Development Server

```bash
php artisan serve
```

Buka browser: [http://localhost:8000](http://localhost:8000)

**Login:**
- Email: `admin@uppjampea.id`
- Password: `password`

## Database Schema

### Master Tables
- `pelabuhans` - Master pelabuhan (internal UPP + pelabuhan luar)
- `jenis_pelayarans` - Master jenis pelayaran (fixed 6 jenis)
- `kapals` - Master kapal
- `nakhodas` - Master nakhoda
- `barang_b3s` - Master barang berbahaya B3

### Transaction Tables
- `kunjungans` - **TABEL UTAMA** - 1 row = 1 kunjungan kapal
- `kunjungan_muatans` - Detail bongkar/muat per kunjungan
- `kunjungan_b3s` - Detail barang berbahaya per kunjungan

## Development Progress

### ✅ FASE 1 — Foundation (COMPLETED)
- [x] Setup Laravel 12 project
- [x] Config PostgreSQL connection
- [x] Migration semua tabel (master + transaksi)
- [x] Seeder data awal (pelabuhan, kapal, nakhoda, jenis pelayaran, B3)
- [x] Auth (login/register) + role sederhana
- [x] Layout utama (sidebar + topbar) with Tailwind

### 🔜 FASE 2 — Master Data CRUD (Sprint 3)
- [ ] CRUD Pelabuhan
- [ ] CRUD Kapal
- [ ] CRUD Nakhoda
- [ ] CRUD Barang B3
- [ ] View Jenis Pelayaran
- [ ] API endpoints untuk autocomplete

### 🔜 FASE 3 — Form Input Kunjungan (Sprint 4-6) ← CORE
- [ ] Form wizard 4 step (Data Kapal, Tiba/Tolak, Muatan, SPB/B3)
- [ ] Conditional logic per jenis pelayaran
- [ ] AJAX autocomplete
- [ ] Dynamic repeater bongkar/muat
- [ ] List kunjungan + filter + pagination

### 🔜 FASE 4 — Laporan (Sprint 7-8)
- [ ] Query agregasi per report
- [ ] Preview di browser
- [ ] Export Excel multi-sheet (Data Dukung)

### 🔜 FASE 5 — Dashboard & Polish (Sprint 9-10)
- [ ] Dashboard: summary cards + charts
- [ ] Status input per bulan
- [ ] Audit log
- [ ] Testing & deploy

## File Structure

```
app/
├── Models/              # Eloquent models
├── Http/Controllers/    # Controllers (Dashboard, Master, Kunjungan, Laporan)
├── Http/Requests/       # Form validation
└── Services/            # Business logic (Laporan, Excel Export)

resources/views/
├── dashboard.blade.php
├── master/              # CRUD master data
├── kunjungan/           # Form input kunjungan
├── laporan/             # Laporan & export
├── layouts/
│   └── app.blade.php    # Main layout (sidebar + topbar)
└── components/
    ├── sidebar.blade.php
    ├── topbar.blade.php
    └── ...              # Reusable components

database/
├── migrations/          # Database schema
└── seeders/             # Data awal master
```

## Features

### Master Data
- ✅ Master Pelabuhan (internal UPP + pelabuhan luar)
- ✅ Master Kapal (33 kapal dengan GT, call sign, pemilik)
- ✅ Master Jenis Pelayaran (6 jenis)
- ✅ Master Barang B3 (6 item dengan UN number, kelas)

### Input Kunjungan (Coming Soon)
- 🔜 1 Form Unified (wizard 4 step)
- 🔜 Autocomplete kapal & pelabuhan
- 🔜 Conditional fields per jenis pelayaran
- 🔜 Dynamic repeater bongkar/muat & B3

### Laporan (Coming Soon)
- 🔜 7 Laporan auto-generated (PELRA, Perintis, Ferry, DN, LN, Rekap SPB, Rekap Ops)
- 🔜 Export Excel Data Dukung (multi-sheet)

### Dashboard (Partial)
- ✅ Layout dashboard dengan sidebar & topbar
- 🔜 Summary cards (total kunjungan, GT, penumpang, barang)
- 🔜 Charts (kunjungan per bulan, komposisi jenis pelayaran)
- 🔜 Status input per bulan

## Contributing

Untuk melanjutkan development:

1. Pilih task dari FASE 2 atau FASE 3
2. Buat branch baru dari `main`
3. Commit dengan message yang jelas
4. Test di local sebelum push

## License

Private project - UPP Kelas III Jampea

---

**Dokumentasi lengkap:** Lihat `PLAN-DESIGN-APLIKASI.md`
