# Standar Pembuatan Modul Master (UI/UX Guidelines)

Dokumen ini berisi standar UI/UX yang telah diterapkan pada aplikasi untuk memastikan konsistensi antarmuka, terutama pada modul master data (Pelabuhan, Kapal, Nakhoda, dll). Setiap penambahan modul baru **wajib mengikuti standar berikut**.

---

## 1. Desain Tabel (Compact Layout)
Agar tabel terlihat rapi, tidak memakan terlalu banyak ruang, dan terlihat profesional (kelas enterprise), gunakan padding dan font size yang lebih kecil dari bawaan.

**CSS Kustomisasi Tabel di Blade:**
```css
#nama-tabel thead th {
    background-color: #f9fafb;
    color: #374151;
    text-transform: uppercase;
    font-size: 0.7rem; /* FONT LEBIH KECIL */
    letter-spacing: 0.05em;
    font-weight: 600;
    padding: 0.5rem 1rem; /* PADDING LEBIH KECIL */
    border-bottom: 1px solid #e5e7eb;
}

#nama-tabel tbody td {
    padding: 0.5rem 1rem; /* PADDING LEBIH KECIL */
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.75rem; /* FONT LEBIH KECIL */
}
```

---

## 2. Visibilitas Dropdown & Responsivitas (Mencegah Terpotong)
Untuk mencegah menu aksi (dropdown) terpotong pada baris paling bawah dan memastikan fitur "Responsive" DataTables berjalan lancar tanpa horizontal scroll berlebih.

**Pengaturan HTML Wrapper Tabel:**
Hilangkan class `overflow-x-auto` pada pembungkus tabel, dan pastikan menggunakan `overflow-visible`.
```html
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
    <div class="overflow-visible"> <!-- JANGAN GUNAKAN overflow-x-auto/overflow-hidden -->
        <table id="nama-tabel" class="min-w-full divide-y divide-gray-200">
            <!-- ... -->
        </table>
    </div>
</div>
```

**Konfigurasi DataTables (`dom`, `responsive`, `autoWidth`):**
Gunakan struktur `dom` berikut yang menggunakan `w-full overflow-visible` di wrapper dalam (`t`) untuk memungkinkan dropdown muncul keluar area tabel. Aktifkan juga property responsive.
```javascript
$(document).ready(function() {
    $('#nama-tabel').DataTable({
        // ... opsi lain ...
        
        // GUNAKAN DOM INI (Perhatikan: w-full overflow-visible)
        dom: "<'flex flex-col'<'w-full overflow-visible't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
        
        responsive: true,
        autoWidth: false
    });
});
```

---

## 3. Komponen Tombol (Button Sizing)
Gunakan ukuran tombol yang *compact* (kecil/xs) agar proporsional dengan tabel.

**Tombol Utama (contoh: Tambah Data):**
```html
<button class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-all font-medium">
    Tambah Data
</button>
```

**Tombol Pencarian / Filter:**
```html
<button type="button" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all">
    Cari
</button>
```

---

## 4. Tombol Aksi di Kolom DataTables (Controller)
Untuk merender tombol Aksi yang berupa dropdown menu menggunakan Alpine.js, pastikan `z-index` bernilai `100` (`z-[100]`) pada saat menu aktif, serta gunakan *padding* dan *text* ekstra kecil (`px-2 py-1 text-[10px]`).

**Render HTML di Controller:**
```php
$btn = '<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false" :class="{ \'z-[100]\': open }">
    <button type="button" @click="open = !open" class="inline-flex items-center px-2 py-1 border border-gray-200 text-[10px] font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
        Aksi
        <svg class="ml-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9995] focus:outline-none"
         style="display: none;">
        <!-- ISI MENU DROPDOWN -->
    </div>
</div>';
```
