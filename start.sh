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
# Sekarang konflik sudah teratasi, kita bisa jalankan ini dengan aman
php artisan config:cache
php artisan route:cache

# echo "Setting permissions (if needed)..."
# chmod -R 775 storage bootstrap/cache
# chown -R www-data:www-data storage bootstrap/cache

echo "Startup script finished. Handing over to Nixpacks to start the server."