FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip xml fileinfo

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Crear .env antes de composer install para evitar errores de descubrimiento de paquetes
RUN cp .env.example .env

# Asegurar permisos de escritura para storage y cache
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Instalar dependencias sin scripts (los scripts fallan sin APP_KEY)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Ahora que las dependencias están instaladas, ejecutar scripts manualmente
RUN php artisan package:discover --ansi

RUN php artisan key:generate && \
    php artisan storage:link && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 8000

CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8000
