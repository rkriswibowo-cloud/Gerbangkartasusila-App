# 📱 E-Tourism Mobile App (Flutter)

Aplikasi mobile pariwisata untuk wisatawan mengeksplorasi destinasi, event, kuliner, hotel, artikel wisata, dan booking e-ticket di kawasan **Gerbangkartasusila & Kediri**.

---

## 🛠 Teknologi & Dependensi
* **Flutter SDK**: `>=3.0.0 <4.0.0`
* **State Management**: Provider
* **Networking**: Dio (REST API Client)
* **Maps**: Flutter Map & LatLong2 (OpenStreetMap)
* **Animation & UI**: Flutter Animate, Google Fonts (Poppins), Cached Network Image

---

## 🚀 Panduan Menjalankan Mobile App

1. **Install Dependencies:**
   ```bash
   flutter pub get
   ```

2. **Konfigurasi Host API:**
   Buka `lib/services/api_service.dart` untuk menyesuaikan host backend jika menggunakan perangkat fisik / emulator.

3. **Jalankan Aplikasi:**
   ```bash
   # Jalankan di Web (Chrome)
   flutter run -d chrome

   # Jalankan di Android Emulator / Device
   flutter run
   ```
