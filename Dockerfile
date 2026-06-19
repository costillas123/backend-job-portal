FROM php:8.2-apache

# System dependencies + PHP extensions required by Laravel, maatwebsite/excel and dompdf
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        default-mysql-client \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libicu-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        soap \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache: serve the Laravel public/ directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# PHP runtime overrides (uploads, memory, etc.)
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN [ -f .env ] || cp .env.example .env \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan storage:link \
    && php artisan optimize \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
