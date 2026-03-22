# Panduan Pengguna SAPOJAM UPP Jampea

Panduan ini ditujukan untuk operator dan petugas administrasi pelabuhan yang menggunakan SAPOJAM untuk mencatat kunjungan kapal secara manual, memelihara master data, dan memeriksa hasil input.

Panduan ini hanya membahas penggunaan aplikasi. Panduan ini tidak membahas instalasi, deployment, atau konfigurasi teknis.

## Daftar Isi

- [Tujuan Aplikasi](#tujuan-aplikasi)
- [Status Fitur Saat Ini](#status-fitur-saat-ini)
- [Akses dan Login](#akses-dan-login)
- [Alur Kerja Harian](#alur-kerja-harian)
- [Persiapan Data Master](#persiapan-data-master)
- [Input Kunjungan Kapal](#input-kunjungan-kapal)
- [Memeriksa Hasil Input](#memeriksa-hasil-input)
- [Modul yang Belum Tersedia](#modul-yang-belum-tersedia)
- [Tips Operasional](#tips-operasional)
- [Pertanyaan Umum](#pertanyaan-umum)

## Tujuan Aplikasi

SAPOJAM digunakan untuk memindahkan pencatatan manual operasional pelabuhan dari file Excel ke satu aplikasi yang terpusat.

Dalam kondisi aplikasi saat ini, SAPOJAM dipakai untuk:

1. Mengelola data referensi pelabuhan, kapal, nakhoda, jenis pelayaran, jenis kapal, tipe pelabuhan, dan barang B3.
2. Mencatat satu kejadian kunjungan kapal dalam satu form yang mencakup data kedatangan, keberangkatan, penumpang, kendaraan, muatan, dan barang B3.
3. Menelusuri kembali data kunjungan yang sudah diinput melalui daftar dan halaman detail.
4. Menghapus data kunjungan yang salah lalu menginput ulang bila diperlukan.

## Status Fitur Saat Ini

Status ini penting agar operator tidak salah ekspektasi saat menggunakan aplikasi.

### Fitur yang sudah bisa digunakan

1. Login pengguna.
2. Dashboard sebagai halaman awal.
3. Master Data:
   Pelabuhan, Tipe Pelabuhan, Kapal, Jenis Kapal, Nakhoda, Barang B3, Jenis Pelayaran.
4. Input kunjungan kapal melalui wizard 5 tab.
5. Pencarian dan filter data kunjungan.
6. Melihat detail kunjungan.
7. Menghapus data kunjungan.

### Fitur yang belum tersedia atau belum lengkap

1. Edit kunjungan yang sudah tersimpan belum tersedia di alur aplikasi saat ini.
2. Laporan operasional, laporan PELRA, DN, LN, Perintis, Ferry, Rekap SPB, Rekap Operasional, dan export Excel atau PDF belum aktif.
3. Import data lama dari Excel belum tersedia.
4. Dashboard belum dapat dijadikan sumber resmi laporan bulanan karena modul laporan belum aktif.

## Akses dan Login

### Cara masuk ke aplikasi

1. Buka alamat SAPOJAM yang diberikan admin.
2. Masukkan email dan password.
3. Klik tombol masuk.
4. Setelah login, Anda akan masuk ke dashboard.

### Yang perlu diperhatikan

1. Semua menu utama hanya dapat diakses setelah login.
2. Jika sesi habis, login ulang lalu lanjutkan pekerjaan.
3. Bila username atau password bermasalah, hubungi admin aplikasi.

## Alur Kerja Harian

Urutan kerja yang disarankan untuk operator adalah sebagai berikut:

1. Pastikan data master sudah tersedia.
2. Input kunjungan kapal setiap ada kapal datang atau berangkat.
3. Isi data muatan dan barang B3 bila memang ada.
4. Simpan data kunjungan.
5. Buka daftar kunjungan untuk memeriksa apakah data sudah masuk.
6. Buka halaman detail untuk memastikan data penting sudah benar.
7. Jika ditemukan kesalahan, saat ini langkah koreksi yang tersedia adalah menghapus data lalu input ulang.

## Persiapan Data Master

Sebelum menginput kunjungan, operator perlu memastikan data referensi sudah tersedia. Urutan yang disarankan adalah:

1. Tipe Pelabuhan
2. Pelabuhan
3. Jenis Kapal
4. Jenis Pelayaran
5. Kapal
6. Nakhoda
7. Barang B3

### 1. Master Pelabuhan

Menu: Master Data > Pelabuhan

Gunakan menu ini untuk mencatat pelabuhan internal UPP Jampea dan pelabuhan luar yang sering muncul sebagai asal atau tujuan kapal.

Data yang diisi:

1. Nama pelabuhan.
2. Tipe pelabuhan.
3. Status aktif atau nonaktif.

Kegunaan dalam proses bisnis:

1. Pelabuhan pencatat kunjungan.
2. Pelabuhan asal kapal.
3. Pelabuhan tujuan kapal.

Catatan operasional:

1. Data pelabuhan yang aktif akan dipakai dalam form kunjungan.
2. Gunakan nama yang konsisten agar pencarian mudah.

### 2. Master Kapal

Menu: Master Data > Kapal

Gunakan menu ini untuk mencatat data kapal yang akan muncul pada saat input kunjungan.

Data utama yang bisa dicatat:

1. Nama kapal.
2. Jenis kapal.
3. GT.
4. DWT.
5. Panjang kapal.
6. Tanda selar.
7. Call sign.
8. Tempat kedudukan kapal.
9. Bendera.
10. Pemilik atau agen.
11. Status aktif atau nonaktif.

Catatan operasional:

1. Gunakan nama kapal yang sama dengan dokumen operasional agar tidak terjadi duplikasi penulisan.
2. Jika kapal tidak aktif lagi, ubah statusnya menjadi nonaktif.

### 3. Master Nakhoda

Menu: Master Data > Nakhoda

Gunakan menu ini untuk mencatat nama nakhoda. Nakhoda dapat dikaitkan ke kapal, tetapi yang terpenting adalah namanya tersedia saat input kunjungan.

Data yang diisi:

1. Nama nakhoda.
2. Kapal terkait bila ada.
3. Status aktif atau nonaktif.

### 4. Master Barang B3

Menu: Master Data > Barang B3

Gunakan menu ini bila ada kegiatan bongkar atau muat barang berbahaya dan beracun.

Data yang diisi:

1. Nama barang B3.
2. UN Number.
3. Kelas.
4. Kategori.

Catatan operasional:

1. Gunakan data B3 yang sesuai dengan dokumen pengawasan atau dokumen muatan.
2. Jika jenis barang belum ada, tambahkan dulu di master atau gunakan tombol tambah jenis B3 baru dari form kunjungan.

### 5. Master Lainnya

Menu master lain dipakai sebagai referensi tambahan:

1. Tipe Pelabuhan untuk klasifikasi pelabuhan.
2. Jenis Kapal untuk klasifikasi kapal.
3. Jenis Pelayaran untuk klasifikasi kunjungan, misalnya PELRA, DN, LN, Perintis, atau Ferry.

## Input Kunjungan Kapal

Menu: Input Kunjungan

Input kunjungan kapal adalah proses utama di SAPOJAM. Satu input kunjungan dipakai untuk merekam satu kejadian kapal datang dan berangkat, termasuk penumpang, kendaraan, muatan, dan B3 bila ada.

### Cara membuka form input

1. Klik menu Input Kunjungan.
2. Klik tombol Input Kunjungan.
3. Form akan terbuka dalam wizard 5 tab.

### Gambaran isi 5 tab

1. Data Kunjungan
2. Kedatangan dan Keberangkatan
3. Penumpang dan Kendaraan
4. Data Muatan
5. Barang B3

### Tab 1: Data Kunjungan

Isi informasi dasar kunjungan:

1. Pelabuhan
   Pilih pelabuhan tempat kejadian dicatat.
2. Jenis Pelayaran
   Pilih jenis pelayaran sesuai kegiatan kapal.
3. Kapal
   Ketik nama kapal lalu pilih dari hasil pencarian.
4. Nakhoda
   Ketik nama nakhoda lalu pilih dari hasil pencarian.
5. Bulan
   Pilih bulan pelaporan.
6. Tahun
   Pilih tahun pelaporan.

Catatan operasional:

1. Jika pelabuhan belum ada, gunakan tombol Tambah pada field pelabuhan.
2. Jika nakhoda belum ada, pilih kapal terlebih dahulu lalu gunakan tombol Tambah Nakhoda.
3. Pastikan bulan dan tahun sesuai periode administrasi yang dipakai kantor.

### Tab 2: Kedatangan dan Keberangkatan

Tab ini dipakai untuk mengisi data kedatangan dan data keberangkatan.

#### Bagian kedatangan

1. Tanggal datang.
2. Jam datang.
3. Pelabuhan asal.
4. Nomor SPB datang bila ada.

#### Bagian keberangkatan

1. Tanggal tolak.
2. Jam tolak.
3. Pelabuhan tujuan.
4. Nomor SPB tolak bila ada.

Catatan penting:

1. Pada aplikasi saat ini, field datang dan tolak diperlakukan sebagai data yang harus diisi saat penyimpanan.
2. Artinya, input kunjungan belum mendukung skenario kapal datang dulu lalu data tolak diisi belakangan melalui fitur edit.
3. Jika data keberangkatan belum tersedia, operator perlu menunda input final atau menggunakan kebijakan kerja internal yang disepakati kantor.

### Tab 3: Penumpang dan Kendaraan

Tab ini dipakai untuk mencatat penumpang dan kendaraan turun atau naik.

#### Penumpang

Isi jumlah:

1. Datang - Dewasa
2. Datang - Anak
3. Tolak - Dewasa
4. Tolak - Anak

#### Kendaraan

Isi jumlah datang dan tolak untuk golongan:

1. Gol I: Motor
2. Gol II: Sedan atau jeep
3. Gol III: Minibus
4. Gol IVA: Bus kecil
5. Gol IVB: Bus besar
6. Gol V: Truk

Catatan operasional:

1. Nilai yang tidak ada dapat diisi 0.
2. Data kendaraan pada aplikasi aktif terutama mencakup motor dan mobil melalui agregasi dari golongan-golongan ini.

### Tab 4: Data Muatan

Tab ini dipakai untuk mencatat muatan bongkar, muat, dan muatan lanjutan.

#### Muatan lanjutan

Field yang tersedia saat ini:

1. Lanjutan Muatan (Ton)

Isi field ini bila ada muatan transit atau muatan yang tidak dibongkar.

#### Daftar muatan bongkar dan muat

Klik Tambah Muatan untuk menambahkan baris muatan.

Isi setiap baris dengan:

1. Tipe: Bongkar atau Muat.
2. Jenis Barang.
3. Ton atau M3.
4. Jenis Hewan bila muatan berupa hewan.
5. Jumlah Hewan bila ada.

Catatan operasional:

1. Jika tidak ada muatan, tab ini boleh dibiarkan tanpa baris muatan.
2. Bila muatan lebih dari satu jenis, tambahkan beberapa baris.
3. Pada aplikasi saat ini, muatan lanjutan yang tersedia baru tonase. Rincian lanjutan lain seperti kendaraan lanjutan atau penumpang lanjutan belum diinput melalui form aktif.

### Tab 5: Barang B3

Tab ini dipakai hanya bila kapal melakukan kegiatan bongkar atau muat barang B3.

Klik Tambah B3 untuk menambahkan baris.

Isi setiap baris dengan:

1. Barang B3.
2. Jenis kegiatan: Bongkar atau Muat.
3. Bentuk muatan: Curah atau Padat/Kemasan.
4. Jumlah ton bila ada.
5. Jumlah container bila ada.
6. Kemasan.
7. Jumlah kemasan.
8. Nama petugas.

Catatan operasional:

1. Jika tidak ada B3, tab ini dapat dibiarkan kosong.
2. Bila jenis barang belum tersedia, gunakan tombol Jenis B3 Baru.
3. Pada aplikasi saat ini, jenis kegiatan B3 yang tersedia adalah Bongkar dan Muat.

### Menyimpan data kunjungan

1. Setelah semua data selesai diisi, buka tab 5.
2. Klik tombol Simpan Data Kunjungan.
3. Tunggu sampai aplikasi kembali ke halaman daftar kunjungan dan menampilkan pesan berhasil.

Sebelum menyimpan, lakukan pemeriksaan singkat:

1. Kapal sudah benar.
2. Pelabuhan pencatat, asal, dan tujuan sudah benar.
3. Tanggal dan jam datang serta tolak sudah benar.
4. Jenis pelayaran sudah sesuai.
5. Penumpang, kendaraan, muatan, dan B3 sudah sesuai dokumen kerja.

## Memeriksa Hasil Input

### Daftar kunjungan

Setelah menyimpan, operator kembali ke halaman daftar kunjungan.

Di halaman ini operator dapat:

1. Mencari data berdasarkan bulan.
2. Mencari data berdasarkan tahun.
3. Memfilter data berdasarkan pelabuhan.
4. Memfilter data berdasarkan jenis pelayaran.
5. Membuka detail setiap kunjungan.
6. Menghapus data kunjungan.

### Halaman detail kunjungan

Gunakan tombol Detail untuk memeriksa isi data yang telah direkam.

Informasi yang bisa diperiksa di halaman detail:

1. Informasi umum kunjungan.
2. Kedatangan dan keberangkatan.
3. Data penumpang.
4. Data kendaraan.
5. Data muatan.
6. Data barang B3.

### Bila data salah

Pada aplikasi saat ini belum tersedia tombol edit kunjungan yang aktif di alur kerja utama. Karena itu langkah koreksi yang tersedia adalah:

1. Buka detail kunjungan.
2. Pastikan data yang salah memang perlu diperbaiki.
3. Klik Hapus Data Kunjungan.
4. Konfirmasi penghapusan.
5. Input ulang data kunjungan dengan data yang benar.

Catatan penting:

1. Saat data kunjungan dihapus, data muatan dan data B3 yang terkait juga ikut terhapus.
2. Pastikan Anda sudah menyalin atau mencatat ulang data yang diperlukan sebelum menghapus.

## Modul yang Belum Tersedia

Bagian ini perlu dipahami oleh end user agar tidak menunggu fitur yang memang belum aktif.

### 1. Laporan

Menu laporan sudah tampil di sidebar, tetapi fungsi laporan belum aktif.

Yang belum tersedia saat ini:

1. Laporan PELRA
2. Laporan Perintis
3. Laporan Ferry
4. Laporan Dalam Negeri
5. Laporan Luar Negeri
6. Rekap SPB
7. Rekap Operasional
8. Export Excel
9. Export PDF

Artinya:

1. SAPOJAM saat ini sudah dipakai untuk pencatatan data dasar.
2. Rekap otomatis dalam format laporan bulanan atau data dukung masih belum tersedia langsung dari menu laporan.

### 2. Import Excel Lama

Belum ada menu untuk mengimpor file Excel lama langsung ke aplikasi.

### 3. Edit Kunjungan

Belum ada proses edit kunjungan yang siap dipakai operator untuk memperbarui data yang sudah disimpan.

## Tips Operasional

1. Lengkapi master data terlebih dahulu sebelum mulai input kunjungan.
2. Gunakan satu standar penulisan nama kapal, pelabuhan, dan nakhoda agar data tidak ganda.
3. Periksa kembali tanggal, jam, dan rute sebelum menekan simpan.
4. Isi 0 pada angka yang memang tidak ada agar tidak membingungkan saat verifikasi.
5. Gunakan halaman detail sebagai tempat pemeriksaan akhir setelah input berhasil.
6. Jika data salah, siapkan data pengganti terlebih dahulu sebelum menghapus data lama.

## Pertanyaan Umum

### Apakah satu kunjungan kapal cukup diinput satu kali?

Ya. Satu kunjungan kapal dicatat dalam satu form yang memuat data datang, data tolak, penumpang, kendaraan, muatan, dan B3 bila ada.

### Apakah semua proses Excel lama sudah otomatis menjadi laporan di aplikasi?

Belum. Aplikasi sudah membantu pencatatan data operasional inti, tetapi rekap laporan otomatis dan export file belum aktif.

### Apakah saya bisa memperbaiki data kunjungan yang salah?

Saat ini koreksi dilakukan dengan menghapus data yang salah lalu menginput ulang.

### Apakah data B3 wajib diisi?

Tidak. Isi hanya jika ada kegiatan barang B3.

### Apakah data muatan wajib diisi?

Tidak selalu. Isi jika memang ada kegiatan bongkar, muat, atau muatan lanjutan.

### Apakah data keberangkatan bisa diisi belakangan?

Dalam kondisi aplikasi saat ini, data keberangkatan belum didukung untuk diisi belakangan melalui fitur edit. Karena itu operator perlu menyesuaikan waktu input dengan kelengkapan data yang tersedia.

### Bagaimana bila nama kapal, pelabuhan, nakhoda, atau barang B3 belum tersedia?

Tambahkan dulu melalui menu master data. Untuk beberapa field tertentu, aplikasi juga menyediakan tombol tambah cepat dari dalam form kunjungan.

### Apakah hasil di dashboard sudah bisa dipakai sebagai laporan resmi?

Belum. Gunakan data kunjungan yang tersimpan sebagai sumber pencatatan, dan pahami bahwa modul laporan resmi masih belum aktif.
