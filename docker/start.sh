#!/bin/sh
set -e

echo "Corriendo migraciones..."
php artisan migrate --force

echo "Cacheando configuracion..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando php-fpm..."
php-fpm -D

echo "Esperando php-fpm..."
sleep 5

echo "Verificando php-fpm..."
ps aux | grep php-fpm

echo "Iniciando nginx..."
exec nginx -g "daemon off;"