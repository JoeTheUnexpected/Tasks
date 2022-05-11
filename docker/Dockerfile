FROM php:8.1.0-fpm

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

RUN composer install

USER www-data