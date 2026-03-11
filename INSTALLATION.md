# Panduan Instalasi SILAPOR UPP Jampea

Dokumen ini berisi panduan instalasi lengkap untuk aplikasi SILAPOR UPP Jampea.

---

## 📋 Daftar Isi

- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi Development](#instalasi-development)
- [Instalasi Production](#instalasi-production)
- [Konfigurasi Database](#konfigurasi-database)
- [Troubleshooting](#troubleshooting)

---

## 🔧 Persyaratan Sistem

### Minimum Requirements

| Software | Versi Minimum | Versi Rekomendasi |
|----------|---------------|-------------------|
| PHP | 8.2 | 8.3 |
| PostgreSQL | 14 | 16 |
| Composer | 2.0 | 2.7 |
| Node.js | 18.x | 20.x |
| NPM | 9.x | 10.x |

### Ekstensi PHP yang Diperlukan

```bash
# Cek ekstensi yang terinstall
php -m

# Ekstensi yang harus ada:
- pdo_pgsql
- pgsql
- mbstring
- openssl
- tokenizer
- xml
- ctype
- json
- bcmath
- fileinfo
- curl
```

### Tools Tambahan (Opsional)

- **Git** untuk version control
- **PostgreSQL Admin Tool** (pgAdmin, DBeaver) untuk mengelola database
- **Postman** untuk testing API

---

## 💻 Instalasi Development

### 1. Clone Repository

```bash
# Clone dari Git
git clone <repository-url> silapor-upp-jampea
cd silapor-upp-jampea

# Atau download ZIP dan extract
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

**Catatan**: Jika mendapat error saat `composer install`, coba:
```bash
composer install --ignore-platform-reqs
```

### 3. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Environment

Edit file `.env` dengan konfigurasi yang sesuai:

```env
APP_NAME="SILAPOR UPP Jampea"
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Makassar
APP_URL=http://localhost:8000
APP_LOCALE=id

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=silapor_upp_jampea
DB_USERNAME=postgres
DB_PASSWORD=your_password

# PENTING: Gunakan file driver, BUKAN database
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# CSRF & Cookie Configuration
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_COOKIE=silapor_session
```

**⚠️ PERINGATAN PENTING**:
```env
# JANGAN gunakan database driver untuk:
SESSION_DRIVER=file     # BUKAN database
CACHE_STORE=file        # BUKAN database
QUEUE_CONNECTION=sync   # BUKAN database
```

### 5. Setup Database

#### A. Buat Database PostgreSQL

**Menggunakan psql**:
```bash
# Login ke PostgreSQL
psql -U postgres

# Buat database
CREATE DATABASE silapor_upp_jampea;

# Buat user (opsional)
CREATE USER silapor_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE silapor_upp_jampea TO silapor_user;

# Keluar
\q
```

**Menggunakan pgAdmin**:
1. Klik kanan pada **PostgreSQL 16** > **Create** > **Database**
2. Name: `silapor_upp_jampea`
3. Owner: `postgres` (atau user yang dibuat)
4. Klik **Save**

#### B. Jalankan Migrasi

```bash
# Jalankan semua migrations
php artisan migrate

# Jika ingin fresh start
php artisan migrate:fresh
```

#### C. Seed Data Sample

```bash
# Seed semua data master
php artisan db:seed

# Atau seed spesifik
php artisan db:seed --class=PelabuhanSeeder
php artisan db:seed --class=JenisPelayaranSeeder
php artisan db:seed --class=KapalSeeder
php artisan db:seed --class=BarangB3Seeder
```

**Data yang akan di-seed**:
- ✅ 6 Pelabuhan internal UPP Jampea
- ✅ 6 Jenis pelayaran (PELRA, DN, LN, Perintis, Ferry ASDP, Ferry DJPD)
- ✅ 10 Sample kapal
- ✅ 20 Jenis barang B3 dengan UN Number
- ✅ 1 User admin (email: admin@uppjampea.id, password: password)

### 6. Build Assets

```bash
# Development mode (hot reload)
npm run dev

# Atau di terminal terpisah untuk watch mode
npm run dev &

# Production build (untuk deployment)
npm run build
```

### 7. Jalankan Development Server

```bash
# Default port 8000
php artisan serve

# Custom port
php artisan serve --port=8181

# Accessible dari jaringan
php artisan serve --host=0.0.0.0 --port=8000
```

### 8. Akses Aplikasi

Buka browser dan akses:
```
http://localhost:8000
```

**Login dengan**:
- Email: `admin@uppjampea.id`
- Password: `password`

---

## 🚀 Instalasi Production

### 1. Server Requirements

- **Web Server**: Nginx atau Apache (dengan mod_rewrite)
- **PHP**: 8.3 dengan PHP-FPM
- **Database**: PostgreSQL 16
- **SSL Certificate** (recommended)

### 2. Setup di Server

```bash
# 1. Clone repository ke server
cd /var/www
git clone <repository-url> silapor-upp-jampea
cd silapor-upp-jampea

# 2. Install dependencies (production only)
composer install --optimize-autoloader --no-dev
npm install --production

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env untuk production
nano .env
```

### 3. Production Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://silapor.uppjampea.id

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=silapor_production
DB_USERNAME=silapor_prod
DB_PASSWORD=super_secure_password_here

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Security Settings
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### 4. Database Setup

```bash
# Buat database production
sudo -u postgres psql
CREATE DATABASE silapor_production;
CREATE USER silapor_prod WITH PASSWORD 'super_secure_password';
GRANT ALL PRIVILEGES ON DATABASE silapor_production TO silapor_prod;
\q

# Jalankan migrasi
php artisan migrate --force

# Seed data (hanya data master, bukan sample)
php artisan db:seed --force
```

### 5. Build Assets Production

```bash
npm run build
```

### 6. Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/silapor-upp-jampea

# Set directory permissions
sudo find /var/www/silapor-upp-jampea -type d -exec chmod 755 {} \;
sudo find /var/www/silapor-upp-jampea -type f -exec chmod 644 {} \;

# Set storage and cache writable
sudo chmod -R 775 /var/www/silapor-upp-jampea/storage
sudo chmod -R 775 /var/www/silapor-upp-jampea/bootstrap/cache
```

### 7. Configure Nginx

```nginx
server {
    listen 80;
    server_name silapor.uppjampea.id;
    root /var/www/silapor-upp-jampea/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Restart Nginx:
```bash
sudo nginx -t
sudo systemctl restart nginx
```

### 8. Setup SSL (Let's Encrypt)

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Generate SSL certificate
sudo certbot --nginx -d silapor.uppjampea.id

# Auto-renewal
sudo certbot renew --dry-run
```

### 9. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 10. Setup Cron Jobs (Opsional)

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini
* * * * * cd /var/www/silapor-upp-jampea && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🗄️ Konfigurasi Database

### PostgreSQL Connection String

```env
# Format lengkap
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=silapor_upp_jampea
DB_USERNAME=postgres
DB_PASSWORD=your_password
DB_SCHEMA=public
DB_SSLMODE=prefer
```

### Test Koneksi Database

```bash
# Via artisan
php artisan tinker
>>> DB::connection()->getPdo();

# Via psql
psql -h 127.0.0.1 -U postgres -d silapor_upp_jampea -c "SELECT version();"
```

### Backup Database

```bash
# Backup manual
pg_dump -U postgres -d silapor_upp_jampea -F c -f backup_silapor_$(date +%Y%m%d).dump

# Restore dari backup
pg_restore -U postgres -d silapor_upp_jampea -c backup_silapor_20260312.dump
```

### Setup Automated Backup (Production)

```bash
# Buat script backup
sudo nano /usr/local/bin/backup-silapor.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/silapor"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# Database backup
pg_dump -U silapor_prod silapor_production -F c -f $BACKUP_DIR/db_$DATE.dump

# Files backup (storage)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/silapor-upp-jampea/storage

# Hapus backup lebih dari 30 hari
find $BACKUP_DIR -name "*.dump" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

```bash
# Set executable
sudo chmod +x /usr/local/bin/backup-silapor.sh

# Tambah ke crontab (setiap hari jam 2 pagi)
0 2 * * * /usr/local/bin/backup-silapor.sh
```

---

## 🔍 Troubleshooting

### 1. Error: "could not find driver" (PDO PostgreSQL)

**Masalah**: PHP tidak memiliki ekstensi PostgreSQL.

**Solusi**:
```bash
# Ubuntu/Debian
sudo apt install php8.3-pgsql
sudo systemctl restart php8.3-fpm

# macOS (Homebrew)
brew install php@8.3
brew services restart php@8.3

# Windows (XAMPP)
# Edit php.ini, uncomment:
extension=pdo_pgsql
extension=pgsql
```

### 2. Error: "SQLSTATE[08006] Connection refused"

**Masalah**: PostgreSQL tidak running atau konfigurasi salah.

**Solusi**:
```bash
# Cek status PostgreSQL
sudo systemctl status postgresql

# Start PostgreSQL
sudo systemctl start postgresql

# Cek port PostgreSQL
sudo netstat -plnt | grep 5432

# Pastikan PostgreSQL listen di 127.0.0.1
sudo nano /etc/postgresql/16/main/postgresql.conf
# Cari: listen_addresses = '*' atau '127.0.0.1'
```

### 3. Error: "Permission denied" saat artisan migrate

**Masalah**: User PostgreSQL tidak punya akses.

**Solusi**:
```bash
sudo -u postgres psql
GRANT ALL PRIVILEGES ON DATABASE silapor_upp_jampea TO postgres;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;
\q
```

### 4. Error: "Mix manifest not found"

**Masalah**: Assets belum di-build.

**Solusi**:
```bash
# Hapus cache
rm -rf public/build
rm -rf node_modules

# Install ulang dan build
npm install
npm run build
```

### 5. Error: "419 Page Expired" setelah login

**Masalah**: Session tidak tersimpan.

**Solusi**:
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan session:table  # Jika pakai session database

# Pastikan di .env:
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Pastikan folder writable
chmod -R 775 storage/framework/sessions
```

### 6. Page Blank setelah login

**Masalah**: JavaScript error atau asset tidak load.

**Solusi**:
```bash
# Rebuild assets
npm run build

# Clear browser cache
# Buka DevTools (F12) > Application > Clear storage

# Cek console error di browser
# F12 > Console
```

### 7. Error: "Undefined variable $slot"

**Masalah**: Layout menggunakan component syntax tapi view menggunakan extends.

**Solusi**: Sudah diperbaiki di `resources/views/layouts/app.blade.php`. Pastikan menggunakan `@yield('content')` bukan `{{ $slot }}`.

### 8. Autocomplete tidak berfungsi

**Masalah**: Route API tidak terdaftar atau JavaScript error.

**Solusi**:
```bash
# Cek routes API
php artisan route:list --name=api

# Clear route cache
php artisan route:clear

# Cek console browser untuk error AJAX
```

### 9. Production: 500 Error setelah deploy

**Solusi**:
```bash
# Pastikan permissions benar
sudo chown -R www-data:www-data /var/www/silapor-upp-jampea
sudo chmod -R 775 storage bootstrap/cache

# Clear dan rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cek log
tail -f storage/logs/laravel.log
```

### 10. Slow Performance

**Solusi**:
```bash
# Enable OPcache (production)
# Edit php.ini:
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize

# Database indexing (sudah ada di migrasi)
```

---

## 📞 Support

Jika mengalami masalah yang tidak tercakup di sini, hubungi:
- **Tim IT UPP Jampea**
- Email: support@uppjampea.id (jika tersedia)

---

## ✅ Checklist Instalasi

- [ ] PHP 8.3 terinstall dengan semua ekstensi
- [ ] PostgreSQL 16 running
- [ ] Composer terinstall
- [ ] Node.js dan NPM terinstall
- [ ] Repository di-clone
- [ ] `composer install` sukses
- [ ] `npm install` sukses
- [ ] File `.env` sudah dikonfigurasi
- [ ] Database dibuat
- [ ] `php artisan migrate` sukses
- [ ] `php artisan db:seed` sukses
- [ ] `npm run build` sukses
- [ ] `php artisan serve` running
- [ ] Bisa login dengan admin@uppjampea.id
- [ ] Master data terlihat di menu
- [ ] Form kunjungan bisa diakses
- [ ] Autocomplete berfungsi

---

**Happy Deploying! 🚀**
