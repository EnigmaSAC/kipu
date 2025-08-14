FROM php:8.2-apache

# Paquetes/headers para extensiones requeridas por Akaunting
RUN apt-get update && apt-get install -y \
    git unzip pkg-config \
    libzip-dev zlib1g-dev \
    libicu-dev libxml2-dev \
    libonig-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip intl mbstring gd bcmath \
 && a2enmod rewrite

# Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# DocumentRoot -> /public y permitir .htaccess
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
