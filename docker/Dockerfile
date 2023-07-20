FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y \
    locales \
    supervisor -y \
    cron -y \
    vim \
    mcrypt \
    openssl \
    zip \
    libsodium-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libbz2-dev \
    libjpeg62-turbo-dev \
    libzip-dev && \
    #pecl
    pecl install redis xdebug && \
    #Configure extensions
    docker-php-ext-configure intl && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install pdo_mysql mysqli -j$(nproc) gd pcntl bcmath intl bz2 zip && \
    docker-php-ext-enable xdebug && \
    #Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

COPY php/conf.d/custom.ini /usr/local/etc/php/conf.d/custom.ini

RUN apt-get install -y wget && \
    wget https://phar.phpunit.de/phpunit-9.3.8.phar && \
    mv phpunit-9.3.8.phar /usr/local/bin/phpunit && \
    chmod +x /usr/local/bin/phpunit

RUN apt-get upgrade -y \
    && apt-get autoclean \
    && apt-get purge -y \
        libsodium-dev \
        libicu-dev \
        libbz2-dev \
    && apt-get autoremove -y

WORKDIR /var/www/html

CMD php-fpm
