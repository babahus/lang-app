FROM php:8.1-fpm as lang_app_php

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    libonig-dev \
    libmcrypt-dev \
    libzip-dev \
    zip \
    cron

RUN docker-php-ext-install -j$(nproc) mbstring pdo pdo_mysql exif bcmath zip

WORKDIR /var/www/lang_app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN touch /var/log/cron.log \
    && chmod 777 /var/log/cron.log

RUN echo "0 */6 * * * root /usr/local/bin/php /var/www/lang_app/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab

CMD service cron start && php-fpm

FROM nginx AS nginx

ADD docker/nginx/lang_app.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/lang_app