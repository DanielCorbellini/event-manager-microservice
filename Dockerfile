FROM php:8.3-fpm

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libicu-dev g++ make autoconf pkg-config libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia o binário do Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copia apenas os arquivos necessários primeiro (para cache eficiente)
COPY composer.json composer.lock ./

# Instala as dependências do PHP (vendor) dentro do container
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Agora copia o restante do código
COPY . .

# Dá permissão às pastas do Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
