FROM php:8.2-fpm

#Update apt
RUN apt-get update

#Install necessary program
RUN apt-get -y install \
    screen \
    git \
    vim \
    zip \
    nodejs \
    npm

#Install preprocessors by npm
RUN npm install -g sass typescript

#Install necessary libs
RUN apt-get -y install \
    zlib1g-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    librabbitmq-dev \
    libssh-dev \
    libsodium-dev \
    zlib1g-dev \
    libzip-dev \
    g++ \
    procps

#Install lib for PostgreSQL
RUN apt-get install -y libpq-dev

#Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-source delete

RUN docker-php-ext-install opcache

#Install php extension for PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql soap exif

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
