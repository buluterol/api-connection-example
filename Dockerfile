FROM php:8.0-apache


RUN apt-get update && apt-get install -y \
    cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev \
    libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev \
    libtidy-dev libcurl4-openssl-dev libz-dev libxslt-dev \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli \
    && docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock /var/www/html/

WORKDIR /var/www/html
RUN composer install

COPY . /var/www/html

EXPOSE 80