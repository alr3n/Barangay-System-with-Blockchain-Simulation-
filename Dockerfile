FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

# Install dependencies FIRST
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 🔥 CRITICAL FIX: Laravel required folders + permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# 🔥 IMPORTANT: create storage symlink (VERY COMMON MISSING PIECE)
RUN php artisan storage:link || true

# Set Apache document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Clear + rebuild Laravel caches safely
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan view:clear || true