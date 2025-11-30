# laravel-siakad

**Laravel 11 + React – SIAKAD**  
Sistem Informasi Akademik (SIAKAD) berbasis web untuk manajemen akademik sekolah/kampus, dibangun dengan Laravel + React.

## 📌 Teknologi & Lingkungan (Runtime / Framework / Language / Database / Styling)

- **Backend**: PHP dengan framework Laravel 11  
- **Frontend**: ReactJS
- **Database**: MySQL / MariaDB  
- **Styling / UI**: TailwindCSS

## ✨ Fitur

- Manajemen data akademik (mahasiswa, dosen, fakultas, jurusan, kelas, mata kuliah)
- Autentikasi pengguna (superadmin / admin prodi / dosen / mahasiswa)
- CRUD data akademik & referensi
- Pengaturan jadwal & data akademik lain

## 🚀 Instalasi

1. Clone repository  
   ```bash
   git clone https://github.com/RinnHehe/laravel-siakad.git
   cd laravel-siakad
   ```
2. Install dependencies  
   ```bash
   composer install
   npm install
   ```
3. Konfigurasi environment  
   ```bash
   cp .env.example .env
   ```
4. Generate key & migrate  
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
5. Jalankan server  
   ```bash
   php artisan serve
   npm run dev
   ```

## 📝 Struktur Direktori

- `app/`, `routes/`, `database/` — backend Laravel  
- `public/`, `resources/`, `package.json` — frontend React

