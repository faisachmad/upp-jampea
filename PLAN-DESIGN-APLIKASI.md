# PLAN DEVELOPMENT — APLIKASI LAPORAN UPP KELAS III JAMPEA

**Tech Stack:** Laravel 12 + PostgreSQL + Blade + Alpine.js + Tailwind CSS 4  
**Tanggal:** Maret 2026

---

## 1. KENAPA 1 FORM SAJA?

Setelah analisis ulang, keempat Excel (Operasional, Kunjungan Kapal, SPB, B3) ternyata **mencatat event yang sama** — yaitu **kunjungan kapal**. Perbedaannya hanya pada **sudut pandang data**:

| Excel Lama | Apa yang dicatat | Overlap? |
|-----------|-----------------|----------|
| Operasional | Kapal, tiba/tolak, bongkar/muat, penumpang, kendaraan | **Data inti** |
| Kunjungan Kapal | Kapal, tiba/tolak, status muatan | 100% ada di Operasional |
| SPB | Kapal, tiba/tolak + **No. SPB** | 95% overlap, hanya NO SPB yang baru |
| B3 | Kapal, kegiatan + **detail B3** | 90% overlap, detail B3 yang baru |

**Kesimpulan:** Cukup **1 FORM INPUT** untuk mencatat 1 kunjungan kapal, dengan section tambahan:
- **Section SPB** → Isi No. SPB tiba & tolak
- **Section B3** → Toggle ON jika ada barang berbahaya
- Field muncul/hilang secara **conditional** berdasarkan jenis pelayaran

---

## 2. MASTER DATA

### 2.1 Master Pelabuhan (`pelabuhans`)

Pelabuhan yang dikelola UPP dan pelabuhan luar yang sering muncul sebagai asal/tujuan.

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | SERIAL PK | |
| kode | VARCHAR(20) | IDPJA, IDSLR, dll |
| nama | VARCHAR(100) | Nama pelabuhan |
| tipe | ENUM | `UPP`, `POSKER`, `WILKER`, `LUAR` |
| is_active | BOOLEAN | Default true |

**Data awal (internal UPP):**
| Kode | Nama | Tipe |
|------|------|------|
| IDPJA | Benteng Jampea | UPP |
| - | Posker Ujung | POSKER |
| - | Wilker Kayuadi | WILKER |
| - | Wilker Bonerate | WILKER |
| - | Wilker Jinato | WILKER |
| - | Wilker Kalaotoa | WILKER |

**Data awal (pelabuhan luar — sering muncul):**
Makassar, Selayar, Bulukumba, Bira, Tanjung Perak, Labuan Bajo, Marapokot, REO, Sorong, Sanana, Bawean, Bintuni, Gorom, Geser, Badas, Bima, Bantaeng, Bau-Bau

### 2.2 Master Kapal (`kapals`)

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | SERIAL PK | |
| nama | VARCHAR(150) | Nama kapal |
| jenis | ENUM | `KLM`, `KM`, `KMP`, `MV` |
| gt | DECIMAL(10,2) | Gross Tonnage |
| dwt | DECIMAL(10,2) | Dead Weight Tonnage (nullable) |
| panjang | DECIMAL(10,2) | Panjang (m) |
| tanda_selar | VARCHAR(50) | Nomor tanda selar |
| call_sign | VARCHAR(20) | Nama panggil |
| tempat_kedudukan | VARCHAR(100) | Kedudukan kapal |
| bendera | VARCHAR(50) | Default: INDONESIA |
| pemilik_agen | VARCHAR(200) | Pemilik / Agen |
| is_active | BOOLEAN | Default true |

**Data awal (33 kapal):**

| Nama | Jenis | GT | Pemilik |
|------|-------|----|---------|
| PESONA BAHARI | KLM | 161 | PT. BATANA BAHARI |
| CATUR PUTRA | KLM | 213 | CARLA |
| NEW SELSABIEL | KLM | 36 | H. SYAMSUL BAHRI |
| ANDIN JAYA | KLM | 125 | SARIADIN |
| REZKY AQILA | KLM | 22 | H. SAPPARA |
| CITRA BAHARI | KLM | 162 | PT. BATANA BAHARI |
| AL KAUTSAR 3 | KM | 104 | BAU LINDA |
| NURUL SALSA 01 | KM | 78 | BASRIADI |
| SABUK NUSANTARA 85 | KM | 2097 | PT.PELNI |
| SULTAN HASANUDDIN | KM | 1257 | BLU PIP MAKASSAR |
| SABUK NUSANTARA 49 | KM | 2090 | PT.KUAT |
| MITRA DONGGALA | KM | 655 | PT. MITRA ABADI WISESA |
| MITRA ABADI II | KM | 612 | PT. MITRA ABADI WISESA |
| KAISEI MARU I | KM | 672 | PT. PELNUS SERAM |
| SANGKE PALANGGA | KMP | 560 | PT. ASDP |
| TAKABONERATE | KMP | 842 | DIREKTORAT JENDERAL PERHUBUNGAN DARAT |
| CORAL GEOGRAPHER | MV | 5602 | PT. BAHARI EKA NUSANTARA |
| HARAPAN MULIA | KLM | 165 | H. MUHLIS |
| CITRA MAKMUR | KLM | 250 | PT. WAKATOBI MARITIM SUKSES |
| MANDIRI UTAMA 01 | KLM | 17 | SITTI HUMRAH, S.PD.I |
| RAODATUL JANAH | KLM | 11 | SYAMSUL RIZAL |
| HARAPAN KITA | KLM | 118 | PT. GARUDA INDAH PERMAI |
| AISYA PUTRI | KLM | 22 | SAENONG |
| ANDI ANSAR | KLM | 19 | ANDI AKBAR |
| AHLIANA INDAH | KLM | 16 | RUSDIANTO |
| JUSMA JAYA 02 | KLM | 21 | MUH.YAMIN |
| ...dll | | | |

