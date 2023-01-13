FROM php:8.0-fpm

# Update packages
RUN apt-get update

# Install PHP and composer dependencies
RUN apt-get install -qq git curl libzip-dev libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


# Clear out the local repository of retrieved package files
RUN apt-get clean

# Install needed extensions
# Here you can install any other extension that you need during the test and deployment process
RUN docker-php-ext-install pdo_mysql zip bcmath

# Install Composer
RUN curl --silent --show-error "https://getcomposer.org/installer" | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel Envoy
RUN composer global require "laravel/envoy=~1.0"

RUN mkdir -p "/etc/supervisor/logs"

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /var/www

RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache

EXPOSE 6001
