# 📋 Personal Project Manager

Aplikasi manajemen proyek freelance yang dibangun dengan **Laravel 12**, **Livewire 3**, dan **Tailwind CSS 4**. Mendukung versi web dan desktop (via Tauri).

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=flat-square&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![Tauri](https://img.shields.io/badge/Tauri-2.x-FFC131?style=flat-square&logo=tauri&logoColor=white)

---

## ✨ Fitur Utama

### 🏠 Dashboard Admin
- Statistik real-time (total proyek, proyek aktif, total klien, revenue)
- Grafik status proyek
- Daftar proyek & klien terbaru

### 👥 Manajemen Klien
- CRUD klien dengan modal interaktif
- Pencarian dan filter klien
- Lihat riwayat proyek per klien

### 📁 Manajemen Proyek
- Kelola proyek dengan status (pending, in_progress, completed, on_hold, cancelled)
- Fitur proyek (checklist item dengan progress)
- Generate link public untuk client tracking
- Integrasi pembayaran

### 💳 Manajemen Pembayaran
- Catat pembayaran per proyek
- Invoice management
- Tracking status pembayaran (pending, paid, overdue)

### 💰 Kategori Harga
- Template harga untuk berbagai jenis layanan
- Kategori harga yang dapat dikustomisasi

### 🔗 Client Tracking Page
- Halaman public untuk klien melihat progress proyek
- Akses via link unik (token-based)
- Tampilan riwayat pembayaran

### 🌙 Dark Mode
- Tema gelap yang elegan di seluruh aplikasi

### 🖥️ Desktop App
- Build sebagai aplikasi desktop menggunakan Tauri
- Cross-platform (Windows, macOS, Linux)

---

## 🛠️ Tech Stack

| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| **PHP** | ^8.2 | Runtime environment |
| **Laravel** | 12.x | Backend framework |
| **Livewire** | 3.x | Dynamic components |
| **Volt** | 1.7.x | Single-file Livewire components |
| **Tailwind CSS** | 4.x | Utility-first CSS |
| **Vite** | 7.x | Frontend build tool |
| **Tauri** | 2.x | Desktop app framework |
| **SQLite/MySQL** | - | Database |

---

## 📦 Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- (Opsional) Rust & Tauri CLI untuk build desktop

### Setup Cepat

```bash
# Clone repository
git clone <repository-url>
cd personal-project-manager

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Build assets
npm run build
```

### Menggunakan Composer Script

```bash
# Setup lengkap (install deps, generate key, migrate, build)
composer run setup
```

---

## 🚀 Menjalankan Aplikasi

### Mode Development (Web)

```bash
# Jalankan semua service sekaligus (server, queue, logs, vite)
composer run dev
```

Atau jalankan terpisah:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

### Mode Development (Desktop)

```bash
# Jalankan Tauri development
npm run dev:tauri:desktop
```

---

## 📂 Struktur Proyek

```
personal-project-manager/
├── app/
│   ├── Http/              # Controllers
│   ├── Livewire/          # Livewire components
│   │   ├── Admin/         # Admin components
│   │   ├── Auth/          # Authentication components
│   │   └── Public/        # Public-facing components
│   └── Models/            # Eloquent models
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── css/               # Stylesheet
│   └── views/             # Blade templates
├── routes/
│   └── web.php            # Web routes
├── src-tauri/             # Tauri desktop app config
└── ...
```

---

## 🗄️ Database Schema

### Models

| Model | Deskripsi |
|-------|-----------|
| **User** | Admin users |
| **Client** | Data klien |
| **Project** | Proyek dengan status & progress |
| **ProjectFeature** | Fitur/checklist item per proyek |
| **Payment** | Pembayaran terkait proyek |
| **PriceCategory** | Template kategori harga |

---

## 🔐 Autentikasi

- Login dengan username dan password
- Session-based authentication
- Middleware `auth` untuk route admin

---

## 🧪 Testing

```bash
# Jalankan test suite
composer run test

# Atau langsung dengan artisan
php artisan test
```

---

## 📝 Script yang Tersedia

### Composer

| Script | Deskripsi |
|--------|-----------|
| `composer run setup` | Setup proyek lengkap |
| `composer run dev` | Jalankan semua dev services |
| `composer run test` | Jalankan test suite |

### NPM

| Script | Deskripsi |
|--------|-----------|
| `npm run dev` | Vite dev server |
| `npm run build` | Build untuk produksi |
| `npm run dev:tauri:desktop` | Mode development desktop |
| `npm run tauri` | Tauri CLI |

---

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Author

Dibuat dengan ❤️ menggunakan Laravel & Livewire