### 2.3 Master Jenis Pelayaran (`jenis_pelayarans`)

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | SERIAL PK | |
| kode | VARCHAR(20) | Kode unik |
| nama | VARCHAR(100) | Nama lengkap |
| prefix | CHAR(1) | A, B, C, D, E |

**Data (fixed — tidak berubah):**
| Kode | Prefix | Nama | Keterangan |
|------|--------|------|------------|
| PELRA | A | Pelayaran Rakyat | KLM tradisional |
| DALAM_NEGERI | B | Pelayaran Dalam Negeri | KM cargo DN |
| LUAR_NEGERI | C | Pelayaran Luar Negeri | Kapal asing |
| PERINTIS | D | Perintis | Sabuk Nusantara, Sultan Hasanuddin |
| FERRY_ASDP | E | Ferry ASDP | Sangke Palangga |
| FERRY_DJPD | F | Ferry DJPD | Takabonerate (Jinato-Bonerate) |

### 2.4 Master Nakhoda (`nakhodas`)

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | SERIAL PK | |
| nama | VARCHAR(150) | Nama nakhoda |
| kapal_id | INT FK | Kapal terakhir (nullable) |
| is_active | BOOLEAN | |

### 2.5 Master Barang B3 (`barang_b3s`)

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | SERIAL PK | |
| nama | VARCHAR(100) | BBM, ELPIJI, KOPRA, PUPUK, ARANG, JAMBU MENTE |
| un_number | INT | Nomor UN |
| kelas | VARCHAR(10) | 2.1, 3, 4.1, 4.2, 5.2 |
| kategori | VARCHAR(100) | GAS MUDAH TERBAKAR, dll |

**Data awal:**
| Nama | UN | Kelas | Kategori |
|------|-----|-------|---------|
| BBM | 1203 | 3 | CAIRAN MUDAH TERBAKAR |
| ELPIJI | 1075 | 2.1 | GAS MUDAH TERBAKAR |
| KOPRA | 1363 | 4.1 | BAHAN PADAT MUDAH TERBAKAR |
| ARANG | 1361 | 4.2 | BAHAN PADAT MUDAH TERBAKAR |
| PUPUK | 3107 | 5.2 | PEROKSIDA ORGANIK |
| JAMBU MENTE | 1325 | 4.1 | BAHAN PADAT MUDAH TERBAKAR |

---

## 3. 1 FORM UNIFIED — DESIGN DETAIL

### Konsep: Wizard / Multi-Step Form dalam 1 halaman

```
┌─────────────────────────────────────────────────────────────────────┐
│  [1. KAPAL & PELAYARAN]  [2. TIBA/TOLAK]  [3. MUATAN]  [4. SPB/B3]│
│  ●━━━━━━━━━━━━━━━━━━━━━━━○━━━━━━━━━━━━━━━○━━━━━━━━━━━━━○          │
└─────────────────────────────────────────────────────────────────────┘
```

Bisa juga ditampilkan sebagai **1 halaman panjang dengan section** (accordion/tab) — user scroll ke bawah.

### STEP 1 — DATA KAPAL & PELAYARAN

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 1: DATA KAPAL & PELAYARAN                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Pelabuhan Pencatat  [🔍 Jampea               ▾]              │
│                                                                 │
│  Jenis Pelayaran     [🔍 Pelayaran Rakyat      ▾]             │
│                                                                 │
│  ── DATA KAPAL ──────────────────────────────────               │
│  Nama Kapal          [🔍 PESONA BAHARI ________▾]  ← autocomplete
│                       (auto-fill fields below)                  │
│  Jenis Kapal         [KLM        ]  (auto)                     │
│  GT                  [161        ]  (auto, editable)            │
│  DWT                 [           ]  (optional)                  │
│  Panjang (m)         [35.10      ]  (auto)                     │
│  Tanda Selar         [GT.161 NO.987/Ka]  (auto)                │
│  Call Sign           [YC5364     ]  (auto)                      │
│  Bendera             [INDONESIA  ]  (auto)                      │
│  Pemilik/Agen        [PT. BATANA BAHARI]  (auto, editable)     │
│  Nakhoda             [🔍 SUDARMIN DAHLAN ___▾]  ← autocomplete │
│                                                                 │
│                                     [Selanjutnya →]            │
└─────────────────────────────────────────────────────────────────┘
```

### STEP 2 — TIBA, TAMBAT, BERANGKAT

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 2: TIBA / TAMBAT / BERANGKAT                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ── TIBA ────────────────────────────────────                   │
│  Tanggal Tiba      [📅 03/01/2026]                             │
│  Jam Tiba (WITA)   [🕐 08:30    ]                              │
│  Pelabuhan Asal    [🔍 SANANA __________▾]                     │
│  Status Muatan     (●) Bermuatan (M)  ( ) Kosong (K)           │
│                    ( ) Muatan Lanjutan (ML)                     │
│                                                                 │
│  ── TAMBAT ──────────────────────────────────                   │
│  Tanggal Tambat    [📅          ]  (optional)                  │
│  Jam Tambat        [🕐          ]  (optional)                  │
│                                                                 │
│  ── BERANGKAT ───────────────────────────────                   │
│  Tanggal Berangkat [📅 03/01/2026]                             │
│  Jam Berangkat     [🕐 13:00    ]                              │
│  Pelabuhan Tujuan  [🔍 TANJUNG PERAK ___▾]                    │
│  Status Muatan     (●) Bermuatan (M)  ( ) Kosong (K)           │
│                    ( ) Muatan Lanjutan (ML)                     │
│                                                                 │
│                        [← Kembali]  [Selanjutnya →]            │
└─────────────────────────────────────────────────────────────────┘
```

