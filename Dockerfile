FROM php:8.1-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/web/

RUN export DOCKER_BUILDKIT=0 && \
    export COMPOSE_DOCKER_CLI_BUILD=0

RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && apt-get install -y libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN docker-php-ext-enable redis \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/web