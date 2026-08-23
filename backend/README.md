# ⚙️ E-Tourism Backend & Admin Panel (Laravel + Filament)

Backend REST API dan Admin Dashboard untuk sistem **E-Tourism Platform (Gerbangkartasusila & Kediri Tourism)**.

---

## 🛠 Teknologi Utama
* **Framework**: Laravel 10 (PHP ^8.1)
* **Admin Dashboard**: Filament v3.2
* **Hak Akses & Role**: Spatie Laravel Permission & Filament Shield
* **Autentikasi API**: Laravel Sanctum
* **Database**: MySQL 8.0

---

## 🚀 Panduan Menjalankan Backend

1. **Install Dependencies:**
   ```bash
   composer install
   ```

2. **Setup Environment:**
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database di file `.env`.

3. **Generate Key & Database Migration:**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

4. **Storage Link:**
   ```bash
   php artisan storage:link
   ```

5. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```
   * **API URL**: `http://127.0.0.1:8000/api`
   * **Admin Panel**: `http://127.0.0.1:8000/admin`

---

## 🔑 Akun Default

* **Super Admin**: `admin@example.com` / `password`
* **Demo User**: `rkriswibowo@gmail.com` / `password`
