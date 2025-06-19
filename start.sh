#!/bin/sh
set -e

echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Kita akan caching lagi nanti setelah konflik nama route teratasi
# php artisan config:cache
# php artisan route:cache

echo "Starting server..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf