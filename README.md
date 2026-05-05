# Satellite Management System

Sistem Informasi Manajemen Satelit berbasis web menggunakan Laravel dan MySQL.

## 🚀 Fitur Utama

- Authentication (Login/Register)
- Role Management (Admin/User)
- CRUD Satellites
- CRUD Ground Stations
- Dashboard dengan Statistik
- Search & Filter Satellites
- Database Relations
- Upload Gambar Satelit

## 🛠️ Tech Stack

- **Framework:** Laravel 10
- **Database:** MySQL
- **Frontend:** AdminLTE 3, Bootstrap 4, Chart.js
- **Version Control:** Git

## 📦 Instalasi

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

1. Clone repository

```bash
git clone https://github.com/username/satellite-management.git
cd satellite-management
```

2. Install dependencies

```bash
composer install
npm install
```

3. Copy file environment

```bash
cp .env.example .env
```

4. Generate application key

```bash
php artisan key:generate
```

5. Konfigurasi database di file `.env`

```env
DB_DATABASE=satellite_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. Buat database MySQL

```bash
mysql -u root -p
CREATE DATABASE satellite_db;
EXIT;
```

7. Jalankan migration & seeder

```bash
php artisan migrate --seed
```

8. Create storage link

```bash
php artisan storage:link
```

9. Compile assets

```bash
npm run dev
```

10. Jalankan aplikasi

```bash
php artisan serve
```

11. Akses aplikasi di browser: `http://127.0.0.1:8000`

## 🔐 Default Login

- **Email:** admin@satellite.com
- **Password:** password

## 📊 Database Schema

### Users Table

- id, name, email, password, role, timestamps

### Ground Stations Table

- id, name, location, country, latitude, longitude, description, timestamps

### Satellites Table

- id, name, country, launch_date, orbit_type, tle, status, description, image, ground_station_id, timestamps

## 🎯 Relasi Database

- 1 Ground Station memiliki banyak Satellites (One to Many)
- 1 Satellite dimiliki oleh 1 Ground Station (Belongs To)

## 📝 Git Workflow

```bash
# Commit message format
feat: menambahkan fitur baru
fix: memperbaiki bug
docs: update dokumentasi
style: perubahan formatting
refactor: refactoring code
test: menambahkan testing
chore: maintenance
```

## 👨‍💻 Developer

**Muhammad Tegar Septian** - MBKM BRIN Internship

## 📄 License

This project is for educational purposes.
