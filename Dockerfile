FROM php:8.2-apache

# Installe les extensions nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev unzip git curl zip libpq-dev libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl

# Active mod_rewrite
RUN a2enmod rewrite

# Copier les fichiers du projet
COPY . /var/www/html

# Définir le dossier de travail
WORKDIR /var/www/html

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copier la config Apache
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
