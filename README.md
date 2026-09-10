# Admin Checker

Admin Checker adalah aplikasi Laravel untuk memantau dan mengelola perangkat komputer yang terdaftar pada lingkungan kerja. Aplikasi menyediakan dashboard web untuk admin dan API untuk mengirim, melihat, serta menghapus data perangkat.

## Teknologi

- PHP 8.3 atau lebih baru
- Laravel 13
- Laravel Sanctum
- SQLite sebagai database default
- Node.js dan npm
- Vite dan Tailwind CSS

## Prasyarat

Pastikan perangkat sudah memiliki:

- PHP 8.3+
- Composer
- Node.js dan npm
- Git

Verifikasi instalasi:

```powershell
php -v
composer -V
node -v
npm -v
```

## Instalasi Setelah Clone

1. Clone repository dan masuk ke folder proyek:

    ```powershell
    git clone <URL-REPOSITORY>
    cd admin-checker
    ```

2. Install dependency PHP:

    ```powershell
    composer install
    ```

3. Buat file environment:

    ```powershell
    Copy-Item .env.example .env
    ```

    Pada Git Bash, gunakan `cp .env.example .env`.

4. Generate application key:

    ```powershell
    php artisan key:generate
    ```

5. Siapkan database SQLite. Jika file belum ada, buat file kosong:

    ```powershell
    New-Item database/database.sqlite -ItemType File -Force
    ```

    Pastikan `.env` menggunakan konfigurasi berikut:

    ```dotenv
    DB_CONNECTION=sqlite
    ```

6. Jalankan migration:

    ```powershell
    php artisan migrate
    ```

7. (Opsional) Buat akun demo:

    ```powershell
    php artisan db:seed
    ```

    Akun demo:
    - Email: `test@example.com`
    - Password: `password`

8. Install dependency frontend dan buat asset production:

    ```powershell
    npm install
    npm run build
    ```

## Menjalankan Aplikasi

Untuk development, jalankan dua proses berikut pada terminal terpisah.

Terminal 1:

```powershell
php artisan serve
```

Terminal 2:

```powershell
npm run dev
```

Buka aplikasi pada [http://localhost:8000](http://localhost:8000), lalu login menggunakan akun admin yang tersedia.

Alternatifnya, proses Laravel dan Vite dapat dijalankan melalui:

```powershell
composer run dev
```

## Fitur

### Dashboard Admin

- Login dan logout admin berbasis session.
- Ringkasan jumlah perangkat terdaftar.
- Jumlah perangkat yang aktif dalam lima menit terakhir.
- Waktu scan terbaru.
- Ringkasan perangkat berdasarkan departemen.
- Pencarian berdasarkan nama perangkat, nama pengguna, CPU, RAM, atau device ID.
- Filter perangkat berdasarkan departemen.

### Monitoring Perangkat

- Daftar perangkat dengan pagination.
- Halaman daftar perangkat khusus.
- Halaman live monitoring.
- Detail informasi perangkat seperti pengguna, departemen, nama perangkat, produsen, model, CPU, RAM, GPU, storage, versi Windows, dan waktu terakhir terlihat.
- Penghapusan data perangkat melalui API.

### API Perangkat

- Menerima pendaftaran atau pembaruan perangkat.
- Mencegah duplikasi berdasarkan `device_id` melalui operasi update-or-create.
- Menampilkan daftar perangkat dengan pagination.
- Menampilkan detail satu perangkat.
- Menghapus perangkat.

Menu Employees, Camera Feed, Detection History, dan Settings sudah tersedia sebagai halaman placeholder untuk pengembangan berikutnya.

## Endpoint API

Base URL:

```text
http://localhost:8000/api
```

| Method | Endpoint                | Fungsi                                  |
| ------ | ----------------------- | --------------------------------------- |
| GET    | `/api/devices`          | Menampilkan daftar perangkat            |
| POST   | `/api/devices`          | Mendaftarkan atau memperbarui perangkat |
| GET    | `/api/devices/{device}` | Menampilkan detail perangkat            |
| DELETE | `/api/devices/{device}` | Menghapus perangkat                     |

### Contoh Request POST

```powershell
Invoke-RestMethod `
  -Uri http://localhost:8000/api/devices `
  -Method Post `
  -ContentType 'application/json' `
  -Body (@{
    user_name = 'Budi'
    department = 'Bidang Pemerintahan Desa'
    device_id = '550e8400-e29b-41d4-a716-446655440000'
    device_name = 'PC Admin'
    manufacturer = 'Dell'
    model = 'OptiPlex 7090'
    cpu = 'Intel Core i7'
    ram = '16 GB'
    gpu = 'Intel UHD Graphics'
    storage = '512 GB SSD'
    windows_version = 'Windows 11 Pro'
  } | ConvertTo-Json)
```

Field wajib:

- `user_name`
- `department`
- `device_id` dengan format UUID

Nilai `department` yang tersedia:

- `Bidang Pemerintahan Desa`
- `Bidang Pembangunan Ekonomi dan Pendapatan Desa`
- `Bidang Sarana Prasarana dan Kewilayahan`
- `Bidang Pemberdayaan Masyarakat Desa`

Parameter `per_page` dapat digunakan pada endpoint daftar perangkat, contohnya `/api/devices?per_page=25`.

## Perintah Berguna

```powershell
# Melihat status migration
php artisan migrate:status

# Melihat seluruh route
php artisan route:list

# Melihat route API perangkat
php artisan route:list --path=api/devices

# Menjalankan test
php artisan test

# Memformat kode PHP
vendor/bin/pint

# Menghapus cache konfigurasi dan route
php artisan optimize:clear
```

## Struktur Direktori Penting

```text
app/Http/Controllers/       Controller web dan API
app/Models/                 Model User dan Device
database/migrations/        Struktur tabel database
database/seeders/           Data awal aplikasi
resources/views/            Tampilan Blade dashboard dan login
resources/css/              Style aplikasi
resources/js/               JavaScript aplikasi
routes/web.php              Route halaman web
routes/api.php              Route API perangkat
```

## Catatan Pengembangan

- Jangan commit file `.env` karena berisi konfigurasi lokal.
- Jalankan `npm run dev` saat mengembangkan frontend agar perubahan asset ter-update otomatis.
- Jalankan `npm run build` sebelum deployment.
- API perangkat saat ini tidak memakai middleware autentikasi. Tambahkan autentikasi atau token sebelum digunakan di lingkungan production.
- Setelah mengubah struktur database, buat migration baru dan jalankan `php artisan migrate`.

## Pengujian

Jalankan test dengan:

```powershell
php artisan test
```

Pastikan migration dan konfigurasi `.env` sudah siap sebelum menjalankan test.
