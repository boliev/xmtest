FROM php:7.4-fpm
WORKDIR /var/app/
RUN apt-get update -y && apt-get install -y libpq-dev libicu-dev zip libzip-dev git \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install opcache intl zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install redis \
    && docker-php-ext-enable redis