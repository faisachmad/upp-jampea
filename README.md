# SILAPOR UPP Jampea

**Sistem Informasi Laporan Pelabuhan - Unit Penyelenggara Pelabuhan Jampea**

Aplikasi web untuk mengelola dan melaporkan data kunjungan kapal di wilayah kerja UPP Jampea, Kabupaten Kepulauan Selayar, Sulawesi Selatan.

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [Dokumentasi](#dokumentasi)
- [Kontributor](#kontributor)
- [Lisensi](#lisensi)

---

## 🎯 Tentang Aplikasi

SILAPOR UPP Jampea adalah sistem informasi berbasis web yang dirancang untuk:

- **Mengelola data master** pelabuhan, kapal, nakhoda, dan barang berbahaya (B3)
- **Mencatat kunjungan kapal** dengan detail lengkap (kedatangan, keberangkatan, penumpang, kendaraan, muatan, B3)
- **Menghasilkan laporan** sesuai jenis pelayaran (PELRA, DN, LN, Perintis, Ferry)
- **Menyajikan dashboard** statistik dan grafik kunjungan kapal

Aplikasi ini mendukung operasional 6 pelabuhan di wilayah UPP Jampea:
1. **Pelabuhan Jampea** (Pelabuhan Utama)
2. Posker Pamatata
3. Posker Kayuangin
4. Wilker Bonerate
5. Wilker Rajuni
6. Wilker Tarupa

---

## ✨ Fitur Utama

### 1. Master Data Management
- ✅ **Master Kapal**: Kelola data kapal (KLM, KM, KMP, MV) dengan detail GT, DWT, panjang, call sign
- ✅ **Master Pelabuhan**: Data pelabuhan internal (UPP/Posker/Wilker) dan eksternal
- ✅ **Master Nakhoda**: Data kapten kapal yang bertugas
- ✅ **Master Barang B3**: Database barang berbahaya dengan UN Number dan klasifikasi

### 2. Input Kunjungan Kapal (Multi-Tab Wizard)
- ✅ **Tab 1 - Data Kunjungan**: Pelabuhan, jenis pelayaran, kapal (autocomplete), nakhoda, periode
- ✅ **Tab 2 - Kedatangan & Keberangkatan**: Tanggal, waktu, pelabuhan asal/tujuan, SPB
- ✅ **Tab 3 - Penumpang & Kendaraan**: Jumlah penumpang (dewasa/anak), kendaraan Gol I-V
- ✅ **Tab 4 - Data Muatan**: Muatan lanjutan, muatan bongkar/muat (ton, hewan)
- ✅ **Tab 5 - Barang B3**: Detail barang berbahaya dengan jenis kegiatan

### 3. Laporan (Coming Soon)
- 📋 Laporan PELRA (Pelayaran Rakyat)
- 📋 Laporan DN (Dalam Negeri)
- 📋 Laporan LN (Luar Negeri)
- 📋 Laporan Perintis
- 📋 Laporan Ferry ASDP
- 📋 Laporan Ferry DJPD

### 4. Dashboard (Coming Soon)
- 📊 Statistik kunjungan bulanan
- 📈 Grafik tren kunjungan
- 🏆 Top 10 kapal dan pelabuhan
- 📅 Ringkasan data tahunan

---

## 🛠 Teknologi

### Backend
- **Laravel** 12.x (PHP 8.3)
- **PostgreSQL** 16
- **Laravel Breeze** (Authentication)

### Frontend
- **Blade Template Engine**
- **Tailwind CSS** 4
- **Alpine.js** 3
- **Vite** (Asset Bundling)

### Additional Libraries
- **Faker** (Data Seeding)
- **Laravel Pint** (Code Formatting)
- **PHPUnit** (Testing)

---

## 📦 Persyaratan Sistem

- **PHP**: >= 8.2
- **PostgreSQL**: >= 14
- **Composer**: >= 2.0
- **Node.js**: >= 18.x
- **NPM**: >= 9.x

### Ekstensi PHP yang Dibutuhkan
```
- pdo_pgsql
- mbstring
- openssl
- tokenizer
- xml
- ctype
- json
- bcmath
```

---

## 🚀 Instalasi

### Quick Start

```bash
# 1. Clone repository
git clone <repository-url>
cd silapor-upp-jampea

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup
# Edit .env dengan kredensial database PostgreSQL Anda
php artisan migrate
php artisan db:seed

# 5. Build assets
npm run dev

# 6. Start server
php artisan serve
```

📖 **Panduan lengkap**: Lihat [INSTALLATION.md](INSTALLATION.md)

---

## 📖 Penggunaan

### Login
Akses aplikasi di `http://localhost:8000` dan login dengan:
- **Email**: admin@uppjampea.id
- **Password**: password

### Workflow Pencatatan Kunjungan
1. **Persiapan**: Pastikan data master (pelabuhan, kapal, nakhoda) sudah lengkap
2. **Input Kunjungan**: Buka menu **Kunjungan** > **Tambah Kunjungan**
3. **Isi Form 5 Tab**:
   - Tab 1: Pilih pelabuhan dan kapal (gunakan autocomplete)
   - Tab 2: Input waktu kedatangan dan keberangkatan
   - Tab 3: Input jumlah penumpang dan kendaraan
   - Tab 4: Tambah data muatan (klik "Tambah Muatan")
   - Tab 5: Tambah barang B3 jika ada (klik "Tambah B3")
4. **Submit**: Klik "Simpan Kunjungan"
5. **Lihat Detail**: Kunjungan tersimpan dan dapat dilihat di daftar

📘 **Panduan lengkap**: Lihat [USER_GUIDE.md](USER_GUIDE.md)

---

## 📚 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [INSTALLATION.md](INSTALLATION.md) | Panduan instalasi lengkap dan troubleshooting |
| [DATABASE.md](DATABASE.md) | Struktur database, relasi, dan ERD |
| [API.md](API.md) | Dokumentasi endpoint API autocomplete |
| [USER_GUIDE.md](USER_GUIDE.md) | Panduan pengguna lengkap dengan screenshot |
| [DEVELOPMENT.md](DEVELOPMENT.md) | Panduan development dan kontribusi |

---

## 🗂 Struktur Direktori

```
silapor-upp-jampea/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/
│   │   │   ├── KapalSearchController.php
│   │   │   └── PelabuhanSearchController.php
│   │   ├── Master/
│   │   │   ├── KapalController.php
│   │   │   ├── PelabuhanController.php
│   │   │   ├── NakhodaController.php
│   │   │   └── BarangB3Controller.php
│   │   └── KunjunganController.php
│   └── Models/
│       ├── Kapal.php
│       ├── Pelabuhan.php
│       ├── Nakhoda.php
│       ├── BarangB3.php
│       ├── JenisPelayaran.php
│       ├── Kunjungan.php
│       ├── KunjunganMuatan.php
│       └── KunjunganB3.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── master/
│   │   │   ├── kapal/
│   │   │   ├── pelabuhan/
│   │   │   ├── nakhoda/
│   │   │   └── barang-b3/
│   │   ├── kunjungan/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── show.blade.php
│   │   └── layouts/
│   └── css/
└── routes/
    ├── web.php
    └── auth.php
```

---

## 👥 Kontributor

Dikembangkan oleh Tim IT UPP Jampea

---

## 📄 Lisensi

Aplikasi ini adalah properti eksklusif **UPP Jampea** dan hanya untuk penggunaan internal instansi.

---

## 📞 Kontak

**Unit Penyelenggara Pelabuhan Jampea**  
Kabupaten Kepulauan Selayar, Sulawesi Selatan

---

## 🔄 Update Log

### v1.0.0 (Maret 2026)
- ✅ Master Data Management (Kapal, Pelabuhan, Nakhoda, Barang B3)
- ✅ Form Input Kunjungan (5-tab wizard)
- ✅ Autocomplete Search (Kapal, Pelabuhan)
- ✅ Detail View Kunjungan
- ✅ List & Filter Kunjungan

### v1.1.0 (Planned)
- 📋 Modul Laporan (6 jenis pelayaran)
- 📊 Dashboard & Analytics
- 📤 Export Excel/PDF
- 🔍 Advanced Search & Filters

---

**© 2026 UPP Jampea. All Rights Reserved.**

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
