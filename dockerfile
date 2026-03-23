# FROM php:8.3-cli

# WORKDIR /var/www

# RUN apt-get update && apt-get install -y \
#     libpq-dev \
#     zip unzip git curl \
#     && docker-php-ext-install pdo pdo_pgsql

# COPY ./fenix-api .

# RUN curl -sS https://getcomposer.org/installer | php
# RUN php composer.phar install

# CMD php artisan serve --host=0.0.0.0 --port=8000

FROM php:8.3-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

COPY ./fenix-api .

RUN curl -sS https://getcomposer.org/installer | php

RUN php composer.phar install

CMD php artisan serve --host=0.0.0.0 --port=8000