### STEP 3 — MUATAN (Conditional)

Field ditampilkan berdasarkan **jenis pelayaran** yang dipilih di Step 1:

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 3: MUATAN & PENUMPANG                                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ ── BONGKAR ──── (tampil untuk: PELRA, DN, LN) ──────           │
│ ┌─────────────────┬───────────┬───────────────┬─────────┐      │
│ │ Jenis Barang    │ Ton/M3    │ Jenis Hewan   │ Ekor    │      │
│ ├─────────────────┼───────────┼───────────────┼─────────┤      │
│ │ [SEMEN       ▾] │ [80     ] │ [-         ▾] │ [     ] │      │
│ │ [B.CAMPURAN  ▾] │ [20     ] │ [-         ▾] │ [     ] │      │
│ └─────────────────┴───────────┴───────────────┴─────────┘      │
│ [+ Tambah Baris]                                                │
│                                                                 │
│ ── MUAT ──── (tampil untuk: PELRA, DN, LN) ──────              │
│ ┌─────────────────┬───────────┬───────────────┬─────────┐      │
│ │ Jenis Barang    │ Ton/M3    │ Jenis Hewan   │ Ekor    │      │
│ ├─────────────────┼───────────┼───────────────┼─────────┤      │
│ │ [KOPRA       ▾] │ [10     ] │ [KERBAU    ▾] │ [1    ] │      │
│ │ [DEDAK       ▾] │ [2      ] │ [-         ▾] │ [     ] │      │
│ └─────────────────┴───────────┴───────────────┴─────────┘      │
│ [+ Tambah Baris]                                                │
│                                                                 │
│ ── KENDARAAN ──── (tampil untuk: FERRY saja) ──────             │
│   Mobil   Turun: [   ]   Naik: [   ]                           │
│   Motor   Turun: [   ]   Naik: [   ]                           │
│                                                                 │
│ ── PENUMPANG ──── (tampil untuk: PERINTIS, FERRY) ──            │
│   Turun: [867 ]   Naik: [321 ]                                 │
│                                                                 │
│ ── MUATAN LANJUTAN ────────────────────────────                 │
│   Jenis Barang    [GC        ]   Ton/M3  [1112  ]             │
│   Mobil Lanjutan  [     ]   Motor Lanjutan  [     ]            │
│   Penumpang Lanjutan  [679  ]                                   │
│                                                                 │
│                        [← Kembali]  [Selanjutnya →]            │
└─────────────────────────────────────────────────────────────────┘
```

### STEP 4 — SPB & B3 (Optional)

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 4: SPB & BARANG BERBAHAYA (B3)                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│ ── SURAT PERSETUJUAN BERLAYAR ──────────────────                │
│   No. SPB Tiba     [SPB.IDSQN.1225.0000039    ]               │
│   No. SPB Tolak    [SPB.IDPJA.0126.0000002    ]               │
│   ETA              [📅 04/01/2026             ]                │
│                                                                 │
│ ── BARANG BERBAHAYA (B3) ─── [✅ Ada B3]  ← toggle             │
│                                                                 │
│ (Muncul jika toggle ON)                                         │
│ ┌──────────┬─────────┬──────┬──────┬─────────┬────────┬──────┐ │
│ │Nama Brg  │Kegiatan │Bentuk│Ton   │Kemasan  │Jumlah  │Ptgs  │ │
│ ├──────────┼─────────┼──────┼──────┼─────────┼────────┼──────┤ │
│ │[BBM   ▾] │[BONGKAR]│CURAH │[40  ]│[TANGKI ]│[2    ] │[LALA]│ │
│ │          │         │(auto)│      │         │        │      │ │
│ │ UN:1203  │ Kls:3   │      │      │         │        │      │ │
│ │ Ktg: CAIRAN MUDAH TERBAKAR                          │      │ │
│ └──────────┴─────────┴──────┴──────┴─────────┴────────┴──────┘ │
│ [+ Tambah B3]                                                   │
│                                                                 │
│                        [← Kembali]  [💾 SIMPAN KUNJUNGAN]      │
└─────────────────────────────────────────────────────────────────┘
```

### Conditional Logic Per Jenis Pelayaran:

```
┌─────────────────┬────────┬─────────┬──────────┬──────┬──────┬─────┐
│                 │Bongkar │Kendaraan│Penumpang │Hewan │SPB   │B3   │
│                 │Muat    │         │          │      │      │     │
├─────────────────┼────────┼─────────┼──────────┼──────┼──────┼─────┤
│ PELRA           │   ✅   │   ❌    │  ✅ opt  │  ✅  │  ✅  │ ✅  │
│ DALAM NEGERI    │   ✅   │   ❌    │   ❌     │  ❌  │  ✅  │ ✅  │
│ LUAR NEGERI     │   ✅   │   ❌    │  ✅ opt  │  ❌  │  ✅  │ ✅  │
│ PERINTIS        │   ✅   │   ❌    │   ✅     │  ❌  │  ✅  │ ❌  │
│ FERRY ASDP      │   ❌   │   ✅    │   ✅     │  ❌  │  ✅  │ ✅  │
│ FERRY DJPD      │   ❌   │   ✅    │   ✅     │  ❌  │  ✅  │ ✅  │
└─────────────────┴────────┴─────────┴──────────┴──────┴──────┴─────┘
```

