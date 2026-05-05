#!/bin/sh

echo "Corriendo migraciones..."
php /var/www/artisan migrate --force

echo "Corriendo seeder..."
php /var/www/artisan db:seed --class=MisionesSeeder --force

echo "Cacheando..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache

echo "Iniciando php-fpm..."
php-fpm -F &
FPM_PID=$!

echo "Esperando php-fpm..."
sleep 4

echo "Iniciando nginx..."
nginx -g "daemon off;" &
NGINX_PID=$!

echo "Ambos servicios corriendo."

wait $FPM_PID $NGINX_PID