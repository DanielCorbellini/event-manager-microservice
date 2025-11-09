FROM php:8.2-fpm

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libicu-dev g++ make autoconf pkg-config \
    && docker-php-ext-install pdo pdo_mysql zip bcmath intl

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar todo o código antes do composer install
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
