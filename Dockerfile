# Use the official PHP image as a base
FROM php:8.1-fpm

# Set the working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code
COPY . .

# Install PHP dependencies
RUN composer install

# Expose the port the app runs on
EXPOSE 9000

# Start the PHP FastCGI Process Manager
# Verificar depois
CMD ["php-fpm"]