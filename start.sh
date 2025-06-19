#!/bin/sh

# Keluar dari skrip jika ada perintah yang gagal
set -e

echo "Running database migrations..."
php artisan migrate --force --no-interaction

echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching configuration for production..."
# 'config:cache' dan 'route:cache' akan membuat file cache
# yang membutuhkan izin yang benar, jadi kita jalankan sebelum 'chmod'
php artisan config:cache
php artisan route:cache

echo "Setting permissions for storage and bootstrap/cache..."
# Memberikan izin tulis ke grup dan pengguna lain untuk folder penting
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Starting server..."
# 'exec' akan menjalankan server dan menggantikan proses skrip ini.
# Kita kembali menggunakan exec agar prosesnya lebih bersih.
# Path ini adalah path yang umum digunakan oleh buildpack Heroku/Nixpacks untuk supervisord.
# Jika ini gagal lagi, kita akan cari tahu path yang benar.
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf