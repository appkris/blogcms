# Blog AppKris (CodeIgniter 4)

Aplikasi Web Blog modern berbasis **CodeIgniter 4** yang dilengkapi dengan panel administrasi, sistem kategori bertingkat, moderasi komentar bertingkat (nested replies) dengan proteksi anti-spam, manajemen artikel, integrasi Google AdSense, dan pengaturan SEO.

---

## Fitur Utama

- **Frontend Blog:**
  - Desain responsif dan modern.
  - Halaman artikel dinamis (Single Post, Category, Subcategory, Tag, Search).
  - Sistem komentar bersarang (*nested comments*) dengan avatar dinamis & badge Admin.
  - Fitur kontak dan pencarian artikel cepat.

- **Backend / Panel Admin:**
  - Autentikasi aman (Login, Forgot Password via Email/SMTP, Reset Password).
  - Manajemen Postingan (Create, Read, Update, Delete) dengan Rich Text Editor & Image Uploader.
  - Manajemen Kategori & Sub-kategori dengan fitur drag & drop / reordering.
  - Moderasi Komentar (Approve, Mark Spam, Trash, Balas langsung sebagai Admin, Bulk Actions).
  - Manajemen Profil Penulis & Pengaturan Blog (Logo, Favicon, Social Media).
  - Integrasi Slot Iklan Google AdSense & Halaman Statis dinamis.

- **Keamanan & Performa:**
  - Rate limiting komentar & pemfilteran link/kata spam otomatis.
  - Proteksi CSRF & XSS bawaan CI4.
  - Pemisahan environment aman via `.env`.

---

## Kebutuhan Sistem

- **PHP**: Versi 8.1 atau lebih baru
- Ekstensi PHP wajib:
  - `intl`
  - `mbstring`
  - `mysqli` / `pdo_mysql`
  - `curl`
  - `gd`
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Composer**: Dependency Manager for PHP

---

## Panduan Instalasi Lokal

1. **Clone Repositori:**
   ```bash
   git clone <URL_REPO_GITHUB_ANDA>
   cd blogappkris
   ```

2. **Install Dependensi Composer:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`):**
   Salin file template `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan kredensial database & konfigurasi SMTP:
   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = localhost
   database.default.database = db_blog
   database.default.username = root
   database.default.password = 

   # Konfigurasi SMTP untuk fitur kirim email / reset password
   EMAIL_HOST = 'smtp.example.com'
   EMAIL_USERNAME = 'your-email@example.com'
   EMAIL_PASSWORD = 'your-email-password'
   EMAIL_ENCRYPTION = 'tls'
   EMAIL_PORT = 587
   EMAIL_FROM_ADDRESS = 'no-reply@example.com'
   EMAIL_FROM_NAME = 'Blog AppKris'
   ```

4. **Migrasi Database & Seeder:**
   Jalankan migrasi tabel dan masukkan data dummy awal:
   ```bash
   php spark migrate
   php spark db:seed MainSeeder
   ```
   *(Opsional) Jika ingin mereset ulang database kapan saja:*
   ```bash
   php spark migrate:refresh && php spark db:seed MainSeeder
   ```

5. **Jalankan Development Server:**
   ```bash
   php spark serve
   ```
   Akses blog di browser: `http://localhost:8080`

---

## Akun Default Administrator

Setelah menjalankan `MainSeeder`, akun login admin bawaan:
- **URL Login**: `http://localhost:8080/admin/login`
- **Email**: `admin@mail.com`
- **Password**: `Admin@123`

> [!IMPORTANT]
> Segera ganti password dan email admin di menu **Profile** setelah login pada environment produksi!

---

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
