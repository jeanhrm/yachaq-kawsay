FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx nodejs npm git curl \
    libpng-dev oniguruma-dev libxml2-dev \
    zip unzip postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 8080

CMD sh -c "php artisan migrate --force && php artisan config:cache && php-fpm -D && sleep 3 && nginx -g 'daemon off;'"