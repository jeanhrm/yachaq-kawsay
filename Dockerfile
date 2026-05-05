FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Instalar dependencias JS y compilar
RUN npm install && npm run build

# Permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configurar nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php-fpm -D && \
    nginx -g "daemon off;"