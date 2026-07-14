# Deployment ke Vercel + Clever Cloud

## 1. Siapkan database di Clever Cloud
- Buat add-on database MySQL/PostgreSQL.
- Salin detail koneksi dari Clever Cloud.
- Pastikan aplikasi Laravel dapat mengakses host, port, username, password, dan nama database.

## 2. Siapkan project di Vercel
- Import repository GitHub ke Vercel.
- Pilih framework: Other / Laravel.
- Set environment variables berikut di Vercel:
  - APP_NAME=NamaAplikasi
  - APP_ENV=production
  - APP_KEY=generate_ulang_di_server
  - APP_DEBUG=false
  - APP_URL=https://nama-project.vercel.app
  - DB_CONNECTION=pgsql atau mysql
  - DB_HOST=...
  - DB_PORT=5432 atau 3306
  - DB_DATABASE=...
  - DB_USERNAME=...
  - DB_PASSWORD=...
  - DB_SSLMODE=require (jika PostgreSQL di Clever Cloud)
  - SESSION_DRIVER=cookie
  - CACHE_DRIVER=array
  - QUEUE_CONNECTION=sync

## 3. Jalankan migrasi
Setelah deploy pertama, jalankan migrasi dari terminal lokal atau Vercel CLI:

```bash
php artisan migrate --force
```

## 4. Jika perlu seed data
```bash
php artisan db:seed --force
```
