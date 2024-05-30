FROM webdevops/php-nginx:8.2-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app
COPY . /app



COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN composer require symfony/maker-bundle

RUN mkdir -p /app/var/cache/prod /app/var/log


RUN php bin/console cache:clear --env=prod --no-warmup
RUN php bin/console cache:clear --env=prod --no-warmup


RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www


ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
