FROM php:8.2-apache

WORKDIR /var/www/html

# =========================
# SYSTEM DEPENDENCIES
# =========================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

# =========================
# COMPOSER
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# COPY PROJECT FILES
# =========================
COPY . /var/www/html

# =========================
# FIX 1: ENV SAFETY
# =========================
RUN cp .env.example .env || true

# =========================
# INSTALL DEPENDENCIES
# =========================
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# =========================
# FIX 2: LARAVEL KEY
# =========================
RUN php artisan key:generate || true

# =========================
# FIX 3: STORAGE + CACHE PATH (MAIN FIX FOR YOUR ERROR)
# =========================
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# =========================
# FIX 4: CLEAR SAFELY (NO CRASH ON BUILD)
# =========================
RUN php artisan optimize:clear || true

# =========================
# STORAGE LINK (SAFE)
# =========================
RUN php artisan storage:link || true

# =========================
# APACHE CONFIG (IMPORTANT)
# =========================
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# =========================
# FIX 5: FINAL PERMISSION SAFETY
# =========================
RUN chown -R www-data:www-data /var/www/html || true