---

## 4. DATABASE SCHEMA (PostgreSQL)

```sql
-- =============================================
-- MASTER TABLES
-- =============================================

CREATE TABLE pelabuhans (
    id SERIAL PRIMARY KEY,
    kode VARCHAR(20) UNIQUE,
    nama VARCHAR(100) NOT NULL,
    tipe VARCHAR(10) CHECK (tipe IN ('UPP','POSKER','WILKER','LUAR')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE jenis_pelayarans (
    id SERIAL PRIMARY KEY,
    kode VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prefix CHAR(1)
);

CREATE TABLE kapals (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    jenis VARCHAR(5) CHECK (jenis IN ('KLM','KM','KMP','MV')),
    gt DECIMAL(10,2),
    dwt DECIMAL(10,2),
    panjang DECIMAL(10,2),
    tanda_selar VARCHAR(50),
    call_sign VARCHAR(20),
    tempat_kedudukan VARCHAR(100),
    bendera VARCHAR(50) DEFAULT 'INDONESIA',
    pemilik_agen VARCHAR(200),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE nakhodas (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    kapal_id INT REFERENCES kapals(id) ON DELETE SET NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE barang_b3s (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    un_number INT,
    kelas VARCHAR(10),
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- =============================================
-- TRANSAKSI UTAMA: 1 row = 1 kunjungan kapal
-- =============================================

CREATE TABLE kunjungans (
    id SERIAL PRIMARY KEY,

    -- Referensi
    pelabuhan_id INT NOT NULL REFERENCES pelabuhans(id),
    kapal_id INT NOT NULL REFERENCES kapals(id),
    jenis_pelayaran_id INT NOT NULL REFERENCES jenis_pelayarans(id),
    nakhoda_id INT REFERENCES nakhodas(id),

    -- Periode
    bulan INT NOT NULL CHECK (bulan BETWEEN 1 AND 12),
    tahun INT NOT NULL,

    -- TIBA
    tgl_tiba DATE,
    jam_tiba TIME,
    pelabuhan_asal_id INT REFERENCES pelabuhans(id),
    status_muatan_tiba VARCHAR(2) CHECK (status_muatan_tiba IN ('M','K','ML')),

    -- TAMBAT
    tgl_tambat DATE,
    jam_tambat TIME,

    -- BERANGKAT
    tgl_berangkat DATE,
    jam_berangkat TIME,
    pelabuhan_tujuan_id INT REFERENCES pelabuhans(id),
    status_muatan_tolak VARCHAR(2) CHECK (status_muatan_tolak IN ('M','K','ML')),

    -- SPB
    no_spb_tiba VARCHAR(50),
    no_spb_tolak VARCHAR(50),
    eta DATE,

    -- PENUMPANG
    penumpang_turun INT DEFAULT 0,
    penumpang_naik INT DEFAULT 0,

    -- KENDARAAN (Ferry)
    mobil_turun INT DEFAULT 0,
    mobil_naik INT DEFAULT 0,
    motor_turun INT DEFAULT 0,
    motor_naik INT DEFAULT 0,

    -- MUATAN LANJUTAN
    lanjutan_jenis VARCHAR(100),
    lanjutan_ton DECIMAL(10,2) DEFAULT 0,
    lanjutan_mobil INT DEFAULT 0,
    lanjutan_motor INT DEFAULT 0,
    lanjutan_penumpang INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_kunjungans_periode ON kunjungans(tahun, bulan);
CREATE INDEX idx_kunjungans_pelabuhan ON kunjungans(pelabuhan_id);
CREATE INDEX idx_kunjungans_pelayaran ON kunjungans(jenis_pelayaran_id);

-- =============================================
-- DETAIL BONGKAR/MUAT (1 kunjungan : N detail)
-- =============================================

CREATE TABLE kunjungan_muatans (
    id SERIAL PRIMARY KEY,
    kunjungan_id INT NOT NULL REFERENCES kunjungans(id) ON DELETE CASCADE,
    tipe VARCHAR(10) NOT NULL CHECK (tipe IN ('BONGKAR','MUAT')),
    jenis_barang VARCHAR(100),
    ton_m3 DECIMAL(10,2) DEFAULT 0,
    jenis_hewan VARCHAR(50),
    jumlah_hewan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_muatans_kunjungan ON kunjungan_muatans(kunjungan_id);

-- =============================================
-- DETAIL B3 (1 kunjungan : N detail B3)
-- =============================================

CREATE TABLE kunjungan_b3s (
    id SERIAL PRIMARY KEY,
    kunjungan_id INT NOT NULL REFERENCES kunjungans(id) ON DELETE CASCADE,
    barang_b3_id INT REFERENCES barang_b3s(id),
    jenis_kegiatan VARCHAR(10) CHECK (jenis_kegiatan IN ('BONGKAR','MUAT')),
    bentuk_muatan VARCHAR(10) CHECK (bentuk_muatan IN ('CURAH','PADAT')),
    jumlah_ton DECIMAL(10,2) DEFAULT 0,
    jumlah_container INT DEFAULT 0,
    kemasan VARCHAR(50),
    jumlah INT DEFAULT 0,
    petugas VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_b3s_kunjungan ON kunjungan_b3s(kunjungan_id);
```

