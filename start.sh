#!/bin/sh

# Menjalankan migrasi database
# Opsi --no-interaction agar tidak ada pertanyaan konfirmasi
php artisan migrate --force --no-interaction

# Menjalankan web server yang disediakan oleh Nixpacks
# 'exec' akan menggantikan proses skrip dengan proses server
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf