# E-Tourism Gerbangkartasusila

Aplikasi wisata dengan frontend Flutter, REST API Laravel 12, dan panel admin Filament 3.3.

## Kebutuhan

- PHP 8.3+, Composer 2, MySQL/MariaDB.
- Ekstensi PHP: bcmath, curl, fileinfo, gd, intl, mbstring, pdo_mysql, pdo_sqlite, zip.
- Flutter stable (minimal 3.27; gunakan versi yang sama dengan pengujian/CI).

## Instalasi lokal dari clone

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Buat database kosong `e_tourism`, lalu sesuaikan `DB_*` pada `.env`.

```bash
php artisan migrate
php artisan db:seed
php artisan app:create-admin
php artisan storage:link
php artisan app:check-readiness
php artisan serve --host=127.0.0.1 --port=8000
```

`db:seed` hanya untuk database demo baru. Jangan ulangi pada database berisi data.
Seeder tidak menyediakan password akun bawaan; buat admin sendiri lewat prompt.
Untuk database lama, gunakan `migrate`, bukan `migrate:fresh`.

Di Windows, `scripts/start-backend.ps1` dapat memakai PHP portabel lokal bila tersedia.
PHP portabel bukan bagian repository; pengguna clone lain harus menyediakan PHP 8.3+.
XAMPP dengan PHP 8.1 tidak dapat menjalankan backend yang sudah diperbarui.

Buka terminal lain:

```bash
cd mobile
flutter pub get
flutter run -d chrome --web-port=5173 --dart-define=API_BASE_URL=http://localhost:8000
```

Daftarkan akun wisatawan melalui aplikasi. Admin berada di `http://localhost:8000/admin`.
`API_BASE_URL` tidak menyertakan `/api`. Emulator Android menggunakan `10.0.2.2`
untuk backend di host. Perangkat fisik harus menggunakan alamat jaringan yang dapat
menjangkau backend. Build produksi harus memakai HTTPS.

## Perilaku aplikasi

- Katalog destinasi, event, hotel, kuliner, artikel, serta peta.
- Registrasi, login, profil sesuai akun dan logout dengan token Sanctum.
- Pemesanan tiket dihitung oleh server dan hanya dapat dibaca pemiliknya.
- Pembayaran saat ini **bayar di lokasi**. Tiket baru berstatus `pending`; admin
  mengonfirmasi pembayaran melalui menu Tickets setelah uang diterima.
- Kode pemesanan dapat disalin. Belum ada gateway pembayaran, QR scan, atau unduh
  tiket ke galeri. Jangan menganggap kode pemesanan sebagai bukti pembayaran.
- Sesi frontend disimpan dalam memori; pengguna login ulang setelah aplikasi ditutup
  atau browser di-refresh. Token tidak ditulis ke localStorage.
- Peta dan sebagian gambar membutuhkan koneksi ke layanan eksternal.

## Pengujian

```bash
cd backend
composer validate --strict
composer audit --locked
php artisan test
php artisan app:check-readiness
```

Tes backend menggunakan SQLite `:memory:` yang terisolasi dari database lokal.

```bash
cd mobile
flutter analyze
flutter test
flutter build web --release --dart-define=API_BASE_URL=https://api.example.com
```

Build dengan domain contoh hanya memverifikasi kompilasi. Build ulang menggunakan
alamat backend nyata sebelum deployment. GitHub Actions menjalankan pemeriksaan
backend dan Flutter pada push/PR; hasil CI harus diperiksa setelah diunggah.

## GitHub dan Debian

- Commit source, migrasi, tes, `composer.lock`, dan `pubspec.lock`.
- Jangan commit `.env`, dump database, backup, kredensial, signing key, atau `.local-tools`.
- Gambar unggahan di `backend/storage/app/public` dipindahkan melalui backup privat,
  terpisah dari Git. Gambar demo tersedia di `backend/public/images`.
- Skrip `init_flutter.php`, `make_*.php`, `upgrade_*.php`, dan skrip setup lama adalah
  generator historis, bukan langkah instalasi. Menjalankannya dapat menimpa kode.
- Jangan gunakan root repository sebagai document root server.

Lihat [panduan deployment Debian](docs/DEPLOYMENT_DEBIAN.md) dan
[contoh konfigurasi Nginx](deploy/nginx.conf.example).