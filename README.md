# 📝 Blog AppKris — Modern Blog CMS (CodeIgniter 4)

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/CodeIgniter-4-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter 4">
  <img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Frontend-Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/Security-Google_reCAPTCHA_v2-4285F4?style=for-the-badge&logo=google&logoColor=white" alt="Google reCAPTCHA">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

Aplikasi Web Blog modern dan Content Management System (CMS) bertenaga **CodeIgniter 4**. Dirancang dengan arsitektur bersih, performa tinggi (bebas dari N+1 query problem), serta dilengkapi panel administrasi lengkap, sistem moderasi komentar bertingkat dengan proteksi **Google reCAPTCHA v2**, integrasi Google AdSense, dan fitur SEO-friendly.

---

## 🌟 Fitur Unggulan

### 🌐 Frontend Blog
* **Desain Responsif & Modern**: Tampilan bersih, nyaman dibaca di desktop maupun perangkat mobile.
* **Sistem Navigasi & Taksonomi**: Filter artikel berdasarkan Kategori, Sub-kategori, dan Tag.
* **Fitur Pencarian Cepat**: Pencarian artikel multi-kata kunci dengan pagination otomatis.
* **Nested Comments System**:
  * Komentar bertingkat (*parent & replies*).
  * Proteksi bot dengan **Google reCAPTCHA v2 (Checkbox)**.
  * Proteksi anti-spam otomatis, rate limiting IP, dan sensor kata-kata terlarang.
  * Avatar dinamis serta badge khusus untuk Administrator.
* **Interaksi Sosial**: Tombol berbagi instan ke Twitter, WhatsApp, dan salin tautan.

### ⚙️ Backend / Admin Dashboard
* **Autentikasi Aman**: Login terlindungi dari *user enumeration*, forgot password via email SMTP, dan reset password aman dengan token terenkripsi.
* **Post & Media Manager**: Rich Text Editor (Summernote) dengan thumbnail generator otomatis (*crop & resize*).
* **Category & Sub-category Reordering**: Pengaturan urutan kategori dengan DataTables server-side berkecepatan tinggi.
* **Moderasi Komentar**: Setujui, tandai spam, pindah ke tong sampah, balas langsung sebagai admin, dan eksekusi aksi massal (*bulk action*).
* **Profil Penulis & Pengaturan Blog**: Pengaturan logo, favicon, media sosial, dan identitas penulis.
* **Google AdSense Manager**: Slot iklan dinamis yang mudah diaktifkan langsung dari dasbor.
* **Static Pages**: Buat halaman statis dinamis (Tentang Kami, Privasi, dll) dengan opsi tautan di footer.

---

## 📋 Kebutuhan Sistem

* **PHP**: Versi `8.1` atau lebih baru
* **Ekstensi PHP Wajib**:
  * `intl`
  * `mbstring`
  * `mysqli` / `pdo_mysql`
  * `curl`
  * `gd`
* **Database**: MySQL 5.7+ / MariaDB 10.3+
* **Composer**: Dependency Manager untuk PHP

---

## 🚀 Panduan Instalasi Cepat

### 1. Clone Repositori
```bash
git clone https://github.com/appkris/blogcms.git
cd blogcms
```

### 2. Pasang Dependensi
```bash
composer install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database serta konfigurasi lainnya:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = db_blog
database.default.username = root
database.default.password = 

# Konfigurasi SMTP (Kirim Email & Reset Password)
EMAIL_HOST = 'smtp.example.com'
EMAIL_USERNAME = 'your-email@example.com'
EMAIL_PASSWORD = 'your-email-password'
EMAIL_ENCRYPTION = 'tls'
EMAIL_PORT = 587
EMAIL_FROM_ADDRESS = 'no-reply@example.com'
EMAIL_FROM_NAME = 'Blog AppKris'

# Google reCAPTCHA v2
RECAPTCHA_SITE_KEY = 'your_google_recaptcha_site_key'
RECAPTCHA_SECRET_KEY = 'your_google_recaptcha_secret_key'
```

### 4. Migrasi & Seeder Database
Jalankan migrasi tabel dan masukkan data dummy awal:
```bash
php spark migrate
php spark db:seed MainSeeder
```

### 5. Jalankan Local Server
```bash
php spark serve
```
Buka peramban (browser) di: `http://localhost:8080`

---

## 🔐 Akun Default Administrator

Setelah menjalankan seeder:
* **URL Login**: `http://localhost:8080/admin/login`
* **Email**: `admin@mail.com`
* **Password**: `Admin@123`

> [!IMPORTANT]
> Pastikan untuk segera mengganti password bawaan setelah login di menu **Profile** pada environment produksi.

---

## 🛡️ Keamanan & Kualitas Kode

Proyek ini telah melalui proses *code audit & deep-dive refactor* yang mencakup:
* ✅ Proteksi CSRF & Sanitasi XSS bawaan CI4.
* ✅ Pencegahan serangan *User Enumeration* pada modul otentikasi.
* ✅ Penghapusan pengiriman password mentah (*plaintext*) melalui transit email.
* ✅ Optimasi kueri server-side DataTables (eliminasi *N+1 query problem*).
* ✅ Verifikasi anti-bot dengan Google reCAPTCHA v2 Server-Side Verification.

---

## 📄 Lisensi
Proyek ini didistribusikan di bawah lisensi [MIT License](LICENSE).
