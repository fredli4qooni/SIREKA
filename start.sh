#!/bin/sh
set -e

echo "Running database migrations..."
php artisan migrate --force --no-interaction

# SELALU BERSIHKAN DULU
echo "Clearing all caches..."
php artisan optimize:clear

# BARU BUAT CACHE PRODUKSI
echo "Caching configuration and routes for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache