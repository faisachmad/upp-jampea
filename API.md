# Dokumentasi API SILAPOR UPP Jampea

Dokumentasi lengkap untuk REST API endpoints yang tersedia di aplikasi SILAPOR UPP Jampea.

---

## 📋 Daftar Isi

- [Overview](#overview)
- [Authentication](#authentication)
- [Endpoints](#endpoints)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)

---

## 🔍 Overview

### Base URL

```
Development: http://localhost:8000/api
Production:  https://silapor.uppjampea.id/api
```

### Content Type

Semua request dan response menggunakan format JSON:
```
Content-Type: application/json
Accept: application/json
```

### Authentication

Semua API endpoint memerlukan autentikasi. User harus login terlebih dahulu melalui Laravel Breeze authentication.

---

## 🔐 Authentication

API menggunakan Laravel session-based authentication yang disediakan oleh Laravel Breeze.

### Requirements

- User harus login melalui web interface
- Session cookie harus disertakan dalam setiap request
- CSRF token diperlukan untuk semua POST/PUT/DELETE requests

### Login via Browser

```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=admin@uppjampea.id
password=password
```

Setelah login, session cookie akan otomatis disimpan di browser dan dapat digunakan untuk memanggil API.

---

## 📡 API Endpoints

### 1. Kapal Search (Autocomplete)

**Endpoint**: `GET /api/kapal/search`

**Deskripsi**: Mencari kapal berdasarkan nama, call sign, atau pemilik untuk fitur autocomplete.

**Authentication**: Required (session)

#### Request Parameters

| Parameter | Type | Required | Deskripsi |
|-----------|------|----------|-----------|
| q | string | Yes | Query pencarian (min 2 karakter) |

#### Request Example

```http
GET /api/kapal/search?q=sabuk
```

#### Response Success (200)

```json
[
    {
        "id": 1,
        "nama": "KM Sabuk Nusantara",
        "jenis": "KM",
        "gt": "2500.00",
        "call_sign": "YBXX",
        "pemilik_agen": "PT. Pelayaran Nusantara",
        "label": "KM Sabuk Nusantara - KM (GT: 2500.00) - YBXX"
    },
    {
        "id": 5,
        "nama": "MV Sabuk Merah Putih",
        "jenis": "MV",
        "gt": "5000.00",
        "call_sign": "YBZZ",
        "pemilik_agen": "PT. Shipping Indonesia",
        "label": "MV Sabuk Merah Putih - MV (GT: 5000.00) - YBZZ"
    }
]
```

#### Response Empty (200)

```json
[]
```

#### Response Fields

| Field | Type | Deskripsi |
|-------|------|-----------|
| id | integer | ID kapal |
| nama | string | Nama kapal |
| jenis | string | Jenis kapal (KLM/KM/KMP/MV) |
| gt | string | Gross Tonnage |
| call_sign | string | Call sign kapal |
| pemilik_agen | string | Pemilik atau agen kapal |
| label | string | Label lengkap untuk ditampilkan di autocomplete |

#### Search Behavior

- Pencarian case-insensitive
- Mencari di field: `nama`, `call_sign`, `pemilik_agen`
- Hanya menampilkan kapal yang aktif (`is_active = true`)
- Maksimal 10 hasil
- Diurutkan berdasarkan nama (A-Z)

#### Usage Example (JavaScript)

```javascript
async function searchKapal(query) {
    const response = await fetch(`/api/kapal/search?q=${encodeURIComponent(query)}`);
    const data = await response.json();
    
    // Display in autocomplete dropdown
    data.forEach(kapal => {
        console.log(kapal.label);
        // "KM Sabuk Nusantara - KM (GT: 2500.00) - YBXX"
    });
}
```

---

### 2. Pelabuhan Search (Autocomplete)

**Endpoint**: `GET /api/pelabuhan/search`

**Deskripsi**: Mencari pelabuhan berdasarkan kode atau nama untuk fitur autocomplete (semua pelabuhan).

**Authentication**: Required (session)

#### Request Parameters

| Parameter | Type | Required | Deskripsi |
|-----------|------|----------|-----------|
| q | string | Yes | Query pencarian (min 2 karakter) |

#### Request Example

```http
GET /api/pelabuhan/search?q=jampea
```

#### Response Success (200)

```json
[
    {
        "id": 1,
        "kode": "JMP",
        "nama": "Pelabuhan Jampea",
        "tipe": "UPP",
        "label": "JMP - Pelabuhan Jampea (UPP)"
    }
]
```

#### Response Fields

| Field | Type | Deskripsi |
|-------|------|-----------|
| id | integer | ID pelabuhan |
| kode | string | Kode pelabuhan (unik) |
| nama | string | Nama pelabuhan |
| tipe | string | Tipe pelabuhan (UPP/POSKER/WILKER/LUAR) |
| label | string | Label lengkap untuk ditampilkan |

#### Search Behavior

- Pencarian case-insensitive
- Mencari di field: `kode`, `nama`
- Hanya menampilkan pelabuhan yang aktif
- Maksimal 10 hasil
- Diurutkan berdasarkan tipe (UPP > POSKER > WILKER > LUAR), lalu nama

---

### 3. Pelabuhan Internal (Dropdown)

**Endpoint**: `GET /api/pelabuhan/internal`

**Deskripsi**: Mendapatkan semua pelabuhan internal UPP Jampea (UPP, POSKER, WILKER) untuk dropdown selection.

**Authentication**: Required (session)

#### Request Parameters

Tidak ada parameter.

#### Request Example

```http
GET /api/pelabuhan/internal
```

#### Response Success (200)

```json
[
    {
        "id": 1,
        "kode": "JMP",
        "nama": "Pelabuhan Jampea",
        "tipe": "UPP",
        "label": "JMP - Pelabuhan Jampea (UPP)"
    },
    {
        "id": 2,
        "kode": "PMT",
        "nama": "Posker Pamatata",
        "tipe": "POSKER",
        "label": "PMT - Posker Pamatata (POSKER)"
    },
    {
        "id": 3,
        "kode": "KAY",
        "nama": "Posker Kayuangin",
        "tipe": "POSKER",
        "label": "KAY - Posker Kayuangin (POSKER)"
    },
    {
        "id": 4,
        "kode": "BON",
        "nama": "Wilker Bonerate",
        "tipe": "WILKER",
        "label": "BON - Wilker Bonerate (WILKER)"
    },
    {
        "id": 5,
        "kode": "RAJ",
        "nama": "Wilker Rajuni",
        "tipe": "WILKER",
        "label": "RAJ - Wilker Rajuni (WILKER)"
    },
    {
        "id": 6,
        "kode": "TAR",
        "nama": "Wilker Tarupa",
        "tipe": "WILKER",
        "label": "TAR - Wilker Tarupa (WILKER)"
    }
]
```

#### Response Fields

Same as Pelabuhan Search endpoint.

#### Usage

Endpoint ini digunakan untuk:
- Dropdown pelabuhan pada form kunjungan (Tab 1)
- Filter pelabuhan pada halaman list kunjungan
- Tidak termasuk pelabuhan eksternal (tipe = LUAR)

---

## 📝 Response Format

### Success Response

Semua API mengembalikan array JSON dengan status code 200.

```json
[
    {
        "id": 1,
        "field1": "value1",
        "field2": "value2"
    }
]
```

### Empty Response

Jika tidak ada data yang cocok, return empty array:

```json
[]
```

### HTTP Status Codes

| Code | Deskripsi |
|------|-----------|
| 200 | Success - Request berhasil |
| 401 | Unauthorized - User tidak login |
| 403 | Forbidden - Tidak punya akses |
| 404 | Not Found - Endpoint tidak ditemukan |
| 422 | Validation Error - Parameter tidak valid |
| 500 | Server Error - Internal server error |

---

## ⚠️ Error Handling

### Unauthenticated Error (401)

Terjadi jika user belum login:

```json
{
    "message": "Unauthenticated."
}
```

**Solution**: Redirect user ke halaman login.

### Validation Error (422)

Terjadi jika parameter tidak valid (misalnya query terlalu pendek):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "q": [
            "The q field must be at least 2 characters."
        ]
    }
}
```

### Server Error (500)

```json
{
    "message": "Server Error",
    "exception": "ErrorException: ..."
}
```

---

## 🔒 Security

### CSRF Protection

Untuk request dari JavaScript, tambahkan CSRF token di header:

```javascript
fetch('/api/kapal/search?q=test', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
    }
})
```

### Session Cookie

Pastikan request menyertakan session cookie. Jika menggunakan `fetch()` dari same origin, cookie otomatis disertakan.

Jika menggunakan Axios:

```javascript
axios.defaults.withCredentials = true;
```

---

## 🚀 Rate Limiting

### Current Settings

- **No rate limiting** (development)
- Production: 60 requests per minute per user (recommended)

### Implementation (Production)

```php
// routes/api.php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // API routes
});
```

---

## 📊 Usage Examples

### Example 1: Autocomplete Kapal dengan Alpine.js

```html
<div x-data="{
    searchQuery: '',
    results: [],
    async search() {
        if (this.searchQuery.length < 2) {
            this.results = [];
            return;
        }
        const response = await fetch(`/api/kapal/search?q=${this.searchQuery}`);
        this.results = await response.json();
    }
}">
    <input 
        type="text" 
        x-model="searchQuery" 
        @input.debounce.300ms="search()"
        placeholder="Cari kapal..."
    >
    
    <ul x-show="results.length > 0">
        <template x-for="kapal in results" :key="kapal.id">
            <li x-text="kapal.label"></li>
        </template>
    </ul>
