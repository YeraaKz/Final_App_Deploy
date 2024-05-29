FROM webdevops/php-nginx:8.2-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN addgroup -S www-data && adduser -S -G www-data www-data

WORKDIR /app
COPY . /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN composer require symfony/maker-bundle

RUN mkdir -p /app/var/cache /app/var/log && \
    chown -R www-data:www-data /app/var

USER www-data
RUN php bin/console cache:clear --env=prod && \
    php bin/console cache:warmup --env=prod


ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
