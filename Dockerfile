FROM webdevops/php-nginx:8.2-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app
COPY . /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN composer require symfony/maker-bundle

ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php

COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 80 9000

CMD php-fpm -D && nginx -g 'daemon off;'