### Entity Relationship:

```
pelabuhans ──┐
             │  1
kapals ──────┼───── kunjungans (TABEL UTAMA)
             │         │
nakhodas ────┘         │ 1
                       │
jenis_pelayarans ──────┘
                       │
           ┌───────────┼───────────┐
           │ N         │ N         │
    kunjungan_muatans  kunjungan_b3s
    (bongkar/muat)     (barang berbahaya)
                              │
                       barang_b3s (master)
```

---

## 5. FITUR-FITUR APLIKASI

### 5.1 Modul Master Data
| No | Fitur | Deskripsi |
|----|-------|-----------|
| M1 | CRUD Pelabuhan | Tambah/edit/hapus/cari pelabuhan. Filter: internal UPP vs luar |
| M2 | CRUD Kapal | Tambah/edit/hapus kapal. Search by nama/jenis/GT |
| M3 | CRUD Nakhoda | Daftar nakhoda, link ke kapal |
| M4 | CRUD Barang B3 | Master barang berbahaya (auto-fill UN, kelas, kategori) |
| M5 | Jenis Pelayaran | View-only (data fixed, hanya admin yang bisa edit) |

### 5.2 Modul Input (1 FORM UNIFIED)
| No | Fitur | Deskripsi |
|----|-------|-----------|
| I1 | Form Kunjungan Kapal | 1 form lengkap (4 step/section), conditional per jenis pelayaran |
| I2 | Autocomplete Kapal | Ketik nama → pilih → auto-fill semua field kapal |
| I3 | Autocomplete Pelabuhan | Ketik nama pelabuhan asal/tujuan |
| I4 | Dynamic Repeater Bongkar/Muat | Tambah/hapus baris barang bongkar/muat |
| I5 | Dynamic Repeater B3 | Tambah/hapus baris barang berbahaya |
| I6 | Conditional Sections | Show/hide section berdasarkan jenis pelayaran (Livewire reactive) |
| I7 | Quick Duplicate | Tombol "Duplikat" untuk kapal reguler yang sering berulang (TAKABONERATE, SANGKE PALANGGA) |
| I8 | Daftar Kunjungan | Tabel list semua kunjungan + filter (bulan, pelabuhan, jenis). Klik edit/hapus |

### 5.3 Modul Laporan (Auto-Generated)
| No | Fitur | Deskripsi |
|----|-------|-----------|
| R1 | Rekap Pengeluaran SPB | Per pelabuhan, per bulan, per kategori kapal (PPK.27/PPK.29/KLM/KL/PI) |
| R2 | Lap. PELRA | Agregasi per bulan per pelabuhan: kapal, GT, bongkar, muat, hewan, penumpang |
| R3 | Lap. Perintis | Agregasi per bulan per pelabuhan: kapal, GT, bongkar, muat, penumpang, motor |
| R4 | Lap. Angkutan Penyebrangan | Agregasi per bulan per pelabuhan: kapal, GT, kendaraan, penumpang |
| R5 | Lap. Angkutan Laut DN | Agregasi per bulan per pelabuhan |
| R6 | Lap. Angkutan Laut LN | Agregasi per bulan per pelabuhan |
| R7 | Rekap Operasional | Gabungan semua jenis pelayaran dalam 1 tabel |
| R8 | Export Excel Data Dukung | Export 7 sheet dalam 1 file Excel sesuai format E-Performance |
| R9 | Preview Laporan | Lihat laporan di browser sebelum export |
| R10 | Filter Laporan | Pilih tahun, pelabuhan, bulan |

### 5.4 Modul Dashboard
| No | Fitur | Deskripsi |
|----|-------|-----------|
| D1 | Ringkasan Bulanan | Total kunjungan, total GT, total penumpang, total barang bulan ini |
| D2 | Chart Kunjungan | Grafik batang kunjungan per bulan (YTD) |
| D3 | Chart per Jenis Pelayaran | Pie chart komposisi PELRA/Perintis/Ferry/DN/LN |
| D4 | Status Input | Indikator bulan mana sudah diinput, mana belum |

### 5.5 Modul Sistem
| No | Fitur | Deskripsi |
|----|-------|-----------|
| S1 | Login/Auth | Laravel Breeze, login per user |
| S2 | Role & Permission | Admin (full), Operator (input+view), Viewer (view only) |
| S3 | Audit Log | Siapa input apa, kapan |
| S4 | Backup Data | Export/import database |
| S5 | Import Excel Lama | Migrasi data dari file Excel yang sudah ada |

---

## 6. UI/UX DESIGN

### 6.1 Layout Utama

