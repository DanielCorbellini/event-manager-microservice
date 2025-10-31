FROM php:8.1-fpm

WORKDIR /var/www

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip bcmath intl

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar apenas arquivos de dependências primeiro
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copiar restante do código
COPY . .

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
