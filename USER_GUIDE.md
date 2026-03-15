# Panduan Pengguna SAPOJAM UPP Jampea

Panduan lengkap penggunaan aplikasi SAPOJAM UPP Jampea untuk operator pelabuhan.

---

## 📋 Daftar Isi

- [Login & Dashboard](#login--dashboard)
- [Master Data Pelabuhan](#master-data-pelabuhan)
- [Master Data Kapal](#master-data-kapal)
- [Master Data Nakhoda](#master-data-nakhoda)
- [Master Data Barang B3](#master-data-barang-b3)
- [Input Kunjungan Kapal](#input-kunjungan-kapal)
- [Lihat & Edit Kunjungan](#lihat--edit-kunjungan)
- [Generate Laporan](#generate-laporan)
- [Tips & Trik](#tips--trik)

---

## 🔐 Login & Dashboard

### Login ke Aplikasi

1. Buka browser dan akses URL aplikasi:
   - Development: `http://localhost:8000`
   - Production: `https://sapojam.uppjampea.id`

2. Masukkan kredensial:
   - **Email**: admin@uppjampea.id
   - **Password**: password (ganti setelah login pertama)

3. Klik tombol **"Log in"**

4. Setelah berhasil login, Anda akan diarahkan ke **Dashboard**

### Dashboard Overview

Dashboard menampilkan:
- Ringkasan kunjungan hari ini
- Statistik bulanan
- Quick actions (Tambah Kunjungan, Lihat Laporan)
- Notifikasi

---

## 🏢 Master Data Pelabuhan

### Menampilkan Daftar Pelabuhan

1. Klik menu **"Master Data"** di sidebar
2. Pilih **"Pelabuhan"**
3. Anda akan melihat tabel dengan kolom:
   - Kode Pelabuhan
   - Nama Pelabuhan
   - Tipe (UPP/POSKER/WILKER/LUAR)
   - Status (Aktif/Nonaktif)
   - Aksi

### Menambah Pelabuhan Baru

1. Di halaman Daftar Pelabuhan, klik tombol **"Tambah Pelabuhan"** (kanan atas)
2. Isi form:
   - **Kode**: Kode unik pelabuhan (contoh: MKS, SUB)
   - **Nama**: Nama lengkap pelabuhan
   - **Tipe**: Pilih salah satu:
     - UPP - Pelabuhan Utama
     - POSKER - Pos Pengawasan Kerja
     - WILKER - Wilayah Kerja
     - LUAR - Pelabuhan Eksternal
   - **Status**: Centang "Aktif" jika pelabuhan operasional
3. Klik **"Simpan"**

**Validasi**:
- ❌ Kode tidak boleh kosong dan harus unik
- ❌ Nama tidak boleh kosong
- ❌ Tipe harus dipilih

### Edit Pelabuhan

1. Di kolom Aksi, klik tombol **"Edit"** (ikon pensil)
2. Ubah data yang diperlukan
3. Klik **"Update"**

### Hapus Pelabuhan

1. Di kolom Aksi, klik tombol **"Hapus"** (ikon sampah)
2. Konfirmasi penghapusan
3. **Catatan**: Pelabuhan yang sudah pernah digunakan dalam kunjungan **tidak bisa dihapus**

### Filter & Pencarian

- **Search Box**: Ketik nama atau kode pelabuhan
- **Filter Tipe**: Pilih UPP/POSKER/WILKER/LUAR
- **Filter Status**: Pilih Semua/Aktif/Nonaktif

---

## 🚢 Master Data Kapal

### Menampilkan Daftar Kapal

1. Klik **"Master Data"** > **"Kapal"**
2. Tabel menampilkan:
   - Nama Kapal
   - Jenis (KLM/KM/KMP/MV)
   - GT (Gross Tonnage)
   - Call Sign
   - Pemilik/Agen
   - Status
   - Aksi

### Menambah Kapal Baru

1. Klik tombol **"Tambah Kapal"**
2. Isi form dengan lengkap:

**Data Wajib**:
- **Nama Kapal**: Nama lengkap kapal (contoh: KM Sabuk Nusantara)
- **Jenis Kapal**: Pilih salah satu:
  - **KLM** - Kapal Layar Motor
  - **KM** - Kapal Motor
  - **KMP** - Kapal Motor Penyeberangan
  - **MV** - Motor Vessel

**Data Opsional**:
- **GT**: Gross Tonnage (contoh: 2500.00)
- **DWT**: Dead Weight Tonnage
- **Panjang**: Panjang kapal dalam meter (contoh: 75.50)
- **Tanda Selar**: Tanda selar kapal
- **Call Sign**: Call sign radio (contoh: YBXX)
- **Tempat Kedudukan**: Lokasi kedudukan kapal
- **Bendera**: Bendera negara
- **Pemilik/Agen**: Nama pemilik atau agen kapal

3. Centang **"Aktif"** untuk mengaktifkan kapal
4. Klik **"Simpan"**

### Edit & Hapus Kapal

Sama seperti prosedur Master Pelabuhan.

### Tips:
- 💡 Gunakan search untuk mencari kapal dengan cepat
- 💡 Call Sign harus unik jika diisi
- 💡 Kapal nonaktif tidak akan muncul di autocomplete

---

## 👨‍✈️ Master Data Nakhoda

### Menambah Nakhoda

1. Klik **"Master Data"** > **"Nakhoda"**
2. Klik **"Tambah Nakhoda"**
3. Isi form:
   - **Nama Nakhoda**: Nama lengkap kapten (wajib)
   - **Kapal**: Pilih kapal yang menjadi tugas (opsional, bisa diisi nanti)
   - **Status**: Centang "Aktif"
4. Klik **"Simpan"**

### Assign Nakhoda ke Kapal

**Cara 1 - Via Form Nakhoda**:
1. Edit nakhoda
2. Pilih kapal dari dropdown
3. Simpan

**Cara 2 - Saat Input Kunjungan**:
- Nakhoda bisa dipilih langsung tanpa harus di-assign ke kapal terlebih dahulu

### Filter Nakhoda

- Filter berdasarkan kapal
- Filter berdasarkan status (aktif/nonaktif)
- Search by nama

---

## ☣️ Master Data Barang B3

### Tentang Barang B3

Barang B3 (Bahan Berbahaya dan Beracun) adalah material yang memerlukan penanganan khusus. Setiap barang B3 memiliki **UN Number** (United Nations Number) untuk identifikasi internasional.

### Menambah Barang B3

1. Klik **"Master Data"** > **"Barang B3"**
2. Klik **"Tambah Barang B3"**
3. Isi form:
   - **Nama Barang**: Nama barang B3 (contoh: Bensin, LPG, Asam Sulfat)
   - **UN Number**: Kode UN (contoh: UN1203, UN1075) - harus unik
   - **Kelas**: Kelas bahaya (1-9, bisa desimal seperti 2.1, 3, 6.1)
   - **Kategori**: Kategori barang (contoh: Flammable Liquid, Toxic Gas)
4. Klik **"Simpan"**

### Contoh Data B3 Umum

| Nama Barang | UN Number | Kelas | Kategori |
|-------------|-----------|-------|----------|
| Bensin | UN1203 | 3 | Flammable Liquid |
| Solar | UN1202 | 3 | Flammable Liquid |
| LPG | UN1075 | 2.1 | Flammable Gas |
| Amonia | UN1005 | 2.3 | Toxic Gas |
| Asam Sulfat | UN1830 | 8 | Corrosive |

### Filter Barang B3

- Search by nama atau UN Number
- Filter by kelas (1-9)

---

## ⚓ Input Kunjungan Kapal

### Overview Form Kunjungan

Form input kunjungan menggunakan **wizard 5 tab** untuk memudahkan entry data:
1. **Tab 1**: Data Kunjungan
2. **Tab 2**: Kedatangan & Keberangkatan
3. **Tab 3**: Penumpang & Kendaraan
4. **Tab 4**: Data Muatan
5. **Tab 5**: Barang B3

### Memulai Input Kunjungan

1. Klik menu **"Kunjungan"** di sidebar
2. Klik tombol **"Tambah Kunjungan"**
3. Ikuti wizard step-by-step

---

### TAB 1: Data Kunjungan

**Field yang harus diisi**:

1. **Pelabuhan** (dropdown):
   - Pilih pelabuhan tempat kapal berkunjung
   - Hanya menampilkan 6 pelabuhan internal UPP Jampea

2. **Jenis Pelayaran** (dropdown):
   - PELRA - Pelayaran Rakyat
   - DN - Dalam Negeri
   - LN - Luar Negeri
   - PERINTIS - Kapal Perintis
   - FERRY-ASDP - Ferry ASDP
   - FERRY-DJPD - Ferry DJPD

3. **Kapal** (autocomplete):
   - Ketik minimal 2 karakter
   - Pilih dari hasil pencarian
   - Menampilkan: Nama - Jenis - GT - Call Sign

4. **Nakhoda** (autocomplete):
   - Ketik nama nakhoda
   - Opsional (bisa dikosongkan)

5. **Periode**:
   - **Bulan**: Pilih 1-12
   - **Tahun**: Pilih tahun (2020-sekarang)

**Klik "Selanjutnya"** untuk ke Tab 2.

---

### TAB 2: Kedatangan & Keberangkatan

#### Kolom Kiri: Kedatangan

1. **Tanggal Datang** (required):
   - Format: DD/MM/YYYY
   - Gunakan date picker

2. **Jam Datang** (required):
   - Format: HH:MM
   - Gunakan time picker

3. **Pelabuhan Asal** (autocomplete):
   - Ketik nama pelabuhan asal
   - Opsional

4. **No. SPB Datang**:
   - Nomor Surat Persetujuan Berlayar kedatangan
   - Opsional

#### Kolom Kanan: Keberangkatan

1. **Tanggal Tolak** (opsional):
   - Tanggal keberangkatan
   - Kosongkan jika kapal masih berlabuh

2. **Jam Tolak** (opsional):
   - Jam keberangkatan

3. **Pelabuhan Tujuan** (autocomplete):
   - Ketik nama pelabuhan tujuan
   - Opsional

4. **No. SPB Tolak**:
   - Nomor SPB keberangkatan
   - Opsional

**Tips**:
- 💡 Jika kapal baru datang dan belum tolak, kosongkan semua field keberangkatan
- 💡 Anda bisa edit kunjungan nanti untuk mengisi data keberangkatan

**Klik "Selanjutnya"** atau **"Sebelumnya"** untuk navigasi tab.

---

### TAB 3: Penumpang & Kendaraan

#### Data Penumpang

**Penumpang Datang**:
- Dewasa: Jumlah penumpang dewasa yang turun (default: 0)
- Anak: Jumlah penumpang anak yang turun (default: 0)

**Penumpang Tolak**:
- Dewasa: Jumlah penumpang dewasa yang naik (default: 0)
- Anak: Jumlah penumpang anak yang naik (default: 0)

#### Data Kendaraan

Tabel 6 baris (Golongan I - V + IVA/IVB):

| Golongan | Deskripsi | Datang | Tolak |
|----------|-----------|--------|-------|
| **Gol I** | Sepeda motor | 0 | 0 |
| **Gol II** | Sedan, jeep, mini bus | 0 | 0 |
| **Gol III** | Bus kecil, truk kecil | 0 | 0 |
| **Gol IVA** | Bus besar | 0 | 0 |
| **Gol IVB** | Truk besar | 0 | 0 |
| **Gol V** | Truk gandeng | 0 | 0 |

**Isi sesuai jumlah kendaraan yang naik/turun**.

**Klik "Selanjutnya"**.

---

### TAB 4: Data Muatan

#### Muatan Lanjutan

**Muatan Lanjutan (ton)**: Muatan yang tetap di kapal (tidak bongkar/muat).
- Opsional
- Format: desimal (contoh: 150.50)

#### Muatan Bongkar/Muat (Dynamic Repeater)

Klik **"Tambah Muatan"** untuk menambah baris muatan:

1. **Tipe Muatan** (dropdown):
   - BONGKAR - Muatan yang dibongkar
   - MUAT - Muatan yang dimuat

2. **Jenis Barang**:
   - Nama/jenis barang (contoh: Beras, Semen, Kayu)

3. **Ton/M³**:
   - Volume muatan dalam ton atau meter kubik
   - Format desimal (contoh: 25.75)

4. **Jenis Hewan** (jika muatan hewan):
   - Jenis hewan (contoh: Sapi, Kambing, Ayam)

5. **Jumlah Ekor** (jika muatan hewan):
   - Jumlah ekor hewan

**Contoh**:
```
Muatan 1:
- Tipe: BONGKAR
- Jenis Barang: Beras
- Ton/M³: 50.00
- Jenis Hewan: (kosong)
- Jumlah Ekor: (kosong)

Muatan 2:
- Tipe: MUAT
- Jenis Barang: (kosong)
- Ton/M³: (kosong)
- Jenis Hewan: Sapi
- Jumlah Ekor: 20
```

**Untuk menghapus muatan**: Klik tombol **"Hapus"** di baris yang ingin dihapus.

**Klik "Selanjutnya"**.

---

### TAB 5: Barang B3

#### Tambah Barang B3 (Dynamic Repeater)

**Hanya isi tab ini jika kapal membawa barang B3!**

Klik **"Tambah B3"** untuk menambah baris:

1. **Barang B3** (dropdown):
   - Pilih dari master barang B3
   - Menampilkan: Nama - UN Number - Kelas

2. **Jenis Kegiatan** (dropdown):
   - BONGKAR - Membongkar B3
   - MUAT - Memuat B3
   - SIMPANLABUH - Simpan labuh B3

3. **Bentuk Muatan**:
   - Bentuk fisik (contoh: Curah, Peti, Drum, Container)

4. **Jumlah (ton)**:
   - Berat barang B3 dalam ton

5. **Jumlah Container**:
   - Jumlah kontainer jika menggunakan container

6. **Kemasan**:
   - Jenis kemasan (contoh: Drum 200L, Jerrycan, Tangki)

7. **Jumlah Kemasan**:
   - Jumlah unit kemasan

8. **Petugas**:
   - Nama petugas yang menangani

**Contoh**:
```
B3 #1:
- Barang B3: Bensin - UN1203 - Kelas 3
- Jenis Kegiatan: BONGKAR
- Bentuk Muatan: Curah
- Jumlah (ton): 5.00
- Jumlah Container: (kosong)
- Kemasan: Drum 200L
- Jumlah Kemasan: 25
- Petugas: Budi Santoso
```

**Untuk menghapus B3**: Klik tombol **"Hapus"**.

---

### Submit Data Kunjungan

Setelah semua tab diisi:
1. Review data di setiap tab (gunakan navigasi tab)
2. Klik tombol **"Simpan Kunjungan"** di Tab 5
3. Tunggu proses:
   - ✅ Data kunjungan disimpan
   - ✅ Data muatan disimpan (jika ada)
   - ✅ Data B3 disimpan (jika ada)
4. Anda akan diarahkan ke halaman detail kunjungan

**Catatan**:
- ⚠️ Tombol "Simpan" hanya muncul di Tab 5
- ⚠️ Pastikan semua data wajib di Tab 1 & 2 sudah diisi
- ⚠️ Jangan tutup browser saat proses penyimpanan

---

## 👁️ Lihat & Edit Kunjungan

### Daftar Kunjungan

1. Klik menu **"Kunjungan"**
2. Halaman menampilkan tabel kunjungan dengan kolom:
   - Tanggal Datang
   - Pelabuhan
   - Kapal
   - Jenis Pelayaran
   - Nakhoda
   - Rute (Asal → Tujuan)
   - Aksi

### Filter Kunjungan

Gunakan form filter di atas tabel:
- **Bulan**: Pilih bulan (1-12)
- **Tahun**: Pilih tahun
- **Pelabuhan**: Filter berdasarkan pelabuhan
- **Jenis Pelayaran**: Filter berdasarkan jenis

Klik **"Filter"** untuk menerapkan.

### Detail Kunjungan

Klik tombol **"Detail"** untuk melihat informasi lengkap:

**7 Section Detail**:
1. **Informasi Umum**: Pelabuhan, kapal, jenis pelayaran, nakhoda, periode
2. **Kedatangan & Keberangkatan**: Tanggal, jam, pelabuhan asal/tujuan, SPB
3. **Data Penumpang**: 4 card (datang dewasa, datang anak, tolak dewasa, tolak anak)
4. **Data Kendaraan**: Tabel 6 golongan × 2 kolom (datang/tolak)
5. **Data Muatan**: Muatan lanjutan + tabel muatan bongkar/muat
6. **Data B3**: Tabel barang B3 dengan detail lengkap
7. **Action Buttons**: Kembali, Edit, Hapus

### Edit Kunjungan

1. Dari halaman detail, klik tombol **"Edit"**
2. Form wizard akan muncul dengan data yang sudah terisi
3. Edit data yang diperlukan
4. Klik **"Update"** untuk menyimpan perubahan

### Hapus Kunjungan

1. Di halaman detail atau list, klik tombol **"Hapus"**
2. Konfirmasi penghapusan
3. **Catatan**: 
   - Menghapus kunjungan akan otomatis menghapus semua muatan dan B3 terkait
   - Data tidak bisa dikembalikan setelah dihapus

---

## 📊 Generate Laporan

### Jenis Laporan

Aplikasi menyediakan 6 jenis laporan sesuai jenis pelayaran:
1. Laporan PELRA
2. Laporan DN
3. Laporan LN
4. Laporan Perintis
5. Laporan Ferry ASDP
6. Laporan Ferry DJPD

### Cara Generate Laporan (Coming Soon)

1. Klik menu **"Laporan"**
2. Pilih jenis laporan
3. Pilih periode (bulan & tahun)
4. Pilih format export:
   - Excel (.xlsx)
   - PDF
5. Klik **"Generate"**
6. Download file

---

## 💡 Tips & Trik

### Tips Umum

1. **Gunakan Autocomplete**:
   - Ketik minimal 2-3 karakter untuk hasil yang relevan
   - Gunakan nama atau kode untuk pencarian lebih cepat

2. **Save Progress**:
   - Tidak ada fitur draft, pastikan isi semua data sebelum submit
   - Jika browser crash, data akan hilang

3. **Data Master Lengkap**:
   - Isi data master (kapal, pelabuhan, nakhoda) terlebih dahulu
   - Data master yang lengkap mempercepat input kunjungan

4. **Backup Data**:
   - Admin: Lakukan backup database secara berkala
   - Export laporan sebagai arsip

### Keyboard Shortcuts

- **Tab**: Pindah ke field berikutnya
- **Shift + Tab**: Pindah ke field sebelumnya
- **Enter**: Submit form (di field terakhir)
- **Esc**: Tutup modal/popup

### Performance Tips

1. **Filter Data**: Gunakan filter untuk menampilkan data yang relevan
2. **Pagination**: Jangan load semua data sekaligus
3. **Clear Browser Cache**: Jika tampilan error, clear cache & refresh

### Error Handling

**Jika form tidak bisa disubmit**:
- ✅ Cek semua field wajib (marked dengan *)
- ✅ Cek format tanggal & waktu
- ✅ Pastikan koneksi internet stabil
- ✅ Cek console browser untuk error (F12)

**Jika autocomplete tidak muncul**:
- ✅ Ketik minimal 2 karakter
- ✅ Pastikan data master sudah ada
- ✅ Refresh halaman

**Jika session expired**:
- ✅ Login ulang
- ✅ Atur SESSION_LIFETIME di .env untuk durasi lebih lama

---

## ❓ FAQ

**Q: Apakah bisa input kunjungan tanpa nakhoda?**  
A: Ya, field nakhoda opsional.

**Q: Apakah bisa edit kunjungan yang sudah disimpan?**  
A: Ya, klik tombol Detail > Edit.

**Q: Bagaimana jika kapal belum tolak?**  
A: Kosongkan field keberangkatan di Tab 2. Edit nanti setelah kapal tolak.

**Q: Apakah muatan dan B3 wajib diisi?**  
A: Tidak, hanya isi jika kapal membawa muatan/B3.

**Q: Bagaimana cara hapus baris muatan/B3?**  
A: Klik tombol "Hapus" di baris yang ingin dihapus.

**Q: Apakah bisa input kunjungan bulan lalu?**  
A: Ya, pilih bulan & tahun yang sesuai di Tab 1.

**Q: Format UN Number untuk B3?**  
A: UN diikuti 4 digit angka (contoh: UN1203).

**Q: Maksimal berapa muatan/B3 yang bisa ditambah?**  
A: Tidak ada limit, tambahkan sebanyak yang diperlukan.

---

## 📞 Bantuan & Support

Jika mengalami kesulitan, hubungi:
- **Tim IT UPP Jampea**
- **Email**: support@uppjampea.id (jika tersedia)
- **Telepon**: (021) xxx-xxxx

---

**Panduan ini akan terus diupdate sesuai perkembangan aplikasi.**

---

**© 2026 UPP Jampea - User Guide v1.0**
