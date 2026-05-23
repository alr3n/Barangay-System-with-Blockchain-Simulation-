FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ================================
# 🔥 LARAVEL CRITICAL FIXES
# ================================

RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# IMPORTANT: ensure writable permissions for runtime
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage link (safe mode)
RUN php artisan storage:link || true

# Clear Laravel caches safely
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan view:clear || true
RUN php artisan optimize:clear || true

# ================================
# 🔥 APACHE CONFIG
# ================================
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Final permission fix
RUN chown -R www-data:www-data /var/www/html