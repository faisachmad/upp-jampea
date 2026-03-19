---
name: master-data-ui-ux
description: Applies standard, compact, enterprise-grade UI/UX rules when creating or modifying Master Data modules (DataTables, Buttons, Dropdowns). Activates when the user mentions building, fixing, or creating master data modules, tables, datatables, or UI/UX standardization.
---
# Master Data UI/UX Standardization

When creating or modifying Master Data modules (DataTables, buttons, etc.), you MUST adhere to the following enterprise-grade conventions.

## 1. Desain Tabel (Compact Layout)
Agar tabel terlihat rapi, tidak memakan terlalu banyak ruang, dan terlihat profesional (kelas enterprise), gunakan padding dan font size yang lebih kecil dari bawaan. Abaikan default styling dari Laravel Breeze/Tailwind jika bertentangan.

**Wajib Gunakan CSS Kustom Ini di Blade View:**
```css
#table-id thead th {
    background-color: #f9fafb;
    color: #374151;
    text-transform: uppercase;
    font-size: 0.7rem; /* FONT LEBIH KECIL */
    letter-spacing: 0.05em;
    font-weight: 600;
    padding: 0.5rem 1rem; /* PADDING LEBIH KECIL */
    border-bottom: 1px solid #e5e7eb;
}

#table-id tbody td {
    padding: 0.5rem 1rem; /* PADDING LEBIH KECIL */
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.75rem; /* FONT LEBIH KECIL */
}
```

## 2. Visibilitas Dropdown & Responsivitas (DataTables)
Untuk mencegah menu aksi (dropdown) terpotong pada baris paling bawah dan memastikan fitur "Responsive" DataTables berjalan lancar tanpa horizontal scroll.

**Wajib Hilangkan `overflow-x-auto` pada Wrapper:**
Gunakan `overflow-visible`, HINDARI `overflow-x-auto` atau `overflow-hidden` pada pembungkus tabel:
```html
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-visible">
    <div class="overflow-visible"> 
        <table id="table-id" class="min-w-full divide-y divide-gray-200">
```

**Konfigurasi DataTables DOM Wajib:**
Dalam inisialisasi DataTables, pastikan parameter `dom` menggunakan `w-full overflow-visible` di wrapper dalam (`t`), dan aktifkan `responsive: true`, `autoWidth: false`.
```javascript
table = $('#table-id').DataTable({
    // ... opsi dataTables lainnya ...
    dom: "<'flex flex-col'<'w-full overflow-visible't><'flex flex-col md:flex-row justify-between items-center p-4 gap-4'<'flex items-center gap-6'li>p>>",
    responsive: true,
    autoWidth: false
});
```

## 3. Komponen Tombol (Button Sizing 'xs' atau 'sm')
Gunakan ukuran tombol yang *compact* (kecil/xs) agar proporsional dengan tabel. 

**Tombol Utama (contoh: Tambah Data):**
```html
<button class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-all font-medium">
    Tambah Data
</button>
```

**Tombol Filter / Cari:**
```html
<button type="button" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all">
    Cari
</button>
```

## 4. Tombol Aksi Dropdown (Controller / Render Component)
Pastikan menu dropdown aksi Alpine.js di baris DataTable memiliki `z-index` bernilai `100` (`z-[100]`) agar selalu muncul di atas elemen lain.

**Render HTML di Controller:**
```php
$btn = '<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false" :class="{ \'z-[100]\': open }">
    <button type="button" @click="open = !open" class="inline-flex items-center px-2 py-1 border border-gray-200 text-[10px] font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
        Aksi
        <svg class="ml-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            ...
        </svg>
    </button>
    <div x-show="open" class="origin-top-right absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9995] focus:outline-none" style="display: none;">
        ...
    </div>
</div>';
```
