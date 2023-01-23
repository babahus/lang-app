FROM php:8.1-fpm as lang_app_php

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    libonig-dev \
    libmcrypt-dev \
    libzip-dev \
    zip

RUN docker-php-ext-install -j$(nproc) mbstring pdo pdo_mysql exif bcmath zip

WORKDIR /var/www/lang_app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

FROM nginx AS nginx

ADD docker/nginx/lang_app.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/lang_app