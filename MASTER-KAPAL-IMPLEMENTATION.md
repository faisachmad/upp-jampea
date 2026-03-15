# Master Kapal - Implementation Summary

## Changes Implemented

### 1. Database Structure

#### New Tables Created:
1. **jenis_kapals** - Master data for ship types
   - id (primary key)
   - kode (unique, max 10 chars)
   - nama (max 100 chars)
   - keterangan (text, nullable)
   - is_active (boolean, default true)
   - timestamps

2. **benderas** - Master data for country flags
   - id (primary key)
   - kode (unique, ISO 3166-1 alpha-3, max 3 chars)
   - nama_negara (max 100 chars)
   - is_active (boolean, default true)
   - timestamps

#### Updated Tables:
3. **kapals** - Modified to use foreign keys
   - Removed: `jenis` (enum) column
   - Removed: `bendera` (string) column
   - Added: `jenis_kapal_id` (foreign key to jenis_kapals)
   - Added: `bendera_id` (foreign key to benderas)

### 2. Models Created/Updated

#### New Models:
- `App\Models\JenisKapal` - with relationship to Kapal
- `App\Models\Bendera` - with relationship to Kapal

#### Updated Models:
- `App\Models\Kapal` - added relationships to JenisKapal and Bendera

### 3. Controller Updates

**KapalController** enhancements:
- Server-side pagination with configurable per-page (10, 15, 25, 50, 100)
- Eager loading of relationships (jenisKapal, bendera)
- New method: `storeJenisKapal()` - AJAX endpoint for adding new ship types
- New method: `storeBendera()` - AJAX endpoint for adding new flags
- Updated validation rules for new structure
- Filter by jenis_kapal_id instead of enum

### 4. Routes Added

```php
Route::post('master/kapal/store-jenis-kapal', [KapalController::class, 'storeJenisKapal'])
    ->name('master.kapal.store-jenis-kapal');
Route::post('master/kapal/store-bendera', [KapalController::class, 'storeBendera'])
    ->name('master.kapal.store-bendera');
```

### 5. View Features

**master.kapal.index** now includes:
- ✅ Server-side pagination with configurable items per page (dropdown)
- ✅ Per-page selector (10, 15, 25, 50, 100)
- ✅ Data count display (showing X to Y of Z records)
- ✅ Filter by Jenis Kapal (from master table)
- ✅ Modal for adding new Jenis Kapal
- ✅ Modal for adding new Bendera
- ✅ Dropdown select for Jenis Kapal with "+ Tambah Baru" button
- ✅ Dropdown select for Bendera with "+ Tambah Baru" button
- ✅ AJAX form submission for modals (no page reload)
- ✅ Dynamic dropdown update after adding new items
- ✅ Display Bendera column in table
- ✅ Updated Edit modal with new fields

### 6. Seeders Created

1. **BenderaSeeder** - Seeds 195 countries with ISO codes
2. **JenisKapalSeeder** - Seeds 4 default ship types:
   - KLM - Kapal Layar Motor
   - KM - Kapal Motor
   - KMP - Kapal Motor Penyeberangan
   - MV - Motor Vessel

## ⚠️ IMPORTANT: Database Migration Required

**CRITICAL NOTICE:** This is a production database. Do NOT run migrations automatically.

### Migration Files Created:
1. `2026_03_15_034539_create_jenis_kapals_table.php`
2. `2026_03_15_034546_create_benderas_table.php`
3. `2026_03_15_034649_add_jenis_kapal_bendera_relationships_to_kapals_table.php`

### Steps to Apply Changes (ON PRODUCTION):

#### Option 1: Manual Migration (Recommended for Production)
1. **BACKUP DATABASE FIRST!**
2. Review all migration files
3. Plan downtime window
4. Run migrations in order:
   ```bash
   php artisan migrate --path=database/migrations/2026_03_15_034539_create_jenis_kapals_table.php
   php artisan migrate --path=database/migrations/2026_03_15_034546_create_benderas_table.php
   php artisan migrate --path=database/migrations/2026_03_15_034649_add_jenis_kapal_bendera_relationships_to_kapals_table.php
   ```
5. Run seeders:
   ```bash
   php artisan db:seed --class=BenderaSeeder
   php artisan db:seed --class=JenisKapalSeeder
   ```

#### Option 2: Test on Development First (Highly Recommended)
1. Create a development/staging database
2. Copy production data to development
3. Test migrations and application functionality
4. Once verified, apply to production