</div>
```

### Example 2: Load Pelabuhan Internal untuk Dropdown

```javascript
async function loadPelabuhanInternal() {
    const response = await fetch('/api/pelabuhan/internal');
    const pelabuhans = await response.json();
    
    const select = document.getElementById('pelabuhan_id');
    pelabuhans.forEach(p => {
        const option = new Option(p.label, p.id);
        select.add(option);
    });
}

// Call on page load
loadPelabuhanInternal();
```

### Example 3: Autocomplete dengan Debounce

```javascript
let debounceTimer;

function searchKapal(query) {
    clearTimeout(debounceTimer);
    
    debounceTimer = setTimeout(async () => {
        if (query.length < 2) return;
        
        const response = await fetch(`/api/kapal/search?q=${encodeURIComponent(query)}`);
        const results = await response.json();
        
        displayResults(results);
    }, 300); // Wait 300ms after user stops typing
}
```

---

## 🧪 Testing API

### Using cURL

```bash
# Login first (get session cookie)
curl -X POST http://localhost:8000/login \
  -d "email=admin@uppjampea.id&password=password" \
  -c cookies.txt

# Call API with session cookie
curl http://localhost:8000/api/kapal/search?q=sabuk \
  -b cookies.txt \
  -H "Accept: application/json"
```

### Using Postman

1. **Login**:
   - Method: POST
   - URL: `http://localhost:8000/login`
   - Body (x-www-form-urlencoded):
     - email: admin@uppjampea.id
     - password: password

2. **Call API**:
   - Method: GET
   - URL: `http://localhost:8000/api/kapal/search?q=sabuk`
   - Headers:
     - Accept: application/json
   - Cookies: Set automatically after login

### Using Browser DevTools

```javascript
// di console browser (setelah login)
fetch('/api/kapal/search?q=sabuk')
    .then(r => r.json())
    .then(data => console.table(data));
```

---

## 📈 Future API Endpoints (Planned)

### Export API

```
GET /api/laporan/pelra/export?bulan=3&tahun=2026&format=excel
GET /api/laporan/dn/export?bulan=3&tahun=2026&format=pdf
```

### Statistics API

```
GET /api/dashboard/stats?periode=bulanan&tahun=2026
GET /api/dashboard/top-kapal?limit=10
GET /api/dashboard/trend?periode=yearly
```

### Advanced Search API

```
GET /api/kunjungan/search?kapal_id=1&pelabuhan_id=2&start_date=2026-01-01&end_date=2026-03-31
```

---

## 📞 Support

Jika ada pertanyaan atau menemukan bug terkait API, hubungi:
- Tim IT UPP Jampea
- Email: support@uppjampea.id

---

## ✅ API Checklist

- [x] Kapal autocomplete search
- [x] Pelabuhan autocomplete search
- [x] Pelabuhan internal dropdown
- [x] Session-based authentication
- [x] CSRF protection
- [ ] Rate limiting (production)
- [ ] API versioning (v2)
- [ ] Export endpoints
- [ ] WebSocket notifications

---

**API Documentation v1.0 - Last Updated: Maret 2026**
