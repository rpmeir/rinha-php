FROM composer:2 AS build

WORKDIR /app/
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

FROM php:8.3-alpine

# recommended: install optional extensions ext-ev and ext-sockets
RUN apk --no-cache add ${PHPIZE_DEPS} libev linux-headers \
    && pecl install ev \
    && docker-php-ext-enable ev \
    && docker-php-ext-install sockets \
    && apk del ${PHPIZE_DEPS} linux-headers \
    && echo "memory_limit = -1" >> "$PHP_INI_DIR/conf.d/acme.ini"

WORKDIR /app/
COPY public/ public/
COPY src/ src/
COPY --from=build /app/vendor/ vendor/

ENV MYSQL_PASSWORD rinha
ENV MYSQL_ROOT_PASSWORD rinha
ENV MYSQL_USER rinha
ENV MYSQL_DATABASE rinha
ENV X_LISTEN 0.0.0.0:8080

EXPOSE 8080

USER www-data:www-data
ENTRYPOINT ["php", "public/index.php"]