### Data Migration Considerations

**IMPORTANT:** Existing kapal records have `jenis` as enum and `bendera` as string. After migration:
- Old `jenis` column will be removed
- Old `bendera` column will be removed
- Need to map existing data to new foreign keys

**Recommended data migration script:**
```sql
-- After running migrations and seeders

-- Map existing jenis to jenis_kapal_id
UPDATE kapals k
SET jenis_kapal_id = (
    SELECT id FROM jenis_kapals jk
    WHERE jk.kode = k.jenis
)
WHERE k.jenis IS NOT NULL;

-- Map existing bendera to bendera_id (if possible)
-- This is more complex as old bendera is free text
-- May need manual mapping or leave NULL
```

## Usage Guide

### Adding Jenis Kapal from Modal:
1. Click "Tambah Kapal" button
2. In the Jenis Kapal field, click "+ Tambah Baru"
3. Fill in the modal:
   - Kode (e.g., "KLM")
   - Nama (e.g., "Kapal Layar Motor")
   - Keterangan (optional)
4. Click "Simpan"
5. New option appears automatically in dropdown

### Adding Bendera from Modal:
1. Click "Tambah Kapal" button
2. In the Bendera field, click "+ Tambah Baru"
3. Fill in the modal:
   - Kode (3-letter ISO code, e.g., "IDN")
   - Nama Negara (e.g., "Indonesia")
4. Click "Simpan"
5. New option appears automatically in dropdown

### Configuring Items Per Page:
1. Use the "Per Halaman" dropdown in the filter section
2. Select: 10, 15, 25, 50, or 100
3. Click "Filter" to apply
4. Selection is maintained across filter operations

## Files Modified/Created

### Created:
- `app/Models/JenisKapal.php`
- `app/Models/Bendera.php`
- `database/migrations/2026_03_15_034539_create_jenis_kapals_table.php`
- `database/migrations/2026_03_15_034546_create_benderas_table.php`
- `database/migrations/2026_03_15_034649_add_jenis_kapal_bendera_relationships_to_kapals_table.php`
- `database/seeders/JenisKapalSeeder.php`
- `database/seeders/BenderaSeeder.php`
- `resources/views/master/kapal/index-old.blade.php` (backup)

### Modified:
- `app/Models/Kapal.php`
- `app/Http/Controllers/Master/KapalController.php`
- `routes/web.php`
- `resources/views/master/kapal/index.blade.php`
- `database/seeders/DatabaseSeeder.php`

## Testing Checklist

Before deploying to production, test:
- [ ] List kapal displays correctly with pagination
- [ ] Per-page selector works (10, 15, 25, 50, 100)
- [ ] Search functionality works
- [ ] Filter by jenis kapal works
- [ ] Filter by status works
- [ ] Add new kapal with jenis kapal selection
- [ ] Add new kapal with bendera selection
- [ ] Add jenis kapal from modal (AJAX works)
- [ ] Add bendera from modal (AJAX works)
- [ ] Edit kapal with new fields
- [ ] Delete kapal
- [ ] Dropdowns update after adding new items
- [ ] Page count and navigation work correctly

## API Endpoints

### POST /master/kapal/store-jenis-kapal
**Purpose:** Add new ship type via AJAX
**Request:**
```json
{
    "kode": "KLM",
    "nama": "Kapal Layar Motor",
    "keterangan": "Description..."
}
```
**Response:**
```json
{
    "success": true,
    "message": "Jenis kapal berhasil ditambahkan.",
    "data": {
        "id": 5,
        "kode": "KLM",
        "nama": "Kapal Layar Motor",
        ...
    }
}
```

### POST /master/kapal/store-bendera
**Purpose:** Add new country flag via AJAX
**Request:**
```json
{
    "kode": "IDN",
    "nama_negara": "Indonesia"
}
```
**Response:**
```json
{
    "success": true,
    "message": "Bendera berhasil ditambahkan.",
    "data": {
        "id": 196,
        "kode": "IDN",
        "nama_negara": "Indonesia",
        ...
    }
}
```

## Notes

- All 195 countries are pre-seeded with ISO 3166-1 alpha-3 codes
- Default 4 ship types are pre-seeded
- Modals use Alpine.js for reactivity
- AJAX submissions use Fetch API
- Pagination preserves query strings (filters remain after page change)
- Old index view backed up to `index-old.blade.php`

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Alpine.js Documentation: https://alpinejs.dev
- Tailwind CSS Documentation: https://tailwindcss.com
