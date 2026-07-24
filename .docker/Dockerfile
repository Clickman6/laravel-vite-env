FROM composer:2 AS composer

FROM php:8.3-cli-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --prefer-dist

CMD ["vendor/bin/phpunit"]
