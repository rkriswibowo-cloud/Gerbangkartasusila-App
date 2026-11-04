# Deployment Debian

Panduan ini menggunakan dua host: `wisata.example.com` untuk Flutter Web dan
`api.example.com` untuk Laravel serta `/admin`. Ganti seluruh domain contoh.
Versi Debian, domain nyata, SSH, dan TLS server belum diverifikasi dari komputer lokal.

## Persiapan

- Gunakan PHP yang memenuhi `backend/composer.json`, Composer 2, MariaDB/MySQL,
  Nginx, dan ekstensi PHP: bcmath, curl, dom/xml, fileinfo, gd, intl, mbstring,
  mysql, sqlite3 (tes), zip, serta opcache.
- Periksa `php -v` dan `composer check-platform-reqs`. PHP CLI dan PHP-FPM harus
  menggunakan versi yang kompatibel. Jangan memakai `--ignore-platform-reqs`.
- Clone repository ke `/var/www/e-tourism` sebagai pengguna deployment biasa.
  Jangan membuka root repository sebagai document root web server.
- Buat database `e_tourism` dengan charset `utf8mb4` dan pengguna database khusus
  yang hanya memiliki akses ke database tersebut.

## Backend

```bash
cd /var/www/e-tourism/backend
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
```

Isi `.env` langsung di server, bukan di GitHub:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.example.com
CORS_ALLOWED_ORIGINS=https://wisata.example.com
LOG_LEVEL=warning
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_tourism
DB_USERNAME=e_tourism
DB_PASSWORD=ISI_PASSWORD_UNIK_DI_SERVER
SESSION_SECURE_COOKIE=true
```

Untuk instalasi baru, buat key sekali:

```bash
php artisan key:generate
php artisan migrate --force
php artisan app:create-admin
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan app:check-readiness
```

Jangan menjalankan `migrate:fresh` pada database berisi data. Jangan menjalankan
demo seeder di production. Admin dibuat lewat prompt interaktif agar password
tidak tersimpan di source atau riwayat perintah. Data wisata dikelola melalui
panel admin. Backup lama dapat diimpor secara terpisah; jangan commit dump SQL.

Untuk memindahkan data lokal, ekspor database menggunakan `mysqldump`/`mariadb-dump`
dengan prompt password, salin `backend/storage/app/public` melalui saluran privat,
dan pertahankan `APP_KEY` lama bila memindahkan data terenkripsi. Uji restore pada
database sementara sebelum mengalihkan trafik. Jangan menyertakan token sesi lama
dalam database demo publik.

Berikan PHP-FPM akses tulis hanya pada `storage` dan `bootstrap/cache`. Contoh
ketika PHP-FPM berjalan sebagai `www-data`:

```bash
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
```

Source dan `.env` tidak boleh writable untuk semua pengguna. Sesuaikan ownership
agar pengguna deployment dan PHP-FPM dapat membaca konfigurasi yang diperlukan.

## Nginx dan TLS

Salin `deploy/nginx.conf.example` ke konfigurasi site, sesuaikan domain dan socket
PHP-FPM (periksa `/run/php/`), lalu jalankan `sudo nginx -t` sebelum reload.
Konfigurasi contoh hanya bootstrap HTTP. Pasang sertifikat TLS yang valid untuk
kedua host dan redirect HTTP ke HTTPS sebelum menerima login atau data pengguna.
Gunakan paket Certbot sesuai versi Debian yang terpasang. Port database tidak
perlu dibuka ke internet.

## Flutter Web

Build di komputer development atau CI dengan Flutter versi yang lolos pengujian:

```bash
cd mobile
flutter pub get
flutter analyze
flutter test
flutter build web --release --dart-define=API_BASE_URL=https://api.example.com
```

`API_BASE_URL` adalah origin backend **tanpa `/api`**. Salin seluruh isi `build/web`
ke `/var/www/e-tourism-web`. Jangan menjalankan `flutter run` sebagai server produksi.
Untuk Android, gunakan origin HTTPS yang sama saat build APK; signing rilis dan
pengujian perangkat harus dilakukan sebelum distribusi. `localhost` di ponsel
bukan alamat komputer backend.

## Verifikasi setelah deployment

1. `composer audit --locked --no-dev` harus bersih dan platform requirements lulus.
2. `php artisan migrate:status` tidak menunjukkan migrasi pending.
3. GET `/api/destinations` menghasilkan 200; GET `/api/tickets` tanpa token 401.
4. Login admin, tambah destinasi dan unggah gambar, lalu lihat hasilnya di frontend.
5. Daftarkan dua akun berbeda. Pesan tiket pada akun pertama; akun kedua tidak
   boleh melihatnya. Harga dihitung server, status awal `pending`.
6. Konfirmasi pembayaran nyata melalui menu Tickets admin; refresh Tiket Saya.
7. Uji CORS dari domain frontend, logout, gambar, peta, dan layout ponsel.
8. Pastikan `.env`, `.git`, vendor, dan skrip PHP generator tidak dapat diakses lewat HTTP.

Saat update, backup database dan media, gunakan `composer install` berdasarkan
lockfile, jalankan migrasi yang diperlukan dan bangun ulang cache. Jangan membuat
ulang `APP_KEY`. Simpan rilis sebelumnya dan backup untuk rollback; jangan otomatis
rollback migrasi berisi data tanpa memeriksa dampaknya.

Referensi: [Laravel deployment](https://laravel.com/docs/12.x/deployment),
[Flutter Web deployment](https://docs.flutter.dev/deployment/web).
