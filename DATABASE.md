# Dokumentasi Database SILAPOR UPP Jampea

Dokumen ini menjelaskan struktur database, relasi antar tabel, dan skema lengkap aplikasi SILAPOR UPP Jampea.

---

## 📋 Daftar Isi

- [Diagram Entity Relationship](#diagram-entity-relationship)
- [Daftar Tabel](#daftar-tabel)
- [Detail Struktur Tabel](#detail-struktur-tabel)
- [Relasi Antar Tabel](#relasi-antar-tabel)
- [Indexing Strategy](#indexing-strategy)
- [Data Seeding](#data-seeding)

---

## 🗂 Diagram Entity Relationship

```
┌─────────────────┐
│  jenis_pelayarans│
│  (Master)       │
└────────┬────────┘
         │ 1
         │
         │ N
┌────────┴────────┐      N ┌──────────────┐ 1      ┌──────────────┐
│   kunjungans    │────────│  nakhodas    │────────│   kapals     │
│  (Transaksi)    │        │  (Master)    │        │  (Master)    │
└────────┬────────┘        └──────────────┘        └──────────────┘
         │ 1
         │
         ├─────── N ┌──────────────────┐
         │          │ kunjungan_muatans│
         │          └──────────────────┘
         │
         └─────── N ┌──────────────────┐ N    ┌──────────────┐
                    │  kunjungan_b3s   │──────│  barang_b3s  │
                    └──────────────────┘ 1    │  (Master)    │
                                               └──────────────┘
         ┌──────────────┐
         │  pelabuhans  │ ────── 3 relasi ke kunjungans:
         │  (Master)    │        - pelabuhan_id (tempat kunjungan)
         └──────────────┘        - pelabuhan_asal_id
                                 - pelabuhan_tujuan_id
```

---

## 📊 Daftar Tabel

| No | Nama Tabel | Tipe | Jumlah Kolom | Keterangan |
|----|------------|------|--------------|------------|
| 1 | `users` | Auth | 8 | User authentication |
| 2 | `pelabuhans` | Master | 6 | Data pelabuhan (UPP/Posker/Wilker/Luar) |
| 3 | `jenis_pelayarans` | Master | 5 | Jenis pelayaran (PELRA/DN/LN/dll) |
| 4 | `kapals` | Master | 15 | Data kapal/vessel |
| 5 | `nakhodas` | Master | 5 | Data nakhoda/captain |
| 6 | `barang_b3s` | Master | 6 | Barang berbahaya (B3) |
| 7 | `kunjungans` | Transaksi | 35 | Data kunjungan kapal |
| 8 | `kunjungan_muatans` | Detail | 8 | Detail muatan per kunjungan |
| 9 | `kunjungan_b3s` | Detail | 11 | Detail B3 per kunjungan |

---

## 📑 Detail Struktur Tabel

### 1. Table: `users`

**Deskripsi**: User authentication dan authorization.

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| name | varchar(255) | NO | - | Nama user |
| email | varchar(255) | NO | - | Email (unique) |
| email_verified_at | timestamp | YES | NULL | Verifikasi email |
| password | varchar(255) | NO | - | Password hash |
| remember_token | varchar(100) | YES | NULL | Remember me token |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Indexes**:
- PRIMARY KEY: `id`
- UNIQUE: `email`

---

### 2. Table: `pelabuhans`

**Deskripsi**: Master data pelabuhan (internal UPP Jampea dan eksternal).

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| kode | varchar(10) | NO | - | Kode pelabuhan (unique) |
| nama | varchar(255) | NO | - | Nama pelabuhan |
| tipe | enum | NO | - | UPP/POSKER/WILKER/LUAR |
| is_active | boolean | NO | true | Status aktif |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Enum Values (tipe)**:
- `UPP` - Pelabuhan Utama
- `POSKER` - Pos Pengawasan Kerja
- `WILKER` - Wilayah Kerja
- `LUAR` - Pelabuhan eksternal (luar UPP Jampea)

**Indexes**:
- PRIMARY KEY: `id`
- UNIQUE: `kode`
- INDEX: `tipe`, `is_active`

**Sample Data**:
```sql
-- 6 Pelabuhan Internal
('JMP', 'Pelabuhan Jampea', 'UPP')
('PMT', 'Posker Pamatata', 'POSKER')
('KAY', 'Posker Kayuangin', 'POSKER')
('BON', 'Wilker Bonerate', 'WILKER')
('RAJ', 'Wilker Rajuni', 'WILKER')
('TAR', 'Wilker Tarupa', 'WILKER')
```

---

### 3. Table: `jenis_pelayarans`

**Deskripsi**: Fixed data jenis pelayaran (6 jenis).

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| kode | varchar(20) | NO | - | Kode jenis (unique) |
| nama | varchar(100) | NO | - | Nama lengkap |
| deskripsi | text | YES | NULL | Deskripsi |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Indexes**:
- PRIMARY KEY: `id`
- UNIQUE: `kode`

**Fixed Data**:
```sql
('PELRA', 'Pelayaran Rakyat', ...)
('DN', 'Dalam Negeri', ...)
('LN', 'Luar Negeri', ...)
('PERINTIS', 'Kapal Perintis', ...)
('FERRY-ASDP', 'Ferry ASDP', ...)
('FERRY-DJPD', 'Ferry DJPD', ...)
```

---

### 4. Table: `kapals`

**Deskripsi**: Master data kapal/vessel.

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| nama | varchar(255) | NO | - | Nama kapal |
| jenis | enum | NO | - | KLM/KM/KMP/MV |
| gt | decimal(10,2) | YES | NULL | Gross Tonnage |
| dwt | decimal(10,2) | YES | NULL | Dead Weight Tonnage |
| panjang | decimal(8,2) | YES | NULL | Panjang kapal (m) |
| tanda_selar | varchar(100) | YES | NULL | Tanda selar |
| call_sign | varchar(50) | YES | NULL | Call sign kapal |
| tempat_kedudukan | varchar(255) | YES | NULL | Tempat kedudukan |
| bendera | varchar(100) | YES | NULL | Bendera negara |
| pemilik_agen | varchar(255) | YES | NULL | Pemilik/agen |
| is_active | boolean | NO | true | Status aktif |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Enum Values (jenis)**:
- `KLM` - Kapal Layar Motor
- `KM` - Kapal Motor
- `KMP` - Kapal Motor Penyeberangan
- `MV` - Motor Vessel

**Indexes**:
- PRIMARY KEY: `id`
- INDEX: `jenis`, `is_active`, `nama`
- INDEX: `call_sign` (untuk search)

---

### 5. Table: `nakhodas`

**Deskripsi**: Master data nakhoda/kapten kapal.

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| nama | varchar(255) | NO | - | Nama nakhoda |
| kapal_id | bigint | YES | NULL | FK ke kapals |
| is_active | boolean | NO | true | Status aktif |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Foreign Keys**:
- `kapal_id` REFERENCES `kapals(id)` ON DELETE SET NULL

**Indexes**:
- PRIMARY KEY: `id`
- INDEX: `kapal_id`, `is_active`

---

### 6. Table: `barang_b3s`

**Deskripsi**: Master barang berbahaya (B3) dengan UN Number.

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| nama | varchar(255) | NO | - | Nama barang B3 |
| un_number | varchar(20) | NO | - | UN Number (unique) |
| kelas | varchar(10) | NO | - | Kelas B3 (1-9) |
| kategori | varchar(100) | YES | NULL | Kategori barang |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Indexes**:
- PRIMARY KEY: `id`
- UNIQUE: `un_number`
- INDEX: `kelas`

**Sample Data**:
```sql
('Bensin', 'UN1203', '3', 'Flammable Liquid')
('Solar', 'UN1202', '3', 'Flammable Liquid')
('LPG', 'UN1075', '2.1', 'Flammable Gas')
```

---

### 7. Table: `kunjungans` ⭐ (Tabel Utama)

**Deskripsi**: Transaksi kunjungan kapal (1 row = 1 kunjungan).

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| pelabuhan_id | bigint | NO | - | FK ke pelabuhans |
| jenis_pelayaran_id | bigint | NO | - | FK ke jenis_pelayarans |
| kapal_id | bigint | NO | - | FK ke kapals |
| nakhoda_id | bigint | YES | NULL | FK ke nakhodas |
| bulan | integer | NO | - | Bulan kunjungan (1-12) |
| tahun | integer | NO | - | Tahun kunjungan |
| **Kedatangan** | | | | |
| tgl_datang | date | NO | - | Tanggal datang |
| jam_datang | time | NO | - | Jam datang |
| pelabuhan_asal_id | bigint | YES | NULL | FK ke pelabuhans (asal) |
| no_spb_datang | varchar(100) | YES | NULL | No SPB kedatangan |
| **Keberangkatan** | | | | |
| tgl_tolak | date | YES | NULL | Tanggal tolak/berangkat |
| jam_tolak | time | YES | NULL | Jam tolak |
| pelabuhan_tujuan_id | bigint | YES | NULL | FK ke pelabuhans (tujuan) |
| no_spb_tolak | varchar(100) | YES | NULL | No SPB keberangkatan |
| **Penumpang** | | | | |
| pnp_datang_dewasa | integer | NO | 0 | Penumpang datang dewasa |
| pnp_datang_anak | integer | NO | 0 | Penumpang datang anak |
| pnp_tolak_dewasa | integer | NO | 0 | Penumpang tolak dewasa |
| pnp_tolak_anak | integer | NO | 0 | Penumpang tolak anak |
| **Kendaraan** | | | | |
| kend_datang_gol1 | integer | NO | 0 | Kendaraan datang Gol I |
| kend_datang_gol2 | integer | NO | 0 | Kendaraan datang Gol II |
| kend_datang_gol3 | integer | NO | 0 | Kendaraan datang Gol III |
| kend_datang_gol4a | integer | NO | 0 | Kendaraan datang Gol IVA |
| kend_datang_gol4b | integer | NO | 0 | Kendaraan datang Gol IVB |
| kend_datang_gol5 | integer | NO | 0 | Kendaraan datang Gol V |
| kend_tolak_gol1 | integer | NO | 0 | Kendaraan tolak Gol I |
| kend_tolak_gol2 | integer | NO | 0 | Kendaraan tolak Gol II |
| kend_tolak_gol3 | integer | NO | 0 | Kendaraan tolak Gol III |
| kend_tolak_gol4a | integer | NO | 0 | Kendaraan tolak Gol IVA |
| kend_tolak_gol4b | integer | NO | 0 | Kendaraan tolak Gol IVB |
| kend_tolak_gol5 | integer | NO | 0 | Kendaraan tolak Gol V |
| **Muatan** | | | | |
| lanjutan_ton | decimal(12,2) | YES | NULL | Muatan lanjutan (ton) |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Foreign Keys**:
- `pelabuhan_id` REFERENCES `pelabuhans(id)` ON DELETE RESTRICT
- `jenis_pelayaran_id` REFERENCES `jenis_pelayarans(id)` ON DELETE RESTRICT
- `kapal_id` REFERENCES `kapals(id)` ON DELETE RESTRICT
- `nakhoda_id` REFERENCES `nakhodas(id)` ON DELETE SET NULL
- `pelabuhan_asal_id` REFERENCES `pelabuhans(id)` ON DELETE SET NULL
- `pelabuhan_tujuan_id` REFERENCES `pelabuhans(id)` ON DELETE SET NULL

**Indexes**:
- PRIMARY KEY: `id`
- INDEX: `pelabuhan_id`, `jenis_pelayaran_id`, `kapal_id`
- INDEX: `bulan`, `tahun` (untuk filtering laporan)
- INDEX: `tgl_datang` (untuk sorting)

---

### 8. Table: `kunjungan_muatans`

**Deskripsi**: Detail muatan per kunjungan (1 kunjungan bisa punya banyak muatan).

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| kunjungan_id | bigint | NO | - | FK ke kunjungans |
| tipe | enum | NO | - | BONGKAR/MUAT |
| jenis_barang | varchar(255) | YES | NULL | Nama jenis barang |
| ton_m3 | decimal(12,2) | YES | NULL | Jumlah ton/m3 |
| jenis_hewan | varchar(100) | YES | NULL | Jenis hewan (jika muatan hewan) |
| jumlah_hewan | integer | YES | NULL | Jumlah ekor |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Enum Values (tipe)**:
- `BONGKAR` - Muatan bongkar
- `MUAT` - Muatan muat

**Foreign Keys**:
- `kunjungan_id` REFERENCES `kunjungans(id)` ON DELETE CASCADE

**Indexes**:
- PRIMARY KEY: `id`
- INDEX: `kunjungan_id`

---

### 9. Table: `kunjungan_b3s`

**Deskripsi**: Detail barang B3 per kunjungan (1 kunjungan bisa punya banyak B3).

| Column | Type | Nullable | Default | Keterangan |
|--------|------|----------|---------|------------|
| id | bigserial | NO | - | Primary key |
| kunjungan_id | bigint | NO | - | FK ke kunjungans |
| barang_b3_id | bigint | NO | - | FK ke barang_b3s |
| jenis_kegiatan | enum | NO | - | BONGKAR/MUAT/SIMPANLABUH |
| bentuk_muatan | varchar(100) | YES | NULL | Bentuk muatan (curah, peti, dll) |
| jumlah_ton | decimal(12,2) | YES | NULL | Jumlah (ton) |
| jumlah_container | integer | YES | NULL | Jumlah container |
| kemasan | varchar(100) | YES | NULL | Jenis kemasan |
| jumlah | integer | YES | NULL | Jumlah kemasan |
| petugas | varchar(255) | YES | NULL | Petugas yang menangani |
| created_at | timestamp | YES | NULL | Waktu dibuat |
| updated_at | timestamp | YES | NULL | Waktu diupdate |

**Enum Values (jenis_kegiatan)**:
- `BONGKAR` - Membongkar B3
- `MUAT` - Memuat B3
- `SIMPANLABUH` - Simpan labuh B3

**Foreign Keys**:
- `kunjungan_id` REFERENCES `kunjungans(id)` ON DELETE CASCADE
- `barang_b3_id` REFERENCES `barang_b3s(id)` ON DELETE RESTRICT

**Indexes**:
- PRIMARY KEY: `id`
- INDEX: `kunjungan_id`, `barang_b3_id`

---

## 🔗 Relasi Antar Tabel

### One-to-Many Relationships

#### 1. Pelabuhan → Kunjungan (3 relasi)
```
pelabuhans.id → kunjungans.pelabuhan_id        (tempat kunjungan)
pelabuhans.id → kunjungans.pelabuhan_asal_id   (pelabuhan asal)
pelabuhans.id → kunjungans.pelabuhan_tujuan_id (pelabuhan tujuan)
```

#### 2. Jenis Pelayaran → Kunjungan
```
jenis_pelayarans.id → kunjungans.jenis_pelayaran_id
```

#### 3. Kapal → Kunjungan
```
kapals.id → kunjungans.kapal_id
```

#### 4. Kapal → Nakhoda
```
kapals.id → nakhodas.kapal_id
```

#### 5. Nakhoda → Kunjungan
```
nakhodas.id → kunjungans.nakhoda_id
```

#### 6. Kunjungan → Kunjungan Muatan
```
kunjungans.id → kunjungan_muatans.kunjungan_id (CASCADE DELETE)
```

#### 7. Kunjungan → Kunjungan B3
```
kunjungans.id → kunjungan_b3s.kunjungan_id (CASCADE DELETE)
```

#### 8. Barang B3 → Kunjungan B3
```
barang_b3s.id → kunjungan_b3s.barang_b3_id
```

### Cascade Rules

| Parent Table | Child Table | On Delete | Keterangan |
|--------------|-------------|-----------|------------|
| kunjungans | kunjungan_muatans | CASCADE | Hapus muatan jika kunjungan dihapus |
| kunjungans | kunjungan_b3s | CASCADE | Hapus B3 jika kunjungan dihapus |
| kapals | nakhodas | SET NULL | Set NULL jika kapal dihapus |
| nakhodas | kunjungans | SET NULL | Set NULL jika nakhoda dihapus |
| pelabuhans | kunjungans | RESTRICT | Tidak bisa hapus pelabuhan jika ada kunjungan |
| jenis_pelayarans | kunjungans | RESTRICT | Tidak bisa hapus jenis jika ada kunjungan |
| kapals | kunjungans | RESTRICT | Tidak bisa hapus kapal jika ada kunjungan |
| barang_b3s | kunjungan_b3s | RESTRICT | Tidak bisa hapus B3 jika masih terpakai |

---

## 🔍 Indexing Strategy

### Primary Indexes
Semua tabel menggunakan `bigserial` auto-increment sebagai primary key.

### Unique Indexes
```sql
-- Pelabuhan
CREATE UNIQUE INDEX idx_pelabuhans_kode ON pelabuhans(kode);

-- Jenis Pelayaran
CREATE UNIQUE INDEX idx_jenis_pelayarans_kode ON jenis_pelayarans(kode);

-- Barang B3
CREATE UNIQUE INDEX idx_barang_b3s_un_number ON barang_b3s(un_number);

-- Users
CREATE UNIQUE INDEX idx_users_email ON users(email);
```

### Performance Indexes
```sql
-- Kapal (untuk search dan filter)
CREATE INDEX idx_kapals_jenis ON kapals(jenis);
CREATE INDEX idx_kapals_is_active ON kapals(is_active);
CREATE INDEX idx_kapals_nama ON kapals(nama);
CREATE INDEX idx_kapals_call_sign ON kapals(call_sign);

-- Pelabuhan (untuk filter)
CREATE INDEX idx_pelabuhans_tipe ON pelabuhans(tipe);
CREATE INDEX idx_pelabuhans_is_active ON pelabuhans(is_active);

-- Nakhoda (untuk join)
CREATE INDEX idx_nakhodas_kapal_id ON nakhodas(kapal_id);
CREATE INDEX idx_nakhodas_is_active ON nakhodas(is_active);

-- Barang B3 (untuk filter)
CREATE INDEX idx_barang_b3s_kelas ON barang_b3s(kelas);

-- Kunjungan (untuk reporting dan filter)
CREATE INDEX idx_kunjungans_pelabuhan_id ON kunjungans(pelabuhan_id);
CREATE INDEX idx_kunjungans_jenis_pelayaran_id ON kunjungans(jenis_pelayaran_id);
CREATE INDEX idx_kunjungans_kapal_id ON kunjungans(kapal_id);
CREATE INDEX idx_kunjungans_bulan_tahun ON kunjungans(bulan, tahun);
CREATE INDEX idx_kunjungans_tgl_datang ON kunjungans(tgl_datang);

-- Kunjungan Muatan (untuk join)
CREATE INDEX idx_kunjungan_muatans_kunjungan_id ON kunjungan_muatans(kunjungan_id);

-- Kunjungan B3 (untuk join)
CREATE INDEX idx_kunjungan_b3s_kunjungan_id ON kunjungan_b3s(kunjungan_id);
CREATE INDEX idx_kunjungan_b3s_barang_b3_id ON kunjungan_b3s(barang_b3_id);
```

---

## 🌱 Data Seeding

### Master Data Seeding

#### 1. PelabuhanSeeder
Seeds 6 pelabuhan internal UPP Jampea:
```php
// 1 UPP + 2 POSKER + 3 WILKER
Pelabuhan::create(['kode' => 'JMP', 'nama' => 'Pelabuhan Jampea', 'tipe' => 'UPP']);
Pelabuhan::create(['kode' => 'PMT', 'nama' => 'Posker Pamatata', 'tipe' => 'POSKER']);
// ... dst
```

#### 2. JenisPelayaranSeeder
Seeds 6 jenis pelayaran tetap:
```php
JenisPelayaran::create([
    'kode' => 'PELRA',
    'nama' => 'Pelayaran Rakyat',
    'deskripsi' => 'Pelayaran rakyat/tradisional'
]);
// ... 5 jenis lainnya
```

#### 3. KapalSeeder
Seeds 10 sample kapal dengan data realistic:
```php
Kapal::create([
    'nama' => 'KM Sabuk Nusantara',
    'jenis' => 'KM',
    'gt' => 2500.00,
    'call_sign' => 'YBXX',
    // ... dll
]);
```

#### 4. BarangB3Seeder
Seeds 20 jenis barang B3 dengan UN Number:
```php
BarangB3::create([
    'nama' => 'Bensin',
    'un_number' => 'UN1203',
    'kelas' => '3',
    'kategori' => 'Flammable Liquid'
]);
```

#### 5. DatabaseSeeder
Seed user admin default:
```php
User::factory()->create([
    'name' => 'Admin UPP Jampea',
    'email' => 'admin@uppjampea.id',
    'password' => Hash::make('password')
]);
```

### Running Seeders

```bash
# Seed all
php artisan db:seed

# Seed specific
php artisan db:seed --class=PelabuhanSeeder

# Fresh migration + seed
php artisan migrate:fresh --seed
```

---

## 📐 Business Rules

### 1. Kunjungan Rules
- ✅ Setiap kunjungan HARUS punya: pelabuhan, jenis_pelayaran, kapal, tgl_datang, jam_datang
- ✅ Nakhoda OPSIONAL (bisa NULL)
- ✅ Bulan & tahun harus sesuai dengan tgl_datang
- ✅ tgl_tolak & jam_tolak OPSIONAL (kapal bisa masih berlabuh)
- ✅ Default value 0 untuk semua penumpang dan kendaraan

### 2. Muatan Rules
- ✅ Muatan bisa KOSONG (kunjungan tanpa muatan)
- ✅ Jika ada muatan, minimal isi tipe (BONGKAR/MUAT)
- ✅ ton_m3 atau jumlah_hewan salah satu harus diisi
- ✅ CASCADE DELETE: hapus kunjungan = hapus semua muatan

### 3. B3 Rules
- ✅ B3 bisa KOSONG (tidak semua kunjungan bawa B3)
- ✅ Jika ada B3, HARUS pilih barang_b3_id dari master
- ✅ jenis_kegiatan WAJIB (BONGKAR/MUAT/SIMPANLABUH)
- ✅ CASCADE DELETE: hapus kunjungan = hapus semua B3

### 4. Master Data Rules
- ✅ Pelabuhan, Jenis Pelayaran, Kapal, Barang B3: RESTRICT DELETE (tidak bisa dihapus jika masih ada kunjungan)
- ✅ Nakhoda: SET NULL (jika dihapus, kunjungan tetap ada tapi nakhoda_id jadi NULL)
- ✅ is_active flag untuk soft disable (tidak delete data)

---

## 🔒 Security Considerations

### 1. SQL Injection Protection
- ✅ Semua query menggunakan Eloquent ORM (prepared statements)
- ✅ Tidak ada raw query tanpa binding

### 2. Data Validation
- ✅ Validation rules di Controller sebelum insert/update
- ✅ Database constraints (NOT NULL, UNIQUE, FK)

### 3. Backup Strategy
- 📅 Daily backup database (recommended)
- 📦 Export SQL dump + storage files
- 🔄 Keep last 30 days backup

```bash
# Backup command
pg_dump -U postgres silapor_upp_jampea -F c -f backup_$(date +%Y%m%d).dump
```

---

## 📊 Query Performance Tips

### 1. Eager Loading (N+1 Problem)
```php
// ❌ BAD (N+1 queries)
$kunjungans = Kunjungan::all();
foreach ($kunjungans as $k) {
    echo $k->pelabuhan->nama;
}

// ✅ GOOD (2 queries)
$kunjungans = Kunjungan::with('pelabuhan')->get();
```

### 2. Using Scopes
```php
// Filter by periode
Kunjungan::byPeriode($bulan, $tahun)->get();

// Filter by pelabuhan
Kunjungan::byPelabuhan($pelabuhan_id)->get();
```

### 3. Limit Results
```php
// Pagination
Kunjungan::paginate(20);

// Limit for reports
Kunjungan::latest()->limit(100)->get();
```

---

## ✅ Database Checklist

- [x] 9 Tables created
- [x] All foreign keys defined
- [x] Indexes for performance
- [x] Cascade rules configured
- [x] Enum values validated
- [x] Seeders for master data
- [x] Sample data populated
- [x] Constraints tested

---

**Database schema verified and production-ready! ✨**
