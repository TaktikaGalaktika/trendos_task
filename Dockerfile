FROM php:8.2-fpm

# Install system dependencies and PHP extensions for Symfony
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev \
    && docker-php-ext-install intl pdo_mysql zip

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html