#!/bin/sh

echo "Corriendo migraciones..."
php /var/www/artisan migrate --force

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

echo "Ambos servicios corriendo. FPM=$FPM_PID NGINX=$NGINX_PID"

wait $FPM_PID $NGINX_PID