```
┌──────────────────────────────────────────────────────────────────────┐
│  🚢 SILAPOR UPP JAMPEA                          👤 Admin  [Logout] │
├──────────┬───────────────────────────────────────────────────────────┤
│          │                                                           │
│ MENU     │   CONTENT AREA                                           │
│          │                                                           │
│ 📊 Dashboard│                                                       │
│          │   ┌──────────┬──────────┬──────────┬──────────┐          │
│ 📝 Input │   │Total     │Total     │Total     │Total     │          │
│  Kunjungan│   │Kunjungan │  GT      │Penumpang │ Barang   │          │
│          │   │   93     │ 79,819   │  5,647   │ 1,014 T  │          │
│ 📑 Master│   └──────────┴──────────┴──────────┴──────────┘          │
│  ├ Pelabuhan│                                                       │
│  ├ Kapal │   ┌──────────────────────────────────────────┐           │
│  ├ Nakhoda│  │  📊 Grafik Kunjungan per Bulan (2026)    │           │
│  └ Barang B3│ │  ████                                    │           │
│          │   │  ████ ████                                │           │
│ 📈 Laporan│  │  Jan  Feb  Mar  Apr  ...                  │           │
│  ├ PELRA │   └──────────────────────────────────────────┘           │
│  ├ Perintis│                                                        │
│  ├ Ferry │                                                          │
│  ├ DN    │                                                          │
│  ├ LN    │                                                          │
│  ├ Rekap SPB│                                                       │
│  ├ Rekap Ops│                                                       │
│  └ Export│                                                          │
│          │                                                           │
│ ⚙ Setting│                                                          │
│          │                                                           │
└──────────┴───────────────────────────────────────────────────────────┘
```

### 6.2 Halaman Input Kunjungan — Mode List

```
┌──────────────────────────────────────────────────────────────────────┐
│  DAFTAR KUNJUNGAN KAPAL                        [+ Tambah Kunjungan] │
├──────────────────────────────────────────────────────────────────────┤
│  Filter: Bulan [Januari ▾] Tahun [2026 ▾] Pelabuhan [Semua ▾]     │
│          Jenis [Semua ▾]   Cari: [________________🔍]              │
├──────────────────────────────────────────────────────────────────────┤
│ No │ Tgl Tiba  │ Kapal              │ Jenis   │ Asal     │ Tujuan  │
│────┼───────────┼────────────────────┼─────────┼──────────┼─────────│
│  1 │ 03/01/26  │ PESONA BAHARI      │ PELRA   │ SANANA   │ TJ.PERAK│
│  2 │ 03/01/26  │ SABUK NUSANTARA 85 │ PERINTIS│ SELAYAR  │ SELAYAR │
│  3 │ 04/01/26  │ SULTAN HASANUDDIN  │ PERINTIS│ SELAYAR  │ MAKASSAR│
│  4 │ 05/01/26  │ TAKABONERATE       │ FERRY   │ JINATO   │ BONERATE│
│  5 │ 06/01/26  │ SANGKE PALANGGA    │ FERRY   │ BIRA     │ L.BAJO  │
│ ...│           │                    │         │          │         │
├──────────────────────────────────────────────────────────────────────┤
│  Menampilkan 1-25 dari 93                    [◀ 1 2 3 4 ▶]        │
└──────────────────────────────────────────────────────────────────────┘
```

### 6.3 Halaman Master Kapal

```
┌──────────────────────────────────────────────────────────────────────┐
│  MASTER KAPAL                                     [+ Tambah Kapal]  │
├──────────────────────────────────────────────────────────────────────┤
│  Cari: [________________🔍]  Filter Jenis: [Semua ▾]              │
├──────────────────────────────────────────────────────────────────────┤
│ No │ Nama Kapal          │ Jenis │  GT  │ Call Sign │ Pemilik      │
│────┼─────────────────────┼───────┼──────┼───────────┼──────────────│
│  1 │ PESONA BAHARI       │ KLM   │  161 │ YC5364    │ PT. BATANA.. │
│  2 │ CATUR PUTRA         │ KLM   │  213 │ YC.7397   │ CARLA        │
│  3 │ SABUK NUSANTARA 85  │ KM    │ 2097 │ YBZO2     │ PT.PELNI     │
│  4 │ SANGKE PALANGGA     │ KMP   │  560 │ YCEM      │ PT. ASDP     │
│  5 │ TAKABONERATE        │ KMP   │  842 │ YDFT2     │ DJPD         │
│ ...│                     │       │      │           │              │
├──────────────────────────────────────────────────────────────────────┤
│  [Edit] [Hapus]  per row                                            │
└──────────────────────────────────────────────────────────────────────┘
```

### 6.4 Halaman Laporan — Preview

```
┌──────────────────────────────────────────────────────────────────────┐
│  LAPORAN PELRA — TAHUN 2026                                         │
│  Pelabuhan: [Semua ▾]                          [📥 Export Excel]   │
├──────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  PELABUHAN JAMPEA                                                    │
│ ┌─────┬──────┬────────┬─────────────┬─────────────┬───────┬───────┐ │
│ │ No  │Bulan │Jml Kpl │  BONGKAR    │    MUAT     │ HEWAN │PENUMP.│ │
│ │     │      │  | GT  │Cargo │Curah │Cargo │Curah │Tu│Na │Tu │Na │ │
│ ├─────┼──────┼────────┼──────┼──────┼──────┼──────┼──┼───┼───┼───┤ │
│ │  1  │JAN   │ 10│ 959│  337 │  80  │  42  │  0   │ 0│ 1 │ 0 │ 0│ │
│ │  2  │FEB   │ 18│1038│  432 │  80  │ 181  │  0   │ 0│29 │23 │ 5│ │
│ │  3  │MAR   │   │    │      │  80  │      │      │  │   │   │  │ │
│ │ ... │      │   │    │      │      │      │      │  │   │   │  │ │
│ │     │TOTAL │ 28│1997│  769 │ 960  │ 223  │  0   │ 0│30 │23 │ 5│ │
│ └─────┴──────┴────────┴──────┴──────┴──────┴──────┴──┴───┴───┴───┘ │
│                                                                      │
│  PELABUHAN UJUNG                                                     │
│  ...                                                                 │
└──────────────────────────────────────────────────────────────────────┘
```

### 6.5 Design Principles

| Aspek | Keputusan |
|-------|----------|
| **Warna** | Biru navy (pelabuhan/maritim), putih bersih, aksen hijau untuk success |
| **Font** | Inter (UI) — bersih, mudah dibaca |
| **Mobile** | Responsive tapi prioritas Desktop (user input di kantor) |
| **Form UX** | Multi-step wizard dengan Alpine.js state management |
| **Feedback** | Toast notification (Alpine.js), inline validation (Blade + JS) |
| **Speed** | AJAX partial update — form tidak reload full page |

---

## 7. TECH STACK DETAIL

| Layer | Teknologi | Versi | Alasan |
|-------|----------|-------|--------|
| **Backend** | Laravel | 12 | Terbaru, modern PHP 8.4 |
| **Database** | PostgreSQL | 16+ | Kuat untuk agregasi, JSON support, window functions |
| **Frontend** | Blade Templates | - | Server-side rendering, no build step |
| **JS Framework** | Alpine.js | 3 | Lightweight reactive, toggle, conditional show/hide |
| **AJAX** | Fetch API + JSON | - | Form submission, autocomplete, dynamic data |
| **CSS** | Tailwind CSS | 4 | Utility-first, rapid prototyping |
| **UI Components** | Custom Blade Components | - | Reusable components (repeater, select, datepicker) |
| **Export Excel** | Laravel Excel (Maatwebsite) | 3.1 | Export multi-sheet sesuai format Data Dukung |
| **Chart** | Chart.js | - | Dashboard grafik |
| **Auth** | Laravel Breeze (Blade) | - | Simple login |
| **Search** | PostgreSQL FTS + Controller | - | Autocomplete kapal/pelabuhan via AJAX |

### Alasan PostgreSQL vs MySQL:
1. **Aggregate functions** lebih powerful — FILTER, GROUPING SETS cocok untuk laporan pivot
2. **Window functions** bagus untuk running total
3. **JSONB** kalau butuh flexible field di masa depan
4. **CHECK constraints** native — validasi data di DB level

---

## 8. LOGIKA GENERATE REPORT

### Contoh Query: PELRA per bulan per pelabuhan

```sql
SELECT
    p.nama AS pelabuhan,
    k.bulan,
    COUNT(DISTINCT k.id) AS jumlah_kapal,
    SUM(kpl.gt) AS isi_kotor,
    -- Bongkar
    SUM(CASE WHEN km.tipe = 'BONGKAR' AND km.jenis_hewan IS NULL
        THEN km.ton_m3 ELSE 0 END) AS bongkar_cargo,
    -- (curah dihitung dari jenis_barang tertentu atau field khusus)
    -- Muat
    SUM(CASE WHEN km.tipe = 'MUAT' AND km.jenis_hewan IS NULL
        THEN km.ton_m3 ELSE 0 END) AS muat_cargo,
    -- Hewan
    SUM(CASE WHEN km.tipe = 'BONGKAR'
        THEN km.jumlah_hewan ELSE 0 END) AS hewan_turun,
    SUM(CASE WHEN km.tipe = 'MUAT'
        THEN km.jumlah_hewan ELSE 0 END) AS hewan_naik,
    -- Penumpang
    SUM(k.penumpang_turun) AS penumpang_turun,
    SUM(k.penumpang_naik) AS penumpang_naik,
    SUM(k.lanjutan_penumpang) AS penumpang_lanjutan
FROM kunjungans k
JOIN pelabuhans p ON k.pelabuhan_id = p.id
JOIN kapals kpl ON k.kapal_id = kpl.id
JOIN jenis_pelayarans jp ON k.jenis_pelayaran_id = jp.id
LEFT JOIN kunjungan_muatans km ON k.id = km.kunjungan_id
WHERE jp.kode = 'PELRA'
  AND k.tahun = 2026
GROUP BY p.nama, k.bulan
ORDER BY p.nama, k.bulan;
```

### Contoh Query: Rekap SPB per bulan per pelabuhan per kategori

```sql
SELECT
    p.nama AS pelabuhan,
    k.bulan,
    SUM(CASE WHEN kpl.gt > 500 AND kpl.jenis IN ('KM','KMP','MV')
        THEN 1 ELSE 0 END) AS ppk_27,
    SUM(CASE WHEN kpl.gt <= 500 AND kpl.jenis IN ('KM','KMP')
        THEN 1 ELSE 0 END) AS ppk_29,
    SUM(CASE WHEN kpl.jenis = 'KLM' THEN 1 ELSE 0 END) AS klm,
    COUNT(*) AS jumlah
FROM kunjungans k
JOIN pelabuhans p ON k.pelabuhan_id = p.id
JOIN kapals kpl ON k.kapal_id = kpl.id
WHERE k.tahun = 2026
  AND k.no_spb_tolak IS NOT NULL  -- hanya yang ada SPB
GROUP BY p.nama, k.bulan
ORDER BY p.nama, k.bulan;
```

---

## 9. FASE DEVELOPMENT

### FASE 1 — Foundation (Sprint 1-2)
```
[x] Setup Laravel 12 project
[x] Config PostgreSQL connection
[x] Migration semua tabel (master + transaksi)
[x] Seeder data awal (pelabuhan, kapal, nakhoda, jenis pelayaran, B3)
[x] Auth (login/register) + role sederhana
[x] Layout utama (sidebar + topbar) with Tailwind
```

### FASE 2 — Master Data CRUD (Sprint 3)
```
[ ] CRUD Pelabuhan (Controller + Blade views)
[ ] CRUD Kapal (Controller + AJAX search/filter)
[ ] CRUD Nakhoda
[ ] CRUD Barang B3
[ ] View Jenis Pelayaran
[ ] API endpoints untuk autocomplete (kapal, pelabuhan)
```

### FASE 3 — Form Input Kunjungan (Sprint 4-6) ← CORE
```
[ ] Step 1: Data Kapal & Pelayaran (Alpine.js + AJAX autocomplete)
[ ] Step 2: Tiba/Tambat/Berangkat (Flatpickr date/time)
[ ] Step 3: Muatan (Alpine.js dynamic repeater)
[ ] Step 4: SPB & B3 (Alpine.js toggle, conditional)
[ ] Conditional logic per jenis pelayaran (Alpine.js x-show)
[ ] Validasi form (FormRequest + Alpine.js client-side)
[ ] AJAX form submission (no page reload)
[ ] List kunjungan + filter + pagination (Blade + AJAX)
[ ] Edit & Delete kunjungan
[ ] Quick Duplicate untuk kapal reguler
```

### FASE 4 — Laporan (Sprint 7-8)
```
[ ] Query agregasi per report (PELRA, Perintis, Ferry, DN, LN, Rekap SPB, Rekap Ops)
[ ] Preview di browser (tabel HTML sesuai format)
[ ] Filter tahun, pelabuhan
[ ] Export Excel multi-sheet (Data Dukung E-Performance)
```

### FASE 5 — Dashboard & Polish (Sprint 9-10)
```
[ ] Dashboard: summary cards + charts
[ ] Status input per bulan
[ ] Audit log
[ ] Import dari Excel lama (opsional)
[ ] Testing & bug fix
[ ] Deploy
```

---

## 10. STRUKTUR FOLDER LARAVEL

```
app/
├── Models/
│   ├── Pelabuhan.php
│   ├── Kapal.php
│   ├── Nakhoda.php
│   ├── JenisPelayaran.php
│   ├── BarangB3.php
│   ├── Kunjungan.php
│   ├── KunjunganMuatan.php
│   └── KunjunganB3.php
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── Master/
│   │   │   ├── PelabuhanController.php
│   │   │   ├── KapalController.php
│   │   │   ├── NakhodaController.php
│   │   │   └── BarangB3Controller.php
│   │   ├── Kunjungan/
│   │   │   └── KunjunganController.php    ← CRUD + wizard
│   │   ├── Laporan/
│   │   │   └── LaporanController.php       ← All reports
│   │   └── Api/
│   │       ├── KapalSearchController.php   ← Autocomplete API
│   │       └── PelabuhanSearchController.php
│   └── Requests/
│       └── KunjunganRequest.php            ← Form validation
├── Services/
│   ├── LaporanService.php                  ← Query agregasi
│   └── ExcelExportService.php              ← Generate Excel Data Dukung
├── Exports/
│   └── DataDukungExport.php                ← Multi-sheet Excel
database/
├── migrations/
│   ├── create_pelabuhans_table.php
│   ├── create_kapals_table.php
│   ├── create_nakhodas_table.php
│   ├── create_jenis_pelayarans_table.php
│   ├── create_barang_b3s_table.php
│   ├── create_kunjungans_table.php
│   ├── create_kunjungan_muatans_table.php
│   └── create_kunjungan_b3s_table.php
├── seeders/
│   ├── PelabuhanSeeder.php
│   ├── KapalSeeder.php
│   ├── JenisPelayaranSeeder.php
│   └── BarangB3Seeder.php
resources/views/
├── dashboard.blade.php
├── master/
│   ├── pelabuhan/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── kapal/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── nakhoda/
│   └── barang-b3/
├── kunjungan/
│   ├── index.blade.php                     ← List + filter
│   ├── create.blade.php                    ← Form wizard
│   └── edit.blade.php
├── laporan/
│   ├── index.blade.php                     ← Menu laporan
│   ├── pelra.blade.php
│   ├── perintis.blade.php
│   ├── ferry.blade.php
│   ├── dalam-negeri.blade.php
│   ├── luar-negeri.blade.php
│   ├── rekap-spb.blade.php
│   └── rekap-operasional.blade.php
├── layouts/
│   └── app.blade.php                       ← Main layout
└── components/
    ├── sidebar.blade.php
    ├── topbar.blade.php
    ├── autocomplete.blade.php              ← Reusable autocomplete
    ├── repeater.blade.php                  ← Dynamic form rows
    └── toast.blade.php                     ← Toast notification

public/js/
└── app.js                                  ← Custom JS (AJAX, form logic)
```

---

## 11. RINGKASAN

| Aspek | Keputusan |
|-------|-----------|
| **Form Input** | **1 form unified** (wizard 4 step), conditional per jenis pelayaran |
| **Master Data** | 5 master: Pelabuhan, Kapal, Nakhoda, Jenis Pelayaran, Barang B3 |
| **Report** | 7 laporan auto-generated + 1 export Excel multi-sheet |
| **Stack** | Laravel 12 + PostgreSQL 16 + Blade + Alpine.js + Tailwind 4 |
| **Tabel utama** | `kunjungans` (1 row = 1 kunjungan kapal) |
| **Detail** | `kunjungan_muatans` (N baris bongkar/muat), `kunjungan_b3s` (N baris B3) |
| **Jumlah Sprint** | ~10 sprint (2 minggu/sprint) |
