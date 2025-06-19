#!/bin/sh

# Hanya jalankan migrasi, lalu biarkan skrip ini selesai.
echo "Running database migrations..."
php artisan migrate --force --no-interaction