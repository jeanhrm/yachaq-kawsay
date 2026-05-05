#!/bin/sh
set -e

echo "Corriendo migraciones..."
php artisan migrate --force

echo "Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando php-fpm..."
php-fpm -D

echo "Esperando php-fpm..."
sleep 3

echo "Iniciando nginx..."
nginx -g "daemon off;"