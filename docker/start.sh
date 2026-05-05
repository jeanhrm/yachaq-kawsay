#!/bin/sh

echo "Corriendo migraciones..."
php /var/www/artisan migrate --force

echo "Cacheando..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache

echo "Iniciando servidor..."
php /var/www/artisan serve --host=0.0.0.0 --port=8080