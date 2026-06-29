# Web Service API (Laravel 11)

Repositori ini adalah hasil akhir dari Praktikum Web Service, yang berisi implementasi RESTful API lengkap menggunakan Framework Laravel 11. 

Fitur yang tersedia dalam API ini meliputi:
1. **Autentikasi JWT (JSON Web Token)** untuk keamanan rute.
2. **Sistem Logging Otomatis** menggunakan Middleware untuk mencatat semua *request* (termasuk menyensor *password*).
3. **CRUD Lengkap** untuk mengelola entitas `Province`, `City`, dan `District`.

---

## 🛠️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan *project* ini, pastikan laptop Anda sudah terinstal:
- **PHP** (Minimal versi 8.2)
- **Composer** (Package Manager untuk PHP)
- **XAMPP / MySQL** (Untuk Database)
- **Git**

---

## 🚀 Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah di bawah ini untuk menjalankan *project* ini di laptop Anda secara lokal.

### 1. Kloning Repositori
Buka terminal (Git Bash / CMD / Terminal) dan jalankan:
```bash
git clone <URL_GITHUB_REPO_INI>
cd webservice-api
```
*(Jangan lupa ganti `<URL_GITHUB_REPO_INI>` dengan URL asli repositori Github ini).*

### 2. Install Dependensi (Vendor)
Jalankan perintah Composer untuk mengunduh semua *library* pendukung:
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Laravel membutuhkan file konfigurasi rahasia bernama `.env`.
Duplikat file `.env.example` dan ubah namanya menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows, Anda juga bisa melakukan *Copy-Paste* file `.env.example` secara manual melalui File Explorer lalu me-rename hasilnya menjadi `.env`).*

Buka file `.env` di teks editor (seperti VSCode), dan pastikan konfigurasi databasenya sesuai dengan komputer Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webservice      # Ganti sesuai nama database Anda
DB_USERNAME=root
DB_PASSWORD=                # Kosongkan jika XAMPP default
```
**PENTING:** Pastikan juga `SESSION_DRIVER` dan `CACHE_STORE` diatur ke `file`, bukan `database`:
```env
SESSION_DRIVER=file
CACHE_STORE=file
```

### 4. Setup Database
1. Buka **XAMPP Control Panel**, nyalakan modul **MySQL** dan **Apache**.
2. Buka Browser, akses `http://localhost/phpmyadmin/`.
3. Buat database baru bernama `webservice` (sesuai `DB_DATABASE` di `.env`).
4. **Import** file database bawaan praktikum (`.sql`) yang diberikan oleh dosen/asisten praktikum Anda ke dalam database tersebut.

### 5. Generate Application Key & JWT Secret Key
Jalankan dua perintah berikut secara bergantian di terminal Anda untuk menghasilkan kunci enkripsi keamanan:
```bash
php artisan key:generate
php artisan jwt:secret
```

### 6. Jalankan Server Lokal
Setelah semuanya siap, hidupkan server lokal Laravel:
```bash
php artisan serve
```
Aplikasi API Anda sekarang sudah berjalan di `http://127.0.0.1:8000`.

---

## 📖 Dokumentasi API & Cara Testing Menggunakan Postman

Untuk melakukan pengetesan seluruh *endpoint* (Modul 13), sangat disarankan untuk menggunakan **Postman**. 

### Daftar *Endpoint* Tersedia:
| Method | Endpoint                 | Deskripsi                       | Butuh Auth (Token)? |
|--------|--------------------------|---------------------------------|---------------------|
| POST   | `/api/login`             | Login & mendapatkan Token       | Tidak               |
| GET    | `/api/me`                | Cek profil user saat ini        | Ya                  |
| GET    | `/api/refresh`           | Memperbarui Token               | Ya                  |
| GET    | `/api/logout`            | Logout (menghapus sesi)         | Ya                  |
| GET    | `/api/province`          | Mengambil semua data provinsi   | Ya                  |
| POST   | `/api/province`          | Menambahkan data provinsi baru  | Ya                  |
| PUT    | `/api/province/{id}`     | Mengupdate seluruh data provinsi| Ya                  |
| DELETE | `/api/province/{id}`     | Menghapus data provinsi         | Ya                  |

*(Perintah CRUD yang sama juga berlaku untuk rute `/api/city` dan `/api/district`).*

### Aturan Wajib Postman:
1. **Header `Accept`:** Karena ini adalah API, Anda wajib menambahkan Header `Accept` bernilai `application/json` pada **SETIAP** *request* di Postman.
2. **Bearer Token:** Untuk *endpoint* yang membutuhkan Auth (di kolom "Ya" pada tabel atas), Anda wajib memasukkan token hasil dari `/api/login` ke tab **Authorization -> Bearer Token**.
