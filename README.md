# Backend Test

Ini adalah API sederhana untuk mengelola buku dan penulis, yg dibangun menggunakan Laravel. API ini memungkinkan pengguna untuk melakukan (CRUD).

## Persyaratan

Beberapa kebutuhan agar program bisa jalan di device masing masing:

- PHP >= 8.0
- Composer
- MySQL

## Instalasi
**Clone repository** ini ke direktori lokal:
```
git clone https://github.com/HarisTanone/BackendTest.git
composer install
cp .env.example .env
php artisan key:generate
```
**Setup Env**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database
```
**Migrasi Database**
```
php artisan migrate
```
**Jalankan Server**
```
php artisan serve
```

# Penggunaan API
## Endpoint Author
- GET `/api/author` - Mengambil semua author
- GET `/api/author/{id}` - Mengambil data berdasarkan ID
- POST `/api/author` - Menambahkan author baru
- PUT `/api/author/{id}` - Mengubah data author
- DELETE `/api/author/{id}` - Menghapus data
- GET `api/author/{id}/books` - Mengambil buku berdasarkan ID author

## Endpoint Book
- GET `/api/book` - Mengambil semua book
- GET `/api/book/{id}` - Mengambil data berdasarkan ID
- POST `/api/book` - Menambahkan book baru
- PUT `/api/book/{id}` - Mengubah data book
- DELETE `/api/book/{id}` - Menghapus data

Route list juga bisa dilihat dengan
```
php artisan route:list
```

## Pengujian
Untuk menjalankan semua unit testing bisa menggunakan
```
php artisan test
```

atau untuk menjalankan unit test tertentu bisa menggunakan
```
php artisan test --filter Classname or Method
```
