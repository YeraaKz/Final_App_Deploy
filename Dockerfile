# PHP
FROM php:8.3-fpm

# Dependencies
RUN apt-get update && apt-get install -y \
        nginx \
        git \
        unzip \
        libpng-dev \
        libonig-dev \
        libpq-dev \
        libxml2-dev \
        zip \
        curl \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Copy root directory
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Copy nginx conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/

# Access
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# nginx, fpm ports
EXPOSE 80 9000

# run nginx, fpm
CMD php-fpm -D && nginx -g 'daemon off;'