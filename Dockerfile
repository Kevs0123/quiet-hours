# syntax=docker/dockerfile:1

FROM composer:2.7-php8.4 AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

FROM node:20 AS assets
WORKDIR /app
COPY package.json package-lock.json ./
# Use a normal install here because `npm ci` fails when the lockfile and
# package manifest are not perfectly aligned in this repository.
RUN npm install --legacy-peer-deps --no-audit --progress=false
COPY . .
RUN npm run build

FROM php:8.4-cli
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip zip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
        libxrender1 libfontconfig1 libxext6 libx11-6 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip xml \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
